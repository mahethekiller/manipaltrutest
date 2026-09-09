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

        // Helper: Get term options array for WPBakery dropdown
        $get_taxonomy_options = function( $taxonomy ) {
            $options = array( __( 'All / Any', 'pathology-booking-system' ) => '' );
            $terms   = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options[$term->name] = (string) $term->term_id;
                }
            }
            return $options;
        };

        $cat_options    = $get_taxonomy_options( 'ptbs_category' );
        $subcat_options = $get_taxonomy_options( 'ptbs_subcategory' );
        $cond_options   = $get_taxonomy_options( 'ptbs_condition' );
        $city_options   = $get_taxonomy_options( 'ptbs_city' );
        $state_options  = $get_taxonomy_options( 'ptbs_state' );

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
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by Category', 'pathology-booking-system' ),
                    'param_name'  => 'category_id',
                    'value'       => $cat_options,
                    'description' => __( 'Show tests belonging to a specific category', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by Subcategory', 'pathology-booking-system' ),
                    'param_name'  => 'subcategory_id',
                    'value'       => $subcat_options,
                    'description' => __( 'Show tests belonging to a specific subcategory', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by Health Condition', 'pathology-booking-system' ),
                    'param_name'  => 'condition_id',
                    'value'       => $cond_options,
                    'description' => __( 'Show tests associated with a specific health condition', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by City', 'pathology-booking-system' ),
                    'param_name'  => 'city_id',
                    'value'       => $city_options,
                    'description' => __( 'Show tests available in a specific city', 'pathology-booking-system' ),
                ),
            ),
        ) );

        // 3. Featured Health Packages Slider & Grid Element (Premium Card Layout)
        vc_map( array(
            'name'        => __( 'Health Packages Slider & Grid', 'pathology-booking-system' ),
            'base'        => 'pathology_health_packages_slider',
            'description' => __( 'Premium Health Checkups Slider & Grid with Slick Carousel, View All button, Badges & Cart Actions', 'pathology-booking-system' ),
            'category'    => $category_name,
            'icon'        => 'dashicons-welcome-widgets-menus',
            'params'      => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Sub Title / Kicker Tag', 'pathology-booking-system' ),
                    'param_name'  => 'sub_heading',
                    'value'       => __( 'HEALTH CHECKUPS', 'pathology-booking-system' ),
                    'description' => __( 'Small uppercase badge text above title (e.g. HEALTH CHECKUPS)', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Main Title', 'pathology-booking-system' ),
                    'param_name'  => 'title',
                    'value'       => __( 'Keep your family TRUly healthy.', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Description / Subheading Text', 'pathology-booking-system' ),
                    'param_name'  => 'description',
                    'value'       => __( 'Choose a package. Get tested TODAY!', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Display Layout Mode', 'pathology-booking-system' ),
                    'param_name'  => 'layout_mode',
                    'value'       => array(
                        __( 'Slick Carousel Slider (Touch Swipe + Arrows)', 'pathology-booking-system' ) => 'carousel',
                        __( 'Responsive Grid Layout', 'pathology-booking-system' )                     => 'grid',
                    ),
                    'std'         => 'carousel',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Show Top Right "VIEW ALL ↗" Button?', 'pathology-booking-system' ),
                    'param_name'  => 'show_view_all',
                    'value'       => array(
                        __( 'Yes', 'pathology-booking-system' ) => 'yes',
                        __( 'No', 'pathology-booking-system' )  => 'no',
                    ),
                    'std'         => 'yes',
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'View All Button Target URL', 'pathology-booking-system' ),
                    'param_name'  => 'view_all_url',
                    'value'       => '#',
                    'description' => __( 'URL where View All button directs visitors', 'pathology-booking-system' ),
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Number of Packages to Display', 'pathology-booking-system' ),
                    'param_name'  => 'limit',
                    'value'       => '8',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Columns per Slide / Row', 'pathology-booking-system' ),
                    'param_name'  => 'columns',
                    'value'       => array(
                        __( '2 Columns', 'pathology-booking-system' ) => '2',
                        __( '3 Columns', 'pathology-booking-system' ) => '3',
                        __( '4 Columns', 'pathology-booking-system' ) => '4',
                    ),
                    'std'         => '4',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Carousel Autoplay', 'pathology-booking-system' ),
                    'param_name'  => 'autoplay',
                    'value'       => array(
                        __( 'No', 'pathology-booking-system' )  => 'no',
                        __( 'Yes', 'pathology-booking-system' ) => 'yes',
                    ),
                    'std'         => 'no',
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by Category', 'pathology-booking-system' ),
                    'param_name'  => 'category_id',
                    'value'       => $cat_options,
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by Health Condition', 'pathology-booking-system' ),
                    'param_name'  => 'condition_id',
                    'value'       => $cond_options,
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by City', 'pathology-booking-system' ),
                    'param_name'  => 'city_id',
                    'value'       => $city_options,
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
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by State', 'pathology-booking-system' ),
                    'param_name'  => 'state_id',
                    'value'       => $state_options,
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __( 'Filter by City', 'pathology-booking-system' ),
                    'param_name'  => 'city_id',
                    'value'       => $city_options,
                ),
            ),
        ) );
    }
}

// Initialize WPBakery Integration
PTBS_WPBakery::get_instance();
