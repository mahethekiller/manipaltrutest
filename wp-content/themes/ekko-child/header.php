<?php
/**
 * Fully Dynamic Ekko Child Header Template (Manipal TRUtest Style)
 *
 * @package Ekko Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$website_url   = home_url();
$selected_city = function_exists( 'ptbs_child_get_selected_city' ) ? ptbs_child_get_selected_city() : 'Noida';
$all_cities    = function_exists( 'ptbs_child_get_all_cities' ) ? ptbs_child_get_all_cities() : array( 'Noida', 'Deoghar', 'Ranchi', 'Patna' );
$cart_count    = function_exists( 'ptbs_child_get_cart_count' ) ? ptbs_child_get_cart_count() : 0;

// Dynamic Account Page URL
$account_url = wp_login_url();
if ( is_user_logged_in() ) {
    if ( class_exists( 'WooCommerce' ) ) {
        $account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
    } else {
        $account_url = admin_url( 'profile.php' );
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'custom-manipal-header-active' ); ?>>
<?php wp_body_open(); ?>

<header id="custom-site-header" class="custom-header-wrapper">
    <!-- Top White Header Tier -->
    <div class="top-header-tier">
        <div class="container top-header-container">
            
            <!-- Logo & Location Group -->
            <div class="header-brand-wrap">
                <a href="<?php echo esc_url( $website_url ); ?>" class="custom-header-logo">
                    <?php
                    $primary_logo = function_exists( 'ekko_get_option' ) ? ekko_get_option( 'tek-logo' ) : false;
                    if ( isset( $primary_logo['url'] ) && ! empty( $primary_logo['url'] ) ) {
                        echo '<img src="' . esc_url( $primary_logo['url'] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="main-logo-img" />';
                    } else {
                        // Diagnostic Brand Text / Fallback Logo
                        ?>
                        <div class="manipal-logo-flex">
                            <svg class="logo-symbol-svg" viewBox="0 0 100 100" width="36" height="36" fill="none">
                                <circle cx="50" cy="25" r="14" fill="#e05423"/>
                                <path d="M25 45 C 25 35, 75 35, 75 45 L 75 80 C 75 85, 65 85, 65 80 L 65 60 L 55 60 L 55 80 C 55 85, 45 85, 45 80 L 45 60 L 35 60 L 35 80 C 35 85, 25 85, 25 80 Z" fill="#e05423"/>
                            </svg>
                            <div class="logo-text-stack">
                                <span class="brand-text-logo"><span class="brand-manipal">manipal</span> <span class="brand-tru">TRU</span><span class="brand-test">test</span></span>
                                <span class="brand-tagline">FAST. ACCURATE. RELIABLE. <i class="fa fa-angle-double-right"></i></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </a>
                
                <span class="header-vertical-divider"></span>

                <!-- Dynamic Location Selector Pill with Dropdown -->
                <div class="location-selector-pill-wrap">
                    <div class="location-selector-pill" id="locationSelectorPill" title="Change Location">
                        <span class="location-icon-circle">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </span>
                        <div class="location-text-group">
                            <span class="location-label">Location</span>
                            <span class="location-value" id="currentLocationText"><?php echo esc_html( $selected_city ); ?> <i class="fa fa-chevron-down location-arrow"></i></span>
                        </div>
                    </div>

                    <!-- Dynamic City Dropdown Popover -->
                    <div class="location-dropdown-popover" id="locationDropdownPopover">
                        <div class="popover-header">
                            <span>Select Diagnostic City</span>
                            <span class="close-popover" id="closeCityPopover">&times;</span>
                        </div>
                        <ul class="city-list">
                            <?php foreach ( $all_cities as $city ) : ?>
                                <li class="city-item <?php echo ( $city === $selected_city ) ? 'active' : ''; ?>" data-city="<?php echo esc_attr( $city ); ?>">
                                    <i class="fa fa-map-marker-alt"></i> <?php echo esc_html( $city ); ?>
                                    <?php if ( $city === $selected_city ) : ?>
                                        <i class="fa fa-check check-icon"></i>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Central Live Search Bar -->
            <div class="header-search-wrap">
                <form role="search" method="get" class="custom-header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-input-group">
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" name="s" id="headerSearchInput" class="custom-search-field" placeholder="Search tests, packages, diagnostic..." value="<?php echo get_search_query(); ?>" autocomplete="off" />
                        <span class="search-spinner" id="searchSpinner" style="display: none;"><i class="fa fa-spinner fa-spin"></i></span>
                    </div>
                </form>
                <!-- Dynamic Live Search Suggestions Overlay -->
                <div class="live-search-suggestions" id="liveSearchSuggestions"></div>
            </div>

            <!-- Right Actions Group -->
            <div class="header-actions-wrap">
                <!-- Dynamic Language Selector -->
                <div class="lang-selector-pill-wrap">
                    <div class="lang-selector-pill" id="langSelectorPill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                        <span class="lang-text">English</span>
                        <i class="fa fa-chevron-down lang-arrow"></i>
                    </div>
                </div>

                <!-- Notifications Icon -->
                <button type="button" class="header-circle-btn" id="headerNotifBtn" title="Notifications">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </button>

                <!-- Dynamic Shopping Bag / Cart Icon (Pathology Booking) -->
                <a href="javascript:void(0);" class="header-circle-btn header-cart-btn" id="headerCartBtn" title="Pathology Cart">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span class="cart-count-badge" id="headerCartBadge" style="<?php echo ( $cart_count > 0 ) ? 'display:flex;' : 'display:none;'; ?>"><?php echo esc_html( $cart_count ); ?></span>
                </a>

                <span class="header-vertical-divider action-divider"></span>

                <!-- Dynamic Sign In / Profile Dropdown Button -->
                <?php if ( is_user_logged_in() ) : 
                    $current_user  = wp_get_current_user();
                    $ptbs_settings = get_option( 'ptbs_settings', array() );
                    $dashboard_url = ! empty( $ptbs_settings['dashboard_page_url'] ) ? $ptbs_settings['dashboard_page_url'] : home_url( '/patient-dashboard/' );
                ?>
                    <div class="user-profile-dropdown-wrap" style="position: relative; display: inline-block;">
                        <button type="button" class="btn-sign-in ptbs-profile-dropdown-toggle" id="ptbsProfileBtn" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; border: none; background: #2563eb; color: #ffffff; padding: 10px 18px; border-radius: 8px; font-weight: 600;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span><?php echo esc_html( strtoupper( $current_user->display_name ) ); ?></span>
                            <i class="fa fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                        </button>
                        <div class="ptbs-profile-dropdown-menu" id="ptbsProfileMenu" style="display: none; position: absolute; right: 0; top: 110%; background: #ffffff; min-width: 200px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border-radius: 10px; padding: 8px 0; z-index: 99999; border: 1px solid #e2e8f0;">
                            <a href="<?php echo esc_url( $dashboard_url ); ?>" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #1e293b; text-decoration: none; font-size: 13px; font-weight: 600;">
                                📋 <?php esc_html_e( 'My Bookings & Profile', 'pathology-booking-system' ); ?>
                            </a>
                            <div style="height: 1px; background: #f1f5f9; margin: 4px 0;"></div>
                            <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; color: #ef4444; text-decoration: none; font-size: 13px; font-weight: 600;">
                                🚪 <?php esc_html_e( 'Log Out', 'pathology-booking-system' ); ?>
                            </a>
                        </div>
                    </div>
                <?php else : ?>
                    <button type="button" class="btn-sign-in" id="ptbsHeaderAuthBtn" style="cursor: pointer; border: none; background: #2563eb; color: #ffffff; padding: 10px 18px; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> SIGN IN
                    </button>
                <?php endif; ?>

                <!-- Mobile Hamburger Toggle -->
                <button type="button" class="mobile-menu-toggle-btn" id="mobileNavToggle" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Bottom Deep Blue Navigation Tier (Rendered dynamically from Admin Menus ID 23) -->
    <div class="bottom-header-tier">
        <div class="container bottom-header-container">
            
            <nav class="bottom-nav-wrap" id="bottomNavWrap">
                <?php
                // Render Admin Menu ID 23 dynamically (wp-admin/nav-menus.php?action=edit&menu=23)
                $menu_args = array(
                    'menu'           => 23,
                    'theme_location' => 'header-menu',
                    'container'      => false,
                    'menu_class'     => 'bottom-nav-list',
                    'depth'          => 2,
                    'fallback_cb'    => false,
                );

                if ( wp_get_nav_menu_object( 23 ) || has_nav_menu( 'header-menu' ) ) {
                    wp_nav_menu( $menu_args );
                } else {
                    // Fallback to active WP menus or default diagnostic navigation
                    wp_nav_menu( array(
                        'theme_location' => 'header-menu',
                        'container'      => false,
                        'menu_class'     => 'bottom-nav-list',
                        'depth'          => 2,
                    ) );
                }
                ?>
            </nav>

            <!-- Dynamic Book Now CTA Button -->
            <?php
            $ptbs_settings   = get_option( 'ptbs_settings', array() );
            $booking_page_url = ! empty( $ptbs_settings['booking_page_url'] ) ? $ptbs_settings['booking_page_url'] : home_url( '/lab/' );
            ?>
            <div class="bottom-cta-wrap">
                <a href="<?php echo esc_url( $booking_page_url ); ?>" class="btn-book-now" id="btnBookNow">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> BOOK NOW
                </a>
            </div>

        </div>
    </div>
</header>

<div id="wrapper" class="header-redesign-wrapper">
