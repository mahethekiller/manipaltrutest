---
name: figma-to-wpbakery-page
description: >
  Turns a Figma design (a figma.com/design/... link, with or without a node-id) into a real,
  live WordPress page on the manipaltrutest site (D:\SOFTWARES\xampp82new\htdocs\manipaltrutest),
  built as WPBakery Page Builder shortcode content on the active Ekko theme. Use this whenever
  the user shares a Figma link and wants it "built", "implemented", "made real", "put on the
  site", or turned into a page/section — even if they don't say "WPBakery" or "shortcode"
  explicitly. Also use when the user asks to update, extend, or pixel-match an existing page
  against a Figma design, or asks what shortcodes/elements are available on this theme. Do not
  use for plugin/PHP-logic work (that's the wp-plugin-* subagents) or for editing raw theme PHP
  templates — this skill's output is page *content* (shortcodes in a page's post_content), not
  template files.
---

# Figma → WPBakery page (manipaltrutest / Ekko theme)

## Why this exists

This project's WordPress install already has a page-builder (WPBakery / js_composer 8.7.2) and
theme (Ekko, active as the parent theme — `ekko-child` exists but is currently inactive) doing all
the rendering. Handing Figma a design and getting a real page out the other end is a translation
problem — Figma frame tree → WPBakery shortcode tree — not a build-a-plugin problem, so it doesn't
need the full `wp-plugin-architect → wp-plugin-builder → wp-wpcs-reviewer` pipeline. What it does
need is: read the design correctly, pick shortcodes that actually exist with the params they
actually have (not remembered/guessed ones — this theme bundles ~45 custom `tek_*` shortcodes on
top of core WPBakery), get images into the media library, and write the result into the target
page safely.

There's no WP-CLI on this install, so a small bundled PHP script (`scripts/wp_helper.php`) stands
in for it — see below.

## Environment facts (established, don't re-derive)

- Project root: `D:\SOFTWARES\xampp82new\htdocs\manipaltrutest`
- Site URL: `http://localhost/manipaltrutest`
- PHP CLI: `D:/SOFTWARES/xampp82new/php/php.exe` — boots WordPress fine via `wp-load.php` with the
  install's own `DB_HOST=localhost` (no CLI/DB-connection workaround needed here, unlike the
  sibling `wp` install — see the `wp-local-db-environment` memory, which does NOT apply to this DB).
- Active theme: **`ekko`** (parent), not `ekko-child`. WPBakery (`js_composer`) and the theme's own
  `keydesign-addon` plugin (the `tek_*` shortcodes) are both active.
- No WP-CLI installed → use `scripts/wp_helper.php` instead (see next section).

## Workflow

1. **Get the Figma reference.** From the user's URL, extract `fileKey` and `nodeId`. If there's no
   `node-id` in the URL, ask which frame/page they mean rather than guessing — building the wrong
   section wastes the whole pipeline. Call `get_design_context` (or `get_metadata` first if the
   node is large/unknown) and `get_screenshot` for the target node.

2. **Figure out the target WordPress page.** Run:
   ```
   php scripts/wp_helper.php list-pages
   ```
   Match against what the user said, or ask them which page (existing or new) this design should
   become. If it needs to be a new page, create it first the normal way (via wp-admin, or leave a
   placeholder page) before writing content into it — this script edits existing posts, it doesn't
   create pages.

3. **Translate the design into shortcodes.** Follow `references/figma-to-vc-mapping.md` for the
   structural heuristics (row/column/stack decisions, repeating-card handling, column-width
   rounding) and `references/vc-core-shortcodes.md` for core WPBakery syntax. Before using any
   shortcode — core or `tek_*` — confirm its real params with:
   ```
   php scripts/wp_helper.php inspect-shortcode <base>          # e.g. tek_pricing, vc_row
   php scripts/wp_helper.php list-tek-shortcodes                # browse what's available
   ```
   This matters more than it sounds like it should: guessing a plausible-looking attribute name
   silently no-ops in WPBakery rather than erroring, so the fastest way to end up with a page that
   *looks* right in the shortcode source but *renders* wrong is to skip this step.

4. **Pull and import image assets.** Use `download_assets` on the relevant Figma nodes, save the
   files locally, then import each into the media library:
   ```
   php scripts/wp_helper.php import-image <path> "<title>"
   ```
   Use the returned `attachment_id` in shortcode image params. Don't reference Figma's own
   temporary asset URLs directly in the page content — they expire.

5. **Write the page content.** Assemble the full shortcode string into a file, then:
   ```
   php scripts/wp_helper.php set-content <page_id> <path-to-shortcode-file>
   ```
   This automatically backs up the page's previous content to `backups/` before overwriting —
   mention the backup path to the user so they know it's recoverable. For an existing page with
   real content already on it, tell the user what you're about to replace before running this.

6. **Verify against the design.** Load the live page (Browser tool) and compare it to the
   `get_screenshot` reference. Check section order, column proportions, and text overflow at
   minimum. Report any deliberate deviations (a missing font, an icon substituted for a raster
   image, a color approximated) rather than letting them pass silently.

   The Browser pane's screenshot compositing has been unreliable in this environment ("the
   Browser pane is not displayed" even after `preview_start`/`navigate` succeed) — don't burn
   retries on it. If it fails, fall back to structural verification instead of skipping
   verification entirely: `get_page_text` for the rendered copy, and `javascript_tool` to inspect
   specific elements (e.g. confirm a shortcode's expected text/attributes actually landed in the
   DOM, not just that the page returned 200). State plainly that visual/pixel verification wasn't
   possible if the screenshot path fails — don't imply you visually confirmed fidelity when you
   only confirmed content structure.

## Scope reminder

If the ask drifts into new PHP behavior (a custom post type, a form handler, a REST endpoint) —
that's plugin/functionality work, not page content. Hand off to `wp-plugin-architect` /
`wp-plugin-builder` per this project's standard flow instead of trying to force it into a
shortcode.
