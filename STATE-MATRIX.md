# Phase 4.5 - Interaction State Matrix (LOCKED 2026-05-06)

Reference contract for every Livewire component / page. No "No items found." literal text. All copy via `__('messages.states.*')`.

Visual catalog: `/states` (Laravel route).

Reusable Blade components: `resources/views/components/state/`
- `<x-state.skeleton-row :count="N" />` - row-style skeleton (borang, tender)
- `<x-state.skeleton-card :count="N" :cols="N" />` - card-style skeleton (news)
- `<x-state.loading size="sm|md|lg" :label="..." />` - inline spinner
- `<x-state.empty icon="..." :title="..." :message="..." :cta="..." href="..." tone="neutral|info|warning" />`
- `<x-state.error :title :message :retry="action" :support="email" />`
- `<x-state.toast tone="info|success|warning|error" :title :message :auto-close-ms />`

---

## Matrix

| # | Feature | Loading | Empty | Error | Success |
|---|---------|---------|-------|-------|---------|
| 1 | Borang search | skeleton-row x5 | empty (info) - "Tiada borang sepadan. Cuba kata kunci lain atau hubungi webmaster" + chatbot CTA | error - "Carian tergendala. Cuba semula." + retry btn | results table |
| 2 | News listing | skeleton-card x6 | empty (warning) - "Tiada berita terkini." | toast (warning) - "Sumber tergendala" | cards w/ banner + date |
| 3 | Tender listing | skeleton-row x5 | empty (info) - "Tiada sebut harga aktif." + "Lihat arkib" link | toast (warning) | rows w/ deadline countdown |
| 4 | Service detail (no SOP) | skeleton-row x3 | empty (warning) - "SOP belum diterbitkan. Hubungi bahagian." + email | n/a | SOP rendered |
| 5 | Chatbot first open | n/a | greeting + 5 quick-reply chips + privacy notice | error - "Saya belum tahu. Webmaster: ..." | bot reply + citation + sources |
| 6 | Chatbot mid-conv typing | typing 3-dots animation | n/a | error - "Sambungan tergendala. Cuba semula." | bubble appended |
| 7 | Chatbot LLM quota out | n/a | n/a | n/a | toast (info) - "Mod ringkas aktif" badge + canned answer |
| 8 | Filament dashboard | skeleton bars x4 | empty (info) - "Belum ada data" + admin-doc link | toast top-right (warning) | widgets render |
| 9 | Aduan submit | spinner on btn (disabled) | n/a | inline field-level errors (`aria-invalid`) | success card + reference no. e.g. `JKPTG-MB-2026-00123` |
| 10 | Search results | skeleton-row | empty (info) - "Tiada hasil untuk '{q}'." + 5 popular searches + chatbot CTA | error - "Carian gagal. Hubungi webmaster." | grouped tabs (Pages/Services/News/Forms/FAQ) |
| 11 | Login | spinner on btn | n/a | inline "E-mel atau kata laluan tidak betul." | redirect by role |
| 12 | Persona landing | n/a | n/a | n/a | persona-curated content |
| 13 | /akaun (citizen) | skeleton-card x3 | empty (info) - "Belum ada lawatan terdahulu" + 3 CTA cards | toast (warning) | greeted by name + last visit + topic feed |
| 14 | Form download | spinner on click | n/a | toast (error) - "Fail tidak ditemui." | browser download triggers + counter +1 |
| 15 | Map embed (cawangan) | spinner | empty (warning) - "Lokasi belum ditandakan." | map fallback to static address text | Leaflet renders w/ marker |
| 16 | Megamenu | n/a | n/a | n/a | open with featured cards + categories |

---

## Component Wiring Pattern (Livewire 3)

```blade
@if ($errors)
    <x-state.error :message="$errors" retry="refresh" />
@elseif ($loading)
    <x-state.skeleton-card :count="6" />
@elseif ($items->isEmpty())
    <x-state.empty
        icon="heroicon-o-newspaper"
        :title="__('messages.states.empty.news')"
        tone="warning" />
@else
    {{-- success: render items --}}
@endif
```

For Livewire wire:loading:

```blade
<div wire:loading.remove wire:target="search">
    {{-- normal content --}}
</div>
<div wire:loading wire:target="search">
    <x-state.skeleton-row :count="5" />
</div>
```

---

## Translation keys (`lang/ms/messages.php` + `lang/en/messages.php`)

All state copy lives under `messages.states.*`:

- `states.loading`
- `states.empty.{title|message|borang_search|news|tender|service_no_sop|search|akaun|dashboard}`
- `states.error.{title|message|retry|contact|search_failed|connection|login}`
- `states.success.{submitted|saved}`
- `states.toast.dismiss`
- `states.chatbot.{greeting|privacy|unknown|simple_mode_badge}`

Anti-pattern: hardcoded English/BM strings in Blade. Always `__('messages.states.*')`.

---

## Verification

- `/states` route renders all 16 state variants without errors
- Toast `auto-close-ms="0"` keeps visible for inspection (production: auto-close 5000ms)
- Skeletons have `role="status"` + `aria-live="polite"` + `aria-label="Sedang memuat..."` for screen readers
- Errors have `role="alert"` + `aria-live="assertive"`
- All interactive elements keyboard-reachable, ESC dismisses toasts/dialogs

---

## Status

| Item | Done |
|------|------|
| 6 Blade components | yes |
| BM + EN translation keys (28 phrases) | yes |
| `/states` demo route + view (16 examples) | yes |
| STATE-MATRIX.md contract | yes |
| Phases 5-12 will reference this contract when building features | pending |
