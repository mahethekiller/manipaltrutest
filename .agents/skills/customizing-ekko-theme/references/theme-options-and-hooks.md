# Ekko Theme Options, Hooks & Advanced Customizations

This reference provides detailed technical specifications for programmatically extending and configuring the Ekko WordPress Theme by KeyDesign.

---

## 1. Redux Theme Options Programmatic Overrides

Ekko stores its global options in the `ekko_options` Redux instance. You can override settings programmatically using the `redux/options/ekko_options/options` filter hook.

### Example: Programmatically Force Theme Options
```php
/**
 * Override Ekko theme options programmatically.
 */
function ekko_child_override_theme_options( $options ) {
    // Force primary color
    $options['tek_accent_color'] = '#0055ff';
    
    // Enable sticky header programmatically
    $options['tek_header_sticky'] = '1';
    
    // Customize footer copyright text
    $options['tek_footer_copyright'] = '&copy; ' . date('Y') . ' Custom Brand Name. All rights reserved.';
    
    return $options;
}
add_filter( 'redux/options/ekko_options/options', 'ekko_child_override_theme_options' );
```

---

## 2. Ekko Theme Hooks & Template Entry Points

Ekko provides action hooks throughout template header, content, and footer sections:

### Action Hooks Table
| Hook Name | Location / Execution Point | Primary Use Case |
|---|---|---|
| `ekko_before_header` | Right after `<body>` opening tag, before site header | Injecting top notice banners, alert bars, Google Tag Manager `<body>` script |
| `ekko_header_topbar` | Inside header topbar container | Adding custom contact icons, language switcher, or WPML selectors |
| `ekko_after_header` | Immediately after header navigation | Inserting hero banners, notification popups, or slider breadcrumbs |
| `ekko_before_footer` | Before `#site-footer` section | Inserting newsletter registration blocks, call-to-action sections |
| `ekko_after_footer` | Before `</body>` closing tag | Injecting tracking scripts, modal windows, or chat widgets |

### Example: Injecting a Custom Top Notice Bar
```php
function ekko_child_custom_notice_bar() {
    if ( is_front_page() ) {
        echo '<div class="ekko-custom-notice-bar"><div class="container"><p>Special Announcement: New Services Available!</p></div></div>';
    }
}
add_action( 'ekko_before_header', 'ekko_child_custom_notice_bar' );
```

---

## 3. KeyDesign Shortcode Overrides in WPBakery

KeyDesign Addons plugin powers custom WPBakery elements (`tek_*`).

### Overriding Shortcode Layout Templates
1. Locate shortcode template file in parent plugin:
   `wp-content/plugins/keydesign-addon/elements/templates/tek_feature_sections.php`
2. Create folder `vc_templates` in child theme:
   `wp-content/themes/ekko-child/vc_templates/`
3. Copy template file to `ekko-child/vc_templates/tek_feature_sections.php` and make your edits. WPBakery will automatically prioritize the child theme copy.

---

## 4. Customizing Ekko Navigation & Mega Menu

Ekko includes built-in Mega Menu support via WordPress Menu Manager (`Appearance -> Menus`):
- **Menu Locations**:
  - `primary-menu`: Main header navbar
  - `topbar-menu`: Header top bar links
  - `footer-menu`: Bottom footer links
- **Mega Menu Configuration**:
  1. Go to `Appearance -> Menus`.
  2. Expand top-level menu item.
  3. Check **Enable Mega Menu**.
  4. Select number of columns (2, 3, 4, or 5 columns).
  5. Add sub-items as column headings and child links under column headings.

---

## 5. Performance & Speed Optimization Guidelines for Ekko

1. **Optimize KeyDesign CSS/JS**: Disable unused KeyDesign elements under `KeyDesign -> Plugin Settings -> Active Elements`.
2. **Font Loading**: Use `font-display: swap;` for custom web fonts loaded in `style.css`.
3. **WPBakery Optimization**: Disable unused grid animations and Revolution Slider scripts on pages where sliders are not present.
