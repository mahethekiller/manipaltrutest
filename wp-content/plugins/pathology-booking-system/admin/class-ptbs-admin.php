<?php
/**
 * Admin Dashboard Manager & Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Admin {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'admin_post_ptbs_save_settings', array( $this, 'handle_save_settings' ) );
        add_action( 'admin_post_ptbs_update_booking_status', array( $this, 'handle_update_booking_status' ) );
    }

    public function register_admin_menus() {
        add_menu_page(
            __( 'Pathology Booking', 'pathology-booking-system' ),
            __( 'Pathology Booking', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-dashboard',
            array( $this, 'render_bookings_page' ),
            'dashicons-clipboard',
            25
        );

        add_submenu_page(
            'ptbs-dashboard',
            __( 'Diagnostic Management', 'pathology-booking-system' ),
            __( 'Diagnostic Management', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-diagnostic-management',
            array( $this, 'render_diagnostic_management_page' )
        );

        add_submenu_page(
            'ptbs-dashboard',
            __( 'Bookings Manager', 'pathology-booking-system' ),
            __( 'All Bookings', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-dashboard',
            array( $this, 'render_bookings_page' )
        );

        add_submenu_page(
            'ptbs-dashboard',
            __( 'Coupons & Discounts', 'pathology-booking-system' ),
            __( 'Coupons', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-coupons',
            array( $this, 'render_coupons_page' )
        );

        add_submenu_page(
            'ptbs-dashboard',
            __( 'Gateway & API Settings', 'pathology-booking-system' ),
            __( 'Settings', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-settings',
            array( $this, 'render_settings_page' )
        );

        add_submenu_page(
            'ptbs-dashboard',
            __( 'Documentation & Setup', 'pathology-booking-system' ),
            __( 'Documentation', 'pathology-booking-system' ),
            'manage_options',
            'ptbs-documentation',
            array( $this, 'render_documentation_page' )
        );
    }

    public function register_settings() {
        register_setting( 'ptbs_settings_group', 'ptbs_settings' );
    }

    public function enqueue_admin_assets( $hook ) {
        if ( false === strpos( $hook, 'ptbs' ) ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0' );
        wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '4.1.0', true );
        wp_enqueue_style( 'ptbs-admin-css', PTBS_DIR_URL . 'admin/css/ptbs-admin.css', array( 'select2' ), PTBS_VERSION );
        wp_enqueue_script( 'ptbs-admin-js', PTBS_DIR_URL . 'admin/js/ptbs-admin.js', array( 'jquery', 'select2' ), PTBS_VERSION, true );
    }

    public function render_diagnostic_management_page() {
        include PTBS_DIR_PATH . 'admin/views/diagnostic-management.php';
    }

    public function render_coupons_page() {
        include PTBS_DIR_PATH . 'admin/views/coupons-management.php';
    }

    public function render_bookings_page() {
        global $wpdb;

        if ( isset( $_GET['action'] ) && 'view' === $_GET['action'] && isset( $_GET['id'] ) ) {
            $booking_id = absint( $_GET['id'] );
            $booking    = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ptbs_bookings WHERE id = %d", $booking_id ) );
            $items      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ptbs_booking_items WHERE booking_id = %d", $booking_id ) );
            include PTBS_DIR_PATH . 'admin/views/booking-details.php';
            return;
        }

        $table_name = $wpdb->prefix . 'ptbs_bookings';
        $bookings   = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY id DESC LIMIT 100" );
        include PTBS_DIR_PATH . 'admin/views/bookings-list.php';
    }

    public function render_settings_page() {
        $settings = get_option( 'ptbs_settings', array() );
        include PTBS_DIR_PATH . 'admin/views/gateway-settings.php';
    }

    public function render_documentation_page() {
        include PTBS_DIR_PATH . 'admin/views/documentation.php';
    }

    public function handle_save_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized capability.', 'pathology-booking-system' ) );
        }

        check_admin_referer( 'ptbs_save_settings_action', 'ptbs_settings_nonce' );

        $time_slots = array();
        if ( isset( $_POST['time_slots'] ) && is_array( $_POST['time_slots'] ) ) {
            foreach ( $_POST['time_slots'] as $slot ) {
                if ( ! empty( $slot['label'] ) ) {
                    $label = sanitize_text_field( wp_unslash( $slot['label'] ) );
                    $max   = ( isset( $slot['max_bookings'] ) && '' !== trim( $slot['max_bookings'] ) ) ? absint( $slot['max_bookings'] ) : '';
                    $time_slots[] = array(
                        'label'        => $label,
                        'max_bookings' => $max,
                    );
                }
            }
        }

        $settings = array(
            'test_permalink_slug'    => isset( $_POST['test_permalink_slug'] ) ? sanitize_title( wp_unslash( $_POST['test_permalink_slug'] ) ) : 'test',
            'package_permalink_slug' => isset( $_POST['package_permalink_slug'] ) ? sanitize_title( wp_unslash( $_POST['package_permalink_slug'] ) ) : 'package',
            'booking_page_url'       => isset( $_POST['booking_page_url'] ) ? esc_url_raw( wp_unslash( $_POST['booking_page_url'] ) ) : home_url( '/lab/' ),
            'dashboard_page_url'     => isset( $_POST['dashboard_page_url'] ) ? esc_url_raw( wp_unslash( $_POST['dashboard_page_url'] ) ) : '',
            'city_trigger_mode'      => isset( $_POST['city_trigger_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['city_trigger_mode'] ) ) : 'lab_page',
            'time_slots'             => $time_slots,
            'enable_mock_payment'    => isset( $_POST['enable_mock_payment'] ) ? '1' : '0',
            'razorpay_key_id'        => isset( $_POST['razorpay_key_id'] ) ? sanitize_text_field( wp_unslash( $_POST['razorpay_key_id'] ) ) : '',
            'razorpay_key_secret'    => isset( $_POST['razorpay_key_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['razorpay_key_secret'] ) ) : '',
            'phonepe_merchant_id'    => isset( $_POST['phonepe_merchant_id'] ) ? sanitize_text_field( wp_unslash( $_POST['phonepe_merchant_id'] ) ) : '',
            'phonepe_salt_key'       => isset( $_POST['phonepe_salt_key'] ) ? sanitize_text_field( wp_unslash( $_POST['phonepe_salt_key'] ) ) : '',
            'phonepe_salt_index'     => isset( $_POST['phonepe_salt_index'] ) ? sanitize_text_field( wp_unslash( $_POST['phonepe_salt_index'] ) ) : '1',
            'google_client_id'       => isset( $_POST['google_client_id'] ) ? sanitize_text_field( wp_unslash( $_POST['google_client_id'] ) ) : '',
        );

        update_option( 'ptbs_settings', $settings );

        // Register CPTs with new slugs and flush rewrite rules
        PTBS_CPT::register_post_types();
        PTBS_CPT::register_taxonomies();
        flush_rewrite_rules();

        wp_safe_redirect( add_query_arg( array( 'page' => 'ptbs-settings', 'updated' => 'true' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    public function handle_update_booking_status() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized capability.', 'pathology-booking-system' ) );
        }

        check_admin_referer( 'ptbs_update_booking_action', 'ptbs_booking_nonce' );

        global $wpdb;
        $booking_id         = isset( $_POST['booking_id'] ) ? absint( $_POST['booking_id'] ) : 0;
        $fulfillment_status = isset( $_POST['fulfillment_status'] ) ? sanitize_text_field( wp_unslash( $_POST['fulfillment_status'] ) ) : 'pending';
        $report_url         = isset( $_POST['report_file_url'] ) ? esc_url_raw( wp_unslash( $_POST['report_file_url'] ) ) : '';

        if ( $booking_id > 0 ) {
            $wpdb->update(
                $wpdb->prefix . 'ptbs_bookings',
                array(
                    'fulfillment_status' => $fulfillment_status,
                    'report_file_url'    => $report_url,
                ),
                array( 'id' => $booking_id )
            );
        }

        wp_safe_redirect( add_query_arg( array( 'page' => 'ptbs-dashboard', 'action' => 'view', 'id' => $booking_id, 'updated' => 'true' ), admin_url( 'admin.php' ) ) );
        exit;
    }
}
