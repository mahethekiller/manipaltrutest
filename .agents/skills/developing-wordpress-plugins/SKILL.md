---
name: developing-wordpress-plugins
description: Guides the creation, refactoring, and auditing of WordPress plugins to ensure 100% strict compliance with official WordPress.org Plugin Directory Guidelines, WP Coding Standards, and security practices. Use when building WordPress plugins, adding plugin features, or preparing plugins for submission to the WordPress Plugin Repository.
---

# Developing WordPress Plugins (WordPress.org Compliant)

## When to use this skill
- Creating a new WordPress plugin from scratch intended for public release or repository submission.
- Refactoring existing plugin code to meet official WordPress.org Plugin Directory guidelines.
- Auditing plugin security (sanitization, escaping, nonce validation, capability checks).
- Preparing a plugin package, `readme.txt`, and assets for WordPress.org review.

## Official WordPress.org Guidelines Overview

For full expanded details of all 18 rules, see [references/detailed_plugin_guidelines.md](references/detailed_plugin_guidelines.md).

### Pre-Submission & Security Checklist

- [ ] **1. GPL Compatibility**: License must be GPLv2 or later compatible. All included 3rd-party code/libraries/media must be GPL-compatible.
- [ ] **2. Developer Responsibility**: Full accountability for code, libraries, media licensing, and third-party API terms of use.
- [ ] **3. Stable Version Hosted on SVN**: Official release builds must be hosted directly on WordPress.org SVN.
- [ ] **4. Human Readable Code**: No code obfuscation, mangling, or minification without providing source code & build instructions.
- [ ] **5. No Trialware**: No locked features, trial timeouts, or feature expiration after quotas. Paid SaaS backends are allowed under Rule 6.
- [ ] **6. Software as a Service (SaaS)**: Permitted if external service provides genuine value. Key validation services or false offloading are prohibited.
- [ ] **7. User Privacy & Consent**: No telemetry, tracking, or external server calls without explicit, authorized opt-in consent.
- [ ] **8. No Third-Party Executable Code**: No dynamic fetching/executing of external PHP/JS code, remote installers, or non-font CDNs.
- [ ] **9. Honest & Moral Conduct**: No black-hat SEO, keyword stuffing, fake reviews, extorting feedback, or trademark violation.
- [ ] **10. Front-End Credit Links**: "Powered by" or credit links must default to OFF/hidden and require explicit user opt-in.
- [ ] **11. Clean Admin Dashboard**: No admin spam, intrusive ads, or non-dismissible alerts. Notices must be `is-dismissible`.
- [ ] **12. Spam-Free Readmes**: Maximum 5 tags. No affiliate link cloaking, competitor tagging, or keyword stuffing.
- [ ] **13. Default WP Libraries**: Must use bundled WordPress libraries (jQuery, SimplePie, PHPMailer, etc.) via `wp_enqueue_script()`.
- [ ] **14. SVN Commit Best Practices**: SVN is for releases, not active development. Avoid rapid-fire spam commits.
- [ ] **15. Semantic Versioning**: Increment version numbers in `readme.txt` and main plugin header for every release.
- [ ] **16. Complete Plugin Submission**: The submitted zip file must contain a complete, working plugin.
- [ ] **17. Trademark Compliance**: Slugs and titles CANNOT start with trademarked names (e.g., use "SEO Tool for WordPress", NOT "WordPress SEO Tool").
- [ ] **18. Core Directory Rules**: Respect WordPress.org plugin directory maintenance and security resolution procedures.

---

## Code Security & Structural Standards

- **Direct File Access Prevention**: Every PHP file MUST start with:
  ```php
  if ( ! defined( 'ABSPATH' ) ) {
      exit;
  }
  ```
- **Unique Prefixing**: All functions, classes, interfaces, constants, global variables, DB table names, option keys, transients, and hooks MUST be uniquely prefixed with the plugin slug (e.g., `my_plugin_`, `MY_PLUGIN_`).
- **Input Sanitization**: Always sanitize input data on arrival using `sanitize_text_field()`, `sanitize_textarea_field()`, `absint()`, `sanitize_key()`, `sanitize_email()`, etc.
- **Output Escaping**: Late-escape all dynamic variables at rendering using `esc_html()`, `esc_attr()`, `esc_url()`, `esc_textarea()`, or `wp_kses_post()`.
- **Nonces & Capability Checks**: Verify user capabilities (`current_user_can()`) AND nonces (`wp_verify_nonce()`, `check_ajax_referer()`) on state-changing requests.
- **Database Safety**: Always use `$wpdb->prepare()` for dynamic SQL queries.

---

## Workflow: Plugin Development & Submission Prep

### Step 1: Initialize Plugin Structure
```
my-awesome-tool/
├── my-awesome-tool.php       (Main plugin file)
├── readme.txt                (WordPress.org standard readme)
├── uninstall.php             (Clean cleanup routine on deletion)
├── includes/                 (Core PHP logic & classes)
├── admin/                    (Admin dashboard screens & assets)
├── public/                   (Frontend scripts, styles, & views)
└── languages/                (i18n translation files)
```

### Step 2: Main File & Readme Setup
- Use header boilerplate from [resources/plugin_header_template.php](resources/plugin_header_template.php).
- Use readme template from [resources/readme_template.txt](resources/readme_template.txt).

### Step 3: Implement Logic & Enforce Security
- Follow code patterns in [examples/security_and_escaping_cheatsheet.php](examples/security_and_escaping_cheatsheet.php).
- Refer to detailed guidelines in [references/detailed_plugin_guidelines.md](references/detailed_plugin_guidelines.md).

### Step 4: Pre-Submission Audit
1. Audit for un-prefixed functions/classes/globals.
2. Audit for unescaped `echo` / `print` calls.
3. Check for raw SQL strings without `$wpdb->prepare()`.
4. Check `uninstall.php` for complete data cleanup.
5. Validate `readme.txt` with standard WordPress.org validator.

---

## Bundled References & Templates
- Detailed Guidelines Breakdown: [references/detailed_plugin_guidelines.md](references/detailed_plugin_guidelines.md)
- Header & Entry Boilerplate: [resources/plugin_header_template.php](resources/plugin_header_template.php)
- Official Readme Template: [resources/readme_template.txt](resources/readme_template.txt)
- Security & Escaping Cheat Sheet: [examples/security_and_escaping_cheatsheet.php](examples/security_and_escaping_cheatsheet.php)
