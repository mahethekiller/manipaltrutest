# Plugin Specification: [Plugin Name]

> **Slug:** `[plugin-slug]`  
> **Prefix:** `[prefix_]`  
> **Text Domain:** `[plugin-slug]`  
> **Target WP Version:** 6.0+  
> **Target PHP Version:** 7.4+  
> **License:** GPL-2.0-or-later  

---

## 1. Executive Summary & Purpose
[Provide a clear 2-3 sentence overview of the plugin's goal, problem solved, and target audience.]

---

## 2. Key Features List
- **Feature 1**: [Description]
- **Feature 2**: [Description]
- **Feature 3**: [Description]

---

## 3. Architecture & File Directory Layout

```
[plugin-slug]/
├── [plugin-slug].php         # Main plugin header & loader file
├── readme.txt                # WordPress.org compliant readme file
├── uninstall.php             # Cleanup script (deletes options/tables on delete)
├── includes/                 # Core business logic & database managers
│   ├── class-[prefix]-core.php
│   ├── class-[prefix]-db.php
│   └── class-[prefix]-api.php
├── admin/                    # Admin dashboard UI, settings & assets
│   ├── class-[prefix]-admin.php
│   ├── css/[prefix]-admin.css
│   └── js/[prefix]-admin.js
├── public/                   # Public frontend rendering, shortcodes & blocks
│   ├── class-[prefix]-public.php
│   ├── css/[prefix]-public.css
│   └── js/[prefix]-public.js
└── languages/                # i18n Translation template (.pot)
```

---

## 4. Data Architecture & Database Schema

### Options API (`wp_options`)
- Option Key: `[prefix]_settings`
  - Array schema: `array( 'setting_1' => '', 'api_key' => '' )`

### Custom Post Types / Meta (if applicable)
- CPT Name: `[prefix]_item`
- Meta Keys: `_[prefix]_meta_key`

### Custom DB Tables (if applicable)
```sql
CREATE TABLE {$wpdb->prefix}[prefix]_logs (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
) {$charset_collate};
```

---

## 5. Security & WordPress.org Compliance Strategy
- **Direct Access Prevention**: All PHP files contain `defined('ABSPATH') || exit;`.
- **Prefixing**: Every function, class, hook, option, and table uses `[prefix]_`.
- **Capability Check**: Admin screens restricted via `current_user_can('manage_options')`.
- **Nonce Action**: `[prefix]_save_settings_nonce`.
- **Sanitization Mapping**: `sanitize_text_field()`, `absint()`, `sanitize_key()`.
- **Escaping Mapping**: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`.
- **Trademark Compliance**: Title does not begin with "WordPress".

---

## 6. Implementation Handoff Checklist
- [ ] Present specification to user for signoff.
- [ ] Initialize file structure using `developing-wordpress-plugins` skill templates.
- [ ] Build core PHP classes, admin UI, and public features.
- [ ] Perform pre-submission security & readme audit.
