<?php
/**
 * SubCategory Form Partial
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$term       = ( $item_id > 0 ) ? get_term( $item_id, 'ptbs_subcategory' ) : null;
$term_name  = $term && ! is_wp_error( $term ) ? $term->name : '';
$parent_id  = $term && ! is_wp_error( $term ) ? $term->parent : 0;
$image_id   = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_image_id', true ) : '';
$categories = get_terms( array( 'taxonomy' => 'ptbs_category', 'hide_empty' => false ) );
?>
<input type="hidden" name="ptbs_action_type" value="save_subcategory">

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Category Name', 'pathology-booking-system' ); ?></label>
        <select name="parent_category_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
            <option value=""><?php esc_html_e( 'Select', 'pathology-booking-system' ); ?></option>
            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $c ) : ?>
                <option value="<?php echo esc_attr( $c->term_id ); ?>" <?php selected( $parent_id, $c->term_id ); ?>><?php echo esc_html( $c->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Sub Category Name', 'pathology-booking-system' ); ?></label>
        <input type="text" name="subcategory_name" value="<?php echo esc_attr( $term_name ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<?php
$term_subtitle = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_term_subtitle', true ) : '';
$term_color    = $term && ! is_wp_error( $term ) ? get_term_meta( $term->term_id, '_ptbs_term_color', true ) : '#dcfce7';
?>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Circle Background Color', 'pathology-booking-system' ); ?></label>
        <input type="color" name="term_color" value="<?php echo esc_attr( $term_color ?: '#dcfce7' ); ?>" style="width:100%; height:42px; border:1px solid #cbd5e1; border-radius:6px; padding:2px; cursor:pointer;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Tagline / Subtitle', 'pathology-booking-system' ); ?></label>
        <input type="text" name="term_subtitle" value="<?php echo esc_attr( $term_subtitle ); ?>" placeholder="e.g. Creatinine, Urea, eGFR" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Upload Icon Image / Illustration (PNG or SVG)', 'pathology-booking-system' ); ?></label>
    <input type="hidden" name="subcategory_image_id" id="ptbs_cat_img_id" value="<?php echo esc_attr( $image_id ); ?>">
    <button type="button" class="button" id="ptbs_cat_img_btn" style="border-color:#0284c7; color:#0284c7; padding:6px 20px; font-weight:600;"><?php esc_html_e( 'Upload Icon Image', 'pathology-booking-system' ); ?></button>
    <div id="ptbs_cat_img_preview" style="margin-top:10px;">
        <?php if ( $image_id ) echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
    </div>
</div>
