<?php
/**
 * Test Form Partial
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post          = ( $item_id > 0 ) ? get_post( $item_id ) : null;
$categories    = get_terms( array( 'taxonomy' => 'ptbs_category', 'hide_empty' => false ) );
$subcategories = get_terms( array( 'taxonomy' => 'ptbs_subcategory', 'hide_empty' => false ) );
$conditions    = get_terms( array( 'taxonomy' => 'ptbs_condition', 'hide_empty' => false ) );
$cities        = get_terms( array( 'taxonomy' => 'ptbs_city', 'hide_empty' => false ) );
$centers       = get_posts( array( 'post_type' => 'ptbs_center_location', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
?>
<input type="hidden" name="ptbs_action_type" value="save_test">

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Test Name', 'pathology-booking-system' ); ?></label>
        <input type="text" name="test_name" value="<?php echo esc_attr( $post ? $post->post_title : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Price', 'pathology-booking-system' ); ?></label>
        <input type="number" step="0.01" name="price" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_price', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Main Test Name', 'pathology-booking-system' ); ?></label>
    <input type="text" name="main_test_name" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_main_test_name', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Category', 'pathology-booking-system' ); ?></label>
        <select name="category_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
            <option value=""><?php esc_html_e( 'Select', 'pathology-booking-system' ); ?></option>
            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $c ) : ?>
                <option value="<?php echo esc_attr( $c->term_id ); ?>"><?php echo esc_html( $c->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Sub Category', 'pathology-booking-system' ); ?></label>
        <select name="subcategory_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
            <option value=""><?php esc_html_e( 'Select', 'pathology-booking-system' ); ?></option>
            <?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : foreach ( $subcategories as $sc ) : ?>
                <option value="<?php echo esc_attr( $sc->term_id ); ?>"><?php echo esc_html( $sc->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Conditions', 'pathology-booking-system' ); ?></label>
        <select name="condition_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $conditions ) && ! is_wp_error( $conditions ) ) : foreach ( $conditions as $cond ) : ?>
                <option value="<?php echo esc_attr( $cond->term_id ); ?>"><?php echo esc_html( $cond->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Center Location', 'pathology-booking-system' ); ?></label>
        <select name="center_location_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $centers ) ) : foreach ( $centers as $cp ) : ?>
                <option value="<?php echo esc_attr( $cp->ID ); ?>"><?php echo esc_html( $cp->post_title ); ?></option>
            <?php endforeach; endif; ?>
            <?php if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) : foreach ( $cities as $ct ) : ?>
                <option value="<?php echo esc_attr( $ct->term_id ); ?>"><?php echo esc_html( $ct->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Test Code', 'pathology-booking-system' ); ?></label>
        <input type="text" name="test_code" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_code', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Cut Off time', 'pathology-booking-system' ); ?></label>
        <input type="text" name="cutoff_time" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_cutoff_time', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Method', 'pathology-booking-system' ); ?></label>
        <input type="text" name="method" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_method', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Specimen', 'pathology-booking-system' ); ?></label>
        <input type="text" name="specimen" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_sample_type', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Report Delivery', 'pathology-booking-system' ); ?></label>
        <input type="text" name="report_delivery" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_tat_hours', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Status', 'pathology-booking-system' ); ?></label>
        <?php $status = $post ? get_post_meta( $post->ID, '_ptbs_status', true ) : 'Active'; ?>
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
