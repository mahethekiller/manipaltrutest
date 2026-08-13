<?php
/**
 * Dynamic Header Redesign Functions, Hooks & AJAX Handlers
 *
 * @package Ekko Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue Header Custom Styles, Scripts & FontAwesome CDN
 */
function ekko_child_header_assets() {
    // Enqueue FontAwesome 4.7 CDN for complete icon reliability
    wp_enqueue_style(
        'font-awesome-cdn',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        array(),
        '4.7.0'
    );

    // Enqueue FontAwesome 6 CDN for modern icons
    wp_enqueue_style(
        'font-awesome-6-cdn',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Custom Header CSS
    wp_enqueue_style(
        'ekko-child-custom-header',
        get_stylesheet_directory_uri() . '/css/header-custom.css',
        array( 'bootstrap', 'keydesign-style', 'child-style' ),
        '1.2.0'
    );

    // Custom Header JS
    wp_enqueue_script(
        'ekko-child-custom-header-js',
        get_stylesheet_directory_uri() . '/js/header-custom.js',
        array( 'jquery' ),
        '1.2.0',
        true
    );

    // Localize AJAX variables for live search & city switcher
    wp_localize_script( 'ekko-child-custom-header-js', 'ptbsHeaderData', array(
        'ajaxurl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'ptbs_header_nonce' ),
        'home_url'  => home_url( '/' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'ekko_child_header_assets', 25 );

/**
 * Helper: Get dynamic list of available diagnostic cities
 */
function ptbs_child_get_all_cities() {
    $cities = array();

    // Check taxonomy ptbs_city terms
    if ( taxonomy_exists( 'ptbs_city' ) ) {
        $terms = get_terms( array(
            'taxonomy'   => 'ptbs_city',
            'hide_empty' => false,
        ) );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $cities[] = $term->name;
            }
        }
    }

    // Default diagnostic cities list
    if ( empty( $cities ) ) {
        $cities = array( 'Noida', 'Deoghar', 'Ranchi', 'Patna', 'Kolkata', 'Delhi', 'Mumbai', 'Gurugram' );
    }

    return apply_filters( 'ptbs_child_header_cities', $cities );
}

/**
 * Helper: Get selected city name from cookie or default
 */
function ptbs_child_get_selected_city() {
    if ( isset( $_COOKIE['ptbs_selected_city'] ) && ! empty( $_COOKIE['ptbs_selected_city'] ) ) {
        return sanitize_text_field( $_COOKIE['ptbs_selected_city'] );
    }
    $cities = ptbs_child_get_all_cities();
    return ! empty( $cities ) ? $cities[0] : 'Noida';
}

/**
 * Helper: Get Pathology / WooCommerce Cart Items Count dynamically
 */
function ptbs_child_get_cart_count() {
    if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
        return WC()->cart->get_cart_contents_count();
    }
    if ( isset( $_SESSION['ptbs_cart'] ) && is_array( $_SESSION['ptbs_cart'] ) ) {
        return count( $_SESSION['ptbs_cart'] );
    }
    return 0;
}

/**
 * Dynamic Nav Menu Filter: Inject FontAwesome icons into WP Nav Menus
 */
function ekko_child_nav_menu_icons( $title, $item, $args, $depth ) {
    $clean_title = strtolower( trim( strip_tags( $title ) ) );
    $icon_class  = '';

    if ( strpos( $clean_title, 'blood' ) !== false || strpos( $clean_title, 'test' ) !== false ) {
        $icon_class = 'fa-flask';
    } elseif ( strpos( $clean_title, 'package' ) !== false || strpos( $clean_title, 'health' ) !== false ) {
        $icon_class = 'fa-plus-square-o';
    } elseif ( strpos( $clean_title, 'radiology' ) !== false || strpos( $clean_title, 'scan' ) !== false || strpos( $clean_title, 'x-ray' ) !== false ) {
        $icon_class = 'fa-heartbeat';
    } elseif ( strpos( $clean_title, 'center' ) !== false || strpos( $clean_title, 'locator' ) !== false || strpos( $clean_title, 'location' ) !== false ) {
        $icon_class = 'fa-map-marker';
    } elseif ( strpos( $clean_title, 'custom' ) !== false ) {
        $icon_class = 'fa-sliders';
    } elseif ( strpos( $clean_title, 'report' ) !== false ) {
        $icon_class = 'fa-file-text-o';
    } elseif ( strpos( $clean_title, 'more' ) !== false ) {
        $icon_class = 'fa-th-large';
    } elseif ( strpos( $clean_title, 'home' ) !== false ) {
        $icon_class = 'fa-home';
    } elseif ( strpos( $clean_title, 'shop' ) !== false || strpos( $clean_title, 'cart' ) !== false ) {
        $icon_class = 'fa-shopping-bag';
    }

    if ( ! empty( $icon_class ) && strpos( $title, 'fa-' ) === false && 0 === $depth ) {
        $title = '<i class="fa ' . esc_attr( $icon_class ) . ' nav-icon"></i> ' . $title;
    }
    return $title;
}
add_filter( 'nav_menu_item_title', 'ekko_child_nav_menu_icons', 10, 4 );

/**
 * AJAX Handler: Dynamic Live Search Auto-Suggest for Tests & Packages
 */
function ptbs_ajax_live_search() {
    check_ajax_referer( 'ptbs_header_nonce', 'nonce' );

    $query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';

    if ( strlen( $query ) < 2 ) {
        wp_send_json_error( array( 'message' => 'Query too short' ) );
    }

    $results = array();

    // Query ptbs_test, ptbs_package, product, and posts
    $args = array(
        'post_type'      => array( 'ptbs_test', 'ptbs_package', 'product', 'post' ),
        'post_status'    => 'publish',
        's'              => $query,
        'posts_per_page' => 6,
    );

    $search_query = new WP_Query( $args );

    if ( $search_query->have_posts() ) {
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
            $post_id   = get_the_ID();
            $post_type = get_post_type();
            $price     = get_post_meta( $post_id, '_ptbs_test_price', true );
            if ( empty( $price ) && function_exists( 'wc_get_product' ) ) {
                $product = wc_get_product( $post_id );
                if ( $product ) {
                    $price = $product->get_price();
                }
            }

            $badge = ( 'ptbs_package' === $post_type ) ? 'PACKAGE' : ( ('ptbs_test' === $post_type) ? 'TEST' : 'DIAGNOSTIC' );

            $results[] = array(
                'id'        => $post_id,
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'price'     => ! empty( $price ) ? '₹' . number_format( floatval( $price ), 2 ) : '',
                'badge'     => $badge,
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_ptbs_live_search', 'ptbs_ajax_live_search' );
add_action( 'wp_ajax_nopriv_ptbs_live_search', 'ptbs_ajax_live_search' );

/**
 * AJAX Handler: Dynamic City Selection Cookie Update
 */
function ptbs_ajax_set_city() {
    check_ajax_referer( 'ptbs_header_nonce', 'nonce' );

    $city = isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : 'Noida';
    setcookie( 'ptbs_selected_city', $city, time() + ( 86400 * 30 ), COOKIEPATH, COOKIE_DOMAIN );

    wp_send_json_success( array( 'city' => $city ) );
}
add_action( 'wp_ajax_ptbs_set_city', 'ptbs_ajax_set_city' );
add_action( 'wp_ajax_nopriv_ptbs_set_city', 'ptbs_ajax_set_city' );
