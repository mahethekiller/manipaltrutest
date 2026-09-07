<?php
/**
 * Plugin Name:       Pathology Test & Package Booking System
 * Plugin URI:        https://example.com/plugins/pathology-booking-system/
 * Description:       Complete pathology test and health package booking system with city selection, Google OAuth login, Home Collection & Lab Visit workflows, and Razorpay/PhonePe payment gateways.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Lab Tech Systems
 * Author URI:        https://example.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pathology-booking-system
 * Domain Path:       /languages
 */

// Abort if called directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Plugin Constants.
define( 'PTBS_VERSION', '1.0.0' );
define( 'PTBS_FILE', __FILE__ );
define( 'PTBS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PTBS_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include Core Classes
 */
require_header_classes();

function require_header_classes() {
    require_once PTBS_DIR_PATH . 'includes/class-ptbs-db.php';
    require_once PTBS_DIR_PATH . 'includes/class-ptbs-importer.php';
    require_once PTBS_DIR_PATH . 'includes/class-ptbs-cpt.php';
    require_once PTBS_DIR_PATH . 'includes/class-ptbs-auth.php';
    require_once PTBS_DIR_PATH . 'includes/class-ptbs-booking.php';
    require_once PTBS_DIR_PATH . 'includes/gateways/class-ptbs-razorpay.php';
    require_once PTBS_DIR_PATH . 'includes/gateways/class-ptbs-phonepe.php';
    require_once PTBS_DIR_PATH . 'admin/class-ptbs-admin.php';
    require_once PTBS_DIR_PATH . 'public/class-ptbs-public.php';
}

/**
 * Load Text Domain for i18n
 */
function ptbs_load_textdomain() {
    load_plugin_textdomain(
        'pathology-booking-system',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'ptbs_load_textdomain' );
add_action( 'init', array( 'PTBS_DB', 'create_tables' ) );

/**
 * Plugin Activation Callback
 */
function ptbs_activate_plugin() {
    // Install custom SQL database tables
    PTBS_DB::create_tables();

    // Register CPTs and flush rewrite rules
    PTBS_CPT::register_post_types();
    PTBS_CPT::register_taxonomies();
    flush_rewrite_rules();

    // Add Patient user role
    add_role(
        'ptbs_patient',
        __( 'Patient', 'pathology-booking-system' ),
        array(
            'read' => true,
        )
    );
}
register_activation_hook( __FILE__, 'ptbs_activate_plugin' );

/**
 * Plugin Deactivation Callback
 */
function ptbs_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ptbs_deactivate_plugin' );

/**
 * Initialize Plugin Logic
 */
function ptbs_init() {
    PTBS_CPT::get_instance();
    PTBS_Auth::get_instance();
    PTBS_Booking::get_instance();
    PTBS_Admin::get_instance();
    PTBS_Public::get_instance();
}
add_action( 'plugins_loaded', 'ptbs_init' );
