<?php
/**
 * Coupon Form Partial
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wpdb;
$table_coupons = $wpdb->prefix . 'ptbs_coupons';
$coupon        = ( $item_id > 0 ) ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_coupons} WHERE id = %d", $item_id ) ) : null;

$code           = $coupon ? $coupon->code : '';
$discount_type  = $coupon ? $coupon->discount_type : 'percentage';
$discount_val   = $coupon ? $coupon->discount_value : '';
$min_cart       = $coupon ? $coupon->min_cart_amount : '';
$max_discount   = $coupon ? $coupon->max_discount_amount : '';
$usage_limit    = $coupon ? $coupon->usage_limit : '';
$expiry_date    = $coupon ? $coupon->expiry_date : '';
$status         = $coupon ? $coupon->status : 'Active';
?>

<form method="post" action="" style="max-width:900px;">
    <?php wp_nonce_field( 'ptbs_coupon_action', 'ptbs_coupon_nonce' ); ?>
    <input type="hidden" name="ptbs_action_type" value="save_coupon">

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Coupon Code', 'pathology-booking-system' ); ?></label>
            <input type="text" name="code" value="<?php echo esc_attr( $code ); ?>" placeholder="<?php esc_attr_e( 'e.g. HEALTH20', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; text-transform:uppercase;">
        </div>
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Discount Type', 'pathology-booking-system' ); ?></label>
            <select name="discount_type" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
                <option value="percentage" <?php selected( $discount_type, 'percentage' ); ?>><?php esc_html_e( 'Percentage (%)', 'pathology-booking-system' ); ?></option>
                <option value="fixed" <?php selected( $discount_type, 'fixed' ); ?>><?php esc_html_e( 'Fixed Amount (₹)', 'pathology-booking-system' ); ?></option>
            </select>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Discount Value', 'pathology-booking-system' ); ?></label>
            <input type="number" step="0.01" name="discount_value" value="<?php echo esc_attr( $discount_val ); ?>" placeholder="<?php esc_attr_e( 'e.g. 20 or 500', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
        </div>
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Minimum Cart Amount (₹)', 'pathology-booking-system' ); ?></label>
            <input type="number" step="0.01" name="min_cart_amount" value="<?php echo esc_attr( $min_cart ); ?>" placeholder="<?php esc_attr_e( '0.00', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Max Discount Cap (₹) (For Percentage)', 'pathology-booking-system' ); ?></label>
            <input type="number" step="0.01" name="max_discount_amount" value="<?php echo esc_attr( $max_discount ); ?>" placeholder="<?php esc_attr_e( '0.00 = Unlimited', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
        </div>
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Usage Limit Per Coupon', 'pathology-booking-system' ); ?></label>
            <input type="number" name="usage_limit" value="<?php echo esc_attr( $usage_limit ); ?>" placeholder="<?php esc_attr_e( '0 = Unlimited', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Expiry Date', 'pathology-booking-system' ); ?></label>
            <input type="date" name="expiry_date" value="<?php echo esc_attr( $expiry_date ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
        </div>
        <div>
            <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Status', 'pathology-booking-system' ); ?></label>
            <div style="display:flex; gap:20px; margin-top:8px;">
                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-weight:600; color:#334155;">
                    <input type="radio" name="status" value="Active" <?php checked( $status !== 'Inactive' ); ?>> <?php esc_html_e( 'Active', 'pathology-booking-system' ); ?>
                </label>
                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-weight:600; color:#334155;">
                    <input type="radio" name="status" value="Inactive" <?php checked( $status, 'Inactive' ); ?>> <?php esc_html_e( 'Inactive', 'pathology-booking-system' ); ?>
                </label>
            </div>
        </div>
    </div>

    <!-- FORM BUTTONS -->
    <div style="margin-top:30px; display:flex; gap:16px; border-top:1px solid #e2e8f0; padding-top:20px;">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=ptbs-coupons' ) ); ?>" class="button" style="background:#e2e8f0; border:none; color:#475569; font-weight:600; padding:8px 30px; border-radius:6px;">
            <?php esc_html_e( 'CANCEL', 'pathology-booking-system' ); ?>
        </a>
        <button type="submit" class="button button-primary" style="background:#0b4f8c; border-color:#0b4f8c; font-weight:600; padding:8px 30px; border-radius:6px;">
            <?php esc_html_e( 'SAVE & PUBLISH', 'pathology-booking-system' ); ?>
        </button>
    </div>
</form>
