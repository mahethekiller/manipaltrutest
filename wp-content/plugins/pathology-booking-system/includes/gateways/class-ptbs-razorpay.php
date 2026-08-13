<?php
/**
 * Razorpay Payment Gateway Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_Razorpay {

    /**
     * Create Razorpay Order & Return Options Payload
     */
    public static function create_order( $booking_id, $booking_number, $amount, $email, $phone ) {
        $settings = get_option( 'ptbs_settings', array() );
        $key_id   = isset( $settings['razorpay_key_id'] ) ? $settings['razorpay_key_id'] : 'rzp_test_sampleKey123';
        $secret   = isset( $settings['razorpay_key_secret'] ) ? $settings['razorpay_key_secret'] : 'sampleSecret123';

        $amount_in_paise = round( floatval( $amount ) * 100 );

        return array(
            'key'          => $key_id,
            'amount'       => $amount_in_paise,
            'currency'     => 'INR',
            'name'         => get_bloginfo( 'name' ),
            'description'  => 'Pathology Test Booking #' . $booking_number,
            'order_id'     => 'order_' . strtolower( $booking_number ),
            'prefill'      => array(
                'email'   => $email,
                'contact' => $phone,
            ),
            'notes'        => array(
                'booking_id'     => $booking_id,
                'booking_number' => $booking_number,
            ),
        );
    }

    /**
     * Verify Razorpay Payment Signature
     */
    public static function verify_signature( $razorpay_order_id, $razorpay_payment_id, $razorpay_signature ) {
        $settings = get_option( 'ptbs_settings', array() );
        $secret   = isset( $settings['razorpay_key_secret'] ) ? $settings['razorpay_key_secret'] : 'sampleSecret123';

        $generated_signature = hash_hmac( 'sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $secret );

        return hash_equals( $generated_signature, $razorpay_signature );
    }
}
