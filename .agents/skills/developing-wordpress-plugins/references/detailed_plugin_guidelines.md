# WordPress.org Detailed Plugin Guidelines (Complete Reference)

Source: https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/

## 1. Plugins must be compatible with the GNU General Public License
- Although any GPL-compatible license is acceptable, using "GPLv2 or later" is strongly recommended.
- All code, data, images, and third-party libraries hosted in the plugin directory must comply with the GPL or a GPL-Compatible license.

## 2. Developers are responsible for the contents and actions of their plugins
- Developers must ensure all files comply with guidelines and licensing terms of all included code, images, and APIs.
- Circumventing guidelines or re-adding prohibited code will lead to permanent bans.

## 3. A stable version of a plugin must be available from its WordPress Plugin Directory page
- The version hosted on WordPress.org SVN is the official distribution version. Distributing alternate un-synced builds elsewhere is prohibited.

## 4. Code must be (mostly) human readable
- Code obfuscation (`p,a,c,k,e,r`, mangled variable names like `$z12sdf813d`, encrypted strings) is strictly prohibited.
- Developers must provide public access to source code and build tool instructions.

## 5. Trialware is not permitted
- Plugins cannot lock/disable features after a trial period or quota.
- All code in the plugin must be functional; premium extensions must be hosted separately outside WordPress.org.

## 6. Software as a Service is permitted
- SaaS integration is allowed if the service provides genuine external functionality (e.g. video hosting, CDN, Akismet).
- Moving plugin PHP code to an external server solely to bypass open-source rules or license checks is prohibited.
- Storefront-only plugins (acting solely as buy buttons) are forbidden.

## 7. Plugins may not track users without their consent
- Explicit, authorized opt-in consent is required before transmitting telemetry or user data to external servers.
- Privacy policy and data collection details must be clearly documented in `readme.txt`.

## 8. Plugins may not send executable code via third-party systems
- Executing code loaded from external servers (e.g., dynamic JS/PHP remote loading, remote plugin installers, non-font CDNs, admin iframes) is strictly forbidden.

## 9. Developers and their plugins must not do anything illegal, dishonest, or morally offensive
- Prohibits black-hat SEO, keyword stuffing, fake reviews/sockpuppeting, extorting reviews, copying other plugins, automated legal compliance guarantees, and cryptomining.

## 10. Plugins may not embed external links or credits on the public site without explicitly asking permission
- "Powered by" or credit links on the public website MUST default to hidden/OFF and require explicit opt-in by the site owner.

## 11. Plugins should not hijack the admin dashboard
- Admin notices must be contextual, limited, and dismissible (`is-dismissible`).
- No intrusive ads, affiliate tracking banners, or locked settings screens.

## 12. Public facing pages on WordPress.org (readmes) must not spam
- No affiliate link cloaking/redirects, maximum 5 tags total, no tagging competitor names, no keyword stuffing.

## 13. Plugins must use WordPress' default libraries
- Must use bundled versions of libraries (jQuery, SimplePie, PHPMailer, etc.) via `wp_enqueue_script()` / `wp_enqueue_style()` instead of bundling duplicate copies.

## 14. Frequent commits to a plugin should be avoided
- SVN is a release repository. Avoid spamming SVN with minor tweak commits or vague messages like "update".

## 15. Plugin version numbers must be incremented for each new release
- Version numbers in main PHP header and `readme.txt` must match and increment on release.

## 16. A complete plugin must be available at the time of submission
- Names cannot be "reserved" with placeholder zip files.

## 17. Plugins must respect trademarks, copyrights, and project names
- Plugin names/slugs CANNOT begin with trademarked names (e.g., "WordPress Custom Plugin" is prohibited; use "Custom Plugin for WordPress").

## 18. Maintenance rights of WordPress.org
- WordPress.org reserves the right to remove, update, or disable plugins in the interest of public security.
