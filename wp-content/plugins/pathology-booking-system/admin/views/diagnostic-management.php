<?php
/**
 * Diagnostic Management SPA View Shell
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$c_tests = wp_count_posts( 'ptbs_test' )->publish;
$c_pkgs  = wp_count_posts( 'ptbs_package' )->publish;
$c_subs  = wp_count_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) );
$c_conds = wp_count_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) );
?>

<div class="ptbs-diagnostic-wrap">

    <!-- Header Banner -->
    <div class="ptbs-header-banner">
        <div class="ptbs-header-title">
            <h1>🧪 <?php esc_html_e( 'Diagnostic Management', 'pathology-booking-system' ); ?></h1>
            <p class="ptbs-header-subtitle"><?php esc_html_e( 'Manage pathology tests, packages, categories, subcategories & conditions with fast AJAX actions.', 'pathology-booking-system' ); ?></p>
        </div>
        <div>
            <button id="ptbs_btn_add_item" class="ptbs-btn-primary">+ Add New Item</button>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="ptbs-stats-grid">
        <div class="ptbs-stat-card">
            <div class="ptbs-stat-icon blue">🔬</div>
            <div class="ptbs-stat-info">
                <div class="ptbs-stat-value" id="stat_active_tests"><?php echo esc_html( $c_tests ); ?></div>
                <div class="ptbs-stat-label">Active Tests</div>
            </div>
        </div>

        <div class="ptbs-stat-card">
            <div class="ptbs-stat-icon teal">📦</div>
            <div class="ptbs-stat-info">
                <div class="ptbs-stat-value" id="stat_active_packages"><?php echo esc_html( $c_pkgs ); ?></div>
                <div class="ptbs-stat-label">Health Packages</div>
            </div>
        </div>

        <div class="ptbs-stat-card">
            <div class="ptbs-stat-icon purple">📂</div>
            <div class="ptbs-stat-info">
                <div class="ptbs-stat-value" id="stat_subcategories"><?php echo esc_html( $c_subs ); ?></div>
                <div class="ptbs-stat-label">Sub Categories</div>
            </div>
        </div>

        <div class="ptbs-stat-card">
            <div class="ptbs-stat-icon amber">❤️</div>
            <div class="ptbs-stat-info">
                <div class="ptbs-stat-value" id="stat_conditions"><?php echo esc_html( $c_conds ); ?></div>
                <div class="ptbs-stat-label">Health Conditions</div>
            </div>
        </div>
    </div>

    <!-- SPA Segmented Navigation Bar -->
    <div class="ptbs-nav-container">
        <div class="ptbs-nav-tabs">
            <button class="ptbs-tab-btn active" data-tab="category">🏷️ Categories</button>
            <button class="ptbs-tab-btn" data-tab="subcategory">📁 Sub Categories</button>
            <button class="ptbs-tab-btn" data-tab="condition">🩺 Conditions</button>
            <button class="ptbs-tab-btn" data-tab="test">🧪 Tests</button>
            <button class="ptbs-tab-btn" data-tab="package">📦 Packages</button>
            <button class="ptbs-tab-btn" data-tab="sync" style="color:#0284c7; font-weight:700;">⚡ Catalog Sync & Import</button>
        </div>
    </div>

    <!-- Dynamic AJAX Table Container -->
    <div id="ptbs_table_container">
        <!-- Content dynamically injected by diagnostic-management-admin.js -->
    </div>

</div>

<!-- Slide-Over Drawer Modal Overlay -->
<div class="ptbs-drawer-overlay" id="ptbs_drawer_overlay">
    <div class="ptbs-drawer">
        <div class="ptbs-drawer-header">
            <h2 id="ptbs_drawer_title">Add New Item</h2>
            <button class="ptbs-drawer-close">&times;</button>
        </div>
        <div class="ptbs-drawer-body" id="ptbs_drawer_body">
            <!-- Dynamic Form Injection -->
        </div>
    </div>
</div>

<!-- Floating Toast Notification Area -->
<div class="ptbs-toast-container" id="ptbs_toast_container"></div>
