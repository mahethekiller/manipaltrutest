<?php
/**
 * WPBakery Page Builder Elements Guide Admin View
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap ptbs-admin-wrap">
    <h1>🧩 <?php esc_html_e( 'WPBakery Page Builder Elements & Shortcodes Guide', 'pathology-booking-system' ); ?></h1>
    <p class="description"><?php esc_html_e( 'Discover all custom WPBakery Page Builder drag-and-drop elements registered by Pathology Booking System under the "Pathology Booking" element category.', 'pathology-booking-system' ); ?></p>
    <hr class="wp-header-end">

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-top:20px;">
        
        <!-- Element 1: Pathology Catalog App -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#e0f2fe; color:#0284c7; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">🩺</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Pathology Catalog App</h2>
                    <span style="font-size:12px; font-weight:700; color:#0284c7; background:#f0f9ff; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Embeds the main interactive pathology catalog app featuring city selection, search toolbar, tests & packages tabs, add-to-cart drawer, and Razorpay/PhonePe payment modal.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_booking default_tab="all"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Heading Title</strong>: Customizable section heading.</li>
                <li><strong>Default View Tab</strong>: All Items, Pathology Tests, or Health Packages.</li>
            </ul>
        </div>

        <!-- Element 2: Featured Pathology Tests -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#fae8ff; color:#a855f7; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">🧪</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Featured Pathology Tests</h2>
                    <span style="font-size:12px; font-weight:700; color:#a855f7; background:#faf5ff; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays a responsive grid or list of popular diagnostic lab tests with test codes, prices (₹), and direct view links.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_featured_tests title="Popular Lab Tests" limit="6" columns="3"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Section Title</strong>: Custom section title.</li>
                <li><strong>Limit</strong>: Number of tests to show (default: 6).</li>
                <li><strong>Columns</strong>: 2, 3, or 4 columns.</li>
            </ul>
        </div>

        <!-- Element 3: Featured Health Packages -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#ccfbf1; color:#0d9488; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">📦</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Featured Health Packages</h2>
                    <span style="font-size:12px; font-weight:700; color:#0d9488; background:#f0fdf4; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays discounted health checkup packages with calculated % OFF discount badges, strike-through MRP prices, and booking CTA buttons.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_health_packages title="Full Body Packages" limit="3" columns="3"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Section Title</strong>: Custom section title.</li>
                <li><strong>Limit</strong>: Number of packages to show.</li>
                <li><strong>Columns</strong>: 2, 3, or 4 columns.</li>
            </ul>
        </div>

        <!-- Element 4: Lab Center Locations -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#ffedd5; color:#ea580c; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">📍</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Lab Center Locations Grid</h2>
                    <span style="font-size:12px; font-weight:700; color:#ea580c; background:#fff7ed; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays lab center location cards showing city badges, full addresses, operating hours, contact numbers, and direct view center page links.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_center_locations title="Our Lab Centers" limit="6" columns="3"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Section Title</strong>: Custom section title.</li>
                <li><strong>Limit</strong>: Number of centers to show.</li>
                <li><strong>Columns</strong>: 2 or 3 columns.</li>
            </ul>
        </div>

    </div>
</div>
