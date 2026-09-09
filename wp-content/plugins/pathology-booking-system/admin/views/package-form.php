<?php
/**
 * Package Form Partial
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
$all_tests     = get_posts( array( 'post_type' => 'ptbs_test', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$linked_tests  = $post ? get_post_meta( $post->ID, '_ptbs_linked_test_ids', true ) : array();
if ( ! is_array( $linked_tests ) ) $linked_tests = array();
?>
<input type="hidden" name="ptbs_action_type" value="save_package">

<?php
$selected_cats    = $post ? wp_get_post_terms( $post->ID, 'ptbs_category', array( 'fields' => 'ids' ) ) : array();
$selected_subcats = $post ? wp_get_post_terms( $post->ID, 'ptbs_subcategory', array( 'fields' => 'ids' ) ) : array();
if ( ! is_array( $selected_cats ) ) $selected_cats = array();
if ( ! is_array( $selected_subcats ) ) $selected_subcats = array();
?>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Package Name', 'pathology-booking-system' ); ?></label>
        <input type="text" name="package_name" value="<?php echo esc_attr( $post ? $post->post_title : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Categories (Multi-Select)', 'pathology-booking-system' ); ?></label>
        <select name="category_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $c ) : ?>
                <option value="<?php echo esc_attr( $c->term_id ); ?>" <?php selected( in_array( $c->term_id, $selected_cats ) ); ?>><?php echo esc_html( $c->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Tests Involved', 'pathology-booking-system' ); ?></label>
        <select name="linked_test_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $all_tests ) ) : foreach ( $all_tests as $t ) : ?>
                <option value="<?php echo esc_attr( $t->ID ); ?>" <?php selected( in_array( $t->ID, $linked_tests ) ); ?>>
                    <?php echo esc_html( $t->post_title ); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Sub Categories (Multi-Select)', 'pathology-booking-system' ); ?></label>
        <select name="subcategory_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : foreach ( $subcategories as $sc ) : ?>
                <option value="<?php echo esc_attr( $sc->term_id ); ?>" <?php selected( in_array( $sc->term_id, $selected_subcats ) ); ?>><?php echo esc_html( $sc->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
</div>

<?php
$selected_conds   = $post ? wp_get_post_terms( $post->ID, 'ptbs_condition', array( 'fields' => 'ids' ) ) : array();
$selected_centers = $post ? get_post_meta( $post->ID, '_ptbs_center_location_ids', true ) : array();
if ( ! is_array( $selected_conds ) ) $selected_conds = array();
if ( ! is_array( $selected_centers ) ) $selected_centers = array();
?>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Select Conditions', 'pathology-booking-system' ); ?></label>
        <select name="condition_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $conditions ) && ! is_wp_error( $conditions ) ) : foreach ( $conditions as $cond ) : ?>
                <option value="<?php echo esc_attr( $cond->term_id ); ?>" <?php selected( in_array( $cond->term_id, $selected_conds ) ); ?>><?php echo esc_html( $cond->name ); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Center Location', 'pathology-booking-system' ); ?></label>
        <select name="center_location_ids[]" class="ptbs-select2-multi" multiple="multiple" style="width:100%;">
            <?php if ( ! empty( $centers ) ) : foreach ( $centers as $cp ) : ?>
                <option value="<?php echo esc_attr( $cp->ID ); ?>" <?php selected( in_array( $cp->ID, $selected_centers ) ); ?>><?php echo esc_html( $cp->post_title ); ?></option>
            <?php endforeach; endif; ?>
            <?php if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) : foreach ( $cities as $ct ) : ?>
                <option value="<?php echo esc_attr( $ct->term_id ); ?>" <?php selected( in_array( $ct->term_id, $selected_centers ) ); ?>><?php echo esc_html( $ct->name ); ?></option>
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
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'MRP', 'pathology-booking-system' ); ?></label>
        <input type="number" step="0.01" name="mrp" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_mrp', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Price (₹)', 'pathology-booking-system' ); ?></label>
        <input type="number" step="0.01" name="price" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_price', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Parameters Count', 'pathology-booking-system' ); ?></label>
        <input type="number" name="parameters" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_parameters_count', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here e.g. 53', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Package Subtitle / Tagline', 'pathology-booking-system' ); ?></label>
        <input type="text" name="subtitle" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_subtitle', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Care for Mother and Baby', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Gender Recommendation', 'pathology-booking-system' ); ?></label>
        <input type="text" name="gender_recommendation" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_gender_recommendation', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. Recommended for Male & Female', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Badge Text', 'pathology-booking-system' ); ?></label>
        <input type="text" name="badge_text" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_badge_text', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'e.g. MOST POPULAR, BEST VALUE', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Badge Color', 'pathology-booking-system' ); ?></label>
        <input type="color" name="badge_color" value="<?php echo esc_attr( $post ? ( get_post_meta( $post->ID, '_ptbs_badge_color', true ) ?: '#22c55e' ) : '#22c55e' ); ?>" style="width:100%; height:42px; border:1px solid #cbd5e1; border-radius:6px; cursor:pointer;">
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Meta Title', 'pathology-booking-system' ); ?></label>
        <input type="text" name="meta_title" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_meta_title', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Meta Keywords', 'pathology-booking-system' ); ?></label>
        <input type="text" name="meta_keywords" value="<?php echo esc_attr( $post ? get_post_meta( $post->ID, '_ptbs_meta_keywords', true ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;">
    </div>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Meta Description', 'pathology-booking-system' ); ?></label>
    <textarea name="meta_description" rows="3" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $post ? get_post_meta( $post->ID, '_ptbs_meta_description', true ) : '' ); ?></textarea>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Description', 'pathology-booking-system' ); ?></label>
    <textarea name="description" rows="4" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $post ? $post->post_content : '' ); ?></textarea>
</div>

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'FAQ', 'pathology-booking-system' ); ?></label>
    <textarea name="faq" rows="4" placeholder="<?php esc_attr_e( 'Enter here', 'pathology-booking-system' ); ?>" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $post ? get_post_meta( $post->ID, '_ptbs_faqs', true ) : '' ); ?></textarea>
</div>

<div style="margin-bottom:20px;">
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

<div style="margin-bottom:20px;">
    <label style="display:block; font-weight:600; margin-bottom:8px; color:#334155;"><?php esc_html_e( 'Image', 'pathology-booking-system' ); ?></label>
    <input type="hidden" name="package_image_id" id="ptbs_cat_img_id" value="<?php echo esc_attr( $post ? get_post_thumbnail_id( $post->ID ) : '' ); ?>">
    <button type="button" class="button" id="ptbs_cat_img_btn" style="border-color:#0284c7; color:#0284c7; padding:6px 20px; font-weight:600;"><?php esc_html_e( 'Upload', 'pathology-booking-system' ); ?></button>
    <div id="ptbs_cat_img_preview" style="margin-top:10px;">
        <?php if ( $post && get_post_thumbnail_id( $post->ID ) ) echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
    </div>
</div>
