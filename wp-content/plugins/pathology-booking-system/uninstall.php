<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Delete custom plugin options.
delete_option( 'ptbs_settings' );

// Drop custom database tables (if clean cleanup is enabled).
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ptbs_city_prices" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ptbs_booking_items" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ptbs_bookings" );
