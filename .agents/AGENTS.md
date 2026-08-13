# Project Agent Rules & Development Directives

## 1. Mandatory Planning & Signoff Before Development
- **NEVER directly start plugin development or write code upon receiving a plugin request.**
- Whenever a user asks to build or extend a WordPress plugin, you MUST first invoke the `planning-wordpress-plugins` skill.
- You MUST ask the required architectural questionnaire questions (Identity, UI/UX, Data Architecture, Third-Party APIs, WordPress.org repository goals) using interactive prompts (`ask_question`).
- You MUST generate a complete plugin specification (`plugin_spec.md`) and obtain explicit user approval before writing any plugin code or invoking `developing-wordpress-plugins`.

## 2. WordPress.org Plugin Directory Compliance Standards
- **Strict Compliance**: All generated WordPress code MUST strictly follow official WordPress.org Plugin Directory guidelines.
- **Prefixing**: All functions, classes, constants, global variables, DB table names, option keys, transients, and hooks MUST be uniquely prefixed with the plugin slug.
- **Direct File Access Prevention**: Every PHP file MUST start with `if ( ! defined( 'ABSPATH' ) ) { exit; }`.
- **Security & Data Handling**:
  - Sanitize user input immediately (`sanitize_text_field()`, `sanitize_textarea_field()`, `absint()`, `sanitize_key()`).
  - Late-escape dynamic variables at rendering (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`).
  - Enforce nonces (`wp_verify_nonce()`, `check_ajax_referer()`) and capability checks (`current_user_can()`).
  - Use `$wpdb->prepare()` for dynamic SQL queries.
- **Prohibited Practices**: No code obfuscation, no `eval()`, no dynamic external code execution, and no non-consensual telemetry.
