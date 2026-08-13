---
name: customizing-ekko-theme
description: Provides comprehensive instructions, child theme standards, Theme Options setup, WPBakery KeyDesign content element overrides, navigation/mega menu configuration, and PHP hook extensions for the Ekko WordPress Theme by KeyDesign. Use when customizing Ekko theme, modifying Ekko child theme files, extending WPBakery elements, or configuring Ekko options.
---

# Ekko WordPress Theme Customization

Expert guide and instructions for customizing and extending the **Ekko WordPress Theme** by KeyDesign Themes.

## When to Use This Skill
- Customizing or modifying the Ekko WordPress Theme layout, styles, or functionality.
- Setting up or editing the Ekko Child Theme (`functions.php`, `style.css`, template overrides).
- Configuring Ekko Theme Options (Redux framework options, colors, headers, typography).
- Extending WPBakery Page Builder elements provided by KeyDesign Elements.
- Implementing custom action hooks, filters, mega menus, or custom post type templates in Ekko.

---

## Workflow Checklist

Copy and maintain this checklist when executing Ekko theme customizations:

```markdown
- [ ] 1. Verify Ekko Parent Theme & Child Theme installation status.
- [ ] 2. Check system requirements (PHP >= 7.4, memory_limit >= 25MB/256MB, max_execution_time >= 300).
- [ ] 3. Ensure changes are placed in `ekko-child/` to preserve theme update safety.
- [ ] 4. Enqueue parent styles and custom assets correctly in `ekko-child/functions.php`.
- [ ] 5. Override templates by copying parent template files into `ekko-child/` following path structure.
- [ ] 6. Test WPBakery Page Builder elements and responsiveness across desktop, tablet, and mobile.
- [ ] 7. Validate custom CSS/JS for theme design consistency and performance.
```

---

## Key Customization Standards & Rules

### 1. Child Theme Priority Rule
- **NEVER** edit files directly inside the parent `ekko/` folder. All modifications MUST occur in `ekko-child/`.
- File structure in `ekko-child`:
  ```text
  ekko-child/
  ├── functions.php          # Enqueue hooks, custom PHP logic & filters
  ├── style.css              # Custom CSS overrides (Theme Name: Ekko Child)
  ├── templates/             # Custom page/section template overrides
  ├── single-portfolio.php   # Custom portfolio item template override
  └── js/
      └── custom-child.js    # Custom child theme JavaScript
  ```

### 2. Correct Child Theme Asset Enqueuing
In `ekko-child/functions.php`, use proper WP dependencies:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function ekko_child_enqueue_styles() {
    // Enqueue Parent Style
    wp_enqueue_style( 'ekko-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Enqueue Child Style
    wp_enqueue_style( 'ekko-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'ekko-parent-style' ),
        wp_get_theme()->get('Version')
    );

    // Enqueue Custom Child Script
    wp_enqueue_script( 'ekko-child-script',
        get_stylesheet_directory_uri() . '/js/custom-child.js',
        array( 'jquery' ),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'ekko_child_enqueue_styles', 20 );
```

---

## Ekko Core Components & Customization Areas

### A. Theme Options (Redux Framework)
Ekko uses Redux Framework for central configuration (`Appearance -> Theme Options`):
- **Global Settings**: Palette colors (`#0066FF` primary brand default), container width (1200px), site layout (Boxed / Fullwidth).
- **Header & Topbar**: Topbar contact info, sticky header, logo display, pop-up modal triggers, side panel widget areas.
- **Typography**: Google Fonts integration, custom font imports (@font-face / Typekit), line heights, heading scales.
- **Footer**: Widget layout columns (1-4 columns), copyright text, footer menu.

*See detailed options reference in [references/theme-options-and-hooks.md](references/theme-options-and-hooks.md).*

### B. WPBakery & KeyDesign Content Elements
Ekko extends WPBakery with custom KeyDesign shortcodes (e.g. `[tek_button]`, `[tek_feature_sections]`, `[tek_portfolio]`, `[tek_team]`, `[tek_testimonial]`):
- **Customizing Shortcode Templates**: KeyDesign shortcode views live in `plugins/keydesign-addon/elements/`. To override, copy shortcode template files into `ekko-child/vc_templates/`.
- **Enabling Builder for CPTs**: Enable WPBakery Page Builder for `portfolio` and `post` under `WPBakery Page Builder -> Role Manager -> Post Types -> Custom`.

---

## Detailed References & Examples
- [Theme Options & Hooks Reference](references/theme-options-and-hooks.md)
- [Sample Child Theme functions.php](examples/child-theme-functions.php)
