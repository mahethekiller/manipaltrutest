<?php
/**
 * Center Location Form Partial
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post       = ( $item_id > 0 ) ? get_post( $item_id ) : null;
$phone      = $post ? get_post_meta( $post->ID, '_ptbs_phone', true ) : '';
$email      = $post ? get_post_meta( $post->ID, '_ptbs_email', true ) : '';
$address    = $post ? get_post_meta( $post->ID, '_ptbs_address', true ) : '';
$hours      = $post ? get_post_meta( $post->ID, '_ptbs_hours', true ) : '';
$status     = $post ? get_post_meta( $post->ID, '_ptbs_status', true ) : 'Active';
$cities     = get_terms( array( 'taxonomy' => 'ptbs_city', 'hide_empty' => false ) );
$sel_cities = $post ? wp_get_post_terms( $post->ID, 'ptbs_city', array( 'fields' => 'ids' ) ) : array();
if ( ! is_array( $sel_cities ) ) $sel_cities = array();
?>
<input type="hidden" name="ptbs_action_type" value="save_center_location">

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Center Location Name *', 'pathology-booking-system' ); ?></label>
    <input type="text" name="center_name" value="<?php echo esc_attr( $post ? $post->post_title : '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Manipal TRUtest Delhi Main Lab', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Phone Number', 'pathology-booking-system' ); ?></label>
        <input type="text" name="phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="+91 1800-123-4567" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Email Address', 'pathology-booking-system' ); ?></label>
        <input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="info@example.com" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Operating Hours', 'pathology-booking-system' ); ?></label>
        <input type="text" name="hours" value="<?php echo esc_attr( $hours ); ?>" placeholder="Mon - Sat: 07:00 AM - 08:00 PM" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'City Location', 'pathology-booking-system' ); ?></label>
        <select name="city_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) : foreach ( $cities as $c ) : ?>
                <option value="<?php echo esc_attr( $c->term_id ); ?>" <?php selected( in_array( $c->term_id, $sel_cities ) ); ?>><?php echo esc_html( $c->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Full Address', 'pathology-booking-system' ); ?></label>
    <textarea name="address" rows="3" placeholder="Enter complete center address..." style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $address ); ?></textarea>
</div>

<div style="margin-bottom:20px;">
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
