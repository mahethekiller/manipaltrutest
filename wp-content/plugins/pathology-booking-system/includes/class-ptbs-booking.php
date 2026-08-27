<?php
/**
 * Booking Processing & Slot Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Booking {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'wp_ajax_ptbs_process_booking', array( $this, 'ajax_process_booking' ) );
        add_action( 'wp_ajax_nopriv_ptbs_process_booking', array( $this, 'ajax_process_booking' ) );
        add_action( 'wp_ajax_ptbs_get_city_catalog', array( $this, 'ajax_get_city_catalog' ) );
        add_action( 'wp_ajax_nopriv_ptbs_get_city_catalog', array( $this, 'ajax_get_city_catalog' ) );
        add_action( 'wp_ajax_ptbs_apply_coupon', array( $this, 'ajax_apply_coupon' ) );
        add_action( 'wp_ajax_nopriv_ptbs_apply_coupon', array( $this, 'ajax_apply_coupon' ) );
    }

    /**
     * Get Catalog items (Tests & Packages) for selected city
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
            $tax_query = array(
                array(
                    'taxonomy' => 'ptbs_city',
                    'field'    => 'term_id',
                    'terms'    => $city_id,
                ),
            );
            $args_tests['tax_query']    = $tax_query;
            $args_packages['tax_query'] = $tax_query;
        }

        $test_posts = get_posts( $args_tests );
        $pkg_posts  = get_posts( $args_packages );

        $tests    = array();
        $packages = array();

        foreach ( $test_posts as $post ) {
            $tests[] = array(
                'id'          => $post->ID,
                'title'       => $post->post_title,
                'excerpt'     => $post->post_excerpt,
                'code'        => get_post_meta( $post->ID, '_ptbs_test_code', true ),
                'price'       => get_post_meta( $post->ID, '_ptbs_price', true ),
                'fasting_req' => get_post_meta( $post->ID, '_ptbs_fasting_req', true ),
                'tat_hours'   => get_post_meta( $post->ID, '_ptbs_tat_hours', true ),
            );
        }

        foreach ( $pkg_posts as $post ) {
            $packages[] = array(
                'id'      => $post->ID,
                'title'   => $post->post_title,
                'excerpt' => $post->post_excerpt,
                'price'   => get_post_meta( $post->ID, '_ptbs_price', true ),
            );
        }

        wp_send_json_success( array(
            'tests'    => $tests,
            'packages' => $packages,
        ) );
    }

    /**
     * Process patient booking & initialize gateway order
     */
    public function ajax_process_booking() {
        check_ajax_referer( 'ptbs_public_nonce', 'security' );

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array(
                'message'      => __( 'Authentication required. Please log in or create an account to complete your booking.', 'pathology-booking-system' ),
                'require_auth' => true,
            ) );
        }

        $patient_name   = isset( $_POST['patient_name'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_name'] ) ) : '';
        $patient_email  = isset( $_POST['patient_email'] ) ? sanitize_email( wp_unslash( $_POST['patient_email'] ) ) : '';
        $patient_phone  = isset( $_POST['patient_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_phone'] ) ) : '';
        $patient_age    = isset( $_POST['patient_age'] ) ? absint( $_POST['patient_age'] ) : 30;
        $patient_gender = isset( $_POST['patient_gender'] ) ? sanitize_text_field( wp_unslash( $_POST['patient_gender'] ) ) : 'male';
        $booking_type   = isset( $_POST['booking_type'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_type'] ) ) : 'home_collection';
        $city_id        = isset( $_POST['city_id'] ) ? absint( $_POST['city_id'] ) : 0;
        $city_name      = isset( $_POST['city_name'] ) ? sanitize_text_field( wp_unslash( $_POST['city_name'] ) ) : 'Default City';
        $address_line1  = isset( $_POST['address_line1'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address_line1'] ) ) : '';
        $pincode        = isset( $_POST['pincode'] ) ? sanitize_text_field( wp_unslash( $_POST['pincode'] ) ) : '';
        $booking_date = isset( $_POST['booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_date'] ) ) : '';

        $booking_slot = '';
        if ( ! empty( $_POST['booking_slot'] ) ) {
            $booking_slot = sanitize_text_field( wp_unslash( $_POST['booking_slot'] ) );
        } elseif ( ! empty( $_POST['time_slot'] ) ) {
            $booking_slot = sanitize_text_field( wp_unslash( $_POST['time_slot'] ) );
        }

        $gateway = 'mock';
        if ( ! empty( $_POST['gateway'] ) ) {
            $gateway = sanitize_text_field( wp_unslash( $_POST['gateway'] ) );
        } elseif ( ! empty( $_POST['payment_method'] ) ) {
            $gateway = sanitize_text_field( wp_unslash( $_POST['payment_method'] ) );
        }

        $items_raw    = isset( $_POST['items'] ) ? json_decode( wp_unslash( $_POST['items'] ), true ) : array();
        $patients_raw = isset( $_POST['patients'] ) ? json_decode( wp_unslash( $_POST['patients'] ), true ) : array();

        if ( empty( $patient_email ) || empty( $booking_date ) || empty( $booking_slot ) || empty( $items_raw ) ) {
            wp_send_json_error( array( 'message' => __( 'Please fill in all mandatory booking fields and select at least one test.', 'pathology-booking-system' ) ) );
        }

        // Format Multi-Patient Summary Name
        $patient_names_arr = array();
        if ( ! empty( $patients_raw ) && is_array( $patients_raw ) ) {
            foreach ( $patients_raw as $p ) {
                if ( ! empty( $p['name'] ) ) {
                    $patient_names_arr[] = sanitize_text_field( $p['name'] ) . ( ! empty( $p['relation'] ) && $p['relation'] !== 'Self' ? ' (' . sanitize_text_field( $p['relation'] ) . ')' : '' );
                }
            }
        }

        if ( ! empty( $patient_names_arr ) ) {
            $patient_name = implode( ', ', $patient_names_arr );
        }

        $patient_count = ! empty( $patients_raw ) && is_array( $patients_raw ) ? count( $patients_raw ) : 1;

        // Compute total amount (Subtotal x Patient Count)
        $subtotal      = 0;
        $booking_items = array();

        foreach ( $items_raw as $item ) {
            $item_id   = absint( $item['id'] );
            $item_type = sanitize_text_field( $item['type'] ); // 'test' or 'package'
            $post      = get_post( $item_id );

            if ( $post ) {
                $price          = get_post_meta( $item_id, '_ptbs_price', true );
                $subtotal      += floatval( $price );
                $booking_items[] = array(
                    'type'  => $item_type,
                    'id'    => $item_id,
                    'name'  => $post->post_title,
                    'price' => floatval( $price ),
                );
            }
        }

        $total_amount = $subtotal * $patient_count;

        if ( $total_amount <= 0 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid booking item total.', 'pathology-booking-system' ) ) );
        }

        $booking_number = 'PTBS-' . strtoupper( wp_generate_password( 8, false ) );
        $user_id        = get_current_user_id();

        $booking_data = array(
            'booking_number' => $booking_number,
            'user_id'        => $user_id,
            'city_id'        => $city_id,
            'city_name'      => $city_name,
            'booking_type'   => $booking_type,
            'patient_name'   => $patient_name,
            'patient_age'    => $patient_age,
            'patient_gender' => $patient_gender,
            'patient_phone'  => $patient_phone,
            'patient_email'  => $patient_email,
            'patients_json'  => ! empty( $patients_raw ) ? wp_json_encode( $patients_raw ) : '',
            'address_line1'  => $address_line1,
            'pincode'        => $pincode,
            'booking_date'   => $booking_date,
            'booking_slot'   => $booking_slot,
            'total_amount'   => $total_amount,
            'gateway'        => $gateway,
            'payment_status' => 'pending',
            'transaction_id' => '',
        );

        $booking_id = PTBS_DB::insert_booking( $booking_data );

        if ( ! $booking_id ) {
            wp_send_json_error( array( 'message' => __( 'Database insertion failed.', 'pathology-booking-system' ) ) );
        }

        // Save latest phone, address, and pincode to user meta for automatic prefilling
        if ( $user_id > 0 ) {
            if ( ! empty( $patient_phone ) ) {
                update_user_meta( $user_id, 'ptbs_patient_phone', $patient_phone );
            }
            if ( ! empty( $address_line1 ) ) {
                update_user_meta( $user_id, 'ptbs_patient_address', $address_line1 );
            }
            if ( ! empty( $pincode ) ) {
                update_user_meta( $user_id, 'ptbs_patient_pincode', $pincode );
            }
        }

        foreach ( $booking_items as $b_item ) {
            PTBS_DB::insert_booking_item( $booking_id, $b_item['type'], $b_item['id'], $b_item['name'], $b_item['price'] );
        }

        // Handle Mock Payment vs Live Payment Gateways
        if ( 'mock' === $gateway ) {
            global $wpdb;
            $mock_txn_id = 'MOCK_TXN_' . rand( 100000, 999999 );

            $wpdb->update(
                $wpdb->prefix . 'ptbs_bookings',
                array(
                    'payment_status'     => 'paid',
                    'transaction_id'     => $mock_txn_id,
                    'fulfillment_status' => 'confirmed',
                ),
                array( 'id' => $booking_id )
            );

            wp_send_json_success( array(
                'is_mock'        => true,
                'booking_id'     => $booking_id,
                'booking_number' => $booking_number,
                'message'        => sprintf( __( 'Test Booking #%s confirmed via Mock Payment!', 'pathology-booking-system' ), $booking_number ),
            ) );
        }

        // Initialize Gateway Checkout Payload for live gateways
        if ( 'phonepe' === $gateway ) {
            $gateway_data = PTBS_PhonePe::create_order( $booking_id, $booking_number, $total_amount, $patient_email, $patient_phone );
        } else {
            // Default to Razorpay
            $gateway_data = PTBS_Razorpay::create_order( $booking_id, $booking_number, $total_amount, $patient_email, $patient_phone );
        }

        wp_send_json_success( array(
            'is_mock'        => false,
            'booking_id'     => $booking_id,
            'booking_number' => $booking_number,
            'total_amount'   => $total_amount,
            'gateway'        => $gateway,
            'gateway_data'   => $gateway_data,
        ) );
    }

    /**
     * AJAX handler to validate and apply promo coupon codes
     */
    public function ajax_apply_coupon() {
        check_ajax_referer( 'ptbs_public_nonce', 'security' );

        $code       = strtoupper( sanitize_text_field( wp_unslash( $_POST['coupon_code'] ?? '' ) ) );
        $subtotal   = floatval( $_POST['cart_subtotal'] ?? 0 );

        if ( empty( $code ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a coupon code.', 'pathology-booking-system' ) ) );
        }

        global $wpdb;
        $table_coupons = $wpdb->prefix . 'ptbs_coupons';
        $coupon        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_coupons} WHERE code = %s", $code ) );

        if ( ! $coupon ) {
            wp_send_json_error( array( 'message' => __( 'Invalid coupon code.', 'pathology-booking-system' ) ) );
        }

        // Status check
        if ( 'Inactive' === $coupon->status ) {
            wp_send_json_error( array( 'message' => __( 'This coupon code is currently disabled.', 'pathology-booking-system' ) ) );
        }

        // Expiry date check
        if ( ! empty( $coupon->expiry_date ) && strtotime( $coupon->expiry_date ) < strtotime( current_time( 'Y-m-d' ) ) ) {
            wp_send_json_error( array( 'message' => __( 'This coupon code has expired.', 'pathology-booking-system' ) ) );
        }

        // Usage limit check
        if ( $coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit ) {
            wp_send_json_error( array( 'message' => __( 'Coupon usage limit reached.', 'pathology-booking-system' ) ) );
        }

        // Minimum cart subtotal check
        if ( $coupon->min_cart_amount > 0 && $subtotal < $coupon->min_cart_amount ) {
            wp_send_json_error( array(
                'message' => sprintf( __( 'Minimum cart order subtotal of ₹%s required for this coupon.', 'pathology-booking-system' ), number_format( $coupon->min_cart_amount, 2 ) )
            ) );
        }

        // Calculate Discount
        $discount = 0.00;
        if ( 'percentage' === $coupon->discount_type ) {
            $discount = ( $subtotal * ( floatval( $coupon->discount_value ) / 100 ) );
            if ( $coupon->max_discount_amount > 0 && $discount > $coupon->max_discount_amount ) {
                $discount = floatval( $coupon->max_discount_amount );
            }
        } else {
            $discount = floatval( $coupon->discount_value );
        }

        if ( $discount > $subtotal ) {
            $discount = $subtotal;
        }

        $new_total = max( 0, $subtotal - $discount );

        wp_send_json_success( array(
            'coupon_code'     => $coupon->code,
            'discount_type'   => $coupon->discount_type,
            'discount_amount' => round( $discount, 2 ),
            'new_total'       => round( $new_total, 2 ),
            'message'         => sprintf( __( 'Coupon "%s" applied! You saved ₹%s.', 'pathology-booking-system' ), $coupon->code, number_format( $discount, 2 ) ),
        ) );
    }
}
