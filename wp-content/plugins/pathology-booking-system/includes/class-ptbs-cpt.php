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
    }

    /**
     * Register Custom Post Types with Custom Rewrite Slugs
     */
    public static function register_post_types() {
        $settings     = get_option( 'ptbs_settings', array() );
        $test_slug    = ! empty( $settings['test_permalink_slug'] ) ? sanitize_title( $settings['test_permalink_slug'] ) : 'test';
        $package_slug = ! empty( $settings['package_permalink_slug'] ) ? sanitize_title( $settings['package_permalink_slug'] ) : 'package';

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
    }

    /**
     * Register City Taxonomy
     */
    public static function register_taxonomies() {
        $labels = array(
            'name'              => __( 'Cities / Locations', 'pathology-booking-system' ),
            'singular_name'     => __( 'City', 'pathology-booking-system' ),
            'search_items'      => __( 'Search Cities', 'pathology-booking-system' ),
            'all_items'         => __( 'All Cities', 'pathology-booking-system' ),
            'edit_item'         => __( 'Edit City', 'pathology-booking-system' ),
            'update_item'       => __( 'Update City', 'pathology-booking-system' ),
            'add_new_item'      => __( 'Add New City', 'pathology-booking-system' ),
            'new_item_name'     => __( 'New City Name', 'pathology-booking-system' ),
            'menu_name'         => __( 'Cities / Locations', 'pathology-booking-system' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'city' ),
        );

        register_taxonomy( 'ptbs_city', array( 'ptbs_test', 'ptbs_package' ), $args );
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

        $code        = get_post_meta( $post->ID, '_ptbs_code', true );
        $price       = get_post_meta( $post->ID, '_ptbs_price', true );
        $sample_type = get_post_meta( $post->ID, '_ptbs_sample_type', true );
        $fasting_req = get_post_meta( $post->ID, '_ptbs_fasting_req', true );
        $tat_hours   = get_post_meta( $post->ID, '_ptbs_tat_hours', true );
        $parameters  = get_post_meta( $post->ID, '_ptbs_parameters', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="ptbs_code"><?php esc_html_e( 'Test Code:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_code" name="ptbs_code" value="<?php echo esc_attr( $code ); ?>" class="regular-text" placeholder="e.g. CBC01"></td>
            </tr>
            <tr>
                <th><label for="ptbs_price"><?php esc_html_e( 'Regular Price (₹):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" step="0.01" id="ptbs_price" name="ptbs_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" required placeholder="e.g. 499.00"></td>
            </tr>
            <tr>
                <th><label for="ptbs_sample_type"><?php esc_html_e( 'Sample Type Required:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_sample_type" name="ptbs_sample_type" value="<?php echo esc_attr( ! empty( $sample_type ) ? $sample_type : 'Blood (EDTA / Whole Blood)' ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_fasting_req"><?php esc_html_e( 'Fasting Requirement:', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_fasting_req" name="ptbs_fasting_req" value="<?php echo esc_attr( ! empty( $fasting_req ) ? $fasting_req : '10-12 Hours Fasting Required' ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_tat_hours"><?php esc_html_e( 'Turnaround Time (TAT):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="text" id="ptbs_tat_hours" name="ptbs_tat_hours" value="<?php echo esc_attr( ! empty( $tat_hours ) ? $tat_hours : '24 Hours' ); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="ptbs_parameters"><?php esc_html_e( 'Parameters Measured (One per line):', 'pathology-booking-system' ); ?></label></th>
                <td>
                    <textarea id="ptbs_parameters" name="ptbs_parameters" rows="6" class="large-text" placeholder="Hemoglobin (Hb)&#10;Red Blood Cell (RBC) Count&#10;Total Leucocyte Count (TLC)&#10;Platelet Count"><?php echo esc_textarea( $parameters ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter sub-tests / parameters included in this test, one per line.', 'pathology-booking-system' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function render_package_meta_box( $post ) {
        wp_nonce_field( 'ptbs_save_cpt_meta', 'ptbs_cpt_meta_nonce' );

        $price           = get_post_meta( $post->ID, '_ptbs_price', true );
        $linked_test_ids = get_post_meta( $post->ID, '_ptbs_linked_test_ids', true );
        if ( ! is_array( $linked_test_ids ) ) {
            $linked_test_ids = array();
        }

        // Fetch all available Pathology Tests
        $all_tests = get_posts( array(
            'post_type'      => 'ptbs_test',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="ptbs_price"><?php esc_html_e( 'Package Price (₹):', 'pathology-booking-system' ); ?></label></th>
                <td><input type="number" step="0.01" id="ptbs_price" name="ptbs_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" required placeholder="e.g. 1499.00"></td>
            </tr>
            <tr>
                <th><label for="ptbs_linked_test_ids"><?php esc_html_e( 'Linked Pathology Tests (Multi-Select):', 'pathology-booking-system' ); ?></label></th>
                <td>
                    <p class="description"><?php esc_html_e( 'Hold Ctrl (or Cmd on Mac) to multi-select tests included in this health checkup package. The single package page will automatically build the Parameter Tree Accordion from these tests!', 'pathology-booking-system' ); ?></p>
                    
                    <div style="margin: 10px 0; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                        <input type="text" id="ptbs_test_search" placeholder="🔍 Search tests..." class="regular-text" style="padding: 4px 10px;">
                        <button type="button" class="button button-secondary" id="ptbs_select_all_tests"><?php esc_html_e( 'Select All', 'pathology-booking-system' ); ?></button>
                        <button type="button" class="button button-secondary" id="ptbs_deselect_all_tests"><?php esc_html_e( 'Deselect All', 'pathology-booking-system' ); ?></button>
                    </div>

                    <select name="ptbs_linked_test_ids[]" id="ptbs_linked_test_ids" multiple="multiple" class="large-text" style="height: 200px; padding: 8px; border-radius: 6px;">
                        <?php if ( ! empty( $all_tests ) ) : ?>
                            <?php foreach ( $all_tests as $t ) : 
                                $price_val = floatval( get_post_meta( $t->ID, '_ptbs_price', true ) );
                            ?>
                                <option value="<?php echo esc_attr( $t->ID ); ?>" <?php selected( in_array( $t->ID, $linked_test_ids ) ); ?>>
                                    <?php echo esc_html( $t->post_title ); ?> — (₹<?php echo esc_html( number_format( $price_val, 2 ) ); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <option value=""><?php esc_html_e( 'No pathology tests created yet.', 'pathology-booking-system' ); ?></option>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>
        </table>

        <script>
        jQuery(document).ready(function($) {
            $('#ptbs_select_all_tests').on('click', function() {
                $('#ptbs_linked_test_ids option:visible').prop('selected', true);
            });
            $('#ptbs_deselect_all_tests').on('click', function() {
                $('#ptbs_linked_test_ids option').prop('selected', false);
            });
            $('#ptbs_test_search').on('keyup', function() {
                const val = $(this).val().toLowerCase();
                $('#ptbs_linked_test_ids option').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(val) > -1);
                });
            });
        });
        </script>
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

        if ( isset( $_POST['ptbs_sample_type'] ) ) {
            update_post_meta( $post_id, '_ptbs_sample_type', sanitize_text_field( wp_unslash( $_POST['ptbs_sample_type'] ) ) );
        }

        if ( isset( $_POST['ptbs_fasting_req'] ) ) {
            update_post_meta( $post_id, '_ptbs_fasting_req', sanitize_text_field( wp_unslash( $_POST['ptbs_fasting_req'] ) ) );
        }

        if ( isset( $_POST['ptbs_tat_hours'] ) ) {
            update_post_meta( $post_id, '_ptbs_tat_hours', sanitize_text_field( wp_unslash( $_POST['ptbs_tat_hours'] ) ) );
        }

        if ( isset( $_POST['ptbs_parameters'] ) ) {
            update_post_meta( $post_id, '_ptbs_parameters', sanitize_textarea_field( wp_unslash( $_POST['ptbs_parameters'] ) ) );
        }

        if ( isset( $_POST['ptbs_linked_test_ids'] ) && is_array( $_POST['ptbs_linked_test_ids'] ) ) {
            $linked_ids = array_map( 'absint', $_POST['ptbs_linked_test_ids'] );
            update_post_meta( $post_id, '_ptbs_linked_test_ids', $linked_ids );
        } else {
            update_post_meta( $post_id, '_ptbs_linked_test_ids', array() );
        }
    }
}
