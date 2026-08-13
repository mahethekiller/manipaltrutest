<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap ptbs-admin-wrap">
    <h1><?php esc_html_e( 'Pathology Booking System — Settings', 'pathology-booking-system' ); ?></h1>
    <hr class="wp-header-end">

    <?php if ( isset( $_GET['updated'] ) ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Settings updated and permalinks flushed successfully.', 'pathology-booking-system' ); ?></p>
        </div>
    <?php endif; ?>

    <h2 class="nav-tab-wrapper" style="margin-top: 15px; margin-bottom: 20px;">
        <a href="#tab-general" class="nav-tab nav-tab-active" data-tab="general">🔗 <?php esc_html_e( 'General & Permalinks', 'pathology-booking-system' ); ?></a>
        <a href="#tab-slots" class="nav-tab" data-tab="slots">⏰ <?php esc_html_e( 'Time Slots', 'pathology-booking-system' ); ?></a>
        <a href="#tab-testing" class="nav-tab" data-tab="testing">🧪 <?php esc_html_e( 'Testing & Mock Payment', 'pathology-booking-system' ); ?></a>
        <a href="#tab-gateways" class="nav-tab" data-tab="gateways">💳 <?php esc_html_e( 'Payment Gateways', 'pathology-booking-system' ); ?></a>
        <a href="#tab-google" class="nav-tab" data-tab="google">🔐 <?php esc_html_e( 'Google OAuth', 'pathology-booking-system' ); ?></a>
    </h2>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'ptbs_save_settings_action', 'ptbs_settings_nonce' ); ?>
        <input type="hidden" name="action" value="ptbs_save_settings">

        <!-- TAB 1: General & Permalinks -->
        <div class="ptbs-admin-tab-content active" id="ptbs-tab-general">
            <h3><?php esc_html_e( 'Custom Permalinks & Patient Dashboard URL', 'pathology-booking-system' ); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="test_permalink_slug"><?php esc_html_e( 'Single Test URL Slug:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <code><?php echo esc_url( home_url( '/' ) ); ?></code>
                        <input type="text" id="test_permalink_slug" name="test_permalink_slug" value="<?php echo esc_attr( isset( $settings['test_permalink_slug'] ) ? $settings['test_permalink_slug'] : 'test' ); ?>" class="regular-text" placeholder="test">
                        <code>/cbc-blood-test/</code>
                        <p class="description"><?php esc_html_e( 'Customize the URL base for individual pathology test pages (e.g. "test" or "pathology-test").', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="package_permalink_slug"><?php esc_html_e( 'Single Package URL Slug:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <code><?php echo esc_url( home_url( '/' ) ); ?></code>
                        <input type="text" id="package_permalink_slug" name="package_permalink_slug" value="<?php echo esc_attr( isset( $settings['package_permalink_slug'] ) ? $settings['package_permalink_slug'] : 'package' ); ?>" class="regular-text" placeholder="package">
                        <code>/full-body-checkup/</code>
                        <p class="description"><?php esc_html_e( 'Customize the URL base for health checkup package pages (e.g. "package" or "health-package").', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="booking_page_url"><?php esc_html_e( 'Main Pathology Booking Page URL:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <input type="url" id="booking_page_url" name="booking_page_url" value="<?php echo esc_url( isset( $settings['booking_page_url'] ) ? $settings['booking_page_url'] : home_url( '/lab/' ) ); ?>" class="large-text" placeholder="https://example.com/lab/">
                        <p class="description"><?php esc_html_e( 'Enter the URL of the main Pathology Booking / Lab page (containing [pathology_booking] shortcode). Used for the header "BOOK NOW" CTA button.', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="dashboard_page_url"><?php esc_html_e( 'Patient Dashboard Page URL:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <input type="url" id="dashboard_page_url" name="dashboard_page_url" value="<?php echo esc_url( isset( $settings['dashboard_page_url'] ) ? $settings['dashboard_page_url'] : '' ); ?>" class="large-text" placeholder="https://example.com/patient-dashboard/">
                        <p class="description"><?php esc_html_e( 'Enter the URL of the page containing the [pathology_patient_dashboard] shortcode.', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="city_trigger_mode"><?php esc_html_e( 'City Selection Modal Trigger Mode:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <select id="city_trigger_mode" name="city_trigger_mode" class="regular-text">
                            <option value="lab_page" <?php selected( isset( $settings['city_trigger_mode'] ) ? $settings['city_trigger_mode'] : 'lab_page', 'lab_page' ); ?>>
                                <?php esc_html_e( 'On Pathology / Lab Pages Only', 'pathology-booking-system' ); ?>
                            </option>
                            <option value="site_load" <?php selected( isset( $settings['city_trigger_mode'] ) ? $settings['city_trigger_mode'] : 'lab_page', 'site_load' ); ?>>
                                <?php esc_html_e( 'On Initial Website Load (Global Home & Site-wide)', 'pathology-booking-system' ); ?>
                            </option>
                        </select>
                        <p class="description"><?php esc_html_e( 'Choose when the automatic City Selection Popup appears for new visitors without a saved city.', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TAB 2: Time Slots -->
        <div class="ptbs-admin-tab-content" id="ptbs-tab-slots" style="display:none;">
            <h3><?php esc_html_e( 'Appointment Time Slots & Max Booking Limits', 'pathology-booking-system' ); ?></h3>
            <p class="description" style="font-size:13px; margin-bottom:15px;">
                <?php esc_html_e( 'Configure time slots available for home collection and lab visits. Enter a Max Bookings limit per date (e.g. 5) or leave the field EMPTY for unlimited capacity!', 'pathology-booking-system' ); ?>
            </p>

            <table class="widefat fixed striped" id="ptbs-time-slots-table" style="max-width:800px; margin-bottom:15px;">
                <thead>
                    <tr>
                        <th style="width:50%;"><?php esc_html_e( 'Time Slot Label (e.g. 07:00 AM - 09:00 AM)', 'pathology-booking-system' ); ?></th>
                        <th style="width:35%;"><?php esc_html_e( 'Max Bookings Per Date (Empty = Unlimited)', 'pathology-booking-system' ); ?></th>
                        <th style="width:15%; text-align:center;"><?php esc_html_e( 'Action', 'pathology-booking-system' ); ?></th>
                    </tr>
                </thead>
                <tbody id="ptbs-time-slots-body">
                    <?php
                    $slots = isset( $settings['time_slots'] ) && is_array( $settings['time_slots'] ) ? $settings['time_slots'] : array(
                        array( 'label' => '07:00 AM - 09:00 AM', 'max_bookings' => '' ),
                        array( 'label' => '09:00 AM - 11:00 AM', 'max_bookings' => '' ),
                        array( 'label' => '11:00 AM - 01:00 PM', 'max_bookings' => '' ),
                        array( 'label' => '02:00 PM - 04:00 PM', 'max_bookings' => '' ),
                        array( 'label' => '04:00 PM - 06:00 PM', 'max_bookings' => '' ),
                    );

                    foreach ( $slots as $index => $s ) :
                        $label = isset( $s['label'] ) ? $s['label'] : '';
                        $max   = isset( $s['max_bookings'] ) ? $s['max_bookings'] : '';
                    ?>
                        <tr>
                            <td>
                                <input type="text" name="time_slots[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" class="large-text" required placeholder="07:00 AM - 09:00 AM">
                            </td>
                            <td>
                                <input type="number" min="1" step="1" name="time_slots[<?php echo esc_attr( $index ); ?>][max_bookings]" value="<?php echo esc_attr( $max ); ?>" class="regular-text" placeholder="Leave empty for Unlimited">
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="button button-link-delete ptbs-remove-slot-btn" title="Delete Slot">✖</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="button" class="button button-secondary" id="ptbs-add-slot-btn">
                ➕ <?php esc_html_e( 'Add New Time Slot', 'pathology-booking-system' ); ?>
            </button>
        </div>

        <!-- TAB 2: Testing & Mock Payment -->
        <div class="ptbs-admin-tab-content" id="ptbs-tab-testing" style="display:none;">
            <h3><?php esc_html_e( 'Sandbox & Mock Payment Mode', 'pathology-booking-system' ); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Enable Mock Payment Mode:', 'pathology-booking-system' ); ?></th>
                    <td>
                        <label for="enable_mock_payment">
                            <input type="checkbox" id="enable_mock_payment" name="enable_mock_payment" value="1" <?php checked( isset( $settings['enable_mock_payment'] ) && '1' === $settings['enable_mock_payment'] ); ?>>
                            <strong><?php esc_html_e( 'Enable Mock Payment Mode (ON / OFF)', 'pathology-booking-system' ); ?></strong>
                        </label>
                        <p class="description"><?php esc_html_e( 'When ON, patients can complete bookings instantly using a Mock Payment option without needing live Razorpay/PhonePe API keys.', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TAB 3: Payment Gateways -->
        <div class="ptbs-admin-tab-content" id="ptbs-tab-gateways" style="display:none;">
            <h3><?php esc_html_e( 'Razorpay Payment Gateway API Keys', 'pathology-booking-system' ); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="razorpay_key_id"><?php esc_html_e( 'Razorpay Key ID:', 'pathology-booking-system' ); ?></label></th>
                    <td><input type="text" id="razorpay_key_id" name="razorpay_key_id" value="<?php echo esc_attr( isset( $settings['razorpay_key_id'] ) ? $settings['razorpay_key_id'] : '' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="razorpay_key_secret"><?php esc_html_e( 'Razorpay Key Secret:', 'pathology-booking-system' ); ?></label></th>
                    <td><input type="password" id="razorpay_key_secret" name="razorpay_key_secret" value="<?php echo esc_attr( isset( $settings['razorpay_key_secret'] ) ? $settings['razorpay_key_secret'] : '' ); ?>" class="regular-text"></td>
                </tr>
            </table>

            <hr style="margin: 25px 0;">

            <h3><?php esc_html_e( 'PhonePe UPI Gateway Credentials', 'pathology-booking-system' ); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="phonepe_merchant_id"><?php esc_html_e( 'PhonePe Merchant ID:', 'pathology-booking-system' ); ?></label></th>
                    <td><input type="text" id="phonepe_merchant_id" name="phonepe_merchant_id" value="<?php echo esc_attr( isset( $settings['phonepe_merchant_id'] ) ? $settings['phonepe_merchant_id'] : '' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phonepe_salt_key"><?php esc_html_e( 'PhonePe Salt Key:', 'pathology-booking-system' ); ?></label></th>
                    <td><input type="password" id="phonepe_salt_key" name="phonepe_salt_key" value="<?php echo esc_attr( isset( $settings['phonepe_salt_key'] ) ? $settings['phonepe_salt_key'] : '' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phonepe_salt_index"><?php esc_html_e( 'PhonePe Salt Index:', 'pathology-booking-system' ); ?></label></th>
                    <td><input type="text" id="phonepe_salt_index" name="phonepe_salt_index" value="<?php echo esc_attr( isset( $settings['phonepe_salt_index'] ) ? $settings['phonepe_salt_index'] : '1' ); ?>" class="small-text"></td>
                </tr>
            </table>
        </div>

        <!-- TAB 4: Google OAuth -->
        <div class="ptbs-admin-tab-content" id="ptbs-tab-google" style="display:none;">
            <h3><?php esc_html_e( 'Google One-Tap / OAuth Sign-In Settings', 'pathology-booking-system' ); ?></h3>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="google_client_id"><?php esc_html_e( 'Google Client ID:', 'pathology-booking-system' ); ?></label></th>
                    <td>
                        <input type="text" id="google_client_id" name="google_client_id" value="<?php echo esc_attr( isset( $settings['google_client_id'] ) ? $settings['google_client_id'] : '' ); ?>" class="large-text" placeholder="1234567890-abcdefg.apps.googleusercontent.com">
                        <p class="description"><?php esc_html_e( 'Enter your Web Application Client ID from Google Cloud Console. Leave blank to disable Google One-Tap.', 'pathology-booking-system' ); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 25px;">
            <?php submit_button( __( 'Save Settings & Flush Permalinks', 'pathology-booking-system' ) ); ?>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.nav-tab-wrapper .nav-tab').on('click', function(e) {
        e.preventDefault();
        const tab = $(this).data('tab');
        $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.ptbs-admin-tab-content').hide().removeClass('active');
        $('#ptbs-tab-' + tab).show().addClass('active');

        window.location.hash = 'tab-' + tab;
    });

    if (window.location.hash) {
        const activeTab = window.location.hash.replace('#tab-', '');
        const $tabBtn = $('.nav-tab-wrapper .nav-tab[data-tab="' + activeTab + '"]');
        if ($tabBtn.length > 0) {
            $tabBtn.trigger('click');
        }
    }

    let slotIndex = <?php echo count( $slots ); ?>;
    $('#ptbs-add-slot-btn').on('click', function() {
        const rowHtml = `
            <tr>
                <td>
                    <input type="text" name="time_slots[${slotIndex}][label]" value="" class="large-text" required placeholder="e.g. 06:00 PM - 08:00 PM">
                </td>
                <td>
                    <input type="number" min="1" step="1" name="time_slots[${slotIndex}][max_bookings]" value="" class="regular-text" placeholder="Leave empty for Unlimited">
                </td>
                <td style="text-align:center;">
                    <button type="button" class="button button-link-delete ptbs-remove-slot-btn" title="Delete Slot">✖</button>
                </td>
            </tr>
        `;
        $('#ptbs-time-slots-body').append(rowHtml);
        slotIndex++;
    });

    $(document).on('click', '.ptbs-remove-slot-btn', function() {
        if ($('#ptbs-time-slots-body tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('You must keep at least one time slot.');
        }
    });
});
</script>
