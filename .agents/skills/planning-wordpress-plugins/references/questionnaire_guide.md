# WordPress Plugin Architectural Questionnaire Guide

When planning a new WordPress plugin, run through these key decision dimensions to ensure all technical and business requirements are captured prior to writing code.

---

## Dimension 1: Basic Identity & Metadata
- **Plugin Name**: What is the display name of the plugin? (Check trademark rules: Cannot begin with "WordPress", e.g., "SEO Helper for WordPress", NOT "WordPress SEO Helper").
- **Plugin Slug**: What is the unique hyphenated directory slug? (e.g., `seo-helper-wp`).
- **Function/Class Prefix**: What unique prefix will be used for code isolation? (e.g., `shwp_`, `SHWP_`, `SHWP\`).
- **Text Domain**: What text domain will be used for i18n translation strings? (Matches slug).

---

## Dimension 2: Core Purpose & Key Features
- **Primary Goal**: What single core problem does this plugin solve for the user?
- **Key Features**: List the top 3-5 core features required for MVP (Minimum Viable Product).
- **Target Audience**: Is this for site admins, content creators, ecommerce store managers, or public site visitors?

---

## Dimension 3: User Interface & User Experience (UI/UX)
- **Admin Interfaces**:
  - Top-level admin menu or sub-menu under Settings/Tools/Posts?
  - React/Gutenberg components, WP Admin pointer notices, or standard HTML forms?
- **Frontend Touchpoints**:
  - Gutenberg Blocks (Server-side rendered vs Dynamic React blocks)?
  - Shortcodes (e.g., `[my_plugin_display id="123"]`)?
  - Widgets / Elementor / Beaver Builder integrations?
  - Template tags or frontend asset enqueues?

---

## Dimension 4: Data Storage & Database Architecture
- **Options API**: Single option array vs multiple scalar keys?
- **Custom Post Types (CPT) & Taxonomies**: Does the plugin manage custom content types with custom meta fields (`add_post_meta()`)?
- **Custom Database Tables**: Does the data volume or query complexity require custom `$wpdb` tables created via `dbDelta()`?
- **Transients & Caching**: Are external API responses or heavy computations cached using WordPress Transients API?

---

## Dimension 5: Third-Party APIs & External Services
- **External APIs**: Does the plugin connect to third-party REST APIs, LLM models, payment gateways, or webhooks?
- **API Key Management**: How are credentials stored securely (encrypted in options, environment constants)?
- **Privacy & Opt-In**: Are all external API calls explicitly disclosed to the user with opt-in consent (WordPress.org Guideline #7)?

---

## Dimension 6: Background Tasks & Asynchronous Processing
- **Cron Jobs**: Does the plugin require scheduled background jobs via `wp_schedule_event()` or Action Scheduler?
- **AJAX & REST API**: Does the plugin use `wp_ajax_` / `wp_ajax_nopriv_` endpoints or custom `register_rest_route()` endpoints?

---

## Dimension 7: WordPress.org Repository Submission Strategy
- **Licensing**: Is the entire codebase licensed under GPLv2 or later?
- **Asset Compliance**: Are all bundled JS/CSS libraries using WordPress core defaults (jQuery, etc.) or GPL-compatible?
- **Security Strategy**: Nonce names, capability requirements (`manage_options`), and sanitization/escaping mapping.
