<?php
/**
 * Custom Post Types, Taxonomies & Meta Boxes Handler
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_CPT {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( __CLASS__, 'register_post_types' ) );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
        add_action( 'template_redirect', array( $this, 'handle_legacy_permalink_redirects' ) );
    }

    /**
     * Handle 301 Permanent Redirects for Old Permalink Slugs
     */
    public function handle_legacy_permalink_redirects() {
        if ( is_admin() || is_404() ) {
            return;
        }

        $old_slugs = get_option( 'ptbs_previous_permalink_slugs', array() );
        if ( empty( $old_slugs ) || ! is_array( $old_slugs ) ) {
            return;
        }

        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        foreach ( $old_slugs as $old_slug ) {
            if ( ! empty( $old_slug ) && false !== strpos( $request_uri, '/' . trim( $old_slug, '/' ) . '/' ) ) {
                if ( is_singular( array( 'ptbs_test', 'ptbs_package', 'ptbs_center_location' ) ) ) {
                    wp_safe_redirect( get_permalink(), 301 );
                    exit;
                }
            }
        }
    }

    /**
     * Register Custom Post Types with Custom Rewrite Slugs
     */
    public static function register_post_types() {
        $settings     = get_option( 'ptbs_settings', array() );
        $test_slug    = ! empty( $settings['test_permalink_slug'] ) ? sanitize_title( $settings['test_permalink_slug'] ) : 'test';
        $package_slug = ! empty( $settings['package_permalink_slug'] ) ? sanitize_title( $settings['package_permalink_slug'] ) : 'package';
        $center_slug  = ! empty( $settings['center_location_permalink_slug'] ) ? sanitize_title( $settings['center_location_permalink_slug'] ) : 'center-location';

        // 1. Pathology Test CPT
        $labels_test = array(
            'name'               => __( 'Pathology Tests', 'pathology-booking-system' ),
            'singular_name'      => __( 'Pathology Test', 'pathology-booking-system' ),
            'add_new'            => __( 'Add New Test', 'pathology-booking-system' ),
            'add_new_item'       => __( 'Add New Pathology Test', 'pathology-booking-system' ),
            'edit_item'          => __( 'Edit Test', 'pathology-booking-system' ),
            'new_item'           => __( 'New Test', 'pathology-booking-system' ),
            'all_items'          => __( 'Pathology Tests', 'pathology-booking-system' ),
            'view_item'          => __( 'View Test', 'pathology-booking-system' ),
            'search_items'       => __( 'Search Pathology Tests', 'pathology-booking-system' ),
            'not_found'          => __( 'No Pathology Tests found', 'pathology-booking-system' ),
            'menu_name'          => __( 'Pathology Tests', 'pathology-booking-system' ),
        );

        $args_test = array(
            'labels'             => $labels_test,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'ptbs-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $test_slug, 'with_front' => true ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-testimonial',
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        );

        register_post_type( 'ptbs_test', $args_test );

        // 2. Health Package CPT
        $labels_package = array(
            'name'               => __( 'Health Packages', 'pathology-booking-system' ),
            'singular_name'      => __( 'Health Package', 'pathology-booking-system' ),
            'add_new'            => __( 'Add New Package', 'pathology-booking-system' ),
            'add_new_item'       => __( 'Add New Health Package', 'pathology-booking-system' ),
            'edit_item'          => __( 'Edit Package', 'pathology-booking-system' ),
            'all_items'          => __( 'Health Packages', 'pathology-booking-system' ),
            'menu_name'          => __( 'Health Packages', 'pathology-booking-system' ),
        );

        $args_package = array(
            'labels'             => $labels_package,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'ptbs-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $package_slug, 'with_front' => true ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        );

        register_post_type( 'ptbs_package', $args_package );

        // 3. Center Location CPT
        $labels_center = array(
            'name'          => __( 'Center Locations', 'pathology-booking-system' ),
            'singular_name' => __( 'Center Location', 'pathology-booking-system' ),
            'add_new'       => __( 'Add New Center Location', 'pathology-booking-system' ),
            'add_new_item'  => __( 'Add New Center Location', 'pathology-booking-system' ),
            'edit_item'     => __( 'Edit Center Location', 'pathology-booking-system' ),
            'all_items'     => __( 'Center Locations', 'pathology-booking-system' ),
            'menu_name'     => __( 'Center Locations', 'pathology-booking-system' ),
        );

        $args_center = array(
            'labels'             => $labels_center,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'ptbs-dashboard',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $center_slug, 'with_front' => true ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        );

        register_post_type( 'ptbs_center_location', $args_center );
    }

    /**
     * Register Custom Taxonomies
     */
    public static function register_taxonomies() {
        // 1. Category Taxonomy (ptbs_category)
        register_taxonomy( 'ptbs_category', array( 'ptbs_test', 'ptbs_package' ), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'          => __( 'Categories', 'pathology-booking-system' ),
                'singular_name' => __( 'Category', 'pathology-booking-system' ),
                'search_items'  => __( 'Search Categories', 'pathology-booking-system' ),
                'all_items'     => __( 'All Categories', 'pathology-booking-system' ),
                'edit_item'     => __( 'Edit Category', 'pathology-booking-system' ),
                'update_item'   => __( 'Update Category', 'pathology-booking-system' ),
                'add_new_item'  => __( 'Add New Category', 'pathology-booking-system' ),
                'menu_name'     => __( 'Categories', 'pathology-booking-system' ),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'test-category' ),
        ) );

        // 2. SubCategory Taxonomy (ptbs_subcategory)
        register_taxonomy( 'ptbs_subcategory', array( 'ptbs_test', 'ptbs_package' ), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'          => __( 'Sub Categories', 'pathology-booking-system' ),
                'singular_name' => __( 'Sub Category', 'pathology-booking-system' ),
                'search_items'  => __( 'Search Sub Categories', 'pathology-booking-system' ),
                'all_items'     => __( 'All Sub Categories', 'pathology-booking-system' ),
                'edit_item'     => __( 'Edit Sub Category', 'pathology-booking-system' ),
                'update_item'   => __( 'Update Sub Category', 'pathology-booking-system' ),
                'add_new_item'  => __( 'Add New Sub Category', 'pathology-booking-system' ),
                'menu_name'     => __( 'Sub Categories', 'pathology-booking-system' ),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'test-subcategory' ),
        ) );

        // 3. Condition Taxonomy (ptbs_condition)
        register_taxonomy( 'ptbs_condition', array( 'ptbs_test', 'ptbs_package' ), array(
            'hierarchical'      => false,
            'labels'            => array(
                'name'          => __( 'Conditions', 'pathology-booking-system' ),
                'singular_name' => __( 'Condition', 'pathology-booking-system' ),
                'search_items'  => __( 'Search Conditions', 'pathology-booking-system' ),
                'all_items'     => __( 'All Conditions', 'pathology-booking-system' ),
                'edit_item'     => __( 'Edit Condition', 'pathology-booking-system' ),
                'update_item'   => __( 'Update Condition', 'pathology-booking-system' ),
                'add_new_item'  => __( 'Add New Condition', 'pathology-booking-system' ),
                'menu_name'     => __( 'Conditions', 'pathology-booking-system' ),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'health-condition' ),
        ) );

        // 4. State Taxonomy (ptbs_state)
        register_taxonomy( 'ptbs_state', array( 'ptbs_test', 'ptbs_package', 'ptbs_center_location' ), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'          => __( 'States', 'pathology-booking-system' ),
                'singular_name' => __( 'State', 'pathology-booking-system' ),
                'menu_name'     => __( 'States', 'pathology-booking-system' ),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'state' ),
        ) );

        // 5. City Taxonomy (ptbs_city)
        register_taxonomy( 'ptbs_city', array( 'ptbs_test', 'ptbs_package', 'ptbs_center_location' ), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => __( 'Cities / Locations', 'pathology-booking-system' ),
                'singular_name'     => __( 'City', 'pathology-booking-system' ),
                'search_items'      => __( 'Search Cities', 'pathology-booking-system' ),
                'all_items'         => __( 'All Cities', 'pathology-booking-system' ),
                'edit_item'         => __( 'Edit City', 'pathology-booking-system' ),
                'update_item'       => __( 'Update City', 'pathology-booking-system' ),
                'add_new_item'      => __( 'Add New City', 'pathology-booking-system' ),
                'new_item_name'     => __( 'New City Name', 'pathology-booking-system' ),
                'menu_name'         => __( 'Cities / Locations', 'pathology-booking-system' ),
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'city' ),
        ) );
    }

    /**
     * Meta Boxes Registration
     */
    public function register_meta_boxes() {
        add_meta_box(
            'ptbs_test_details',
            __( 'Diagnostic Test Information & Parameters', 'pathology-booking-system' ),
            array( $this, 'render_test_meta_box' ),
            'ptbs_test',
            'normal',
            'high'
        );

        add_meta_box(
            'ptbs_package_details',
            __( 'Health Package Info & Linked Tests Selector', 'pathology-booking-system' ),
            array( $this, 'render_package_meta_box' ),
            'ptbs_package',
            'normal',
            'high'
        );
    }

    public function render_test_meta_box( $post ) {
        wp_nonce_field( 'ptbs_save_cpt_meta', 'ptbs_cpt_meta_nonce' );

        $code             = get_post_meta( $post->ID, '_ptbs_code', true );
        $price            = get_post_meta( $post->ID, '_ptbs_price', true );
        $main_test_name   = get_post_meta( $post->ID, '_ptbs_main_test_name', true );
        $cutoff_time      = get_post_meta( $post->ID, '_ptbs_cutoff_time', true );
        $method           = get_post_meta( $post->ID, '_ptbs_method', true );
        $sample_type      = get_post_meta( $post->ID, '_ptbs_sample_type', true );
        $tat_hours        = get_post_meta( $post->ID, '_ptbs_tat_hours', true );
        $parameters       = get_post_meta( $post->ID, '_ptbs_parameters', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="ptbs_main_test_name"><?php esc_html_e( 'Main Test Name:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_main_test_name" name="ptbs_main_test_name" value="<?php echo esc_attr( $main_test_name ); ?>" class="regular-text" placeholder="e.g. Complete Blood Count (CBC)"></td>
            </tr>
            <tr>
                <th><label for="ptbs_code"><?php esc_html_e( 'Test Code:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_code" name="ptbs_code" value="<?php echo esc_attr( $code ); ?>" class="regular-text" placeholder="e.g. CBC01"></td>
            </tr>
            <tr>
                <th><label for="ptbs_price"><?php esc_html_e( 'Regular Price (₹):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" step="0.01" id="ptbs_price" name="ptbs_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" required placeholder="e.g. 499.00"></td>
            </tr>
            <tr>
                <th><label for="ptbs_cutoff_time"><?php esc_html_e( 'Cut Off Time:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_cutoff_time" name="ptbs_cutoff_time" value="<?php echo esc_attr( $cutoff_time ); ?>" class="regular-text" placeholder="e.g. 5:00 PM"></td>
            </tr>
            <tr>
                <th><label for="ptbs_method"><?php esc_html_e( 'Method:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_method" name="ptbs_method" value="<?php echo esc_attr( $method ); ?>" class="regular-text" placeholder="e.g. Electrical Impedance"></td>
            </tr>
            <tr>
                <th><label for="ptbs_sample_type"><?php esc_html_e( 'Specimen / Sample Required:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_sample_type" name="ptbs_sample_type" value="<?php echo esc_attr( ! empty( $sample_type ) ? $sample_type : 'Whole Blood (EDTA)' ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_tat_hours"><?php esc_html_e( 'Report Delivery (TAT):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_tat_hours" name="ptbs_tat_hours" value="<?php echo esc_attr( ! empty( $tat_hours ) ? $tat_hours : 'Same Day' ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_parameters"><?php esc_html_e( 'Parameters Measured (One per line):', 'pathology-booking-system' ); ?></label></th>
                <td>
                    <textarea id="ptbs_parameters" name="ptbs_parameters" rows="6" class="large-text" placeholder="Hemoglobin (Hb)&#10;Red Blood Cell (RBC) Count&#10;Total Leucocyte Count (TLC)&#10;Platelet Count"><?php echo esc_textarea( $parameters ); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    public function render_package_meta_box( $post ) {
        wp_nonce_field( 'ptbs_save_cpt_meta', 'ptbs_cpt_meta_nonce' );

        $mrp             = get_post_meta( $post->ID, '_ptbs_mrp', true );
        $price           = get_post_meta( $post->ID, '_ptbs_price', true );
        $code            = get_post_meta( $post->ID, '_ptbs_code', true );
        $params_count    = get_post_meta( $post->ID, '_ptbs_parameters_count', true );
        $meta_title      = get_post_meta( $post->ID, '_ptbs_meta_title', true );
        $meta_keywords   = get_post_meta( $post->ID, '_ptbs_meta_keywords', true );
        $meta_desc       = get_post_meta( $post->ID, '_ptbs_meta_description', true );
        $faq             = get_post_meta( $post->ID, '_ptbs_faqs', true );
        $linked_test_ids = get_post_meta( $post->ID, '_ptbs_linked_test_ids', true );
        if ( ! is_array( $linked_test_ids ) ) {
            $linked_test_ids = array();
        }

        $all_tests = get_posts( array(
            'post_type'      => 'ptbs_test',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="ptbs_code"><?php esc_html_e( 'Package Test Code:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_code" name="ptbs_code" value="<?php echo esc_attr( $code ); ?>" class="regular-text" placeholder="e.g. PKG01"></td>
            </tr>
            <tr>
                <th><label for="ptbs_mrp"><?php esc_html_e( 'MRP (Original Price ₹):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" step="0.01" id="ptbs_mrp" name="ptbs_mrp" value="<?php echo esc_attr( $mrp ); ?>" class="regular-text" placeholder="e.g. 2999.00"></td>
            </tr>
            <tr>
                <th><label for="ptbs_price"><?php esc_html_e( 'Offer Price (₹):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" step="0.01" id="ptbs_price" name="ptbs_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" required placeholder="e.g. 1499.00"></td>
            </tr>
            <tr>
                <th><label for="ptbs_parameters_count"><?php esc_html_e( 'Parameters Measured Count:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" id="ptbs_parameters_count" name="ptbs_parameters_count" value="<?php echo esc_attr( $params_count ); ?>" class="regular-text" placeholder="e.g. 85"></td>
            </tr>
            <tr>
                <th><label for="ptbs_meta_title"><?php esc_html_e( 'Meta Title:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_meta_title" name="ptbs_meta_title" value="<?php echo esc_attr( $meta_title ); ?>" class="large-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_meta_keywords"><?php esc_html_e( 'Meta Keywords:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_meta_keywords" name="ptbs_meta_keywords" value="<?php echo esc_attr( $meta_keywords ); ?>" class="large-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_meta_description"><?php esc_html_e( 'Meta Description:', 'pathology-booking-system' ); ?></label></th>
                <td><textarea id="ptbs_meta_description" name="ptbs_meta_description" rows="3" class="large-text"><?php echo esc_textarea( $meta_desc ); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="ptbs_faqs"><?php esc_html_e( 'FAQ Section:', 'pathology-booking-system' ); ?></label></th>
                <td><textarea id="ptbs_faqs" name="ptbs_faqs" rows="5" class="large-text" placeholder="Q: Is fasting required?&#10;A: Yes, 10-12 hours fasting required."><?php echo esc_textarea( $faq ); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="ptbs_linked_test_ids"><?php esc_html_e( 'Linked Pathology Tests:', 'pathology-booking-system' ); ?></label></th>
                <td>
                    <select name="ptbs_linked_test_ids[]" id="ptbs_linked_test_ids" multiple="multiple" class="large-text" style="height: 180px; padding: 8px; border-radius: 6px;">
                        <?php if ( ! empty( $all_tests ) ) : ?>
                            <?php foreach ( $all_tests as $t ) : ?>
                                <option value="<?php echo esc_attr( $t->ID ); ?>" <?php selected( in_array( $t->ID, $linked_test_ids ) ); ?>>
                                    <?php echo esc_html( $t->post_title ); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['ptbs_cpt_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ptbs_cpt_meta_nonce'], 'ptbs_save_cpt_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['ptbs_code'] ) ) {
            update_post_meta( $post_id, '_ptbs_code', sanitize_text_field( wp_unslash( $_POST['ptbs_code'] ) ) );
        }
        if ( isset( $_POST['ptbs_price'] ) ) {
            update_post_meta( $post_id, '_ptbs_price', floatval( $_POST['ptbs_price'] ) );
        }
        if ( isset( $_POST['ptbs_main_test_name'] ) ) {
            update_post_meta( $post_id, '_ptbs_main_test_name', sanitize_text_field( wp_unslash( $_POST['ptbs_main_test_name'] ) ) );
        }
        if ( isset( $_POST['ptbs_cutoff_time'] ) ) {
            update_post_meta( $post_id, '_ptbs_cutoff_time', sanitize_text_field( wp_unslash( $_POST['ptbs_cutoff_time'] ) ) );
        }
        if ( isset( $_POST['ptbs_method'] ) ) {
            update_post_meta( $post_id, '_ptbs_method', sanitize_text_field( wp_unslash( $_POST['ptbs_method'] ) ) );
        }
        if ( isset( $_POST['ptbs_sample_type'] ) ) {
            update_post_meta( $post_id, '_ptbs_sample_type', sanitize_text_field( wp_unslash( $_POST['ptbs_sample_type'] ) ) );
        }
        if ( isset( $_POST['ptbs_tat_hours'] ) ) {
            update_post_meta( $post_id, '_ptbs_tat_hours', sanitize_text_field( wp_unslash( $_POST['ptbs_tat_hours'] ) ) );
        }
        if ( isset( $_POST['ptbs_parameters'] ) ) {
            update_post_meta( $post_id, '_ptbs_parameters', sanitize_textarea_field( wp_unslash( $_POST['ptbs_parameters'] ) ) );
        }
        if ( isset( $_POST['ptbs_mrp'] ) ) {
            update_post_meta( $post_id, '_ptbs_mrp', floatval( $_POST['ptbs_mrp'] ) );
        }
        if ( isset( $_POST['ptbs_parameters_count'] ) ) {
            update_post_meta( $post_id, '_ptbs_parameters_count', absint( $_POST['ptbs_parameters_count'] ) );
        }
        if ( isset( $_POST['ptbs_meta_title'] ) ) {
            update_post_meta( $post_id, '_ptbs_meta_title', sanitize_text_field( wp_unslash( $_POST['ptbs_meta_title'] ) ) );
        }
        if ( isset( $_POST['ptbs_meta_keywords'] ) ) {
            update_post_meta( $post_id, '_ptbs_meta_keywords', sanitize_text_field( wp_unslash( $_POST['ptbs_meta_keywords'] ) ) );
        }
        if ( isset( $_POST['ptbs_meta_description'] ) ) {
            update_post_meta( $post_id, '_ptbs_meta_description', sanitize_textarea_field( wp_unslash( $_POST['ptbs_meta_description'] ) ) );
        }
        if ( isset( $_POST['ptbs_faqs'] ) ) {
            update_post_meta( $post_id, '_ptbs_faqs', sanitize_textarea_field( wp_unslash( $_POST['ptbs_faqs'] ) ) );
        }
        if ( isset( $_POST['ptbs_linked_test_ids'] ) && is_array( $_POST['ptbs_linked_test_ids'] ) ) {
            $linked_ids = array_map( 'absint', $_POST['ptbs_linked_test_ids'] );
            update_post_meta( $post_id, '_ptbs_linked_test_ids', $linked_ids );
        }
    }
}

