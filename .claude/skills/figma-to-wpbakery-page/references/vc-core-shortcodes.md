# WPBakery core shortcodes — quick reference

Stable js_composer (WPBakery Page Builder) shortcodes, confirmed present via `inspect-shortcode`
on this install (v8.7.2). These are the generic building blocks; for anything theme-flavored
(icon boxes, pricing tables, section titles, carousels, etc.) check
`figma-to-vc-mapping.md` → prefer the Ekko theme's `tek_*` elements instead, since they already
carry the site's look and feel.

Attribute values below are the common/default ones. Always confirm exact `param_name`s and
allowed values for a shortcode you haven't used yet with:

```
php scripts/wp_helper.php inspect-shortcode <base>
```

## Layout

**`vc_row`** — a full-width section. Wraps one or more `vc_column`.
```
[vc_row][vc_column][/vc_column][/vc_row]
```

**`vc_column`** — a column inside a row. `width` is a fraction string, not raw CSS: `1/1` (full),
`1/2`, `1/3`, `2/3`, `1/4`, `3/4`, `1/6`, `5/6`. Default is `1/1`. Multiple columns in one row
should sum to `1/1`.
```
[vc_column width="1/3"]...[/vc_column][vc_column width="2/3"]...[/vc_column]
```

**`vc_row_inner` / `vc_column_inner`** — same as above, but nested inside a `vc_column` when a
Figma frame has a layout grid inside another layout grid (e.g. a 3-up card row sitting inside one
column of a 2-column section).

## Content

**`vc_column_text`** — a block of rich text/HTML. Content goes between the tags, not in an
attribute.
```
[vc_column_text]<h2>Heading</h2><p>Body copy.</p>[/vc_column_text]
```

**`vc_single_image`** — one image. `image` is a media library **attachment ID** (get this from
`wp_helper.php import-image`), not a URL/path.
```
[vc_single_image image="42" img_size="full" alignment="center"]
```

**`vc_btn`** — a button/CTA.
```
[vc_btn title="Book Now" style="flat" color="theme_style_2" size="lg" align="center" link="url:https%3A%2F%2Fexample.com||target:%20_blank|"]
```

**`vc_empty_space`** — vertical spacer, matches Figma's auto-layout gaps between sections.
```
[vc_empty_space height="40px"]
```

**`vc_separator`** — horizontal rule/divider.
```
[vc_separator color="grey" border_width="1"]
```

**`vc_icon`** — a standalone icon (Font Awesome / theme icon set).
```
[vc_icon icon_fontawesome="fas fa-heartbeat" color="theme_style_2" size="lg"]
```

**`vc_raw_html`** — escape hatch for markup that has no clean shortcode equivalent (rare — prefer
a real shortcode first; raw HTML doesn't get WPBakery's responsive/animation handling).

## Tabs / toggles / gallery (for content-heavy Figma sections)

**`vc_tta_tabs`** wrapping **`vc_tta_section`** — tabbed content.
**`vc_toggle`** — accordion/FAQ-style collapse.
**`vc_gallery`** — image gallery grid; `images` takes comma-separated attachment IDs.

## The `css` attribute (applies to almost every shortcode)

Every VC shortcode accepts a `css="vc_custom_..."` attribute holding inline CSS that WPBakery
compiles into a generated class. Use this for one-off Figma-specific values (an exact background
color, padding, border-radius) that a shortcode's own dropdown params don't expose — but don't
reach for it first. Check the shortcode's own params (via `inspect-shortcode`) before falling back
to raw `css`, since native params respect the theme's responsive/breakpoint behavior and `css`
does not.

Example:
```
[vc_row css=".vc_custom_1234567890{background-color: #0a4d8c !important;padding-top: 80px !important;padding-bottom: 80px !important;}"]
```
