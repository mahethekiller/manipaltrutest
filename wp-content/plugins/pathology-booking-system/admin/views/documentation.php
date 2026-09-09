<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap ptbs-admin-wrap">
    <h1>📚 <?php esc_html_e( 'Pathology Booking System - Setup & Usage Documentation', 'pathology-booking-system' ); ?></h1>
    <p class="description"><?php esc_html_e( 'Comprehensive guide on configuring cities, pathology tests, health packages, payment gateways, Google OAuth, and frontend shortcodes.', 'pathology-booking-system' ); ?></p>
    <hr class="wp-header-end">

    <div class="ptbs-doc-grid">
        <!-- Quick Start Shortcodes -->
        <div class="ptbs-card ptbs-card-full">
            <h2>⚡ Quick Start Shortcodes</h2>
            <p><?php esc_html_e( 'Embed these shortcodes on any WordPress page or post to enable the booking app and patient portal:', 'pathology-booking-system' ); ?></p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Shortcode', 'pathology-booking-system' ); ?></th>
                        <th><?php esc_html_e( 'Purpose & Description', 'pathology-booking-system' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>[pathology_booking]</code></td>
                        <td><?php esc_html_e( 'Displays the interactive Pathology Catalog App with City-First selection, Tests/Packages tab, Add-to-Cart drawer, and Razorpay/PhonePe checkout modal.', 'pathology-booking-system' ); ?></td>
                    </tr>
                    <tr>
                        <td><code>[pathology_patient_dashboard]</code></td>
                        <td><?php esc_html_e( 'Displays the Patient Account Portal where logged-in patients can track booking statuses and download PDF lab test reports.', 'pathology-booking-system' ); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Step 1: Cities & Taxonomy -->
        <div class="ptbs-card">
            <h2>1. 📍 Setting Up Cities</h2>
            <ol>
                <li>Go to <strong>Pathology Tests > Cities</strong> in your WordPress dashboard menu.</li>
                <li>Add cities where your lab services operate (e.g. <em>Mumbai, Delhi, Bangalore, Hyderabad</em>).</li>
                <li>Assign tests and health packages to these cities so patients can filter catalog items by location.</li>
            </ol>
        </div>

        <!-- Step 2: Catalog Management -->
        <div class="ptbs-card">
            <h2>2. 🩺 Adding Tests & Health Packages</h2>
            <ol>
                <li>Go to <strong>Pathology Tests > Add New Test</strong>.</li>
                <li>Enter Title, Description, Test Code, Base Price (₹), Fasting requirements (e.g. 10-12 hrs), TAT hours, and assign cities.</li>
                <li>Go to <strong>Health Packages > Add New Package</strong> to bundle multiple tests into a discounted health checkup package.</li>
            </ol>
        </div>

        <!-- Step 3: Payment Gateways -->
        <div class="ptbs-card">
            <h2>3. 💳 Configuring Payment Gateways</h2>
            <ol>
                <li>Go to <strong>Pathology Booking > Settings</strong>.</li>
                <li><strong>Razorpay</strong>: Enter your Razorpay Key ID and Key Secret from your <a href="https://dashboard.razorpay.com/" target="_blank">Razorpay Dashboard</a>.</li>
                <li><strong>PhonePe</strong>: Enter Merchant ID, Salt Key, and Salt Index from your <a href="https://www.phonepe.com/" target="_blank">PhonePe Business Portal</a>.</li>
            </ol>
        </div>

        <!-- Step 4: Google OAuth Login -->
        <div class="ptbs-card">
            <h2>4. 🔑 Google OAuth One-Tap Sign-In</h2>
            <ol>
                <li>Visit the <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a> and create a Web Client ID.</li>
                <li>Add your domain to Authorized JavaScript origins.</li>
                <li>Paste your <strong>Google Client ID</strong> into <strong>Pathology Booking > Settings</strong>.</li>
                <li>Patients can now log in instantly using Google One-Tap or Google Sign-In button!</li>
            </ol>
        </div>

        <!-- Step 5: Managing Bookings & Reports -->
        <div class="ptbs-card ptbs-card-full">
            <h2>5. 📋 Managing Bookings & Uploading PDF Test Reports</h2>
            <ol>
                <li>Go to <strong>Pathology Booking > All Bookings</strong> to view all patient appointments.</li>
                <li>Click <strong>View Details</strong> on any booking to update fulfillment status (<em>Pending, Confirmed, Sample Collected, Completed, Cancelled</em>).</li>
                <li>When the lab test result is ready, paste the PDF Report File URL and click <strong>Update Status & Attach Report</strong>.</li>
                <li>The patient will immediately see the <strong>Download PDF Report</strong> button inside their Patient Portal (`[pathology_patient_dashboard]`).</li>
            </ol>
        </div>

        <!-- Step 6: Overriding Default Page Templates -->
        <div class="ptbs-card ptbs-card-full">
            <h2>6. 🎨 Overriding Default Page Templates in Themes / Child Themes</h2>
            <p><?php esc_html_e( 'You can easily customize and override any default page template by copying template files into your active theme or child theme (e.g. ekko-child/pathology-booking-system/):', 'pathology-booking-system' ); ?></p>
            <table class="widefat striped" style="margin-top:12px;">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Template File Name', 'pathology-booking-system' ); ?></th>
                        <th><?php esc_html_e( 'Child Theme Override Path', 'pathology-booking-system' ); ?></th>
                        <th><?php esc_html_e( 'Purpose', 'pathology-booking-system' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>single-ptbs_test.php</code></td>
                        <td><code>ekko-child/pathology-booking-system/single-ptbs_test.php</code></td>
                        <td><?php esc_html_e( 'Single Pathology Test detail page', 'pathology-booking-system' ); ?></td>
                    </tr>
                    <tr>
                        <td><code>single-ptbs_package.php</code></td>
                        <td><code>ekko-child/pathology-booking-system/single-ptbs_package.php</code></td>
                        <td><?php esc_html_e( 'Single Health Package detail page', 'pathology-booking-system' ); ?></td>
                    </tr>
                    <tr>
                        <td><code>single-ptbs_center_location.php</code></td>
                        <td><code>ekko-child/pathology-booking-system/single-ptbs_center_location.php</code></td>
                        <td><?php esc_html_e( 'Single Lab Center Location page with address, hours, and available tests', 'pathology-booking-system' ); ?></td>
                    </tr>
                    <tr>
                        <td><code>taxonomy-ptbs_category.php</code></td>
                        <td><code>ekko-child/pathology-booking-system/taxonomy-ptbs_category.php</code></td>
                        <td><?php esc_html_e( 'Category & Subcategory archive pages', 'pathology-booking-system' ); ?></td>
                    </tr>
                    <tr>
                        <td><code>archive-ptbs_test.php</code></td>
                        <td><code>ekko-child/pathology-booking-system/archive-ptbs_test.php</code></td>
                        <td><?php esc_html_e( 'Main Lab Tests & Packages catalog archive page', 'pathology-booking-system' ); ?></td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top:16px; font-size:13px; color:#64748b;">
                💡 <strong>Developer Filter Hook</strong>: You can also intercept template location dynamically using <code>add_filter('ptbs_locate_template', 'my_custom_template_fn', 10, 3);</code>.
            </p>
        </div>
    </div>
</div>
