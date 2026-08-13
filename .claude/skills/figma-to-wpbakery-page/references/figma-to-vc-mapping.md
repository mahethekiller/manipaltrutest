# Translating a Figma frame into WPBakery shortcodes

This is a set of heuristics, not a mechanical algorithm — use judgment, and lean on the
screenshot to sanity-check the result, not just the layer tree.

## 1. Read structure before content

Pull `get_design_context` (or `get_metadata` for an overview first if the frame is large) for the
target node. Note, in order:
- The frame's own `layoutMode` (`HORIZONTAL` / `VERTICAL` / none) and `itemSpacing` — this tells
  you row vs. stack vs. free-form.
- Direct children only, first. Don't recurse mentally into grandchildren until you've decided what
  each direct child becomes.

## 2. Map structure to shortcodes

| Figma pattern | WPBakery shape |
|---|---|
| Top-level frame representing one visual "section" (a colored band, a distinct block in the scroll) | One `[vc_row]` |
| `layoutMode: HORIZONTAL` frame, direct children are side-by-side blocks | One `[vc_column]` per child, `width` = child width ÷ row width, rounded to the nearest supported fraction (see table below) |
| `layoutMode: VERTICAL` frame (a stack) | Shortcodes placed sequentially inside one `[vc_column]`, in child order |
| A grid of repeating identical child frames (e.g. 3 or 5 near-identical "card" frames — same structure, different text/image) | Build the shortcode for **one** card, then repeat it — either as multiple `[vc_column]`s of equal width inside one `[vc_row]` (good for ≤4 items), or check whether a `tek_*` carousel/grid shortcode (`tek_photocarousel`, `tek_masonrygallery_elem`, `tek_testimonialcards`) already models "N repeating items" — if so, prefer that over hand-duplicating columns, since it comes with the theme's own responsive/carousel behavior for free |
| A text node (heading style) as the first element of a section | `tek_sectiontitle` (has title/subtitle/separator built in) rather than raw `vc_column_text` — matches the theme's existing section-heading look automatically |
| A plain paragraph/text node elsewhere | `vc_column_text` |
| An image fill (photo) | `vc_single_image` — see asset pipeline below for getting the attachment ID |
| A rounded-rect / icon + short label + short text, repeated in a row (feature list, "why choose us") | `tek_iconbox` |
| A card with image + title + price + CTA (pricing/package card — the exact shape of the "Health Packages" / "Package Customization" screens) | `tek_pricing` or `tek_priceblock` — inspect both with `inspect-shortcode` and pick whichever's params cover more of what the Figma card shows (image support, feature list, CTA button) |
| A banner/CTA strip (heading + button, often full-bleed background) | `tek_calltoaction` |
| A rounded button shape with hover states implied by the design | `tek_button` (theme's own) if it needs to match the site's existing button styling library; `vc_btn` if it's a one-off style |
| Vertical gaps between sibling frames that aren't their own visual section | `vc_empty_space` with `height` = the gap's pixel value, instead of stretching column padding |

### Column width → VC fraction

VC column widths are fractions, not pixels. Convert:

```
fraction = child_width / row_width
```
then snap to the nearest of: `1/1, 3/4, 2/3, 1/2, 1/3, 1/4, 1/6`. If the Figma columns are
unequal and don't cleanly divide (e.g. 55/45), snap each to the nearest supported fraction rather
than inventing a custom width — small pixel differences from Figma rarely matter at the page-builder
level, and it's better to stay within VC's native responsive grid than fight it with custom CSS
widths.

## 3. Asset pipeline (images, icons)

1. `download_assets` (Figma MCP) on the target node — gives you `rawImages` (photos) and
   `svgAssets` (icons/logos) as downloadable files.
2. Save each to a scratch folder, then import into the WP media library:
   ```
   php scripts/wp_helper.php import-image <path> "<descriptive title>"
   ```
   This returns an `attachment_id` — use that in `vc_single_image image="<id>"` or wherever a
   shortcode's image param expects one.
3. For icons that map to `tek_iconbox`/`vc_icon`, check first whether the theme's bundled icon
   font (Font Awesome, per `vc_icon icon_fontawesome`) already has a close match before importing
   the Figma icon as a raster/SVG image — using the icon font keeps it crisp at any size and
   themeable by color, which a flattened image export can't do.

## 4. Colors, type, spacing

Pull exact values from `get_design_context`'s returned styles (or `get_variable_defs` if the file
uses Figma variables/styles) rather than eyeballing the screenshot. Apply them:
- First, via the shortcode's own params if it has a matching one (`title_color`, `separator_color`,
  etc. — check with `inspect-shortcode`).
- Otherwise, via that shortcode's `css=""` attribute (see `vc-core-shortcodes.md`).
- Only touch `ekko-child/style.css` (already enqueued after the parent theme, see the theme's
  `functions.php`) if something genuinely can't be expressed at the shortcode level (e.g. a
  hover/focus state, a `@font-face`, a custom animation) — keep page-specific styling in the page
  content where possible so it stays visible/editable from wp-admin.

## Known gotchas (silent-failure shortcode params)

`inspect-shortcode` shows every param a shortcode *accepts*, but not which ones are secretly
**required for the output to render at all** — some `tek_*` elements have PHP templates with a
conditional around a piece of content, no `else`, and no sensible default, so leaving the param
unset doesn't error, it just silently drops that content. Confirmed cases (found by reading the
plugin source directly, since no tool surfaces this):

- **`tek_iconbox`** — the title renders nothing unless `title_size` is set (e.g.
  `title_size="large-title"`). Always set it explicitly.
- **`tek_pricing`** — the price renders nothing unless `pricing_currency_position` is set. Always
  set it explicitly.

If a rendered page is missing text/values that are clearly present in the shortcode source, treat
"a required-looking param was left unset" as the first hypothesis, and check the element's PHP in
`wp-content/plugins/keydesign-addon/elements/` for an `if ($atts['param'])`-style conditional with
no fallback — not just `inspect-shortcode`'s param list. Add any newly-found case here.

## 5. Verify

After writing the page content (`wp_helper.php set-content`), load the live page and compare
against the Figma screenshot (`get_screenshot` on the same node) side by side. Check at minimum:
section order, column proportions at desktop width, and that no text overflowed a shortcode's
container. Note any deliberate deviations to the user rather than silently leaving them.
