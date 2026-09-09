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
                [pathology_featured_tests category_id="12" city_id="5" limit="6" columns="3"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls & Taxonomy Filters:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Section Title</strong>: Custom section title.</li>
                <li><strong>Limit & Columns</strong>: Control grid count and column span (2, 3, or 4).</li>
                <li><strong>Filter by Category</strong> (<code>category_id</code>): Show tests in a specific Category.</li>
                <li><strong>Filter by Subcategory</strong> (<code>subcategory_id</code>): Show tests in a Subcategory.</li>
                <li><strong>Filter by Condition</strong> (<code>condition_id</code>): Show tests for a Health Condition.</li>
                <li><strong>Filter by City</strong> (<code>city_id</code>): Show tests available in a specific City.</li>
            </ul>
        </div>

        <!-- Element 3: Premium Health Packages Slider & Grid -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#ccfbf1; color:#0d9488; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">📦</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Health Packages Slider & Grid</h2>
                    <span style="font-size:12px; font-weight:700; color:#0d9488; background:#f0fdf4; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Premium Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays premium health checkup package cards in <strong>Slick Carousel Slider</strong> or <strong>Grid</strong> mode with sub-heading kicker tags, main title, description, top right "VIEW ALL ↗" button, overlay badges (MOST POPULAR, BEST VALUE), parameter counts, gender recommendations, and interactive Add to Cart / View buttons.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_health_packages_slider sub_heading="HEALTH CHECKUPS" title="Keep your family TRUly healthy." layout_mode="carousel" columns="4" limit="8"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls & Options:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Sub Title / Kicker Tag</strong>: Small uppercase kicker tag (e.g. <code>HEALTH CHECKUPS</code>).</li>
                <li><strong>Main Title & Description</strong>: Section title and subheading description.</li>
                <li><strong>Display Layout Mode</strong>: Toggle between <strong>Slick Carousel Slider</strong> (touch swipe + arrows) and <strong>Grid</strong> layout.</li>
                <li><strong>View All Button</strong>: Show top right "VIEW ALL ↗" button with custom link.</li>
                <li><strong>Columns & Limit</strong>: 2, 3, or 4 columns per slide/row.</li>
                <li><strong>Taxonomy Filters</strong>: Filter packages by Category, Health Condition, or City.</li>
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
                [pathology_center_locations state_id="2" city_id="5" limit="6" columns="3"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls & Taxonomy Filters:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Section Title</strong>: Custom section title.</li>
                <li><strong>Limit & Columns</strong>: Control grid count and column span (2 or 3).</li>
                <li><strong>Filter by State</strong> (<code>state_id</code>): Show lab centers located in a specific State.</li>
                <li><strong>Filter by City</strong> (<code>city_id</code>): Show lab centers located in a specific City.</li>
            </ul>
        <!-- Element 5: Categories & Health Risks Slider -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="background:#f3e8ff; color:#9333ea; width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px;">🏷️</div>
                <div>
                    <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin:0;">Categories & Health Risks Slider</h2>
                    <span style="font-size:12px; font-weight:700; color:#9333ea; background:#faf5ff; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Premium Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays test categories, subcategories, or health risk conditions in white cards with soft pastel circle icons, titles, taglines, and a bottom centered CTA button (e.g. <code>VIEW ALL TESTS ↗</code>) matching the reference design.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_categories_slider taxonomy_type="ptbs_condition" sub_heading="HEALTH RISKS" title="Tests Based on Health Risks" columns="5" limit="10"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls & Options:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Taxonomy to Display</strong>: Choose <strong>Health Risks / Conditions</strong> (<code>ptbs_condition</code>), <strong>Main Categories</strong> (<code>ptbs_category</code>), or <strong>Subcategories</strong> (<code>ptbs_subcategory</code>).</li>
                <li><strong>Display Layout Mode</strong>: Toggle between <strong>Slick Carousel Slider</strong> and <strong>Responsive Grid</strong> layout.</li>
                <li><strong>Section Sub-Heading & Title</strong>: Custom header titles.</li>
                <li><strong>Columns & Limit</strong>: 3, 4, 5, or 6 columns per slide/row.</li>
                <li><strong>Bottom Centered Button</strong>: Toggle bottom CTA button label (e.g. <code>VIEW ALL TESTS ↗</code>) and target link.</li>
            </ul>
        </div>

        <!-- Element 6 -->
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3 style="margin:0; font-size:18px; font-weight:800; color:#0f172a;">
                    6. Most Booked Lab Tests Slider Element
                </h3>
                <div>
                    <span style="font-size:12px; font-weight:700; color:#0284c7; background:#f0f9ff; padding:2px 8px; border-radius:4px; font-family:monospace; margin-right:8px;">[pathology_lab_tests_slider]</span>
                    <span style="font-size:12px; font-weight:700; color:#9333ea; background:#faf5ff; padding:2px 8px; border-radius:4px; text-transform:uppercase;">WPBakery Premium Element</span>
                </div>
            </div>
            <p style="color:#64748b; font-size:14px; margin-bottom:16px;">
                Displays individual lab tests in clean white cards featuring a circular mint-green test tube flask icon, bold price, <strong>ADD TO CART</strong> button, <strong>VIEW</strong> button, and optional taxonomy filters.
            </p>
            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px; font-family:monospace; font-size:13px; color:#0f172a; margin-bottom:16px;">
                [pathology_lab_tests_slider sub_heading="POPULAR TEST" title="Most Booked Lab Tests" columns="4" limit="10" extra_class="my-custom-slider"]
            </div>
            <h4 style="font-size:14px; font-weight:700; color:#334155; margin-bottom:8px;">WPBakery Controls & Options:</h4>
            <ul style="margin:0; padding-left:20px; font-size:13px; color:#475569;">
                <li><strong>Taxonomy Filters</strong>: Filter lab tests by <strong>Category</strong>, <strong>Subcategory</strong>, or <strong>Health Risk / Condition</strong>.</li>
                <li><strong>Display Layout Mode</strong>: Choose between <strong>Slick Carousel Slider</strong> or <strong>Responsive Grid</strong>.</li>
                <li><strong>Section Sub-Heading & Title</strong>: Customize header texts.</li>
                <li><strong>Columns & Limit</strong>: 2, 3, 4, or 5 columns per slide/row and maximum items to display.</li>
                <li><strong>Extra CSS Class</strong>: Attach custom CSS class name to the element container.</li>
            </ul>
        </div>

    </div>
</div>

