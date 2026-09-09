<?php
/**
 * Condition Form Partial
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$term      = ( $item_id > 0 ) ? get_term( $item_id, 'ptbs_condition' ) : null;
$term_name = $term && ! is_wp_error( $term ) ? $term->name : '';
$image_id  = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_image_id', true ) : '';
?>
<input type="hidden" name="ptbs_action_type" value="save_condition">

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Conditions Name', 'pathology-booking-system' ); ?></label>
    <input type="text" name="condition_name" value="<?php echo esc_attr( $term_name ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
</div>

<?php
$term_subtitle = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_term_subtitle', true ) : '';
$term_color    = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_term_color', true ) : '#f3e8ff';
?>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Circle Background Color', 'pathology-booking-system' ); ?></label>
        <input type="color" name="term_color" value="<?php echo esc_attr( $term_color ?: '#f3e8ff' ); ?>" style="width:100%; height:42px; border:1px solid #cbd5e1; border-radius:6px; padding:2px; cursor:pointer;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Tagline / Subtitle', 'pathology-booking-system' ); ?></label>
        <input type="text" name="term_subtitle" value="<?php echo esc_attr( $term_subtitle ); ?>" placeholder="e.g. ECG, Troponin, CK-MB" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Upload Icon Image / Illustration (PNG or SVG)', 'pathology-booking-system' ); ?></label>
    <input type="hidden" name="condition_image_id" id="ptbs_cat_img_id" value="<?php echo esc_attr( $image_id ); ?>">
    <button type="button" class="button" id="ptbs_cat_img_btn" style="border-color:#0284c7; color:#0284c7; padding:6px 20px; font-weight:600;"><?php esc_html_e( 'Upload Icon Image', 'pathology-booking-system' ); ?></button>
    <div id="ptbs_cat_img_preview" style="margin-top:10px;">
        <?php if ( $image_id ) echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
    </div>
</div>
