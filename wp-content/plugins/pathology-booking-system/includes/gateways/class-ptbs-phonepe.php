<?php
/**
 * PhonePe Payment Gateway Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_PhonePe {

    /**
     * Create PhonePe Order Request Payload
     */
    public static function create_order( $booking_id, $booking_number, $amount, $email, $phone ) {
        $settings    = get_option( 'ptbs_settings', array() );
        $merchant_id = isset( $settings['phonepe_merchant_id'] ) ? $settings['phonepe_merchant_id'] : 'PGTESTPAYUAT';
        $salt_key    = isset( $settings['phonepe_salt_key'] ) ? $settings['phonepe_salt_key'] : '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
        $salt_index  = isset( $settings['phonepe_salt_index'] ) ? $settings['phonepe_salt_index'] : '1';

        $amount_in_paise = round( floatval( $amount ) * 100 );

        $payload = array(
            'merchantId'            => $merchant_id,
            'merchantTransactionId' => $booking_number,
            'merchantUserId'        => 'MUID_' . $booking_id,
            'amount'                => $amount_in_paise,
            'redirectUrl'           => add_query_arg( array( 'ptbs_phonepe_callback' => '1', 'booking_id' => $booking_id ), get_permalink() ),
            'redirectMode'          => 'POST',
            'callbackUrl'           => add_query_arg( array( 'ptbs_phonepe_webhook' => '1' ), get_site_url() ),
            'mobileNumber'          => $phone,
            'paymentInstrument'     => array(
                'type' => 'PAY_PAGE',
            ),
        );

        $encode = base64_encode( wp_json_encode( $payload ) );
        $string = $encode . '/pg/v1/pay' . $salt_key;
        $sha256 = hash( 'sha256', $string );
        $x_verify = $sha256 . '###' . $salt_index;

        return array(
            'request'  => $encode,
            'x_verify' => $x_verify,
            'url'      => 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay',
        );
    }
}
