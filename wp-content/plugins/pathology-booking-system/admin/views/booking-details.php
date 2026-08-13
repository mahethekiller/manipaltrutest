<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$patients = ! empty( $booking->patients_json ) ? json_decode( $booking->patients_json, true ) : array();
?>
<div class="wrap ptbs-admin-wrap">
    <h1><?php printf( esc_html__( 'Booking Details: %s', 'pathology-booking-system' ), esc_html( $booking->booking_number ) ); ?></h1>
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=ptbs-dashboard' ) ); ?>">&larr; <?php esc_html_e( 'Back to All Bookings', 'pathology-booking-system' ); ?></a>

    <div class="ptbs-admin-grid" style="display:grid; grid-template-columns: 1.2fr 1fr; gap:20px; margin-top:16px;">
        <div class="ptbs-card" style="background:#fff; padding:20px; border-radius:10px; border:1px solid #cbd5e1;">
            <h2>👥 <?php esc_html_e( 'Patient Roster & Contact Info', 'pathology-booking-system' ); ?></h2>
            
            <?php if ( ! empty( $patients ) && is_array( $patients ) ) : ?>
                <p style="color:#64748b; font-size:13px;"><?php printf( esc_html__( 'Total Patients in Appointment: %d', 'pathology-booking-system' ), count( $patients ) ); ?></p>
                <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:16px;">
                    <?php foreach ( $patients as $idx => $p ) : ?>
                        <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:12px;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <strong style="color:#1d4ed8; font-size:14px;">Patient #<?php echo ($idx + 1); ?>: <?php echo esc_html( $p['name'] ); ?></strong>
                                <span style="background:#dbeafe; color:#1e40af; font-size:11px; font-weight:bold; padding:2px 8px; border-radius:12px;">
                                    <?php echo esc_html( ! empty( $p['relation'] ) ? $p['relation'] : 'Self' ); ?>
                                </span>
                            </div>
                            <div style="font-size:13px; color:#475569; margin-top:6px;">
                                Age: <strong><?php echo esc_html( isset( $p['age'] ) ? $p['age'] : 30 ); ?> Yrs</strong> | Gender: <strong><?php echo esc_html( ucfirst( isset( $p['gender'] ) ? $p['gender'] : 'male' ) ); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><strong><?php esc_html_e( 'Name:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->patient_name ); ?></p>
                <p><strong><?php esc_html_e( 'Age / Gender:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->patient_age ); ?> Yrs / <?php echo esc_html( ucfirst( $booking->patient_gender ) ); ?></p>
            <?php endif; ?>

            <hr style="border:0; border-top:1px solid #e2e8f0; margin:16px 0;">
            <p><strong><?php esc_html_e( 'Primary Contact Phone:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->patient_phone ); ?></p>
            <p><strong><?php esc_html_e( 'Primary Contact Email:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->patient_email ); ?></p>
            <p><strong><?php esc_html_e( 'Appointment City:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->city_name ); ?></p>
            <?php if ( 'home_collection' === $booking->booking_type ) : ?>
                <p><strong><?php esc_html_e( 'Collection Address:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->address_line1 ); ?>, PIN: <?php echo esc_html( $booking->pincode ); ?></p>
            <?php endif; ?>
        </div>

        <div class="ptbs-card" style="background:#fff; padding:20px; border-radius:10px; border:1px solid #cbd5e1;">
            <h2><?php esc_html_e( 'Booking & Payment Status', 'pathology-booking-system' ); ?></h2>
            <p><strong><?php esc_html_e( 'Appointment Date & Slot:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( $booking->booking_date ); ?> (<?php echo esc_html( $booking->booking_slot ); ?>)</p>
            <p><strong><?php esc_html_e( 'Payment Gateway:', 'pathology-booking-system' ); ?></strong> <?php echo esc_html( strtoupper( $booking->gateway ) ); ?></p>
            <p><strong><?php esc_html_e( 'Payment Status:', 'pathology-booking-system' ); ?></strong> <span class="ptbs-pay-<?php echo esc_attr( strtolower( $booking->payment_status ) ); ?>"><?php echo esc_html( ucfirst( $booking->payment_status ) ); ?></span></p>
            <p><strong><?php esc_html_e( 'Total Amount Paid:', 'pathology-booking-system' ); ?></strong> ₹<?php echo esc_html( number_format( $booking->total_amount, 2 ) ); ?></p>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:16px;">
                <?php wp_nonce_field( 'ptbs_update_booking_action', 'ptbs_booking_nonce' ); ?>
                <input type="hidden" name="action" value="ptbs_update_booking_status">
                <input type="hidden" name="booking_id" value="<?php echo esc_attr( $booking->id ); ?>">

                <p>
                    <label for="fulfillment_status"><strong><?php esc_html_e( 'Fulfillment Status:', 'pathology-booking-system' ); ?></strong></label><br>
                    <select name="fulfillment_status" id="fulfillment_status" style="width:100%;">
                        <option value="pending" <?php selected( $booking->fulfillment_status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'pathology-booking-system' ); ?></option>
                        <option value="confirmed" <?php selected( $booking->fulfillment_status, 'confirmed' ); ?>><?php esc_html_e( 'Confirmed', 'pathology-booking-system' ); ?></option>
                        <option value="sample_collected" <?php selected( $booking->fulfillment_status, 'sample_collected' ); ?>><?php esc_html_e( 'Sample Collected', 'pathology-booking-system' ); ?></option>
                        <option value="completed" <?php selected( $booking->fulfillment_status, 'completed' ); ?>><?php esc_html_e( 'Completed (Report Ready)', 'pathology-booking-system' ); ?></option>
                        <option value="cancelled" <?php selected( $booking->fulfillment_status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'pathology-booking-system' ); ?></option>
                    </select>
                </p>

                <p>
                    <label for="report_file_url"><strong><?php esc_html_e( 'Attach PDF Report File URL:', 'pathology-booking-system' ); ?></strong></label><br>
                    <input type="url" name="report_file_url" id="report_file_url" value="<?php echo esc_url( $booking->report_file_url ); ?>" class="large-text" placeholder="https://example.com/wp-content/uploads/reports/report_123.pdf">
                </p>

                <button type="submit" class="button button-primary" style="width:100%; text-align:center; margin-top:8px;"><?php esc_html_e( 'Update Status & Attach Report', 'pathology-booking-system' ); ?></button>
            </form>
        </div>
    </div>

    <div class="ptbs-card" style="margin-top: 20px; background:#fff; padding:20px; border-radius:10px; border:1px solid #cbd5e1;">
        <h2><?php esc_html_e( 'Booked Tests / Packages', 'pathology-booking-system' ); ?></h2>
        <ul>
            <?php foreach ( $items as $item ) : ?>
                <li>
                    <strong><?php echo esc_html( $item->item_name ); ?></strong> (<?php echo esc_html( ucfirst( $item->item_type ) ); ?>) - ₹<?php echo esc_html( number_format( $item->price, 2 ) ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
