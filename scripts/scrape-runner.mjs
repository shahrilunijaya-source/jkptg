#!/usr/bin/env node
/**
 * Portal-JKPTG scrape runner — Path B (lightweight BFS).
 *
 * - BFS crawl from https://www.jkptg.gov.my/{my,en}/ depth N.
 * - Throttle 1 req/sec, single concurrency.
 * - Capture HTML + linked PDFs + images.
 * - Write _index.csv, sitemap.json, menu-tree.json, crawl-log.txt.
 *
 * Path C (Playwright screenshots) handled separately via MCP, not here.
 */

import { fetch, Agent, setGlobalDispatcher } from "undici";
import * as cheerio from "cheerio";
import { mkdir, writeFile, appendFile } from "node:fs/promises";
import { existsSync } from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const ORIGIN = "https://www.jkptg.gov.my";
const SEEDS = [`${ORIGIN}/my/`, `${ORIGIN}/en/`];
const MAX_DEPTH = parseInt(process.env.MAX_DEPTH ?? "3", 10);
const MAX_PAGES = parseInt(process.env.MAX_PAGES ?? "120", 10);
const REQ_DELAY_MS = parseInt(process.env.REQ_DELAY_MS ?? "1100", 10);
const TIMEOUT_MS = 25000;

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, "..");
const OUT = path.join(ROOT, "reference/scrape");
const HTML_DIR = path.join(OUT, "02-html");
const PDF_DIR = path.join(OUT, "04-pdfs");
const PDF_TEXT_DIR = path.join(PDF_DIR, "_extracted");
const IMG_DIR = path.join(OUT, "05-assets/images");
const META_DIR = path.join(OUT, "00-meta");
const SITEMAP_DIR = path.join(OUT, "01-sitemap");
const FORMS_DIR = path.join(OUT, "06-forms");
const INV_DIR = path.join(OUT, "08-content-inventory");

setGlobalDispatcher(new Agent({
  connect: { timeout: TIMEOUT_MS },
  bodyTimeout: TIMEOUT_MS,
  headersTimeout: TIMEOUT_MS,
}));

const UA = "Mozilla/5.0 (compatible; JKPTG-tender-scrape/0.1; +contact-shahril)";

const visited = new Set();
const pdfQueue = new Map(); // url -> sourcePage
const imgQueue = new Map();
const formsList = [];
const pageRows = []; // _index.csv rows
const sitemapTree = { my: [], en: [], other: [] };
const errors = [];

function logLine(...args) {
  const line = `[${new Date().toISOString()}] ${args.join(" ")}`;
  console.log(line);
  return appendFile(path.join(META_DIR, "crawl-log.txt"), line + "\n").catch(() => {});
}

function slugify(url) {
  const u = new URL(url);
  let s = u.pathname.replace(/^\/+|\/+$/g, "").replace(/[^a-zA-Z0-9._-]+/g, "_");
  if (!s) s = "_index";
  if (u.search) s += "_" + u.search.slice(1).replace(/[^a-zA-Z0-9._-]+/g, "_").slice(0, 60);
  if (s.length > 180) s = s.slice(0, 180);
  return s;
}

function isSameOrigin(url) {
  try { return new URL(url).origin === ORIGIN; } catch { return false; }
}

function normalize(url, base) {
  try {
    const u = new URL(url, base);
    u.hash = "";
    // drop common Joomla print/email duplicates
    if (u.searchParams.has("tmpl") && u.searchParams.get("tmpl") === "component") return null;
    if (u.searchParams.has("print")) return null;
    return u.toString();
  } catch { return null; }
}

function classifyHref(url) {
  const lower = url.toLowerCase();
  if (lower.endsWith(".pdf")) return "pdf";
  if (/\.(png|jpe?g|gif|svg|webp)(\?|$)/.test(lower)) return "image";
  if (/\.(zip|doc|docx|xls|xlsx|ppt|pptx)(\?|$)/.test(lower)) return "doc";
  return "page";
}

async function delay(ms) { return new Promise((r) => setTimeout(r, ms)); }

async function fetchOnce(url, kind) {
  try {
    const res = await fetch(url, { headers: { "user-agent": UA, accept: "*/*" } });
    if (!res.ok) {
      errors.push({ url, status: res.status, kind });
      await logLine("FAIL", res.status, url);
      return null;
    }
    if (kind === "binary") return Buffer.from(await res.arrayBuffer());
    return await res.text();
  } catch (e) {
    errors.push({ url, error: String(e), kind });
    await logLine("ERR", String(e?.message ?? e), url);
    return null;
  }
}

