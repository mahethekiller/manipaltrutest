<?php
/**
 * Ekko functions file
 *
 * @package ekko
 * by KeyDesign
 */

 add_action( 'wp_enqueue_scripts', 'kd_enqueue_parent_theme_style', 5 );
 if ( ! function_exists( 'kd_enqueue_parent_theme_style' ) ) {
     function kd_enqueue_parent_theme_style() {
         wp_enqueue_style( 'bootstrap' );
         wp_enqueue_style( 'keydesign-style', get_template_directory_uri() . '/style.css', array( 'bootstrap' ) );
         wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('keydesign-style') );
     }
 }

 add_action( 'after_setup_theme', 'kd_child_theme_setup' );
 if ( ! function_exists( 'kd_child_theme_setup' ) ) {
     function kd_child_theme_setup() {
         load_child_theme_textdomain( 'ekko', get_stylesheet_directory() . '/languages' );
     }
 }

 // -------------------------------------
 // Edit below this line
 // -------------------------------------

 require_once get_stylesheet_directory() . '/inc/custom-header-functions.php';