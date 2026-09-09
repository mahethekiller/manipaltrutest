<?php
/**
 * WPBakery Page Builder Elements Integration Class
 *
 * Registers custom WPBakery elements under dedicated 'Pathology Booking' tab category
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PTBS_WPBakery {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'vc_before_init', array( $this, 'register_elements' ) );
        add_action( 'init', array( $this, 'enable_wpbakery_for_cpts' ), 20 );
    }

    /**
     * Automatically enable WPBakery Page Builder for Pathology CPTs
     */
    public function enable_wpbakery_for_cpts() {
        if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
            $post_types = array( 'page', 'post', 'ptbs_test', 'ptbs_package', 'ptbs_center_location' );
            vc_set_default_editor_post_types( $post_types );
        }
    }

    /**
     * Register Custom WPBakery Elements
     */
    public function register_elements() {
        if ( ! function_exists( 'vc_map' ) ) {
            return;
        }

        $category_name = __( 'Pathology Booking', 'pathology-booking-system' );

        // 1. Pathology Booking Catalog App Element
        vc_map( array(
            'name'        => __( 'Pathology Catalog App', 'pathology-booking-system' ),
            'base'        => 'pathology_booking',
            'description' => __( 'Interactive Pathology Catalog with City selector, search bar & cart modal', 'pathology-booking-system' ),
            'category'    => $category_name,
            'icon'        => 'dashicons-excerpt-view',
            'params'      => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'App Heading Title', 'pathology-booking-system' ),
                    'param_name'  => 'title',
                    'value'       => __( 'Book Diagnostic Pathology Tests & Health Packages', 'pathology-booking-system' ),
                    'description' => __( 'Title displayed at the top of the catalog app', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Default View Tab', 'pathology-booking-system' ),
                    'param_name'  => 'default_tab',
                    'value'       => array(
                        __( 'All Items (Tests & Packages)', 'pathology-booking-system' ) => 'all',
                        __( 'Pathology Tests', 'pathology-booking-system' )            => 'test',
                        __( 'Health Packages', 'pathology-booking-system' )           => 'package',
                    ),
                    'std'         => 'all',
                ),
            ),
        ) );

        // 2. Featured Tests Grid / Carousel Element
        vc_map( array(
            'name'        => __( 'Featured Pathology Tests', 'pathology-booking-system' ),
            'base'        => 'pathology_featured_tests',
            'description' => __( 'Grid or Carousel of top pathology tests with pricing & details modal', 'pathology-booking-system' ),
            'category'    => $category_name,
            'icon'        => 'dashicons-testimonial',
            'params'      => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section Title', 'pathology-booking-system' ),
                    'param_name'  => 'title',
                    'value'       => __( 'Popular Diagnostic Tests', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Number of Tests to Display', 'pathology-booking-system' ),
                    'param_name'  => 'limit',
                    'value'       => '6',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Grid Columns', 'pathology-booking-system' ),
                    'param_name'  => 'columns',
                    'value'       => array(
                        __( '2 Columns', 'pathology-booking-system' ) => '2',
                        __( '3 Columns', 'pathology-booking-system' ) => '3',
                        __( '4 Columns', 'pathology-booking-system' ) => '4',
                    ),
                    'std'         => '3',
                ),
            ),
        ) );

        // 3. Featured Health Packages Grid Element
        vc_map( array(
            'name'        => __( 'Featured Health Packages', 'pathology-booking-system' ),
            'base'        => 'pathology_health_packages',
            'description' => __( 'Card grid of discounted health checkup packages with MRP badges', 'pathology-booking-system' ),
            'category'    => $category_name,
            'icon'        => 'dashicons-welcome-widgets-menus',
            'params'      => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section Title', 'pathology-booking-system' ),
                    'param_name'  => 'title',
                    'value'       => __( 'Comprehensive Health Checkup Packages', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Number of Packages to Display', 'pathology-booking-system' ),
                    'param_name'  => 'limit',
                    'value'       => '3',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Grid Columns', 'pathology-booking-system' ),
                    'param_name'  => 'columns',
                    'value'       => array(
                        __( '2 Columns', 'pathology-booking-system' ) => '2',
                        __( '3 Columns', 'pathology-booking-system' ) => '3',
                        __( '4 Columns', 'pathology-booking-system' ) => '4',
                    ),
                    'std'         => '3',
                ),
            ),
        ) );

        // 4. Center Locations Locator Grid Element
        vc_map( array(
            'name'        => __( 'Lab Center Locations', 'pathology-booking-system' ),
            'base'        => 'pathology_center_locations',
            'description' => __( 'Grid list of lab center locations with operating hours & direct page links', 'pathology-booking-system' ),
            'category'    => $category_name,
            'icon'        => 'dashicons-location-alt',
            'params'      => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Section Title', 'pathology-booking-system' ),
                    'param_name'  => 'title',
                    'value'       => __( 'Our Lab Center Locations', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Number of Centers to Display', 'pathology-booking-system' ),
                    'param_name'  => 'limit',
                    'value'       => '6',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Grid Columns', 'pathology-booking-system' ),
                    'param_name'  => 'columns',
                    'value'       => array(
                        __( '2 Columns', 'pathology-booking-system' ) => '2',
                        __( '3 Columns', 'pathology-booking-system' ) => '3',
                    ),
                    'std'         => '3',
                ),
            ),
        ) );
    }
}

// Initialize WPBakery Integration
PTBS_WPBakery::get_instance();
