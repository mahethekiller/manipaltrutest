<?php
/**
 * Public facing features, catalog rendering & shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Public {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'wp_footer', array( $this, 'render_global_footer_modals' ) );

        // Single Post Template Hierarchy Loader Filter
        add_filter( 'single_template', array( $this, 'load_single_cpt_templates' ) );

        // Shortcodes
        add_shortcode( 'pathology_booking', array( $this, 'render_booking_shortcode' ) );
        add_shortcode( 'pathology_patient_dashboard', array( $this, 'render_patient_dashboard_shortcode' ) );
        add_shortcode( 'pathology_featured_tests', array( $this, 'render_featured_tests_shortcode' ) );
        add_shortcode( 'pathology_health_packages', array( $this, 'render_health_packages_shortcode' ) );
        add_shortcode( 'pathology_health_packages_slider', array( $this, 'render_health_packages_slider_shortcode' ) );
        add_shortcode( 'pathology_center_locations', array( $this, 'render_center_locations_shortcode' ) );

        // Catalog & Time Slots AJAX
        add_action( 'wp_ajax_ptbs_get_city_catalog', array( $this, 'ajax_get_city_catalog' ) );
        add_action( 'wp_ajax_nopriv_ptbs_get_city_catalog', array( $this, 'ajax_get_city_catalog' ) );
        add_action( 'wp_ajax_ptbs_get_available_time_slots', array( $this, 'ajax_get_available_time_slots' ) );
        add_action( 'wp_ajax_nopriv_ptbs_get_available_time_slots', array( $this, 'ajax_get_available_time_slots' ) );
    }

    /**
     * Load Custom Single Post Templates for ptbs_test and ptbs_package CPTs
     */
    public function load_single_cpt_templates( $single_template ) {
        global $post;

        if ( $post && 'ptbs_test' === $post->post_type ) {
            return PTBS_Template_Loader::locate_template( 'single-ptbs_test.php' );
        }

        if ( $post && 'ptbs_package' === $post->post_type ) {
            return PTBS_Template_Loader::locate_template( 'single-ptbs_package.php' );
        }

        if ( $post && 'ptbs_center_location' === $post->post_type ) {
            return PTBS_Template_Loader::locate_template( 'single-ptbs_center_location.php' );
        }

        return $single_template;
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'ptbs-public-css', PTBS_DIR_URL . 'public/css/ptbs-public.css', array(), time() );
        wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
        wp_enqueue_style( 'slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1' );
        wp_enqueue_style( 'slick-carousel-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array( 'slick-carousel' ), '1.8.1' );

        // Google OAuth SDK script
        wp_enqueue_script( 'google-one-tap-js', 'https://accounts.google.com/gsi/client', array(), null, true );
        // Razorpay Checkout JS
        wp_enqueue_script( 'razorpay-checkout-js', 'https://checkout.razorpay.com/v1/checkout.js', array(), null, true );
        // Slick Carousel JS
        wp_enqueue_script( 'slick-carousel-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );

        wp_enqueue_script( 'ptbs-public-js', PTBS_DIR_URL . 'public/js/ptbs-public.js', array( 'jquery', 'slick-carousel-js' ), time(), true );

        $settings  = get_option( 'ptbs_settings', array() );
        $cities    = get_terms( array( 'taxonomy' => 'ptbs_city', 'hide_empty' => false ) );
        $city_list = array();

        if ( ! is_wp_error( $cities ) && ! empty( $cities ) ) {
            foreach ( $cities as $c ) {
                $city_list[] = array(
                    'id'   => $c->term_id,
                    'name' => $c->name,
                );
            }
        }

        $current_user_obj = is_user_logged_in() ? wp_get_current_user() : null;
        $family_members   = is_user_logged_in() ? PTBS_DB::get_family_members( $current_user_obj->ID ) : array();

        wp_localize_script( 'ptbs-public-js', 'ptbs_vars', array(
            'ajax_url'          => admin_url( 'admin-ajax.php' ),
            'site_url'          => home_url(),
            'public_nonce'      => wp_create_nonce( 'ptbs_public_nonce' ),
            'auth_nonce'        => wp_create_nonce( 'ptbs_auth_nonce' ),
            'google_client_id'  => isset( $settings['google_client_id'] ) ? $settings['google_client_id'] : '',
            'city_trigger_mode' => isset( $settings['city_trigger_mode'] ) ? $settings['city_trigger_mode'] : 'lab_page',
            'cities'            => $city_list,
            'is_user_logged'    => is_user_logged_in(),
            'current_user'      => $current_user_obj ? $current_user_obj->display_name : '',
            'current_email'     => $current_user_obj ? $current_user_obj->user_email : '',
            'current_phone'     => $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_phone', true ) : '',
            'current_address'   => $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_address', true ) : '',
            'current_pincode'   => $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_pincode', true ) : '',
            'family_members'    => $family_members,
        ) );
    }

    /**
     * Shortcode [pathology_booking]
     */
    public function render_booking_shortcode() {
        $settings = get_option( 'ptbs_settings', array() );
        ob_start();
        ?>
        <div id="ptbs-booking-app" class="ptbs-container">
            <!-- Header Bar with City & Cart & Auth / Logout Buttons -->
            <div class="ptbs-header-bar">
                <div class="ptbs-city-trigger" id="ptbs-open-city-modal">
                    <span class="ptbs-icon">📍</span>
                    <span id="ptbs-current-city-label"><?php esc_html_e( 'Select City', 'pathology-booking-system' ); ?></span>
                    <span class="ptbs-arrow">▼</span>
                </div>

                <div class="ptbs-header-right">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( ! empty( $settings['dashboard_page_url'] ) ? $settings['dashboard_page_url'] : '#' ); ?>" class="ptbs-user-badge" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; background:#f1f5f9; padding:8px 14px; border-radius:8px; color:#1e293b; font-weight:600;">
                            👤 <?php echo esc_html( wp_get_current_user()->display_name ); ?> (📋 <?php esc_html_e( 'My Bookings', 'pathology-booking-system' ); ?>)
                        </a>
                        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="ptbs-btn ptbs-btn-outline" style="text-decoration:none; padding:8px 12px; font-size:13px;">
                            🚪 <?php esc_html_e( 'Logout', 'pathology-booking-system' ); ?>
                        </a>
                    <?php else : ?>
                        <button type="button" class="ptbs-btn ptbs-btn-outline" id="ptbs-open-auth-modal">
                            <?php esc_html_e( 'Login / Register', 'pathology-booking-system' ); ?>
                        </button>
                    <?php endif; ?>

                    <div class="ptbs-cart-badge" id="ptbs-open-cart">
                        🛒 <span id="ptbs-cart-count">0</span> Items (₹<span id="ptbs-cart-total">0.00</span>)
                    </div>
                </div>
            </div>

            <!-- Tab Navigation: Tests vs Packages -->
            <div class="ptbs-tabs">
                <button type="button" class="ptbs-tab-btn active" data-tab="tests"><?php esc_html_e( 'Individual Tests', 'pathology-booking-system' ); ?></button>
                <button type="button" class="ptbs-tab-btn" data-tab="packages"><?php esc_html_e( 'Health Packages', 'pathology-booking-system' ); ?></button>
            </div>

            <!-- Search Filter Bar -->
            <div class="ptbs-search-box">
                <input type="text" id="ptbs-search-input" placeholder="<?php esc_attr_e( 'Search tests, packages or symptoms (e.g. CBC, Thyroid, Diabetes)...', 'pathology-booking-system' ); ?>" />
            </div>

            <!-- Tests Grid -->
            <div class="ptbs-tab-content active" id="ptbs-tab-tests">
                <div class="ptbs-catalog-grid" id="ptbs-tests-grid">
                    <?php
                    $init_tests_query = new WP_Query( array(
                        'post_type'      => 'ptbs_test',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                    ) );

                    if ( $init_tests_query->have_posts() ) {
                        while ( $init_tests_query->have_posts() ) {
                            $init_tests_query->the_post();
                            echo $this->render_test_card_html( get_the_ID() );
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>' . esc_html__( 'No pathology tests available.', 'pathology-booking-system' ) . '</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="ptbs-tab-content" id="ptbs-tab-packages">
                <div class="ptbs-catalog-grid" id="ptbs-packages-grid">
                    <?php
                    $init_pkgs_query = new WP_Query( array(
                        'post_type'      => 'ptbs_package',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                    ) );

                    if ( $init_pkgs_query->have_posts() ) {
                        while ( $init_pkgs_query->have_posts() ) {
                            $init_pkgs_query->the_post();
                            echo $this->render_package_card_html( get_the_ID() );
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>' . esc_html__( 'No health checkup packages available.', 'pathology-booking-system' ) . '</p>';
                    }
                    ?>
                </div>
            </div>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Shortcode [pathology_patient_dashboard]
     */
    public function render_patient_dashboard_shortcode() {
        if ( ! is_user_logged_in() ) {
            return '<div class="ptbs-container"><p>' . esc_html__( 'Please log in to access your pathology patient dashboard.', 'pathology-booking-system' ) . '</p></div>';
        }

        global $wpdb;
        $user           = wp_get_current_user();
        $user_id        = $user->ID;
        $table_bookings = $wpdb->prefix . 'ptbs_bookings';

        $bookings       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_bookings} WHERE user_id = %d ORDER BY id DESC", $user_id ) );
        $family_members = PTBS_DB::get_family_members( $user_id );
        $phone          = get_user_meta( $user_id, 'ptbs_patient_phone', true );
        $address        = get_user_meta( $user_id, 'ptbs_patient_address', true );
        $pincode        = get_user_meta( $user_id, 'ptbs_patient_pincode', true );

        ob_start();
        ?>
        <div class="ptbs-patient-dashboard">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:20px;">
                <h2>👋 <?php printf( esc_html__( 'Welcome, %s', 'pathology-booking-system' ), esc_html( $user->display_name ) ); ?></h2>
                <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="ptbs-btn ptbs-btn-outline" style="text-decoration:none;">
                    🚪 <?php esc_html_e( 'Logout', 'pathology-booking-system' ); ?>
                </a>
            </div>

            <!-- Dashboard Navigation Tabs -->
            <div class="ptbs-tabs" style="margin-bottom:24px;">
                <button type="button" class="ptbs-tab-btn active" data-dash-tab="bookings">📋 <?php esc_html_e( 'My Test Bookings', 'pathology-booking-system' ); ?></button>
                <button type="button" class="ptbs-tab-btn" data-dash-tab="family">👨‍👩‍👧 <?php esc_html_e( 'Family Profiles', 'pathology-booking-system' ); ?></button>
                <button type="button" class="ptbs-tab-btn" data-dash-tab="account">👤 <?php esc_html_e( 'Account Settings & Security', 'pathology-booking-system' ); ?></button>
            </div>

            <!-- TAB 1: Booking History -->
            <div class="ptbs-dash-tab-content active" id="ptbs-dash-tab-bookings">
                <h3>📋 <?php esc_html_e( 'Appointment & Report History', 'pathology-booking-system' ); ?></h3>

                <?php if ( empty( $bookings ) ) : ?>
                    <p><?php esc_html_e( 'No pathology test bookings found in your account history.', 'pathology-booking-system' ); ?></p>
                <?php else : ?>
                    <table class="ptbs-dash-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Booking #', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'Patient Name', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'Slot Date', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'Total', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'Payment', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'Status', 'pathology-booking-system' ); ?></th>
                                <th><?php esc_html_e( 'PDF Report', 'pathology-booking-system' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $bookings as $b ) : ?>
                                <tr>
                                    <td><strong><?php echo esc_html( $b->booking_number ); ?></strong></td>
                                    <td>
                                        <?php
                                        $p_list = ! empty( $b->patients_json ) ? json_decode( $b->patients_json, true ) : array();
                                        if ( ! empty( $p_list ) && is_array( $p_list ) && count( $p_list ) > 1 ) :
                                        ?>
                                            <strong style="color:#2563eb;">👥 Group (<?php echo count( $p_list ); ?> Patients):</strong>
                                            <ul style="margin:4px 0 0 0; padding-left:14px; font-size:12px; color:#334155;">
                                                <?php foreach ( $p_list as $p ) : ?>
                                                    <li><strong><?php echo esc_html( $p['name'] ); ?></strong> (<?php echo esc_html( ! empty( $p['relation'] ) ? $p['relation'] : 'Self' ); ?>, <?php echo esc_html( isset( $p['age'] ) ? $p['age'] : 30 ); ?>Y)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else : ?>
                                            <strong><?php echo esc_html( $b->patient_name ); ?></strong>
                                            <br><small style="color:#64748b;"><?php echo esc_html( $b->patient_age ); ?> Yrs / <?php echo esc_html( ucfirst( $b->patient_gender ) ); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo esc_html( $b->booking_date ); ?><br><small><?php echo esc_html( $b->booking_slot ); ?></small></td>
                                    <td>₹<?php echo esc_html( number_format( $b->total_amount, 2 ) ); ?></td>
                                    <td>
                                        <span class="ptbs-badge ptbs-badge-<?php echo esc_attr( $b->payment_status ); ?>">
                                            <?php echo esc_html( ucfirst( $b->payment_status ) ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="ptbs-badge ptbs-badge-<?php echo esc_attr( $b->fulfillment_status ); ?>">
                                            <?php echo esc_html( ucfirst( $b->fulfillment_status ) ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ( ! empty( $b->report_file_url ) ) : ?>
                                            <a href="<?php echo esc_url( $b->report_file_url ); ?>" target="_blank" class="ptbs-btn ptbs-btn-primary" style="padding:4px 10px; font-size:12px; text-decoration:none;">📥 Download PDF</a>
                                        <?php else : ?>
                                            <span style="color:#94a3b8; font-size:12px;"><?php esc_html_e( 'Pending Lab', 'pathology-booking-system' ); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- TAB 2: Family Profiles -->
            <div class="ptbs-dash-tab-content" id="ptbs-dash-tab-family" style="display:none;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3>👨‍👩‍👧 <?php esc_html_e( 'Saved Family Member Profiles', 'pathology-booking-system' ); ?></h3>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:12px; margin-bottom:24px;">
                    <h4>➕ <?php esc_html_e( 'Add New Family Member Profile', 'pathology-booking-system' ); ?></h4>
                    <form id="ptbs-add-family-form">
                        <div class="ptbs-field-row">
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Full Name *', 'pathology-booking-system' ); ?></label>
                                <input type="text" name="full_name" required placeholder="e.g. John Doe Sr.">
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Relation *', 'pathology-booking-system' ); ?></label>
                                <select name="relation">
                                    <option value="Spouse">Spouse</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="ptbs-field-row">
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Age *', 'pathology-booking-system' ); ?></label>
                                <input type="number" name="age" required min="1" max="120" value="45">
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Gender *', 'pathology-booking-system' ); ?></label>
                                <select name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Phone (Optional)', 'pathology-booking-system' ); ?></label>
                                <input type="tel" name="phone">
                            </div>
                        </div>
                        <button type="submit" class="ptbs-btn ptbs-btn-primary"><?php esc_html_e( 'Save Family Profile', 'pathology-booking-system' ); ?></button>
                    </form>
                </div>

                <div id="ptbs-family-members-list">
                    <?php if ( empty( $family_members ) ) : ?>
                        <p><?php esc_html_e( 'No saved family members yet.', 'pathology-booking-system' ); ?></p>
                    <?php else : ?>
                        <div class="ptbs-catalog-grid">
                            <?php foreach ( $family_members as $fm ) : ?>
                                <div class="ptbs-card">
                                    <div class="ptbs-card-title"><?php echo esc_html( $fm['full_name'] ); ?></div>
                                    <div class="ptbs-card-meta">
                                        Relation: <strong><?php echo esc_html( $fm['relation'] ); ?></strong><br>
                                        Age: <?php echo esc_html( $fm['age'] ); ?> Yrs | Gender: <?php echo esc_html( ucfirst( $fm['gender'] ) ); ?><br>
                                        Phone: <?php echo esc_html( ! empty( $fm['phone'] ) ? $fm['phone'] : 'N/A' ); ?>
                                    </div>
                                    <div class="ptbs-card-footer">
                                        <button type="button" class="ptbs-btn ptbs-btn-outline ptbs-delete-family-btn" data-id="<?php echo esc_attr( $fm['id'] ); ?>" style="color:#ef4444; border-color:#fca5a5;">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB 3: Account Settings & Password Change -->
            <div class="ptbs-dash-tab-content" id="ptbs-dash-tab-account" style="display:none;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
                    <!-- Update Profile Info -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:12px;">
                        <h3>👤 <?php esc_html_e( 'Update Profile Details', 'pathology-booking-system' ); ?></h3>
                        <form id="ptbs-update-profile-form">
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Full Name', 'pathology-booking-system' ); ?></label>
                                <input type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" required>
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Email Address', 'pathology-booking-system' ); ?></label>
                                <input type="email" value="<?php echo esc_attr( $user->user_email ); ?>" disabled readonly style="background:#f1f5f9; cursor:not-allowed;">
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Phone Number', 'pathology-booking-system' ); ?></label>
                                <input type="tel" name="phone" value="<?php echo esc_attr( $phone ); ?>">
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Default Home Address', 'pathology-booking-system' ); ?></label>
                                <textarea name="address" rows="2"><?php echo esc_textarea( $address ); ?></textarea>
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Pincode', 'pathology-booking-system' ); ?></label>
                                <input type="text" name="pincode" value="<?php echo esc_attr( $pincode ); ?>">
                            </div>
                            <button type="submit" class="ptbs-btn ptbs-btn-primary full-width"><?php esc_html_e( 'Save Profile', 'pathology-booking-system' ); ?></button>
                        </form>
                    </div>

                    <!-- Security & Password Change -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:12px;">
                        <h3>🔒 <?php esc_html_e( 'Change Account Password', 'pathology-booking-system' ); ?></h3>
                        <form id="ptbs-change-password-form">
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Current Password *', 'pathology-booking-system' ); ?></label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'New Password * (Min 8 characters)', 'pathology-booking-system' ); ?></label>
                                <input type="password" name="new_password" required minlength="8">
                            </div>
                            <div class="ptbs-field">
                                <label><?php esc_html_e( 'Confirm New Password *', 'pathology-booking-system' ); ?></label>
                                <input type="password" name="confirm_password" required minlength="8">
                            </div>
                            <button type="submit" class="ptbs-btn ptbs-btn-primary full-width"><?php esc_html_e( 'Update Password', 'pathology-booking-system' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Global Footer Modals (City, Auth, Checkout) Site-Wide
     */
    public function render_global_footer_modals() {
        static $rendered = false;
        if ( $rendered ) {
            return;
        }
        $rendered = true;

        $settings = get_option( 'ptbs_settings', array() );
        ?>
        <!-- Global City Selection Modal -->
        <div class="ptbs-modal" id="ptbs-city-modal">
            <div class="ptbs-modal-content">
                <span class="ptbs-modal-close">&times;</span>
                <h3>📍 <?php esc_html_e( 'Select Your City', 'pathology-booking-system' ); ?></h3>
                <p><?php esc_html_e( 'Please choose your city to view available tests, home collection slots, and local pricing.', 'pathology-booking-system' ); ?></p>
                <div class="ptbs-city-grid" id="ptbs-city-options">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- Global User Auth Modal -->
        <div class="ptbs-modal" id="ptbs-auth-modal">
            <div class="ptbs-modal-content">
                <span class="ptbs-modal-close">&times;</span>
                <div class="ptbs-auth-notice" id="ptbs-auth-notice" style="display:none; background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600;">
                    🔒 <?php esc_html_e( 'Please log in or create an account to complete your pathology test booking.', 'pathology-booking-system' ); ?>
                </div>
                <div class="ptbs-auth-tabs">
                    <button type="button" class="ptbs-auth-tab active" data-auth="login"><?php esc_html_e( 'Login', 'pathology-booking-system' ); ?></button>
                    <button type="button" class="ptbs-auth-tab" data-auth="register"><?php esc_html_e( 'Register', 'pathology-booking-system' ); ?></button>
                </div>

                <!-- Login Form -->
                <form id="ptbs-login-form" class="ptbs-auth-form active">
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Email Address', 'pathology-booking-system' ); ?></label>
                        <input type="email" name="email" required placeholder="patient@example.com">
                    </div>
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Password', 'pathology-booking-system' ); ?></label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="ptbs-btn ptbs-btn-primary full-width"><?php esc_html_e( 'Log In', 'pathology-booking-system' ); ?></button>
                </form>

                <!-- Register Form -->
                <form id="ptbs-register-form" class="ptbs-auth-form">
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Full Name', 'pathology-booking-system' ); ?></label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Email Address', 'pathology-booking-system' ); ?></label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Phone Number', 'pathology-booking-system' ); ?></label>
                        <input type="tel" name="phone" required placeholder="9876543210">
                    </div>
                    <div class="ptbs-field">
                        <label><?php esc_html_e( 'Password', 'pathology-booking-system' ); ?></label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="ptbs-btn ptbs-btn-primary full-width"><?php esc_html_e( 'Create Account', 'pathology-booking-system' ); ?></button>
                </form>

                <?php if ( ! empty( $settings['google_client_id'] ) ) : ?>
                    <div class="ptbs-social-divider"><span>OR</span></div>
                    <div id="ptbs-g_id_onload" data-client_id="<?php echo esc_attr( $settings['google_client_id'] ); ?>" data-auto_prompt="false"></div>
                    <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left"></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Global Booking Checkout Modal -->
        <div class="ptbs-modal" id="ptbs-checkout-modal">
            <div class="ptbs-modal-content ptbs-modal-lg">
                <span class="ptbs-modal-close">&times;</span>
                <h3>🩺 <?php esc_html_e( 'Complete Appointment Booking', 'pathology-booking-system' ); ?></h3>

                <form id="ptbs-checkout-form">
                    <div class="ptbs-checkout-grid">
                        <div class="ptbs-checkout-col">
                            <h4>1. <?php esc_html_e( 'Sample Collection Method', 'pathology-booking-system' ); ?></h4>
                            <div class="ptbs-radio-group">
                                <label class="ptbs-radio-box">
                                    <input type="radio" name="booking_type" value="home_collection" checked>
                                    <span>🏡 <?php esc_html_e( 'Home Sample Collection', 'pathology-booking-system' ); ?></span>
                                </label>
                                <label class="ptbs-radio-box">
                                    <input type="radio" name="booking_type" value="lab_visit">
                                    <span>🏥 <?php esc_html_e( 'Lab Center Visit', 'pathology-booking-system' ); ?></span>
                                </label>
                            </div>

                            <h4>2. <?php esc_html_e( 'Select Patient(s) for Appointment', 'pathology-booking-system' ); ?></h4>
                            <p style="font-size:12px; color:#64748b; margin-top:-6px; margin-bottom:12px;"><?php esc_html_e( 'You can select multiple patients (Myself + Family Members) for this single appointment.', 'pathology-booking-system' ); ?></p>
                            
                            <div id="ptbs-patient-selection-boxes" style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
                                <label class="ptbs-radio-box" style="margin-bottom:0; background:#f8fafc;">
                                    <input type="checkbox" class="ptbs-patient-checkbox" data-type="myself" checked>
                                    <span>👤 <strong><?php esc_html_e( 'Myself (Account Holder)', 'pathology-booking-system' ); ?></strong></span>
                                </label>
                                <div id="ptbs-family-checkboxes-container"></div>
                            </div>

                            <div id="ptbs-patients-cards-container" style="display:flex; flex-direction:column; gap:16px; margin-bottom:16px;">
                                <!-- Dynamic Patient Cards injected by JS -->
                            </div>

                            <button type="button" class="ptbs-btn ptbs-btn-outline" id="ptbs-add-extra-patient-btn" style="margin-bottom:16px; width:100%;">
                                ➕ <?php esc_html_e( 'Add Another New Patient', 'pathology-booking-system' ); ?>
                            </button>

                            <?php
                            $user_email   = $current_user_obj ? $current_user_obj->user_email : '';
                            $user_phone   = $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_phone', true ) : '';
                            $user_address = $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_address', true ) : '';
                            $user_pincode = $current_user_obj ? get_user_meta( $current_user_obj->ID, 'ptbs_patient_pincode', true ) : '';
                            ?>

                            <div class="ptbs-field-row">
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'Contact Phone *', 'pathology-booking-system' ); ?></label>
                                    <input type="tel" name="patient_phone" value="<?php echo esc_attr( $user_phone ); ?>" required placeholder="10-digit mobile number">
                                </div>
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'Contact Email *', 'pathology-booking-system' ); ?></label>
                                    <input type="email" name="patient_email" value="<?php echo esc_attr( $user_email ); ?>" required placeholder="patient@example.com">
                                </div>
                            </div>

                            <div class="ptbs-field" id="ptbs-save-family-checkbox-container" style="display:none;">
                                <label style="font-weight:normal; font-size:13px; cursor:pointer;">
                                    <input type="checkbox" name="save_to_family" value="1">
                                    <?php esc_html_e( 'Save patient details to my Family Profiles for future bookings', 'pathology-booking-system' ); ?>
                                </label>
                            </div>

                            <div id="ptbs-address-fields">
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'House / Street Address *', 'pathology-booking-system' ); ?></label>
                                    <textarea name="address_line1" rows="2" placeholder="Full collection address..."><?php echo esc_textarea( $user_address ); ?></textarea>
                                </div>
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'Pincode *', 'pathology-booking-system' ); ?></label>
                                    <input type="text" name="pincode" value="<?php echo esc_attr( $user_pincode ); ?>" placeholder="e.g. 110001">
                                </div>
                            </div>

                            <h4>3. <?php esc_html_e( 'Preferred Date & Time Slot', 'pathology-booking-system' ); ?></h4>
                            <div class="ptbs-field-row">
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'Appointment Date *', 'pathology-booking-system' ); ?></label>
                                    <input type="date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="ptbs-field">
                                    <label><?php esc_html_e( 'Time Slot *', 'pathology-booking-system' ); ?></label>
                                    <select name="time_slot" id="ptbs-checkout-time-slot" class="ptbs-checkout-time-slot" required>
                                        <?php
                                        $init_slots = isset( $settings['time_slots'] ) && is_array( $settings['time_slots'] ) ? $settings['time_slots'] : array(
                                            array( 'label' => '07:00 AM - 09:00 AM', 'max_bookings' => '' ),
                                            array( 'label' => '09:00 AM - 11:00 AM', 'max_bookings' => '' ),
                                            array( 'label' => '11:00 AM - 01:00 PM', 'max_bookings' => '' ),
                                            array( 'label' => '02:00 PM - 04:00 PM', 'max_bookings' => '' ),
                                            array( 'label' => '04:00 PM - 06:00 PM', 'max_bookings' => '' ),
                                        );
                                        foreach ( $init_slots as $is ) :
                                            $lbl = isset( $is['label'] ) ? $is['label'] : '';
                                            $mx  = ( isset( $is['max_bookings'] ) && '' !== trim( $is['max_bookings'] ) ) ? $is['max_bookings'] : null;
                                            $bdg = is_null( $mx ) ? '(Unlimited)' : '(' . $mx . ' slots max)';
                                        ?>
                                            <option value="<?php echo esc_attr( $lbl ); ?>"><?php echo esc_html( $lbl . ' ' . $bdg ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="ptbs-checkout-col">
                            <h4>3. <?php esc_html_e( 'Selected Tests & Cart Summary', 'pathology-booking-system' ); ?></h4>
                            <ul class="ptbs-summary-list" id="ptbs-summary-list">
                                <!-- Injected by JS -->
                            </ul>
                            <div class="ptbs-summary-total">
                                <span><?php esc_html_e( 'Subtotal Amount:', 'pathology-booking-system' ); ?></span>
                                <span class="price">₹<span id="ptbs-checkout-subtotal">0.00</span></span>
                            </div>

                            <!-- PROMO COUPON CODE SECTION -->
                            <div class="ptbs-coupon-box" style="margin-top:16px; background:#f8fafc; padding:12px; border-radius:8px; border:1px dashed #cbd5e1;">
                                <label style="display:block; font-weight:600; font-size:13px; color:#334155; margin-bottom:6px;">🏷️ <?php esc_html_e( 'Have a Promo Coupon Code?', 'pathology-booking-system' ); ?></label>
                                <div style="display:flex; gap:8px;">
                                    <input type="text" id="ptbs_coupon_code_input" placeholder="<?php esc_attr_e( 'Enter Promo Code', 'pathology-booking-system' ); ?>" style="flex:1; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px; text-transform:uppercase;">
                                    <button type="button" class="ptbs-btn ptbs-btn-primary" id="ptbs_apply_coupon_btn" style="padding:8px 16px; font-size:13px; font-weight:600;"><?php esc_html_e( 'Apply', 'pathology-booking-system' ); ?></button>
                                </div>
                                <div id="ptbs_coupon_msg" style="font-size:12px; font-weight:600; margin-top:6px;"></div>
                            </div>

                            <div class="ptbs-summary-discount" id="ptbs-discount-row" style="display:none; justify-content:space-between; margin-top:10px; color:#16a34a; font-weight:600; font-size:14px;">
                                <span><?php esc_html_e( 'Coupon Discount:', 'pathology-booking-system' ); ?></span>
                                <span>- ₹<span id="ptbs-checkout-discount">0.00</span></span>
                            </div>

                            <div class="ptbs-summary-total" style="border-top:2px solid #e2e8f0; margin-top:10px; padding-top:10px;">
                                <span><?php esc_html_e( 'Final Payable Amount:', 'pathology-booking-system' ); ?></span>
                                <span class="price" style="color:#0b4f8c;">₹<span id="ptbs-checkout-total">0.00</span></span>
                            </div>

                            <h4>4. <?php esc_html_e( 'Payment Gateway Option', 'pathology-booking-system' ); ?></h4>
                            <div class="ptbs-radio-group">
                                <?php if ( isset( $settings['enable_mock_payment'] ) && '1' === $settings['enable_mock_payment'] ) : ?>
                                    <label class="ptbs-radio-box">
                                        <input type="radio" name="gateway" value="mock" checked>
                                        <span>🧪 <?php esc_html_e( 'Mock Payment (Instant Sandbox Test)', 'pathology-booking-system' ); ?></span>
                                    </label>
                                <?php endif; ?>
                                <?php if ( ! empty( $settings['razorpay_key_id'] ) ) : ?>
                                    <label class="ptbs-radio-box">
                                        <input type="radio" name="gateway" value="razorpay" <?php checked( ! isset( $settings['enable_mock_payment'] ) || '1' !== $settings['enable_mock_payment'] ); ?>>
                                        <span>💳 <?php esc_html_e( 'Pay Online (Razorpay Cards/UPI/Netbanking)', 'pathology-booking-system' ); ?></span>
                                    </label>
                                <?php endif; ?>
                                <?php if ( ! empty( $settings['phonepe_merchant_id'] ) ) : ?>
                                    <label class="ptbs-radio-box">
                                        <input type="radio" name="gateway" value="phonepe">
                                        <span>📱 <?php esc_html_e( 'Pay via PhonePe / BHIM UPI', 'pathology-booking-system' ); ?></span>
                                    </label>
                                <?php endif; ?>
                                <label class="ptbs-radio-box">
                                    <input type="radio" name="gateway" value="cod">
                                    <span>💵 <?php esc_html_e( 'Cash on Collection / Pay at Lab', 'pathology-booking-system' ); ?></span>
                                </label>
                            </div>

                            <button type="submit" class="ptbs-btn ptbs-btn-success full-width large-btn" style="margin-top:16px;">
                                🔒 <?php esc_html_e( 'Confirm & Pay Appointment', 'pathology-booking-system' ); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render Single Test Card HTML directly in PHP
     */
    public function render_test_card_html( $test_id, $city_id = 0 ) {
        $title         = get_the_title( $test_id );
        $post_obj      = get_post( $test_id );
        $settings      = get_option( 'ptbs_settings', array() );
        $test_slug     = ! empty( $settings['test_permalink_slug'] ) ? sanitize_title( $settings['test_permalink_slug'] ) : 'test';
        $use_pretty    = (bool) get_option( 'permalink_structure' );

        if ( $use_pretty && ! empty( $post_obj->post_name ) ) {
            $permalink = home_url( '/' . $test_slug . '/' . $post_obj->post_name . '/' );
        } else {
            $permalink = get_permalink( $test_id );
        }

        $regular_price = get_post_meta( $test_id, '_ptbs_price', true );
        $final_price   = $this->get_effective_item_price( $test_id, $city_id, $regular_price );
        $code          = get_post_meta( $test_id, '_ptbs_code', true );
        $fasting_req   = get_post_meta( $test_id, '_ptbs_fasting_req', true );
        $tat_hours     = get_post_meta( $test_id, '_ptbs_tat_hours', true );

        ob_start();
        ?>
        <div class="ptbs-card">
            <div>
                <div class="ptbs-card-title">
                    <a href="<?php echo esc_url( $permalink ); ?>" target="_blank" style="color:inherit; text-decoration:none;">
                        <?php echo esc_html( $title ); ?>
                    </a>
                </div>
                <div class="ptbs-card-meta">
                    Code: <?php echo esc_html( ! empty( $code ) ? $code : 'N/A' ); ?> | Fasting: <?php echo esc_html( ! empty( $fasting_req ) ? $fasting_req : 'None' ); ?><br>
                    TAT: <?php echo esc_html( ! empty( $tat_hours ) ? $tat_hours : '24' ); ?> Hours
                </div>
            </div>
            <div class="ptbs-card-footer">
                <div>
                    <div class="ptbs-card-price">₹<?php echo esc_html( number_format( floatval( $final_price ), 2 ) ); ?></div>
                    <a href="<?php echo esc_url( $permalink ); ?>" target="_blank" style="font-size:12px; color:#2563eb; font-weight:600; text-decoration:none;">
                        View Details ℹ️
                    </a>
                </div>
                <button class="ptbs-btn ptbs-btn-primary ptbs-add-to-cart" 
                        data-id="<?php echo esc_attr( $test_id ); ?>" 
                        data-type="test" 
                        data-title="<?php echo esc_attr( $title ); ?>" 
                        data-price="<?php echo esc_attr( $final_price ); ?>">
                    Add
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Single Package Card HTML directly in PHP
     */
    public function render_package_card_html( $pkg_id, $city_id = 0 ) {
        $title         = get_the_title( $pkg_id );
        $post_obj      = get_post( $pkg_id );
        $settings      = get_option( 'ptbs_settings', array() );
        $package_slug  = ! empty( $settings['package_permalink_slug'] ) ? sanitize_title( $settings['package_permalink_slug'] ) : 'package';
        $use_pretty    = (bool) get_option( 'permalink_structure' );

        if ( $use_pretty && ! empty( $post_obj->post_name ) ) {
            $permalink = home_url( '/' . $package_slug . '/' . $post_obj->post_name . '/' );
        } else {
            $permalink = get_permalink( $pkg_id );
        }

        $regular_price = get_post_meta( $pkg_id, '_ptbs_price', true );
        $final_price   = $this->get_effective_item_price( $pkg_id, $city_id, $regular_price );
        $excerpt       = get_the_excerpt( $pkg_id );

        ob_start();
        ?>
        <div class="ptbs-card">
            <div>
                <div class="ptbs-card-title">
                    <a href="<?php echo esc_url( $permalink ); ?>" target="_blank" style="color:inherit; text-decoration:none;">
                        🎁 <?php echo esc_html( $title ); ?>
                    </a>
                </div>
                <div class="ptbs-card-meta"><?php echo esc_html( ! empty( $excerpt ) ? $excerpt : 'Comprehensive Health Profile' ); ?></div>
            </div>
            <div class="ptbs-card-footer">
                <div>
                    <div class="ptbs-card-price">₹<?php echo esc_html( number_format( floatval( $final_price ), 2 ) ); ?></div>
                    <a href="<?php echo esc_url( $permalink ); ?>" target="_blank" style="font-size:12px; color:#2563eb; font-weight:600; text-decoration:none;">
                        View Info Tree 🌳
                    </a>
                </div>
                <button class="ptbs-btn ptbs-btn-primary ptbs-add-to-cart" 
                        data-id="<?php echo esc_attr( $pkg_id ); ?>" 
                        data-type="package" 
                        data-title="<?php echo esc_attr( $title ); ?>" 
                        data-price="<?php echo esc_attr( $final_price ); ?>">
                    Add Package
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * AJAX Catalog Handler (Tests & Packages by City)
     */
    public function ajax_get_city_catalog() {
        check_ajax_referer( 'ptbs_public_nonce', 'security' );

        $city_id = isset( $_POST['city_id'] ) ? absint( $_POST['city_id'] ) : 0;

        $args_tests = array(
            'post_type'      => 'ptbs_test',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $args_packages = array(
            'post_type'      => 'ptbs_package',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        if ( $city_id > 0 ) {
            $args_tests['tax_query'] = array(
                array(
                    'taxonomy' => 'ptbs_city',
                    'field'    => 'term_id',
                    'terms'    => $city_id,
                ),
            );
            $args_packages['tax_query'] = array(
                array(
                    'taxonomy' => 'ptbs_city',
                    'field'    => 'term_id',
                    'terms'    => $city_id,
                ),
            );
        }

        $query_tests    = new WP_Query( $args_tests );
        $query_packages = new WP_Query( $args_packages );

        $tests_html    = '';
        $packages_html = '';

        if ( $query_tests->have_posts() ) {
            while ( $query_tests->have_posts() ) {
                $query_tests->the_post();
                $tests_html .= $this->render_test_card_html( get_the_ID(), $city_id );
            }
            wp_reset_postdata();
        } else {
            $tests_html = '<p>' . esc_html__( 'No pathology tests available for this location.', 'pathology-booking-system' ) . '</p>';
        }

        if ( $query_packages->have_posts() ) {
            while ( $query_packages->have_posts() ) {
                $query_packages->the_post();
                $packages_html .= $this->render_package_card_html( get_the_ID(), $city_id );
            }
            wp_reset_postdata();
        } else {
            $packages_html = '<p>' . esc_html__( 'No health checkup packages available for this location.', 'pathology-booking-system' ) . '</p>';
        }

        wp_send_json_success( array(
            'tests_html'    => $tests_html,
            'packages_html' => $packages_html,
        ) );
    }

    private function get_effective_item_price( $item_id, $city_id, $default_price ) {
        if ( $city_id <= 0 ) {
            return floatval( $default_price );
        }

        global $wpdb;
        $table_prices = $wpdb->prefix . 'ptbs_city_prices';
        $custom_price = $wpdb->get_var( $wpdb->prepare( "SELECT custom_price FROM {$table_prices} WHERE city_id = %d AND item_id = %d AND is_available = 1", $city_id, $item_id ) );

        if ( null !== $custom_price ) {
            return floatval( $custom_price );
        }

        return floatval( $default_price );
    }

    /**
     * AJAX Handler: Get Available Time Slots with Capacity Badges per Date
     */
    public function ajax_get_available_time_slots() {
        check_ajax_referer( 'ptbs_public_nonce', 'security' );

        $booking_date = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';
        if ( empty( $booking_date ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid booking date.', 'pathology-booking-system' ) ) );
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'ptbs_bookings';

        $settings         = get_option( 'ptbs_settings', array() );
        $configured_slots = isset( $settings['time_slots'] ) && is_array( $settings['time_slots'] ) ? $settings['time_slots'] : array(
            array( 'label' => '07:00 AM - 09:00 AM', 'max_bookings' => '' ),
            array( 'label' => '09:00 AM - 11:00 AM', 'max_bookings' => '' ),
            array( 'label' => '11:00 AM - 01:00 PM', 'max_bookings' => '' ),
            array( 'label' => '02:00 PM - 04:00 PM', 'max_bookings' => '' ),
            array( 'label' => '04:00 PM - 06:00 PM', 'max_bookings' => '' ),
        );

        // Fetch booked slots for the selected date (correct column: booking_slot)
        $bookings_raw = $wpdb->get_results( $wpdb->prepare(
            "SELECT booking_slot, patients_json FROM {$table_name} WHERE booking_date = %s AND payment_status != 'cancelled' AND fulfillment_status != 'cancelled'",
            $booking_date
        ) );

        $booked_map = array();
        if ( ! empty( $bookings_raw ) ) {
            foreach ( $bookings_raw as $row ) {
                $slot_name = trim( $row->booking_slot );
                $p_count   = 1;

                if ( ! empty( $row->patients_json ) ) {
                    $p_arr = json_decode( $row->patients_json, true );
                    if ( is_array( $p_arr ) && count( $p_arr ) > 0 ) {
                        $p_count = count( $p_arr );
                    }
                }

                if ( ! isset( $booked_map[ $slot_name ] ) ) {
                    $booked_map[ $slot_name ] = 0;
                }
                $booked_map[ $slot_name ] += $p_count;
            }
        }

        $slots_data = array();
        foreach ( $configured_slots as $s ) {
            $label        = trim( $s['label'] );
            $max          = ( isset( $s['max_bookings'] ) && '' !== trim( $s['max_bookings'] ) ) ? intval( $s['max_bookings'] ) : null;
            $booked_count = isset( $booked_map[ $label ] ) ? $booked_map[ $label ] : 0;

            if ( is_null( $max ) ) {
                $slots_data[] = array(
                    'label'     => $label,
                    'status'    => 'unlimited',
                    'badge'     => esc_html__( '(Unlimited)', 'pathology-booking-system' ),
                    'is_full'   => false,
                    'remaining' => 'unlimited',
                );
            } else {
                $remaining = $max - $booked_count;
                if ( $remaining <= 0 ) {
                    $slots_data[] = array(
                        'label'     => $label,
                        'status'    => 'full',
                        'badge'     => esc_html__( '(Fully Booked)', 'pathology-booking-system' ),
                        'is_full'   => true,
                        'remaining' => 0,
                    );
                } else {
                    $slots_data[] = array(
                        'label'     => $label,
                        'status'    => 'available',
                        'badge'     => sprintf( esc_html__( '(%d slots left)', 'pathology-booking-system' ), $remaining ),
                        'is_full'   => false,
                        'remaining' => $remaining,
                    );
                }
            }
        }

        wp_send_json_success( array( 'slots' => $slots_data ) );
    }

    /**
     * Render Featured Tests Shortcode Callback
     */
    public function render_featured_tests_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title'          => __( 'Popular Diagnostic Tests', 'pathology-booking-system' ),
            'limit'          => 6,
            'columns'        => 3,
            'category_id'    => '',
            'subcategory_id' => '',
            'condition_id'   => '',
            'city_id'        => '',
        ), $atts );

        $limit     = absint( $atts['limit'] );
        $cols      = absint( $atts['columns'] );
        $args      = array(
            'post_type'      => 'ptbs_test',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
        );

        $tax_query = array();
        if ( ! empty( $atts['category_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_category', 'field' => 'term_id', 'terms' => absint( $atts['category_id'] ) );
        }
        if ( ! empty( $atts['subcategory_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_subcategory', 'field' => 'term_id', 'terms' => absint( $atts['subcategory_id'] ) );
        }
        if ( ! empty( $atts['condition_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_condition', 'field' => 'term_id', 'terms' => absint( $atts['condition_id'] ) );
        }
        if ( ! empty( $atts['city_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_city', 'field' => 'term_id', 'terms' => absint( $atts['city_id'] ) );
        }

        if ( ! empty( $tax_query ) ) {
            $tax_query['relation'] = 'AND';
            $args['tax_query']     = $tax_query;
        }

        $tests = get_posts( $args );

        ob_start();
        ?>
        <div class="ptbs-featured-tests-wrap" style="margin:30px 0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin-bottom:20px; text-align:center;"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr( $cols ); ?>, 1fr); gap:20px;">
                <?php if ( ! empty( $tests ) ) : foreach ( $tests as $t ) : 
                    $price     = get_post_meta( $t->ID, '_ptbs_price', true );
                    $code      = get_post_meta( $t->ID, '_ptbs_code', true );
                    $permalink = get_permalink( $t->ID );
                ?>
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <span style="background:#e0f2fe; color:#0284c7; font-size:11px; font-weight:700; padding:4px 10px; border-radius:12px; display:inline-block; margin-bottom:8px;">
                                🧪 <?php echo esc_html( $code ?: 'LAB TEST' ); ?>
                            </span>
                            <h3 style="font-size:16px; font-weight:700; color:#0f172a; margin:0 0 10px 0; line-height:1.4;">
                                <a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit; text-decoration:none;"><?php echo esc_html( $t->post_title ); ?></a>
                            </h3>
                        </div>
                        <div style="margin-top:16px; border-top:1px solid #f1f5f9; padding-top:12px; display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-size:18px; font-weight:800; color:#0284c7;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></span>
                            <a href="<?php echo esc_url( $permalink ); ?>" style="background:#0f172a; color:#fff; font-size:12px; font-weight:700; padding:8px 16px; border-radius:6px; text-decoration:none;">View Details</a>
                        </div>
                    </div>
                <?php endforeach; else : ?>
                    <p style="color:#94a3b8; text-align:center; grid-column: 1 / -1;">No matching pathology tests found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Featured Health Packages Shortcode Callback
     */
    public function render_health_packages_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title'        => __( 'Comprehensive Health Checkup Packages', 'pathology-booking-system' ),
            'limit'        => 3,
            'columns'      => 3,
            'category_id'  => '',
            'condition_id' => '',
            'city_id'      => '',
        ), $atts );

        $limit     = absint( $atts['limit'] );
        $cols      = absint( $atts['columns'] );
        $args      = array(
            'post_type'      => 'ptbs_package',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
        );

        $tax_query = array();
        if ( ! empty( $atts['category_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_category', 'field' => 'term_id', 'terms' => absint( $atts['category_id'] ) );
        }
        if ( ! empty( $atts['condition_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_condition', 'field' => 'term_id', 'terms' => absint( $atts['condition_id'] ) );
        }
        if ( ! empty( $atts['city_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_city', 'field' => 'term_id', 'terms' => absint( $atts['city_id'] ) );
        }

        if ( ! empty( $tax_query ) ) {
            $tax_query['relation'] = 'AND';
            $args['tax_query']     = $tax_query;
        }

        $packages = get_posts( $args );

        ob_start();
        ?>
        <div class="ptbs-health-packages-wrap" style="margin:30px 0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin-bottom:20px; text-align:center;"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr( $cols ); ?>, 1fr); gap:20px;">
                <?php if ( ! empty( $packages ) ) : foreach ( $packages as $p ) : 
                    $price     = get_post_meta( $p->ID, '_ptbs_price', true );
                    $mrp       = get_post_meta( $p->ID, '_ptbs_mrp', true );
                    $permalink = get_permalink( $p->ID );
                    $discount  = ( $mrp > $price ) ? round( ( ( $mrp - $price ) / $mrp ) * 100 ) : 0;
                ?>
                    <div style="background:#fff; border:2px solid #0d9488; border-radius:12px; padding:24px; box-shadow:0 4px 15px rgba(13,148,136,0.08); position:relative; display:flex; flex-direction:column; justify-content:space-between;">
                        <?php if ( $discount > 0 ) : ?>
                            <div style="position:absolute; top:-12px; right:20px; background:#ef4444; color:#fff; font-size:11px; font-weight:800; padding:4px 12px; border-radius:12px; text-transform:uppercase;">
                                <?php echo esc_html( $discount ); ?>% OFF
                            </div>
                        <?php endif; ?>

                        <div>
                            <span style="background:#ccfbf1; color:#0d9488; font-size:11px; font-weight:700; padding:4px 10px; border-radius:12px; display:inline-block; margin-bottom:8px;">
                                📦 HEALTH PACKAGE
                            </span>
                            <h3 style="font-size:18px; font-weight:800; color:#0f172a; margin:0 0 10px 0; line-height:1.4;">
                                <a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit; text-decoration:none;"><?php echo esc_html( $p->post_title ); ?></a>
                            </h3>
                        </div>

                        <div style="margin-top:20px; border-top:1px solid #f1f5f9; padding-top:16px; display:flex; align-items:center; justify-content:space-between;">
                            <div>
                                <span style="font-size:20px; font-weight:800; color:#0d9488;">₹<?php echo esc_html( number_format( floatval( $price ), 2 ) ); ?></span>
                                <?php if ( $mrp > $price ) : ?>
                                    <span style="font-size:13px; color:#94a3b8; text-decoration:line-through; margin-left:6px;">₹<?php echo esc_html( number_format( floatval( $mrp ), 2 ) ); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url( $permalink ); ?>" style="background:#0d9488; color:#fff; font-size:13px; font-weight:700; padding:10px 18px; border-radius:6px; text-decoration:none;">Book Package</a>
                        </div>
                    </div>
                <?php endforeach; else : ?>
                    <p style="color:#94a3b8; text-align:center; grid-column: 1 / -1;">No matching health packages found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Center Locations Shortcode Callback
     */
    public function render_center_locations_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title'    => __( 'Our Lab Center Locations', 'pathology-booking-system' ),
            'limit'    => 6,
            'columns'  => 3,
            'state_id' => '',
            'city_id'  => '',
        ), $atts );

        $limit     = absint( $atts['limit'] );
        $cols      = absint( $atts['columns'] );
        $args      = array(
            'post_type'      => 'ptbs_center_location',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
        );

        $tax_query = array();
        if ( ! empty( $atts['state_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_state', 'field' => 'term_id', 'terms' => absint( $atts['state_id'] ) );
        }
        if ( ! empty( $atts['city_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_city', 'field' => 'term_id', 'terms' => absint( $atts['city_id'] ) );
        }

        if ( ! empty( $tax_query ) ) {
            $tax_query['relation'] = 'AND';
            $args['tax_query']     = $tax_query;
        }

        $centers = get_posts( $args );

        ob_start();
        ?>
        <div class="ptbs-center-locations-wrap" style="margin:30px 0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin-bottom:20px; text-align:center;"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr( $cols ); ?>, 1fr); gap:20px;">
                <?php if ( ! empty( $centers ) ) : foreach ( $centers as $c ) : 
                    $phone     = get_post_meta( $c->ID, '_ptbs_phone', true );
                    $address   = get_post_meta( $c->ID, '_ptbs_address', true );
                    $hours     = get_post_meta( $c->ID, '_ptbs_hours', true );
                    $permalink = get_permalink( $c->ID );
                    $cities    = wp_get_post_terms( $c->ID, 'ptbs_city', array( 'fields' => 'names' ) );
                    $city_name = ! empty( $cities ) && ! is_wp_error( $cities ) ? $cities[0] : '';
                ?>
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <span style="background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; padding:4px 10px; border-radius:12px; display:inline-block; margin-bottom:8px;">
                                📍 <?php echo esc_html( $city_name ?: 'LAB CENTER' ); ?>
                            </span>
                            <h3 style="font-size:16px; font-weight:700; color:#0f172a; margin:0 0 10px 0; line-height:1.4;">
                                <a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit; text-decoration:none;"><?php echo esc_html( $c->post_title ); ?></a>
                            </h3>
                            <p style="font-size:13px; color:#64748b; margin:0 0 8px 0;"><?php echo esc_html( $address ); ?></p>
                            <?php if ( $hours ) : ?>
                                <div style="font-size:12px; color:#94a3b8;">⏰ <?php echo esc_html( $hours ); ?></div>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top:16px; border-top:1px solid #f1f5f9; padding-top:12px; display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-size:13px; font-weight:600; color:#0f172a;">📞 <?php echo esc_html( $phone ?: '1800-123-4567' ); ?></span>
                            <a href="<?php echo esc_url( $permalink ); ?>" style="background:#0284c7; color:#fff; font-size:12px; font-weight:700; padding:8px 14px; border-radius:6px; text-decoration:none;">View Center Page</a>
                        </div>
                    </div>
                <?php endforeach; else : ?>
                    <p style="color:#94a3b8; text-align:center; grid-column: 1 / -1;">No matching lab centers found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Premium Health Packages Slider & Grid Shortcode Callback
     */
    public function render_health_packages_slider_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'sub_heading'    => __( 'HEALTH CHECKUPS', 'pathology-booking-system' ),
            'title'          => __( 'Keep your family TRUly healthy.', 'pathology-booking-system' ),
            'description'    => __( 'Choose a package. Get tested TODAY!', 'pathology-booking-system' ),
            'show_view_all'  => 'yes',
            'view_all_url'   => '#',
            'layout_mode'    => 'carousel', // 'carousel' or 'grid'
            'limit'          => 8,
            'columns'        => 4,
            'autoplay'       => 'no',
            'category_id'    => '',
            'condition_id'   => '',
            'city_id'        => '',
        ), $atts );

        $limit     = absint( $atts['limit'] );
        $cols      = absint( $atts['columns'] );
        $mode      = ( 'grid' === $atts['layout_mode'] ) ? 'grid' : 'carousel';
        $slider_id = 'ptbs_packages_slider_' . wp_rand( 100, 999 );

        $args = array(
            'post_type'      => 'ptbs_package',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
        );

        $tax_query = array();
        if ( ! empty( $atts['category_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_category', 'field' => 'term_id', 'terms' => absint( $atts['category_id'] ) );
        }
        if ( ! empty( $atts['condition_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_condition', 'field' => 'term_id', 'terms' => absint( $atts['condition_id'] ) );
        }
        if ( ! empty( $atts['city_id'] ) ) {
            $tax_query[] = array( 'taxonomy' => 'ptbs_city', 'field' => 'term_id', 'terms' => absint( $atts['city_id'] ) );
        }

        if ( ! empty( $tax_query ) ) {
            $tax_query['relation'] = 'AND';
            $args['tax_query']     = $tax_query;
        }

        $packages = get_posts( $args );

        ob_start();
        ?>
        <div class="ptbs-premium-packages-section" style="margin:40px 0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            
            <!-- Section Header -->
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
                <div>
                    <?php if ( ! empty( $atts['sub_heading'] ) ) : ?>
                        <span style="color:#0056b3; font-size:13px; font-weight:800; letter-spacing:1.5px; text-transform:uppercase; display:block; margin-bottom:6px;">
                            <?php echo esc_html( $atts['sub_heading'] ); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ( ! empty( $atts['title'] ) ) : ?>
                        <h2 style="font-size:36px; font-weight:800; color:#0b192c; margin:0 0 6px 0; line-height:1.2;">
                            <?php echo esc_html( $atts['title'] ); ?>
                        </h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $atts['description'] ) ) : ?>
                        <p style="font-size:14px; color:#64748b; margin:0;"><?php echo esc_html( $atts['description'] ); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ( 'yes' === $atts['show_view_all'] ) : ?>
                    <div>
                        <a href="<?php echo esc_url( $atts['view_all_url'] ); ?>" style="border:1px solid #cbd5e1; border-radius:20px; padding:8px 20px; font-size:11px; font-weight:800; color:#334155; text-decoration:none; text-transform:uppercase; display:inline-flex; align-items:center; gap:6px; background:#fff; transition:all 0.2s;">
                            VIEW ALL <i class="fas fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content Container (Carousel or Grid) -->
            <?php if ( 'carousel' === $mode ) : ?>
                <div class="ptbs-packages-slick-wrapper">
                    <div id="<?php echo esc_attr( $slider_id ); ?>" class="ptbs-packages-slick-carousel" style="margin:0 -10px;">
            <?php else : ?>
                <div style="display:grid; grid-template-columns: repeat(<?php echo esc_attr( $cols ); ?>, 1fr); gap:20px;">
            <?php endif; ?>

                <?php if ( ! empty( $packages ) ) : foreach ( $packages as $pkg ) : 
                    $pid            = $pkg->ID;
                    $price          = get_post_meta( $pid, '_ptbs_price', true );
                    $mrp            = get_post_meta( $pid, '_ptbs_mrp', true );
                    $badge_text     = get_post_meta( $pid, '_ptbs_badge_text', true );
                    if ( empty( $badge_text ) ) {
                        $badge_text = ( $price < 1200 ) ? 'MOST POPULAR' : ( ( $price < 2000 ) ? 'BEST VALUE' : ( ( $price < 3000 ) ? 'ESSENTIAL' : 'ADVANCED' ) );
                    }
                    $badge_color    = get_post_meta( $pid, '_ptbs_badge_color', true );
                    if ( empty( $badge_color ) ) {
                        if ( 'MOST POPULAR' === strtoupper( $badge_text ) )     { $badge_color = '#34d399'; }
                        elseif ( 'BEST VALUE' === strtoupper( $badge_text ) )  { $badge_color = '#3b82f6'; }
                        elseif ( 'ESSENTIAL' === strtoupper( $badge_text ) )   { $badge_color = '#f59e0b'; }
                        elseif ( 'ADVANCED' === strtoupper( $badge_text ) )    { $badge_color = '#8b5cf6'; }
                        else { $badge_color = '#0056b3'; }
                    }
                    $subtitle       = get_post_meta( $pid, '_ptbs_subtitle', true ) ?: 'Complete Health Checkup';
                    $gender_rec     = get_post_meta( $pid, '_ptbs_gender_recommendation', true ) ?: 'Recommended for Male & Female';
                    $params_count   = get_post_meta( $pid, '_ptbs_parameters_count', true ) ?: 54;
                    $linked_tests   = get_post_meta( $pid, '_ptbs_linked_test_ids', true );
                    if ( ! is_array( $linked_tests ) ) $linked_tests = @unserialize( $linked_tests );
                    $tests_count    = is_array( $linked_tests ) ? count( $linked_tests ) : 21;
                    $img_url        = get_the_post_thumbnail_url( $pid, 'medium_large' );
                    $permalink      = get_permalink( $pid );
                ?>
                    <div style="<?php echo ( 'carousel' === $mode ) ? 'padding:0 10px;' : ''; ?>">
                        <div class="ptbs-pkg-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden; box-shadow:0 4px 18px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between; height:100%;">
                            
                            <!-- Card Header Image & Badge -->
                            <div style="position:relative; width:100%; height:200px; background:#f1f5f9; overflow:hidden;">
                                <?php if ( $img_url ) : ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $pkg->post_title ); ?>" style="width:100%; height:100%; object-fit:cover; border-top-left-radius:20px; border-top-right-radius:20px;">
                                <?php else : ?>
                                    <div style="width:100%; height:100%; background:linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); display:flex; align-items:center; justify-content:center; font-size:48px; color:#0284c7;">
                                        <i class="fas fa-notes-medical"></i>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $badge_text ) ) : ?>
                                    <div style="position:absolute; top:14px; left:14px; background:<?php echo esc_attr( $badge_color ); ?>; color:#fff; font-size:10px; font-weight:800; padding:6px 14px; border-radius:20px; text-transform:uppercase; letter-spacing:0.5px; box-shadow:0 2px 8px rgba(0,0,0,0.12);">
                                        <?php echo esc_html( $badge_text ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Body Content -->
                            <div style="padding:22px 20px; flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                                <div>
                                    <h3 style="font-size:19px; font-weight:800; color:#0b192c; margin:0 0 4px 0; line-height:1.3;">
                                        <a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit; text-decoration:none;"><?php echo esc_html( $pkg->post_title ); ?></a>
                                    </h3>
                                    <p style="font-size:13px; color:#94a3b8; margin:0 0 16px 0;"><?php echo esc_html( $subtitle ); ?></p>
                                </div>

                                <div>
                                    <div style="border-top:1px solid #f1f5f9; padding-top:14px; margin-bottom:14px;">
                                        <div style="font-size:13px; font-weight:700; color:#1e293b;">
                                            <?php echo esc_html( $params_count ); ?> Parameters • <?php echo esc_html( $tests_count ); ?> Tests
                                        </div>
                                        <div style="font-size:12px; font-weight:600; color:#2563eb; margin-top:4px;">
                                            <?php echo esc_html( $gender_rec ); ?>
                                        </div>
                                    </div>

                                    <!-- Price & CTA Buttons -->
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                                        <div>
                                            <span style="font-size:24px; font-weight:800; color:#0056b3;">₹ <?php echo esc_html( number_format( floatval( $price ) ) ); ?></span>
                                            <?php if ( $mrp > $price ) : ?>
                                                <span style="font-size:13px; color:#cbd5e1; text-decoration:line-through; margin-left:8px;">₹ <?php echo esc_html( number_format( floatval( $mrp ) ) ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:8px;">
                                        <button type="button" class="ptbs-add-to-cart-btn ptbs-add-to-cart" data-id="<?php echo esc_attr( $pid ); ?>" data-type="package" data-title="<?php echo esc_attr( $pkg->post_title ); ?>" data-price="<?php echo esc_attr( $price ); ?>" style="flex:1.4; background:#0056b3; color:#fff; border:none; padding:12px 10px; border-radius:10px; font-size:11px; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px; white-space:nowrap; transition:background 0.2s;">
                                            <i class="fas fa-shopping-bag"></i> ADD TO CART
                                        </button>
                                        <a href="<?php echo esc_url( $permalink ); ?>" class="ptbs-view-btn" style="flex:0.8; background:#f1f5f9; color:#334155; padding:12px 12px; border-radius:10px; font-size:11px; font-weight:800; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:5px; white-space:nowrap;">
                                            <i class="far fa-dot-circle"></i> VIEW
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; else : ?>
                    <p style="color:#94a3b8; text-align:center; grid-column: 1 / -1;">No health packages available.</p>
                <?php endif; ?>

            <?php if ( 'carousel' === $mode ) : ?>
                    </div>
                </div>
            <?php else : ?>
                </div>
            <?php endif; ?>

        </div>

        <?php if ( 'carousel' === $mode ) : ?>
            <script>
            jQuery(document).ready(function($) {
                if (typeof $.fn.slick === 'function') {
                    $('#<?php echo esc_js( $slider_id ); ?>').slick({
                        dots: false,
                        arrows: true,
                        prevArrow: '<button type="button" class="slick-prev ptbs-slick-arrow" aria-label="Previous"><i class="fas fa-arrow-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next ptbs-slick-arrow" aria-label="Next"><i class="fas fa-arrow-right"></i></button>',
                        infinite: true,
                        speed: 500,
                        slidesToShow: <?php echo esc_js( $cols ); ?>,
                        slidesToScroll: 1,
                        autoplay: <?php echo ( 'yes' === $atts['autoplay'] ) ? 'true' : 'false'; ?>,
                        responsive: [
                            { breakpoint: 1024, settings: { slidesToShow: 3 } },
                            { breakpoint: 768,  settings: { slidesToShow: 2 } },
                            { breakpoint: 480,  settings: { slidesToShow: 1 } }
                        ]
                    });
                }
            });
            </script>
        <?php endif; ?>

        <?php
        return ob_get_clean();
    }
}