function extractMenu($, lang) {
  const tree = [];
  $("ul.dj-megamenu, ul.dj-mega").each((_, ul) => {
    $(ul).find("> li").each((_, li) => {
      const $li = $(li);
      const a = $li.find("> a, > span > a").first();
      const node = {
        label: a.text().trim() || $li.find("span").first().text().trim(),
        href: a.attr("href") ? new URL(a.attr("href"), ORIGIN).toString() : null,
        children: [],
      };
      $li.find("a").each((_, child) => {
        const t = $(child).text().trim();
        const h = $(child).attr("href");
        if (t && h && t !== node.label) {
          node.children.push({ label: t, href: new URL(h, ORIGIN).toString() });
        }
      });
      tree.push(node);
    });
  });
  return tree;
}

function extractForms($, pageUrl) {
  const out = [];
  $("form").each((i, f) => {
    const $f = $(f);
    const fields = [];
    $f.find("input,select,textarea").each((_, el) => {
      const $el = $(el);
      fields.push({
        tag: el.tagName,
        type: $el.attr("type") || "",
        name: $el.attr("name") || "",
        id: $el.attr("id") || "",
        required: $el.attr("required") != null,
        placeholder: $el.attr("placeholder") || "",
      });
    });
    out.push({
      page: pageUrl,
      idx: i,
      action: $f.attr("action") || "",
      method: ($f.attr("method") || "get").toLowerCase(),
      id: $f.attr("id") || "",
      name: $f.attr("name") || "",
      fields,
    });
  });
  return out;
}

