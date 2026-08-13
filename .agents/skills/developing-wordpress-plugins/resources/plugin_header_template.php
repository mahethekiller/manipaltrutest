<?php
/**
 * Plugin Name:       My Unique Plugin Name
 * Plugin URI:        https://example.com/plugins/my-unique-plugin/
 * Description:       A brief description of what the plugin does.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Your Name / Company
 * Author URI:        https://example.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-unique-plugin
 * Domain Path:       /languages
 */

// Abort if called directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Plugin Constants.
define( 'MY_UNIQUE_PLUGIN_VERSION', '1.0.0' );
define( 'MY_UNIQUE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_UNIQUE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin text domain for i18n translations.
 */
function my_unique_plugin_load_textdomain() {
    load_plugin_textdomain(
        'my-unique-plugin',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'my_unique_plugin_load_textdomain' );

/**
 * Activation hook callback.
 */
function my_unique_plugin_activate() {
    // Perform one-time activation tasks (e.g., set default options, flush rewrite rules).
}
register_activation_hook( __FILE__, 'my_unique_plugin_activate' );

/**
 * Deactivation hook callback.
 */
function my_unique_plugin_deactivate() {
    // Perform deactivation tasks (e.g., clear scheduled cron events).
}
register_deactivation_hook( __FILE__, 'my_unique_plugin_deactivate' );

/**
 * Core initialization logic.
 */
function my_unique_plugin_init() {
    // Register custom post types, taxonomies, shortcodes, or enqueue scripts here.
}
add_action( 'init', 'my_unique_plugin_init' );
