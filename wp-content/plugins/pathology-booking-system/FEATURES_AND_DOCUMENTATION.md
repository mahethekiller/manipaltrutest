# 🩺 Pathology Test & Package Booking System — Comprehensive Features & Documentation

> **Plugin Name:** Pathology Test & Package Booking System  
> **Plugin Slug:** `pathology-booking-system`  
> **Text Domain:** `pathology-booking-system`  
> **Prefix:** `ptbs_` / `PTBS_`  
> **Version:** 1.0.0  
> **Requires at least:** WordPress 6.0 | PHP 7.4+  
> **Compliance Target:** 100% WordPress.org Plugin Directory Guidelines  

---

## 📖 Table of Contents

1. [Plugin Overview](#1-plugin-overview)
2. [Core Architecture & Database Schema](#2-core-architecture--database-schema)
3. [Full Feature Matrix](#3-full-feature-matrix)
   - [A. Custom Permalinks & URL Rewrite Engine](#a-custom-permalinks--url-rewrite-engine)
   - [B. Single Pathology Test Page Template](#b-single-pathology-test-page-template)
   - [C. Single Package Info Page & Linked Parameter Tree](#c-single-package-info-page--linked-parameter-tree)
   - [D. City-First Selection & Persistence](#d-city-first-selection--persistence)
   - [E. Mandatory User Authentication & Google OAuth](#e-mandatory-user-authentication--google-oauth)
   - [F. Saved Family Member Profiles](#f-saved-family-member-profiles)
   - [G. Multi-Patient Group Test Booking](#g-multi-patient-group-test-booking)
   - [H. 3-Tab Patient Dashboard & PDF Reports](#h-3-tab-patient-dashboard--pdf-reports)
   - [I. Password Change & Security Manager](#i-password-change--security-manager)
   - [J. Mock Payment Mode & Gateways](#j-mock-payment-mode--gateways)
4. [Shortcodes & Frontend Setup](#4-shortcodes--frontend-setup)
5. [Admin Dashboard Screens & Links](#5-admin-dashboard-screens--links)
6. [Security & Coding Standards](#6-security--coding-standards)

---

## 1. Plugin Overview

The **Pathology Test & Package Booking System** is a standalone, enterprise-grade WordPress plugin engineered for diagnostic centers, pathology laboratories, and healthcare service providers.

It handles the end-to-end patient workflow: from customizable permalink URLs and single post diagnostic pages to city-based searches, multi-patient family appointments, payment processing, patient dashboards, and PDF lab test report delivery.

---

## 2. Core Architecture & Database Schema

The plugin maintains custom SQL tables installed via `dbDelta()` with automatic self-healing checks on load:

### 1. `{$wpdb->prefix}ptbs_bookings`
Stores all patient appointment bookings and group details (`booking_number`, `user_id`, `patients_json`, `booking_date`, `total_amount`, `gateway`, `payment_status`, `fulfillment_status`, `report_file_url`).

### 2. `{$wpdb->prefix}ptbs_family_members`
Stores saved family member profiles per account holder (`user_id`, `full_name`, `relation`, `age`, `gender`, `phone`).

### 3. `{$wpdb->prefix}ptbs_booking_items`
Line items attached to each booking (Individual Tests & Health Packages).

### 4. `{$wpdb->prefix}ptbs_city_prices`
City-specific price overrides and local availability matrix.

---

## 3. Full Feature Matrix

### A. Custom Permalinks & URL Rewrite Engine
- **Customizable URL Slugs**:
  - Individual Test Slug Base: Default `/test/` (e.g., `example.com/test/cbc-blood-test/` or `example.com/pathology-test/cbc-blood-test/`).
  - Health Package Slug Base: Default `/package/` (e.g., `example.com/package/full-body-checkup/` or `example.com/health-package/full-body-checkup/`).
- **Admin Settings Fields**: Manage URL slugs under **Pathology Booking > Settings**.
- **Automatic Rewrite Flush**: Flushes rewrite rules (`flush_rewrite_rules()`) automatically upon saving settings to prevent 404 errors.

### B. Single Pathology Test Page Template (`single-ptbs_test.php`)
- **Diagnostic Metrics Bar**: 🩸 Sample Required, 🍽️ Fasting Rule, ⏳ Turnaround Time (TAT), NABL Accredited Badge.
- **Parameters Measured Grid**: Bulleted biomarker parameter list (`_ptbs_parameters`).
- **Sticky Booking Sidebar**: Price badge, free home sample collection tag, and **`🛒 Add Test to Booking Cart`** button (syncs with `localStorage`).

### C. Single Package Info Page & Linked Parameter Tree (`single-ptbs_package.php`)
- **Linked Individual Tests Meta Box (`_ptbs_linked_test_ids`)**:
  - In **Pathology Booking > Health Packages > Edit Package**, check off any created pathology tests (e.g. `[x] CBC`, `[x] LFT`, `[x] Thyroid`, `[x] Lipid`, `[x] Diabetes`).
- **Expandable Parameter Tree Accordion**:
  - Queries linked tests, calculates total biomarker count (e.g. *85 Parameters across 5 Profiles*), and renders expandable accordion sections per profile.
- **Sticky Booking Sidebar**: **`🎁 Add Package to Booking Cart`** button with cart persistence.

### D. City-First Selection & Persistence
- **City Selector Modal**: Forces or allows patients to select their city (e.g., *Mumbai, Delhi, Bangalore, Hyderabad*).
- **Catalog Filtering**: Automatically filters diagnostic tests and health checkup packages per city via `ptbs_city` taxonomy.
- **Browser LocalStorage**: Remembers selected city and booking cart items across reloads.

### E. Mandatory User Authentication & Google OAuth
- **Checkout Auth Guard**: Browsing catalog is open to guests, but opening checkout forces authentication.
- **Auto-Popup Auth Modal**: Displays an informative banner: *"🔒 Please log in or create an account to complete your pathology test booking."*
- **Google OAuth One-Tap**: Conditional Google One-Tap Sign-In.
- **Post-Login Handoff**: Seamlessly opens checkout modal post-login without losing cart items.

### F. Saved Family Member Profiles
- **Family Profile Manager**: Save family profiles under account for 1-click checkout auto-filling.

### G. Multi-Patient Group Test Booking
- **Multi-Patient Selection**: Check multiple patients in Step 2: `☑ Myself`, `☑ [Saved Family Profile]`, `➕ Add Another New Patient`.
- **Group Pricing**: Total calculates: `Total = (Subtotal) × (Number of Patients)`.
- **Structured Group JSON**: Preserves patient details in `patients_json`.

### H. 3-Tab Patient Dashboard & PDF Reports
Accessible via shortcode `[pathology_patient_dashboard]`:
- **Tab 1: 📋 My Test Bookings**: Track status & single-click **📥 Download PDF Report** button.
- **Tab 2: 👨‍👩‍👧 Family Profiles**: Add/Edit/Delete family profiles.
- **Tab 3: 👤 Account Settings & Security**: Update profile & change password.

### I. Password Change & Security Manager
- **Current Password Validation**: Validates via `wp_check_password()`.
- **Secure Password Reset**: Minimum 8 characters, updated with `wp_set_password()`.

### J. Mock Payment Mode & Gateways
- **Mock Payment Toggle**: Enable/disable `enable_mock_payment` under **Pathology Booking > Settings**.
- **Instant Test Bookings**: When ON, patients can complete bookings instantly using `🧪 Mock Payment`.
- **Live Gateways**: **Razorpay** and **PhonePe UPI**.

---

## 4. Shortcodes & Frontend Setup

### 1. Diagnostic Catalog & Booking App
```text
[pathology_booking]
```

### 2. Patient Portal & History Dashboard
```text
[pathology_patient_dashboard]
```

---

## 5. Admin Dashboard Screens & Links

- **Bookings Manager Dashboard**: `wp-admin/admin.php?page=ptbs-dashboard`
- **Gateway & Plugin Settings**: `wp-admin/admin.php?page=ptbs-settings`
- **Setup & Documentation Screen**: `wp-admin/admin.php?page=ptbs-documentation`
