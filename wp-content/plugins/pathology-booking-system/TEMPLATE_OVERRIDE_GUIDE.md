# Page Template Override Developer Guide

This guide details how to customize and override the default page templates provided by the **Pathology Test & Package Booking System** (`pathology-booking-system`) inside your active WordPress theme or child theme (such as `ekko-child`).

---

## 1. How Template Overrides Work

The plugin features a WooCommerce-style template hierarchy system implemented via `PTBS_Template_Loader`. When a visitor navigates to a Pathology Test, Health Package, Center Location single page, or catalog archive, the plugin searches for template files in the following priority order:

1. `wp-content/themes/{active-child-theme}/pathology-booking-system/{template-name}`
2. `wp-content/themes/{active-parent-theme}/pathology-booking-system/{template-name}`
3. `wp-content/themes/{active-child-theme}/{template-name}`
4. `wp-content/themes/{active-parent-theme}/{template-name}`
5. Plugin Fallback: `wp-content/plugins/pathology-booking-system/public/templates/{template-name}`

---

## 2. Available Template Files

| Template File Name | Description | Default Path in Plugin |
| :--- | :--- | :--- |
| `single-ptbs_test.php` | Single Pathology Test Page layout | `public/templates/single-ptbs_test.php` |
| `single-ptbs_package.php` | Single Health Package Page layout | `public/templates/single-ptbs_package.php` |
| `single-ptbs_center_location.php` | Single Lab Center Location Page layout | `public/templates/single-ptbs_center_location.php` |
| `taxonomy-ptbs_category.php` | Category & Subcategory archive layout | `public/templates/taxonomy-ptbs_category.php` |
| `archive-ptbs_test.php` | Main Lab Catalog archive layout | `public/templates/archive-ptbs_test.php` |

---

## 3. Step-by-Step Customization Example

To override the Single Pathology Test template (`single-ptbs_test.php`) in your Ekko Child Theme (`ekko-child`):

1. Create a folder named `pathology-booking-system` inside your child theme directory:
   ```text
   wp-content/themes/ekko-child/pathology-booking-system/
   ```
2. Copy `single-ptbs_test.php` from `wp-content/plugins/pathology-booking-system/public/templates/single-ptbs_test.php` into the new folder:
   ```text
   wp-content/themes/ekko-child/pathology-booking-system/single-ptbs_test.php
   ```
3. Edit `ekko-child/pathology-booking-system/single-ptbs_test.php` to add custom headers, hero sections, sidebar widgets, or custom CSS styling.

---

## 4. Developer Filter Hook (`ptbs_locate_template`)

You can dynamically swap or override template locations in PHP using the `ptbs_locate_template` filter hook:

```php
/**
 * Custom template locator filter
 */
function custom_ptbs_template_override( $template, $template_name, $template_path ) {
    if ( 'single-ptbs_test.php' === $template_name ) {
        // Point to a custom template location
        $custom_path = get_stylesheet_directory() . '/custom-templates/my-test-template.php';
        if ( file_exists( $custom_path ) ) {
            return $custom_path;
        }
    }
    return $template;
}
add_filter( 'ptbs_locate_template', 'custom_ptbs_template_override', 10, 3 );
```
