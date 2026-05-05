#!/usr/bin/env node
/**
 * Lightweight xlsx -> per-sheet CSV converter (no deps).
 * xlsx file is a zip of XML. We unzip into memory, parse sheets via regex
 * (good enough for compliance matrices — no formulas, no formatting).
 */
import { readFileSync, writeFileSync, mkdirSync } from "node:fs";
import { gunzipSync, inflateRawSync } from "node:zlib";
import path from "node:path";

const xlsxPath = process.argv[2];
const outDir = process.argv[3] || path.dirname(xlsxPath);
if (!xlsxPath) { console.error("usage: xlsx-to-csv.mjs <file.xlsx> [outDir]"); process.exit(1); }

console.error("DEBUG xlsxPath=", xlsxPath, "outDir=", outDir);
const buf = readFileSync(xlsxPath);
console.error("DEBUG buf bytes=", buf.length);

// minimal zip reader (PKZIP)
function readZip(buf) {
  const files = {};
  // find End of Central Directory
  let eocdOff = buf.length - 22;
  while (eocdOff > 0 && buf.readUInt32LE(eocdOff) !== 0x06054b50) eocdOff--;
  if (eocdOff < 0) throw new Error("not zip");
  const cdOff = buf.readUInt32LE(eocdOff + 16);
  const cdEntries = buf.readUInt16LE(eocdOff + 10);

  let p = cdOff;
  for (let i = 0; i < cdEntries; i++) {
    if (buf.readUInt32LE(p) !== 0x02014b50) throw new Error("bad central dir");
    const method = buf.readUInt16LE(p + 10);
    const compSize = buf.readUInt32LE(p + 20);
    const uncompSize = buf.readUInt32LE(p + 24);
    const fnLen = buf.readUInt16LE(p + 28);
    const extraLen = buf.readUInt16LE(p + 30);
    const cmtLen = buf.readUInt16LE(p + 32);
    const lhOff = buf.readUInt32LE(p + 42);
    const name = buf.slice(p + 46, p + 46 + fnLen).toString("utf8");

    // local header
    const lhFnLen = buf.readUInt16LE(lhOff + 26);
    const lhExtraLen = buf.readUInt16LE(lhOff + 28);
    const dataOff = lhOff + 30 + lhFnLen + lhExtraLen;
    const compData = buf.slice(dataOff, dataOff + compSize);

    let data;
    if (method === 0) data = compData;
    else if (method === 8) data = inflateRawSync(compData);
    else throw new Error("unsupported zip method " + method);

    files[name] = data;
    p += 46 + fnLen + extraLen + cmtLen;
  }
  return files;
}

const zip = readZip(buf);

// shared strings
const ssXml = zip["xl/sharedStrings.xml"]?.toString("utf8") || "";
const sharedStrings = [];
for (const m of ssXml.matchAll(/<si\b[^>]*>([\s\S]*?)<\/si>/g)) {
  // collect all <t> children
  const texts = [...m[1].matchAll(/<t[^>]*>([\s\S]*?)<\/t>/g)].map((x) =>
    x[1].replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&apos;/g, "'")
  );
  sharedStrings.push(texts.join(""));
}

// workbook -> sheet name + r:id
const wbXml = zip["xl/workbook.xml"]?.toString("utf8") || "";
const wbRelsXml = zip["xl/_rels/workbook.xml.rels"]?.toString("utf8") || "";
console.error("DEBUG wbXml chars=", wbXml.length);
const sheets = [];
for (const m of wbXml.matchAll(/<sheet\b([^/]*?)\/>/g)) {
  const attrs = m[1];
  const name = /name="([^"]+)"/.exec(attrs)?.[1] || "Sheet";
  const rid = /r:id="([^"]+)"/.exec(attrs)?.[1] || "";
  sheets.push({ name, rid });
}
console.error("DEBUG sheets=", JSON.stringify(sheets));
const rels = {};
console.error("DEBUG wbRelsXml chars=", wbRelsXml.length, "head=", wbRelsXml.slice(0, 200));
for (const m of wbRelsXml.matchAll(/<Relationship\b([^>]*?)\/>/g)) {
  const a = m[1];
  const id = /Id="([^"]+)"/.exec(a)?.[1];
  const target = /Target="([^"]+)"/.exec(a)?.[1];
  if (id && target) rels[id] = target;
}
console.error("DEBUG rels=", JSON.stringify(rels));

function colA1ToIdx(col) {
  let n = 0;
  for (const ch of col) n = n * 26 + (ch.charCodeAt(0) - 64);
  return n - 1;
}

function parseSheet(xml) {
  const rows = [];
  for (const rm of xml.matchAll(/<row\b[^>]*>([\s\S]*?)<\/row>/g)) {
    const cells = [];
    let maxCol = -1;
    for (const cm of rm[1].matchAll(/<c\b([^>]*)>([\s\S]*?)<\/c>|<c\b([^>]*?)\/>/g)) {
      const attrs = cm[1] ?? cm[3] ?? "";
      const inner = cm[2] ?? "";
      const ref = /r="([A-Z]+)\d+"/.exec(attrs)?.[1];
      const t = /t="([^"]+)"/.exec(attrs)?.[1] || "n";
      const colIdx = ref ? colA1ToIdx(ref) : cells.length;
      let val = "";
      if (t === "s") {
        const v = /<v>([\s\S]*?)<\/v>/.exec(inner)?.[1];
        if (v != null) val = sharedStrings[parseInt(v, 10)] ?? "";
      } else if (t === "inlineStr") {
        val = [...inner.matchAll(/<t[^>]*>([\s\S]*?)<\/t>/g)].map((m) => m[1]).join("");
      } else {
        val = /<v>([\s\S]*?)<\/v>/.exec(inner)?.[1] || "";
      }
      while (cells.length < colIdx) cells.push("");
      cells.push(val);
      if (colIdx > maxCol) maxCol = colIdx;
    }
    rows.push(cells);
  }
  return rows;
}

function csvEsc(s) {
  s = String(s ?? "").replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&apos;/g, "'");
  if (/[",\n]/.test(s)) return `"${s.replace(/"/g, '""')}"`;
  return s;
}

mkdirSync(outDir, { recursive: true });
const summary = [];
for (const sh of sheets) {
  const target = rels[sh.rid];
  if (!target) continue;
  const path1 = "xl/" + target.replace(/^\/?xl\//, "");
  const xml = zip[path1]?.toString("utf8");
  if (!xml) { console.warn("missing", path1); continue; }
  const rows = parseSheet(xml);
  const safe = sh.name.replace(/[^a-zA-Z0-9._-]+/g, "_");
  const fname = path.join(outDir, `SOC-${safe}.csv`);
  writeFileSync(fname, rows.map((r) => r.map(csvEsc).join(",")).join("\n"));
  summary.push({ sheet: sh.name, rows: rows.length, file: fname });
  console.log(`wrote ${rows.length} rows -> ${fname}`);
}
console.log(JSON.stringify(summary, null, 2));
