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

        // Diagnostic Management AJAX Endpoints
        add_action( 'wp_ajax_ptbs_get_diagnostic_tab_data', array( $this, 'ajax_get_diagnostic_tab_data' ) );
        add_action( 'wp_ajax_ptbs_get_diagnostic_form', array( $this, 'ajax_get_diagnostic_form' ) );
        add_action( 'wp_ajax_ptbs_save_diagnostic_item', array( $this, 'ajax_save_diagnostic_item' ) );
        add_action( 'wp_ajax_ptbs_toggle_diagnostic_status', array( $this, 'ajax_toggle_diagnostic_status' ) );
        add_action( 'wp_ajax_ptbs_delete_diagnostic_item', array( $this, 'ajax_delete_diagnostic_item' ) );
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
        wp_enqueue_style( 'ptbs-diagnostic-admin-css', PTBS_DIR_URL . 'admin/css/diagnostic-management-admin.css', array( 'select2' ), PTBS_VERSION );
        wp_enqueue_script( 'ptbs-admin-js', PTBS_DIR_URL . 'admin/js/ptbs-admin.js', array( 'jquery', 'select2' ), PTBS_VERSION, true );
        wp_enqueue_script( 'ptbs-diagnostic-admin-js', PTBS_DIR_URL . 'admin/js/diagnostic-management-admin.js', array( 'jquery', 'select2' ), PTBS_VERSION, true );

        wp_localize_script( 'ptbs-diagnostic-admin-js', 'ptbsAdminSettings', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ptbs_diag_ajax_nonce' ),
        ) );
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

    // ----------------------------------------------------
    // AJAX Handlers for Diagnostic Management
    // ----------------------------------------------------

    public function ajax_get_diagnostic_tab_data() {
        check_ajax_referer( 'ptbs_diag_ajax_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $tab = isset( $_POST['tab'] ) ? sanitize_key( $_POST['tab'] ) : 'category';

        ob_start();
        include PTBS_DIR_PATH . 'admin/views/diagnostic-table-partial.php';
        $html = ob_get_clean();

        // Calculate KPI stats
        $stats = array(
            'active_tests'    => wp_count_posts( 'ptbs_test' )->publish,
            'active_packages' => wp_count_posts( 'ptbs_package' )->publish,
            'subcategories'   => wp_count_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) ),
            'conditions'      => wp_count_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) ),
        );

        wp_send_json_success( array(
            'html'  => $html,
            'stats' => $stats,
        ) );
    }

    public function ajax_get_diagnostic_form() {
        check_ajax_referer( 'ptbs_diag_ajax_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $tab     = isset( $_POST['tab'] ) ? sanitize_key( $_POST['tab'] ) : 'category';
        $item_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

        $title = ( $item_id > 0 ? 'Edit ' : 'Add New ' ) . ucfirst( $tab );

        ob_start();
        echo '<form id="ptbs_drawer_form">';
        wp_nonce_field( 'ptbs_diag_action', 'ptbs_diag_nonce' );
        echo '<input type="hidden" name="item_id" value="' . esc_attr( $item_id ) . '">';
        echo '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '">';

        if ( 'category' === $tab ) {
            include PTBS_DIR_PATH . 'admin/views/category-form.php';
        } elseif ( 'subcategory' === $tab ) {
            include PTBS_DIR_PATH . 'admin/views/subcategory-form.php';
        } elseif ( 'condition' === $tab ) {
            include PTBS_DIR_PATH . 'admin/views/condition-form.php';
        } elseif ( 'test' === $tab ) {
            include PTBS_DIR_PATH . 'admin/views/test-form.php';
        } elseif ( 'package' === $tab ) {
            include PTBS_DIR_PATH . 'admin/views/package-form.php';
        }

        echo '<div style="margin-top:24px; display:flex; justify-content:flex-end; gap:12px;">';
        echo '<button type="button" class="ptbs-action-btn ptbs-drawer-cancel">Cancel</button>';
        echo '<button type="submit" class="ptbs-btn-primary">Save & Publish</button>';
        echo '</div>';
        echo '</form>';
        $html = ob_get_clean();

        wp_send_json_success( array(
            'title' => $title,
            'html'  => $html,
        ) );
    }

    public function ajax_save_diagnostic_item() {
        check_ajax_referer( 'ptbs_diag_ajax_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $tab         = isset( $_POST['tab'] ) ? sanitize_key( $_POST['tab'] ) : '';
        $item_id     = isset( $_POST['item_id'] ) ? absint( $_POST['item_id'] ) : 0;
        $action_type = isset( $_POST['ptbs_action_type'] ) ? sanitize_text_field( $_POST['ptbs_action_type'] ) : '';

        if ( 'save_category' === $action_type ) {
            $cat_name = sanitize_text_field( wp_unslash( $_POST['category_name'] ?? '' ) );
            $image_id = absint( $_POST['category_image_id'] ?? 0 );
            $status   = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

            if ( empty( $cat_name ) ) {
                wp_send_json_error( array( 'message' => 'Category name is required.' ) );
            }

            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_category', array( 'name' => $cat_name ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $cat_name, 'ptbs_category' );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                wp_send_json_success( array( 'message' => 'Category saved successfully.' ) );
            }
        } elseif ( 'save_subcategory' === $action_type ) {
            $subcat_name = sanitize_text_field( wp_unslash( $_POST['subcategory_name'] ?? '' ) );
            $parent_id   = absint( $_POST['parent_category_id'] ?? 0 );
            $image_id    = absint( $_POST['subcategory_image_id'] ?? 0 );
            $status      = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

            if ( empty( $subcat_name ) ) {
                wp_send_json_error( array( 'message' => 'Subcategory name is required.' ) );
            }

            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_subcategory', array( 'name' => $subcat_name, 'parent' => $parent_id ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $subcat_name, 'ptbs_subcategory', array( 'parent' => $parent_id ) );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                update_term_meta( $term_id, '_ptbs_parent_category_id', $parent_id );
                wp_send_json_success( array( 'message' => 'Sub Category saved successfully.' ) );
            }
        } elseif ( 'save_condition' === $action_type ) {
            $cond_name = sanitize_text_field( wp_unslash( $_POST['condition_name'] ?? '' ) );
            $image_id  = absint( $_POST['condition_image_id'] ?? 0 );
            $status    = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

            if ( empty( $cond_name ) ) {
                wp_send_json_error( array( 'message' => 'Condition name is required.' ) );
            }

            if ( $item_id > 0 ) {
                wp_update_term( $item_id, 'ptbs_condition', array( 'name' => $cond_name ) );
                $term_id = $item_id;
            } else {
                $term = wp_insert_term( $cond_name, 'ptbs_condition' );
                $term_id = is_array( $term ) ? $term['term_id'] : 0;
            }

            if ( $term_id > 0 ) {
                update_term_meta( $term_id, '_ptbs_image_id', $image_id );
                update_term_meta( $term_id, '_ptbs_status', $status );
                wp_send_json_success( array( 'message' => 'Condition saved successfully.' ) );
            }
        } elseif ( 'save_test' === $action_type ) {
            $test_title  = sanitize_text_field( wp_unslash( $_POST['test_name'] ?? '' ) );
            $price       = floatval( $_POST['price'] ?? 0 );
            $main_name   = sanitize_text_field( wp_unslash( $_POST['main_test_name'] ?? '' ) );
            $code        = sanitize_text_field( wp_unslash( $_POST['test_code'] ?? '' ) );
            $cutoff      = sanitize_text_field( wp_unslash( $_POST['cutoff_time'] ?? '' ) );
            $method      = sanitize_text_field( wp_unslash( $_POST['method'] ?? '' ) );
            $specimen    = sanitize_text_field( wp_unslash( $_POST['specimen'] ?? '' ) );
            $delivery    = sanitize_text_field( wp_unslash( $_POST['report_delivery'] ?? '' ) );
            $cat_ids     = isset( $_POST['category_ids'] ) && is_array( $_POST['category_ids'] ) ? array_map( 'absint', $_POST['category_ids'] ) : array();
            $subcat_ids  = isset( $_POST['subcategory_ids'] ) && is_array( $_POST['subcategory_ids'] ) ? array_map( 'absint', $_POST['subcategory_ids'] ) : array();
            $cond_ids    = isset( $_POST['condition_ids'] ) && is_array( $_POST['condition_ids'] ) ? array_map( 'absint', $_POST['condition_ids'] ) : array();
            $status      = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

            $post_data = array(
                'post_title'  => $test_title,
                'post_type'   => 'ptbs_test',
                'post_status' => 'publish',
            );

            if ( $item_id > 0 ) {
                $post_data['ID'] = $item_id;
                $post_id = wp_update_post( $post_data );
            } else {
                $post_id = wp_insert_post( $post_data );
            }

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_ptbs_price', $price );
                update_post_meta( $post_id, '_ptbs_main_test_name', $main_name );
                update_post_meta( $post_id, '_ptbs_code', $code );
                update_post_meta( $post_id, '_ptbs_cutoff_time', $cutoff );
                update_post_meta( $post_id, '_ptbs_method', $method );
                update_post_meta( $post_id, '_ptbs_sample_type', $specimen );
                update_post_meta( $post_id, '_ptbs_tat_hours', $delivery );
                update_post_meta( $post_id, '_ptbs_status', $status );

                wp_set_post_terms( $post_id, $cat_ids, 'ptbs_category' );
                wp_set_post_terms( $post_id, $subcat_ids, 'ptbs_subcategory' );
                wp_set_post_terms( $post_id, $cond_ids, 'ptbs_condition' );

                wp_send_json_success( array( 'message' => 'Test details saved successfully.' ) );
            }
        } elseif ( 'save_package' === $action_type ) {
            $pkg_title    = sanitize_text_field( wp_unslash( $_POST['package_name'] ?? '' ) );
            $price        = floatval( $_POST['price'] ?? 0 );
            $mrp          = floatval( $_POST['mrp'] ?? 0 );
            $code         = sanitize_text_field( wp_unslash( $_POST['test_code'] ?? '' ) );
            $params_count = absint( $_POST['parameters'] ?? 0 );
            $meta_title   = sanitize_text_field( wp_unslash( $_POST['meta_title'] ?? '' ) );
            $meta_kw      = sanitize_text_field( wp_unslash( $_POST['meta_keywords'] ?? '' ) );
            $meta_desc    = sanitize_textarea_field( wp_unslash( $_POST['meta_description'] ?? '' ) );
            $desc         = sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) );
            $faq          = sanitize_textarea_field( wp_unslash( $_POST['faq'] ?? '' ) );
            $status       = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );
            $image_id     = absint( $_POST['package_image_id'] ?? 0 );
            $linked_tests = isset( $_POST['linked_test_ids'] ) && is_array( $_POST['linked_test_ids'] ) ? array_values( array_filter( array_map( 'absint', $_POST['linked_test_ids'] ) ) ) : array();
            $cat_ids      = isset( $_POST['category_ids'] ) && is_array( $_POST['category_ids'] ) ? array_map( 'absint', $_POST['category_ids'] ) : array();
            $subcat_ids   = isset( $_POST['subcategory_ids'] ) && is_array( $_POST['subcategory_ids'] ) ? array_map( 'absint', $_POST['subcategory_ids'] ) : array();
            $cond_ids     = isset( $_POST['condition_ids'] ) && is_array( $_POST['condition_ids'] ) ? array_map( 'absint', $_POST['condition_ids'] ) : array();

            $post_data = array(
                'post_title'   => $pkg_title,
                'post_content' => $desc,
                'post_type'    => 'ptbs_package',
                'post_status'  => 'publish',
            );

            if ( $item_id > 0 ) {
                $post_data['ID'] = $item_id;
                $post_id = wp_update_post( $post_data );
            } else {
                $post_id = wp_insert_post( $post_data );
            }

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_ptbs_price', $price );
                update_post_meta( $post_id, '_ptbs_mrp', $mrp );
                update_post_meta( $post_id, '_ptbs_code', $code );
                update_post_meta( $post_id, '_ptbs_parameters_count', $params_count );
                update_post_meta( $post_id, '_ptbs_meta_title', $meta_title );
                update_post_meta( $post_id, '_ptbs_meta_keywords', $meta_kw );
                update_post_meta( $post_id, '_ptbs_meta_description', $meta_desc );
                update_post_meta( $post_id, '_ptbs_faqs', $faq );
                update_post_meta( $post_id, '_ptbs_status', $status );
                update_post_meta( $post_id, '_ptbs_linked_test_ids', $linked_tests );

                wp_set_post_terms( $post_id, $cat_ids, 'ptbs_category' );
                wp_set_post_terms( $post_id, $subcat_ids, 'ptbs_subcategory' );
                wp_set_post_terms( $post_id, $cond_ids, 'ptbs_condition' );

                if ( $image_id > 0 ) {
                    set_post_thumbnail( $post_id, $image_id );
                }

                wp_send_json_success( array( 'message' => 'Package details saved successfully.' ) );
            }
        }

        wp_send_json_error( array( 'message' => 'Invalid save request.' ) );
    }

    public function ajax_toggle_diagnostic_status() {
        check_ajax_referer( 'ptbs_diag_ajax_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $id     = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        $type   = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'Active';

        if ( in_array( $type, array( 'category', 'subcategory', 'condition' ), true ) ) {
            update_term_meta( $id, '_ptbs_status', $status );
            wp_send_json_success( array( 'message' => 'Status updated.' ) );
        } elseif ( in_array( $type, array( 'test', 'package' ), true ) ) {
            update_post_meta( $id, '_ptbs_status', $status );
            wp_send_json_success( array( 'message' => 'Status updated.' ) );
        }

        wp_send_json_error( array( 'message' => 'Invalid toggle request.' ) );
    }

    public function ajax_delete_diagnostic_item() {
        check_ajax_referer( 'ptbs_diag_ajax_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $id   = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        $type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

        if ( 'category' === $type ) {
            wp_delete_term( $id, 'ptbs_category' );
            wp_send_json_success( array( 'message' => 'Category deleted.' ) );
        } elseif ( 'subcategory' === $type ) {
            wp_delete_term( $id, 'ptbs_subcategory' );
            wp_send_json_success( array( 'message' => 'Subcategory deleted.' ) );
        } elseif ( 'condition' === $type ) {
            wp_delete_term( $id, 'ptbs_condition' );
            wp_send_json_success( array( 'message' => 'Condition deleted.' ) );
        } elseif ( 'test' === $type || 'package' === $type ) {
            wp_delete_post( $id, true );
            wp_send_json_success( array( 'message' => ucfirst( $type ) . ' deleted.' ) );
        }

        wp_send_json_error( array( 'message' => 'Invalid delete request.' ) );
    }
}
