<?php
/**
 * Ekko Child Theme functions.php example
 *
 * @package Ekko Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue Parent and Child Theme Styles & Scripts
 */
function ekko_child_enqueue_assets() {
    // Parent theme main stylesheet
    wp_enqueue_style( 'ekko-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Child theme stylesheet
    wp_enqueue_style(
        'ekko-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'ekko-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );

    // Custom Child JS
    wp_enqueue_script(
        'ekko-child-script',
        get_stylesheet_directory_uri() . '/js/custom-child.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'ekko_child_enqueue_assets', 20 );

/**
 * Custom Filter: Modify Portfolio Query Order
 */
function ekko_child_custom_portfolio_order( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'portfolio' ) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'ekko_child_custom_portfolio_order' );

/**
 * Custom Action: Add Custom Content to Ekko Footer
 */
function ekko_child_add_footer_credits() {
    echo '<div class="ekko-child-footer-note text-center"><p>Customized with Ekko Theme Skill</p></div>';
}
add_action( 'ekko_before_footer', 'ekko_child_add_footer_credits' );
