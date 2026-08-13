<?php
/**
* KeyDesign Theme Admin Panel
* Initiate the theme admin pages
*/

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class KeyDesign_Admin {
    protected $tgmpa_instance;
    protected $tgmpa_menu_slug = 'install-required-plugins';
    protected $tgmpa_url = 'admin.php?page=install-required-plugins';

    public function __construct() {
        add_action( 'init', [ $this, 'init_admin_settings' ], 7 );
        add_action( 'admin_bar_menu', [ $this, 'keydesign_admin_bar' ], 99 );
        add_action( 'redux/loaded', [ $this, 'keydesign_remove_redux_demo' ] );
        add_action( 'do_meta_boxes', [ $this, 'keydesign_remove_revslider_metabox' ] );
        add_action( 'admin_init', [ $this, 'vc_disable_update' ], 7 );

        if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
            add_action( 'init', [ $this, 'set_tgmpa_url' ], 10 );
        }

        $types = get_post_types( [], 'objects' );
        foreach ( $types as $type => $values ) {
            if ( isset( $type ) ) {
                $type_name = 'theme_' . $type . '_templates';
                add_filter( $type_name, [ $this, 'keydesign_remove_templates' ], 11 );
            }
        }
	}

    public function keydesign_remove_revslider_metabox() {
        if ( class_exists( 'RevSlider' ) ) {
            $post_types = array('post','page');
            remove_meta_box( 'slider_revolution_metabox', $post_types, 'side' );
        }
    }

    public function keydesign_remove_templates( $page_templates ) {
        if ( class_exists( 'Redux' ) ) {
            unset( $page_templates['redux-templates_contained'] );
            unset( $page_templates['redux-templates_full_width'] );
            unset( $page_templates['redux-templates_canvas'] );
        }
        if ( class_exists( 'RevSlider' ) ) {
            unset( $page_templates['../public/views/revslider-page-template.php'] );
        }
        return $page_templates;
    }

    public function init_admin_settings() {
        if ( !current_user_can( 'edit_theme_options' ) ) {
            return;
        }
        add_action( 'admin_menu', [ $this, 'keydesign_add_admin_menu' ], 9 );

        if ( class_exists( 'Redux' ) ) {
            add_action( 'admin_menu', [ $this, 'keydesign_remove_redux_menu' ], 11 );
        }
    }

    public function set_tgmpa_url() {
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        $this->$tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
        $this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
        $this->tgmpa_menu_slug = apply_filters( 'ekko_tgmpa_menu_slug', 'install-required-plugins' );

        $this->tgmpa_url = apply_filters( 'ekko_tgmpa_url', 'admin.php?page=' . $this->tgmpa_menu_slug );
    }

    public function keydesign_add_admin_menu() {
        $page_menu_func = [ $this, 'menu_callback' ];
        add_menu_page( esc_html__('Ekko Dashboard', 'keydesign'), esc_html__('Ekko', 'keydesign'), 'manage_options', 'ekko-dashboard', '', 'dashicons-welcome-widgets-menus', 2 );
        add_submenu_page( 'ekko-dashboard', 'Ekko Dashboard', 'Dashboard', 'manage_options', 'ekko-dashboard', $page_menu_func, 0 );
    }

    public function keydesign_remove_redux_menu() {
        remove_submenu_page( 'options-general.php', 'redux-framework' );
    }

    public static function menu_callback() {
        include_once( plugin_dir_path( __FILE__ ).'views/keydesign-dashboard.php' );
    }

    public function keydesign_admin_bar( $wp_admin_bar ) {

        if ( !current_user_can( 'edit_theme_options' ) ) {
            return;
        }

		//Add parent shortcut link
		$args = array(
			'id'    => 'ekko-dashboard',
			'title' => 'Ekko',
			'href'  => admin_url( 'admin.php?page=ekko-dashboard' ),
			'meta'  => array(
				'class' => 'ekko-toolbar-page',
				'title' => 'ekko Options',
			)
		);
		$wp_admin_bar->add_node( $args );

		//Add dashboard shortcut link
		$args = array(
			'id' => 'ekko-admin',
			'title' => 'Dashboard',
			'href' => admin_url( 'admin.php?page=ekko-dashboard' ),
			'parent' => 'ekko-dashboard',
			'meta'  => array(
				'class' => 'ekko-dashboard',
				'title' => 'ekko Dashboard',
			),
		);
		$wp_admin_bar->add_node( $args );

        //Add import-demos shortcut link
        $args = array(
			'id' => 'import-demos',
			'title' => 'Import Demos',
			'href' => admin_url( 'admin.php?page=import-demos' ),
			'parent' => 'ekko-dashboard',
			'meta'  => array(
				'class' => 'import-demos',
				'title' => 'Import Demos',
			),
		);
		$wp_admin_bar->add_node( $args );

		//Add theme-options shortcut link
		if ( class_exists( 'Redux' ) ) {
			$args = array(
				'id' => 'ekko-theme-options',
				'title' => 'Theme Options',
				'href' => admin_url( 'admin.php?page=theme-options' ),
				'parent' => 'ekko-dashboard',
				'meta'  => array(
					'class' => 'ekko-theme-options',
					'title' => 'Theme Options',
				),
			);
			$wp_admin_bar->add_node( $args );
		}

        //Add install-required-plugins shortcut link
        $args = array(
			'id' => 'install-required-plugins',
			'title' => 'Install Plugins',
			'href' => admin_url( 'admin.php?page=install-required-plugins' ),
			'parent' => 'ekko-dashboard',
			'meta'  => array(
				'class' => 'install-required-plugins',
				'title' => 'Install Plugins',
			),
		);
		$wp_admin_bar->add_node( $args );

		//Add envato-market shortcut link
		if ( class_exists( 'Envato_Market' ) ) {
			$args = array(
				'id' => 'ekko-envato-market',
				'title' => 'Envato Market',
				'href' => admin_url( 'admin.php?page=envato-market' ),
				'parent' => 'ekko-dashboard',
				'meta'  => array(
					'class' => 'ekko-envato-market',
					'title' => 'Envato Market',
				),
			);
			$wp_admin_bar->add_node( $args );
		}
	}

    public function keydesign_remove_redux_demo() {
        if ( class_exists( 'Redux' ) ) {
            remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2);
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            update_option( 'use_extendify_templates', '0' );
        }
    }

    public function vc_disable_update() {
		if ( function_exists( 'vc_license' ) && function_exists( 'vc_updater' ) && ! vc_license()->isActivated() ) {
            remove_filter( 'upgrader_pre_download', array( vc_updater(), 'preUpgradeFilter' ), 10);
            remove_filter( 'pre_set_site_transient_update_plugins', array( vc_updater()->updateManager(), 'check_update' ) );
            remove_action( 'admin_notices', array( vc_license(), 'adminNoticeLicenseActivation' ) );
		}
	}
}
new KeyDesign_Admin;
