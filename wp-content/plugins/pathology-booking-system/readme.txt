=== Pathology Test & Package Booking System ===
Contributors: labtechsystems
Donate link: https://example.com/donate
Tags: pathology, lab-booking, health-checkup, razorpay, phonepe
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Complete pathology test and health package booking system with city selection, Google OAuth login, Home Collection & Lab Visit workflows, and Razorpay/PhonePe gateways.

== Description ==

The Pathology Test & Package Booking System is a full-featured plugin for diagnostic labs and pathology centers. It features a location-first workflow where patients choose their city before browsing available tests and packages.

= Features =
* **Location-First City Selection**: Multi-city support with city-level price overrides.
* **Patient Login & Google OAuth**: Standard registration/login plus Google One-Tap Sign-In.
* **Flexible Workflows**: Patient options for Home Sample Collection or Lab Center Visit.
* **Payment Gateways**: Razorpay and PhonePe online checkout integrations.
* **Patient Dashboard**: Patients can track booking statuses and download uploaded PDF test reports.
* **Admin Management**: Manage bookings, filter by city/status, export CSV, and upload test reports.

== Third-Party Services Disclosure ==
This plugin connects to third-party services to process payments and handle user authentication:

1. **Razorpay Payment Gateway API**: Used for processing online payments securely.
   * Website: https://razorpay.com/
   * Privacy Policy: https://razorpay.com/privacy/

2. **PhonePe Payment Gateway API**: Used for processing UPI and online payments securely.
   * Website: https://www.phonepe.com/
   * Privacy Policy: https://www.phonepe.com/privacy-policy/

3. **Google Identity / OAuth API**: Used for optional Google One-Tap & Social Sign-In for patients.
   * Website: https://developers.google.com/identity
   * Privacy Policy: https://policies.google.com/privacy

== Installation ==

1. Upload `pathology-booking-system` to `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure API keys under 'Pathology Booking' > 'Settings' in your admin menu.
4. Add shortcode `[pathology_booking]` on a page to show the booking catalog & workflow.
5. Add shortcode `[pathology_patient_dashboard]` on a page for patient account management.

== Changelog ==

= 1.0.0 =
* Initial release.
