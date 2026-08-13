<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap ptbs-admin-wrap">
    <h1><?php esc_html_e( 'Pathology Test & Package Bookings', 'pathology-booking-system' ); ?></h1>
    <hr class="wp-header-end">

    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Booking #', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Patient Info', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'City', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Type', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Slot Date & Time', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Total', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Gateway', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Status', 'pathology-booking-system' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'pathology-booking-system' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $bookings ) ) : ?>
                <?php foreach ( $bookings as $b ) : ?>
                    <?php
                    $p_list = ! empty( $b->patients_json ) ? json_decode( $b->patients_json, true ) : array();
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html( $b->booking_number ); ?></strong></td>
                        <td>
                            <?php if ( ! empty( $p_list ) && count( $p_list ) > 1 ) : ?>
                                <span style="background:#eff6ff; color:#1d4ed8; font-size:11px; padding:2px 6px; border-radius:4px; font-weight:bold;">👥 Group (<?php echo count( $p_list ); ?> Patients)</span><br>
                                <strong><?php echo esc_html( $b->patient_name ); ?></strong><br>
                            <?php else : ?>
                                <strong><?php echo esc_html( $b->patient_name ); ?></strong><br>
                            <?php endif; ?>
                            <small style="color:#64748b;"><?php echo esc_html( $b->patient_phone ); ?> | <?php echo esc_html( $b->patient_email ); ?></small>
                        </td>
                        <td><?php echo esc_html( $b->city_name ); ?></td>
                        <td>
                            <span class="ptbs-badge ptbs-badge-type">
                                <?php echo 'home_collection' === $b->booking_type ? esc_html__( 'Home Collection', 'pathology-booking-system' ) : esc_html__( 'Lab Visit', 'pathology-booking-system' ); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html( $b->booking_date ); ?><br><small><?php echo esc_html( $b->booking_slot ); ?></small></td>
                        <td><strong>₹<?php echo esc_html( number_format( $b->total_amount, 2 ) ); ?></strong></td>
                        <td><?php echo esc_html( strtoupper( $b->gateway ) ); ?></td>
                        <td>
                            <span class="ptbs-status-badge ptbs-status-<?php echo esc_attr( strtolower( $b->fulfillment_status ) ); ?>">
                                <?php echo esc_html( ucfirst( $b->fulfillment_status ) ); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'ptbs-dashboard', 'action' => 'view', 'id' => $b->id ), admin_url( 'admin.php' ) ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'View Details', 'pathology-booking-system' ); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9"><?php esc_html_e( 'No bookings found.', 'pathology-booking-system' ); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
