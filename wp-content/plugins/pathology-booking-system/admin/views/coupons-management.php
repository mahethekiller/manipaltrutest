<?php
/**
 * Coupons Management View Controller
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wpdb;
$table_coupons = $wpdb->prefix . 'ptbs_coupons';

$action  = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';
$item_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
$message = '';

// Toggle Status Action
if ( 'toggle_status' === $action && $item_id > 0 ) {
    $current_status = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM {$table_coupons} WHERE id = %d", $item_id ) );
    $new_status     = ( 'Active' === $current_status ) ? 'Inactive' : 'Active';
    $wpdb->update( $table_coupons, array( 'status' => $new_status ), array( 'id' => $item_id ) );
    $message = sprintf( __( 'Coupon status changed to %s.', 'pathology-booking-system' ), $new_status );
    $action  = 'list';
}

// Form Submission Processing
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['ptbs_coupon_nonce'] ) && wp_verify_nonce( $_POST['ptbs_coupon_nonce'], 'ptbs_coupon_action' ) ) {
    $code         = strtoupper( sanitize_text_field( wp_unslash( $_POST['code'] ?? '' ) ) );
    $type         = sanitize_text_field( wp_unslash( $_POST['discount_type'] ?? 'percentage' ) );
    $val          = floatval( $_POST['discount_value'] ?? 0 );
    $min_cart     = floatval( $_POST['min_cart_amount'] ?? 0 );
    $max_discount = floatval( $_POST['max_discount_amount'] ?? 0 );
    $usage_limit  = absint( $_POST['usage_limit'] ?? 0 );
    $expiry_date  = ! empty( $_POST['expiry_date'] ) ? sanitize_text_field( wp_unslash( $_POST['expiry_date'] ) ) : null;
    $status       = sanitize_text_field( wp_unslash( $_POST['status'] ?? 'Active' ) );

    if ( ! empty( $code ) && $val > 0 ) {
        $data = array(
            'code'                => $code,
            'discount_type'       => $type,
            'discount_value'      => $val,
            'min_cart_amount'     => $min_cart,
            'max_discount_amount' => $max_discount,
            'usage_limit'         => $usage_limit,
            'expiry_date'         => $expiry_date,
            'status'              => $status,
        );

        if ( $item_id > 0 ) {
            $wpdb->update( $table_coupons, $data, array( 'id' => $item_id ) );
            $message = __( 'Coupon updated successfully.', 'pathology-booking-system' );
        } else {
            $wpdb->insert( $table_coupons, $data );
            $message = __( 'Coupon created successfully.', 'pathology-booking-system' );
        }
        $action = 'list';
    }
}
?>

<div class="ptbs-coupons-wrap" style="background:#fff; padding:24px; border-radius:12px; margin-top:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05); font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;">

    <!-- Title Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 style="font-size:26px; font-weight:700; color:#1e293b; margin:0;">
            <?php esc_html_e( 'Coupons & Discounts Management', 'pathology-booking-system' ); ?>
        </h1>
        <?php if ( 'list' === $action ) : ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=ptbs-coupons&action=add' ) ); ?>" class="button button-primary" style="background:#00a896; border-color:#00a896; font-size:14px; padding:6px 20px; border-radius:6px; font-weight:600;">
                + <?php esc_html_e( 'Add New Coupon', 'pathology-booking-system' ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( ! empty( $message ) ) : ?>
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-left:5px solid #16a34a; padding:12px 18px; border-radius:8px; color:#166534; font-weight:600; font-size:14px; margin-bottom:20px;">
            ✓ <?php echo esc_html( $message ); ?>
        </div>
    <?php endif; ?>

    <?php if ( 'add' === $action || 'edit' === $action ) : ?>
        
        <div style="margin-bottom:20px;">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=ptbs-coupons' ) ); ?>" style="text-decoration:none; color:#1e293b; font-size:22px; font-weight:700;">
                ‹ <?php echo ( 'edit' === $action ) ? esc_html__( 'Edit Coupon Details', 'pathology-booking-system' ) : esc_html__( 'Add New Coupon Details', 'pathology-booking-system' ); ?>
            </a>
        </div>

        <?php include PTBS_DIR_PATH . 'admin/views/coupon-form.php'; ?>

    <?php else : ?>

        <table class="wp-list-table widefat fixed striped table-view-list" style="border-radius:8px; overflow:hidden; border:1px solid #e2e8f0;">
            <thead>
                <tr style="background:#00a896; color:#fff;">
                    <th style="color:#fff; font-weight:700; width:60px; text-align:center; padding:12px;">S. No</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Coupon Code</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Discount</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Min Order</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Used / Limit</th>
                    <th style="color:#fff; font-weight:700; padding:12px;">Expiry Date</th>
                    <th style="color:#fff; font-weight:700; padding:12px; width:120px; text-align:center;">Status</th>
                    <th style="color:#fff; font-weight:700; padding:12px; width:140px; text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $coupons = $wpdb->get_results( "SELECT * FROM {$table_coupons} ORDER BY id DESC" );
                if ( ! empty( $coupons ) ) {
                    $i = 1;
                    foreach ( $coupons as $c ) {
                        $disc_str     = ( 'percentage' === $c->discount_type ) ? esc_html( $c->discount_value ) . '%' : '₹' . esc_html( $c->discount_value );
                        $limit_str    = esc_html( $c->used_count ) . ' / ' . ( $c->usage_limit > 0 ? esc_html( $c->usage_limit ) : '∞' );
                        $status       = $c->status ?: 'Active';
                        $status_color = ( 'Inactive' === $status ) ? '#dc2626' : '#16a34a';
                        $edit_url     = admin_url( 'admin.php?page=ptbs-coupons&action=edit&id=' . $c->id );
                        $toggle_url   = admin_url( 'admin.php?page=ptbs-coupons&action=toggle_status&id=' . $c->id );

                        echo '<tr>';
                        echo '<td style="text-align:center;">' . esc_html( $i++ ) . '</td>';
                        echo '<td style="font-weight:700; color:#0b4f8c;">' . esc_html( $c->code ) . '</td>';
                        echo '<td style="font-weight:600; color:#1e293b;">' . $disc_str . '</td>';
                        echo '<td>₹' . esc_html( number_format( $c->min_cart_amount, 2 ) ) . '</td>';
                        echo '<td>' . $limit_str . '</td>';
                        echo '<td>' . ( $c->expiry_date ? esc_html( $c->expiry_date ) : 'No Expiry' ) . '</td>';
                        echo '<td style="text-align:center;"><span style="color:' . esc_attr( $status_color ) . '; font-weight:700;">' . esc_html( $status ) . '</span></td>';
                        echo '<td style="text-align:center;">';
                        echo '<a href="' . esc_url( $edit_url ) . '" style="text-decoration:none; font-weight:600; color:#0284c7; margin-right:10px;">Edit</a>';
                        echo '<a href="' . esc_url( $toggle_url ) . '" style="text-decoration:none; font-weight:600; color:#64748b;">Toggle</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" style="text-align:center; padding:20px;">' . esc_html__( 'No coupons found.', 'pathology-booking-system' ) . '</td></tr>';
                }
                ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>