function csvEsc(v) {
  const s = String(v ?? "");
  if (/[",\n]/.test(s)) return `"${s.replace(/"/g, '""')}"`;
  return s;
}

async function processPage(url, depth) {
  if (visited.has(url) || visited.size >= MAX_PAGES) return [];
  visited.add(url);
  await logLine("GET", `d=${depth}`, url);
  const html = await fetchOnce(url, "text");
  if (!html) return [];

  const $ = cheerio.load(html);
  const lang = $("html").attr("lang") || (url.includes("/en/") ? "en" : "ms");
  const title = $("title").text().trim();
  const h1 = $("h1").first().text().trim();
  const metaDesc = $('meta[name="description"]').attr("content") || "";
  const h2s = $("h2").map((_, el) => $(el).text().trim()).get().filter(Boolean).join(" | ");
  const wordCount = $("body").text().replace(/\s+/g, " ").trim().split(/\s+/).length;

  const slug = slugify(url);
  await writeFile(path.join(HTML_DIR, slug + ".html"), html);

  pageRows.push({ url, lang, title, h1, h2s, metaDesc, wordCount, slug });

  if (url.includes("/my/") || url.endsWith("/my")) sitemapTree.my.push(url);
  else if (url.includes("/en/") || url.endsWith("/en")) sitemapTree.en.push(url);
  else sitemapTree.other.push(url);

  // forms
  const forms = extractForms($, url);
  if (forms.length) {
    formsList.push(...forms);
    await writeFile(path.join(FORMS_DIR, slug + ".forms.json"), JSON.stringify(forms, null, 2));
  }

  // menu (homepage only)
  if (url === SEEDS[0] || url === SEEDS[1]) {
    const tree = extractMenu($, lang);
    if (tree.length) {
      const fname = url.includes("/en/") ? "menu-tree-en.json" : "menu-tree-bm.json";
      await writeFile(path.join(SITEMAP_DIR, fname), JSON.stringify(tree, null, 2));
    }
  }

  // collect links
  const next = [];
  $("a[href]").each((_, a) => {
    const raw = $(a).attr("href");
    if (!raw) return;
    if (raw.startsWith("javascript:") || raw.startsWith("mailto:") || raw.startsWith("tel:")) return;
    const u = normalize(raw, url);
    if (!u || !isSameOrigin(u)) return;
    const cls = classifyHref(u);
    if (cls === "pdf") {
      if (!pdfQueue.has(u)) pdfQueue.set(u, url);
    } else if (cls === "image") {
      if (!imgQueue.has(u)) imgQueue.set(u, url);
    } else if (cls === "page") {
      if (!visited.has(u) && depth < MAX_DEPTH) next.push(u);
    }
  });

  // og:image / og:logo
  $('meta[property="og:image"], meta[property="og:logo"], img[src]').each((_, el) => {
    const src = $(el).attr("content") || $(el).attr("src");
    if (!src) return;
    const u = normalize(src, url);
    if (u && isSameOrigin(u) && classifyHref(u) === "image" && !imgQueue.has(u)) imgQueue.set(u, url);
  });

  return next;
}

async function downloadBinary(url, destDir) {
  const slug = slugify(url);
  const ext = path.extname(new URL(url).pathname) || ".bin";
  const dest = path.join(destDir, slug + ext);
  if (existsSync(dest)) return dest;
  const buf = await fetchOnce(url, "binary");
  if (!buf) return null;
  await writeFile(dest, buf);
  return dest;
}

async function main() {
  await mkdir(META_DIR, { recursive: true });
  await mkdir(SITEMAP_DIR, { recursive: true });
  await mkdir(HTML_DIR, { recursive: true });
  await mkdir(PDF_DIR, { recursive: true });
  await mkdir(PDF_TEXT_DIR, { recursive: true });
  await mkdir(IMG_DIR, { recursive: true });
  await mkdir(FORMS_DIR, { recursive: true });
  await mkdir(INV_DIR, { recursive: true });

  await writeFile(path.join(META_DIR, "crawl-log.txt"), "");
  await writeFile(path.join(META_DIR, "crawl-config.json"), JSON.stringify({
    target: ORIGIN, seeds: SEEDS, maxDepth: MAX_DEPTH, maxPages: MAX_PAGES,
    delayMs: REQ_DELAY_MS, startedAt: new Date().toISOString(),
  }, null, 2));

  const queue = SEEDS.map((u) => ({ url: u, depth: 0 }));
  while (queue.length && visited.size < MAX_PAGES) {
    const { url, depth } = queue.shift();
    const next = await processPage(url, depth);
    for (const u of next) {
      if (!visited.has(u) && !queue.some((q) => q.url === u)) {
        queue.push({ url: u, depth: depth + 1 });
      }
    }
    await delay(REQ_DELAY_MS);
  }

  await logLine("PAGES_DONE", `count=${visited.size}, pdfs=${pdfQueue.size}, imgs=${imgQueue.size}`);

  // PDFs
  for (const [url, src] of pdfQueue) {
    if (visited.size + pageRows.length > MAX_PAGES * 2) break;
    await logLine("PDF", url);
    const dest = await downloadBinary(url, PDF_DIR);
    if (dest) {
      // pdftotext
      try {
        const { spawn } = await import("node:child_process");
        const txt = path.join(PDF_TEXT_DIR, path.basename(dest, path.extname(dest)) + ".txt");
        await new Promise((res) => {
          const p = spawn("pdftotext", ["-layout", dest, txt]);
          p.on("close", () => res());
          p.on("error", () => res());
        });
      } catch {}
    }
    await delay(REQ_DELAY_MS);
  }

  // Images (cap to avoid blowup)
  let imgCount = 0;
  const IMG_CAP = 200;
  for (const [url] of imgQueue) {
    if (imgCount++ >= IMG_CAP) break;
    await downloadBinary(url, IMG_DIR);
    await delay(300);
  }

  // pdfs index
  const pdfRows = ["filename,source_page,size_bytes"];
  for (const [url, src] of pdfQueue) {
    const slug = slugify(url);
    const ext = path.extname(new URL(url).pathname) || ".pdf";
    const file = slug + ext;
    let size = 0;
    try {
      const st = await import("node:fs/promises");
      size = (await st.stat(path.join(PDF_DIR, file))).size;
    } catch {}
    pdfRows.push([file, src, size].map(csvEsc).join(","));
  }
  await writeFile(path.join(PDF_DIR, "_index.csv"), pdfRows.join("\n"));

  // pages index
  const pageHeader = "url,lang,title,h1,h2s,meta_desc,word_count,slug";
  const pageCsv = [pageHeader, ...pageRows.map((r) => [
    r.url, r.lang, r.title, r.h1, r.h2s, r.metaDesc, r.wordCount, r.slug,
  ].map(csvEsc).join(","))].join("\n");
  await writeFile(path.join(HTML_DIR, "_index.csv"), pageCsv);
  await writeFile(path.join(INV_DIR, "pages.csv"), pageCsv);

  // sitemap
  await writeFile(path.join(SITEMAP_DIR, "sitemap.json"), JSON.stringify(sitemapTree, null, 2));

  // forms summary
  if (formsList.length) {
    await writeFile(path.join(FORMS_DIR, "_summary.json"), JSON.stringify(formsList, null, 2));
  }

  // site-summary.md
  const summary = [
    `# Crawl summary`,
    ``,
    `- Target: ${ORIGIN}`,
    `- Pages crawled: ${visited.size}`,
    `- PDFs queued: ${pdfQueue.size}`,
    `- Images queued: ${imgQueue.size} (capped to ${IMG_CAP} downloads)`,
    `- Forms found: ${formsList.length}`,
    `- Errors: ${errors.length}`,
    `- Started: ${new Date().toISOString()}`,
  ].join("\n");
  await writeFile(path.join(META_DIR, "site-summary.md"), summary);

  if (errors.length) {
    await writeFile(path.join(META_DIR, "errors.json"), JSON.stringify(errors, null, 2));
  }

  await logLine("DONE", `pages=${visited.size} pdfs=${pdfQueue.size} imgs=${Math.min(imgQueue.size, IMG_CAP)} errors=${errors.length}`);
}

main().catch(async (e) => {
  await logLine("FATAL", String(e?.stack || e));
  process.exit(1);
});
