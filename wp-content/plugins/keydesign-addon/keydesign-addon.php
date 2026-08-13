<?php
/*
	Plugin Name: KeyDesign Addon
	Plugin URI: http://keydesign-themes.com/
	Author: KeyDesign
	Author URI: http://keydesign-themes.com/
	Version: 6.4
	Description: KeyDesign Core Plugin for Ekko Theme
	Text Domain: keydesign
*/

/*
	If accesed directly, exit.
*/
if (!defined('ABSPATH')) die('-1');
if (!defined('KEYDESIGN_PLUGIN_PATH')){
	define('KEYDESIGN_PLUGIN_PATH', dirname(__FILE__));
}

	/* Load admin area */
	require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/admin/admin-init.php' );

	/* Import OCDI files */
	require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/vendors/ocdi/one-click-demo-import.php' );

	/* Theme demo import config */
	require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/admin/theme-demo-config.php' );

	/* Widgets */
	require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/widgets/widgets-init.php');

	/* Custom menu meta fields */
	require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/admin/wp-custom-menu-meta.php' );

	/* Theme page meta boxes  */
	if ( ! file_exists( get_stylesheet_directory() . '/core/theme-pagemeta.php' ) ) {
		require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'theme/admin/theme-pagemeta.php' );
	}

	add_action('admin_init','initiate_keydesign_addon');
	function initiate_keydesign_addon() {
		if( defined('WPB_VC_VERSION') ){
			if( version_compare( 7.0, WPB_VC_VERSION, '>' )){
				add_action( 'admin_notices', 'keydesign_version_notice' );
				add_action( 'network_admin_notices','keydesign_version_notice' );
			}
		} else {
			add_action( 'admin_notices', 'keydesign_activation_notice' );
			add_action( 'network_admin_notices','keydesign_activation_notice' );
		}
	}

	function keydesign_version_notice() {
		$is_multisite = is_multisite();
		$is_network_admin = is_network_admin();
		if( ( $is_multisite && $is_network_admin ) || !$is_multisite ) {
			echo '<div class="updated">
				<p>'.__('The','keydesign').' <strong>Keydesign Addon</strong> '.__('plugin requires','keydesign').' <strong>WPBakery Page Builder</strong> '.__('version 7.0 or greater.','keydesign').'</p>
			</div>';
		}
	}

	function keydesign_activation_notice() {
		$is_multisite = is_multisite();
		$is_network_admin = is_network_admin();
		if( ( $is_multisite && $is_network_admin) || !$is_multisite ) {
			echo '<div class="updated">
				<p>'.__('The','keydesign').' <strong>KeyDesign Addon</strong> '.__('plugin requires','keydesign').' <strong>WPBakery Page Builder</strong> '.__('Plugin installed and activated.','keydesign').'</p>
			</div>';
		}
	}

	/* Allow SVG icon upload */
	add_filter( 'upload_mimes', 'keydesign_svg_upload' );
	function keydesign_svg_upload( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/* Contact form 7 shortcode init */
	add_action( 'plugins_loaded', 'kd_init_vendor_cf7' );
	function kd_init_vendor_cf7() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || defined( 'WPCF7_PLUGIN' ) ) {
			require_once ( plugin_dir_path( __FILE__ ).'elements/vendors/vendor-contact-form-7.php' );
		} // if contact form7 plugin active
	}

	/* Before VC init */
	add_action( 'vc_before_init', 'keydesign_vc_before_init_actions' );
	function keydesign_vc_before_init_actions() {
		// Force WPBakery Page Builder to initialize as "built into the theme"
		if( function_exists('vc_set_as_theme') ){
			vc_set_as_theme();
		}

		// Link VC elements's folder
    if( function_exists('vc_set_shortcodes_templates_dir') ){
      vc_set_shortcodes_templates_dir( plugin_dir_path( __FILE__ ).'templates/vc-elements' );
		}
	}

	/* After VC init */
	add_action( 'vc_after_init', 'keydesign_vc_after_init_actions' );
	function keydesign_vc_after_init_actions() {

	// Enable VC by default on a list of Post Types
	if ( get_option( 'kd-default-post-types' ) != 'yes' ) {
	if( function_exists('vc_set_default_editor_post_types') ) {
        $list = array(
            'page',
            'post',
            'portfolio',
        );
        vc_set_default_editor_post_types( $list );
		vc_editor_set_post_types($list);
    }
  		 update_option( 'kd-default-post-types', 'yes' );
    }


    if( function_exists('vc_remove_param') ){
      vc_remove_param( 'vc_masonry_grid', 'initial_loading_animation' );
			vc_remove_param( 'vc_masonry_grid', 'filter_color' );
			vc_remove_param( 'vc_masonry_grid', 'filter_size' );
    }

		if ( function_exists('vc_add_param') ) {

        //Add parameters to vc_row
        $base = 'vc_row';

        $attributes = array(
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Fixed background', 'keydesign' ),
						'param_name' => 'kd_fixed_background',
						'description' => esc_html__( 'If checked the background image stays fixed.', 'keydesign' ),
						'group' => esc_html__( 'Background', 'keydesign' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Image overlay', 'keydesign' ),
						'param_name' => 'kd_image_overlay',
						'description' => esc_html__( 'If checked a layer will be applied over the row background image.', 'keydesign' ),
						'group' => esc_html__( 'Background', 'keydesign' ),
					),
					array(
						'type' => 'colorpicker',
						'class' => '',
						'heading' => esc_html__('Overlay color', 'keydesign'),
						'param_name' => 'kd_image_overlay_color',
						'value' => '',
						'dependency' => array(
							 'element' => 'kd_image_overlay',
							 'value'	=> 'true',
				 		),
					 	'group' => esc_html__( 'Background', 'keydesign' ),
					),
					array(
						'type' => 'kd_param_title',
						'text' => 'Top separator',
						'description' => esc_html__( 'Configure top row separator.', 'keydesign' ),
						'param_name' => 'top_section_title',
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Enable top separator', 'keydesign' ),
						'param_name' => 'kd_top_separator',
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'keydesign' ),
						'param_name' => 'kd_top_separator_style',
						'value' =>	array(
								'Rounded up' => 'rounded-up',
								'Rounded down' => 'rounded-down',
								'Skew left' => 'skew-left',
								'Skew right' => 'skew-right',
								'Big triangle down' => 'arrow-down',
								'Big triangle up' => 'arrow-up',
								'Big triangle left' => 'triangle-left',
								'Big triangle right' => 'triangle-right',
								'Small triangle center' => 'small-triangle',
						),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							 'element' => 'kd_top_separator',
							 'value'	=> 'true',
				 		),
						'save_always' => true,
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'colorpicker',
						'class' => '',
						'heading' => esc_html__('Background', 'keydesign'),
						'param_name' => 'kd_top_separator_bg',
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							 'element' => 'kd_top_separator',
							 'value'	=> 'true',
				 		),
					 	'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Height', 'keydesign' ),
						'param_name' => 'kd_top_separator_height',
						'value' =>	array(
								'Small (50px)' => 'separator-height-small',
								'Medium (100px)' => 'separator-height-medium',
								'Large (150px)' => 'separator-height-large',
						),
						'dependency' => array(
							 'element' => 'kd_top_separator',
							 'value'	=> 'true',
				 		),
						'save_always' => true,
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'kd_param_title',
						'text' => 'Bottom separator',
						'description' => esc_html__( 'Configure bottom row separator.', 'keydesign' ),
						'param_name' => 'bottom_section_title',
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Enable bottom separator', 'keydesign' ),
						'param_name' => 'kd_bottom_separator',
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'keydesign' ),
						'param_name' => 'kd_bottom_separator_style',
						'value' =>	array(
								'Rounded up' => 'rounded-up',
								'Rounded down' => 'rounded-down',
								'Skew left' => 'skew-left',
								'Skew right' => 'skew-right',
								'Big triangle down' => 'arrow-down',
								'Big triangle up' => 'arrow-up',
								'Big triangle left' => 'triangle-left',
								'Big triangle right' => 'triangle-right',
								'Small triangle center' => 'small-triangle',
						),
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							 'element' => 'kd_bottom_separator',
							 'value'	=> 'true',
				 		),
						'save_always' => true,
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'colorpicker',
						'class' => '',
						'heading' => esc_html__('Background', 'keydesign'),
						'param_name' => 'kd_bottom_separator_bg',
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array(
							 'element' => 'kd_bottom_separator',
							 'value'	=> 'true',
				 		),
					 	'group' => esc_html__( 'Separator', 'keydesign' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Height', 'keydesign' ),
						'param_name' => 'kd_bottom_separator_height',
						'value' =>	array(
								'Small (50px)' => 'separator-height-small',
								'Medium (100px)' => 'separator-height-medium',
								'Large (150px)' => 'separator-height-large',
						),
						'dependency' => array(
							 'element' => 'kd_bottom_separator',
							 'value'	=> 'true',
				 		),
						'save_always' => true,
						'group' => esc_html__( 'Separator', 'keydesign' ),
					),
				);

				foreach ($attributes as $attribute) {
					vc_add_param( $base, $attribute );
				}

    	}
	}

	if ( ! function_exists( 'kd_output_post_socials' ) ) {
		function kd_output_post_socials() {
			$redux_ThemeTek = get_option( 'redux_ThemeTek' );
			$socials_sharing_content = '<div class="blog-social-sharing">';
			if (isset($redux_ThemeTek['tek-blog-social-sharing-buttons']) && $redux_ThemeTek['tek-blog-social-sharing-buttons']['1'] == '1') {
				$socials_sharing_content .= '<a class="share_button btn-facebook" title="'.apply_filters( 'blog_share_facebook', esc_attr__("Share on Facebook", "keydesign") ).'" target="_blank" href="//www.facebook.com/sharer/sharer.php?u='.get_permalink().'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg></a>';
			}
			if (isset($redux_ThemeTek['tek-blog-social-sharing-buttons']) && $redux_ThemeTek['tek-blog-social-sharing-buttons']['2'] == '1') {
				$socials_sharing_content .= '<a class="share_button btn-twitter-x" title="'.apply_filters( 'blog_share_x', esc_attr__("Share on X", "keydesign") ).'" target="_blank" href="//x.com/intent/post?url='.get_permalink().'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg></a>';
			}
			if (isset($redux_ThemeTek['tek-blog-social-sharing-buttons']) && $redux_ThemeTek['tek-blog-social-sharing-buttons']['3'] == '1') {
				$socials_sharing_content .= '<a class="share_button btn-pinterest" title="'.apply_filters( 'blog_share_pinterest', esc_attr__("Share on Pinterest", "keydesign") ).'" target="_blank" href="https://pinterest.com/pin/create/link/?url='.get_permalink().'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg></a>';
			}
			if (isset($redux_ThemeTek['tek-blog-social-sharing-buttons']) && $redux_ThemeTek['tek-blog-social-sharing-buttons']['4'] == '1') {
				$socials_sharing_content .= '<a class="share_button btn-linkedin" title="'.apply_filters( 'blog_share_linkedin', esc_attr__("Share on LinkedIn", "keydesign") ).'" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url='.get_permalink().'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg></a>';
			}
			$socials_sharing_content .= '</div>';
			echo $socials_sharing_content;
		}
	}
	add_filter( 'ekko_post_after_main_content', 'kd_output_post_socials', 30, 0 );

	if ( ! function_exists( 'keydesign_photoswipe' ) ) {
		function keydesign_photoswipe() {
			if ( file_exists( dirname( __FILE__ ) . '/elements/templates/photoswipe.php' ) ) {
        require_once dirname( __FILE__ ) . '/elements/templates/photoswipe.php';
    	}
		}
	}

	/* Disable notifications */
	function disable_wpb_notice_list() {
		$existing_value = get_transient( 'wpb_notice_list' );
		$new_value = array( 'empty_api_response' => TRUE );
		
		if ( $existing_value !== $new_value ) {
			set_transient( 'wpb_notice_list', $new_value, 0 );
		}
	}
	add_action( 'init', 'disable_wpb_notice_list', 99 );

if (!class_exists('KEYDESIGN_ADDON_CLASS')) {
	class KEYDESIGN_ADDON_CLASS {
		public $elements_folder;
		public $templates_folder;
		public $params_dir;

		function __construct() {
			$this->elements_folder	=	plugin_dir_path( __FILE__ ).'elements/';
			$this->templates_folder	=	plugin_dir_path( __FILE__ ).'templates/';
			$this->params_dir = plugin_dir_path( __FILE__ ).'params/';
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ), 5 );
			add_action( 'after_setup_theme', array( $this, 'integrate_with_vc' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'keydesign_load_front_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'keydesign_compatibility_enqueue' ), 100 );
			add_action( 'vc_load_iframe_jscss', array( $this, 'keydesign_load_front_editor_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'keydesign_load_admin_scripts') );
			add_action( 'init', array( $this, 'keydesign_init_portfolio_cpt' ) );
			add_action( 'init', array( $this, 'keydesign_templates' ), 5 );
		}

		public function load_plugin_textdomain() {
			$lang_dir = plugin_dir_path( __FILE__ ) . '/languages/';
			load_plugin_textdomain( 'keydesign', false, $lang_dir );
		}

		public function keydesign_templates() {
			if( class_exists('WPBakeryShortCode') && get_option( 'keydesign-verify' ) == 'yes' ){
				require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'templates/templates-init.php' );
				require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'templates-panel.php' );
				$KeyDesignTemplates = new KeyDesign_Vc_Templates_Panel_Editor();
				return $KeyDesignTemplates->init();
			}
		}

		public function keydesign_init_portfolio_cpt() {
			if ( function_exists( 'ekko_get_option' ) ) {
				if ( ekko_get_option( 'tek-portfolio-cpt' ) ) {
					require_once ( trailingslashit( KEYDESIGN_PLUGIN_PATH ) . 'custom-post-type.php' );
				}
			}
		}

		public function integrate_with_vc() {
			if( class_exists( 'WPBakeryShortCode' ) ) {
				foreach(glob($this->elements_folder."/*.php") as $elem) {
					require_once($elem);
				}
				foreach(glob($this->params_dir."/*.php") as $param)
				{
					require_once($param);
				}
			}
		}

		public function keydesign_load_front_editor_scripts() {
			wp_enqueue_script( 'masonry' );
			wp_enqueue_script( 'kd_easypiechart_script', plugins_url('assets/js/jquery.easypiechart.min.js', __FILE__), array('jquery') );
			wp_enqueue_script( 'kd_easytabs_script', plugins_url('assets/js/jquery.easytabs.min.js', __FILE__), array('jquery') );
			wp_enqueue_script( 'kd_countdown_script', plugins_url('assets/js/jquery.countdown.js', __FILE__), array('jquery') );
			wp_enqueue_script( 'kd_countto', plugins_url('assets/js/kd_countto.js', __FILE__), array('jquery') );
			wp_enqueue_script( 'kd_front_editor', plugins_url('assets/js/kd_addon_front.js', __FILE__), array('jquery'),'2' );
		}

		public function keydesign_load_front_scripts() {

			// Register & Load plug-in main style sheet
			wp_register_style( 'kd-addon-style', plugins_url('assets/css/kd_vc_front.css',  __FILE__), array('keydesign-style') );
			wp_enqueue_style( 'kd-addon-style' );

			// Easing Script
			wp_register_script( 'kd_easing_script', plugins_url('assets/js/jquery.easing.min.js', __FILE__), array('jquery') );
			wp_enqueue_script ( 'kd_easing_script' );

			// Owl Carousel
			wp_register_script( 'kd_carousel_script', plugins_url('assets/js/owl.carousel.min.js', __FILE__), array('jquery') );
			wp_enqueue_script ( 'kd_carousel_script' );

			// Easy Tabs
			wp_register_script( 'kd_easytabs_script', plugins_url('assets/js/jquery.easytabs.min.js', __FILE__), array('jquery') );

	    	// Countdown Element
			wp_register_script( 'kd_countdown_script', plugins_url('assets/js/jquery.countdown.js', __FILE__), array('jquery') );

			// Pie Chart Element
			wp_register_script( 'kd_easypiechart_script', plugins_url('assets/js/jquery.easypiechart.min.js', __FILE__), array('jquery') );

			// Event session Element
			wp_register_script( 'kd_jquery_appear', plugins_url('assets/js/jquery.appear.js', __FILE__), array('jquery') );
			wp_enqueue_script ( 'kd_jquery_appear' );

			// Register & Load Photoswipe
			wp_register_style( 'photoswipe', plugins_url('assets/css/photoswipe.css', __FILE__), 'all' );
			wp_register_script( 'photoswipejs', plugins_url('assets/js/photoswipe.min.js', __FILE__), array('jquery') );

			// Progressbar element
			wp_register_script( 'kd_progressbar', plugins_url('assets/js/kd_progressbar.js', __FILE__), array('jquery') );

			// Counter element
			wp_register_script( 'kd_countto', plugins_url('assets/js/kd_countto.js', __FILE__), array('jquery') );

			// Image comparison
			wp_register_script( 'jquery_mobile_vmouse', plugins_url('assets/js/jquery.mobile.vmouse.min.js', __FILE__), array('jquery') );
			wp_register_script( 'kd_image_comparison', plugins_url('assets/js/image-comparison-slider.js', __FILE__), array('jquery_mobile_vmouse') );

			// FontAwesome font pack resources
			wp_register_style( 'font-awesome', plugins_url( 'assets/css/font-awesome.min.css', __FILE__) );

			// Plugin Front End Script
			wp_register_script( 'kd_addon_script', plugins_url('assets/js/kd_addon_script.js', __FILE__), array('jquery') );
			wp_enqueue_script ( 'kd_addon_script' );
		}

		public function keydesign_compatibility_enqueue() {
			// Load FontAwesome locally
			if ( class_exists( 'Redux' ) ) {
				wp_dequeue_script( 'font-awesome-kit' );
			}
		}

		public function keydesign_load_admin_scripts() {
			wp_enqueue_style( 'keydesign-iconsmind', plugins_url( 'assets/css/iconsmind.min.css', __FILE__ ) );
			wp_enqueue_style( 'kd_addon_backend_style', plugins_url( 'assets/admin/css/kd_vc_backend.css', __FILE__ ) );
			wp_enqueue_script( 'kd_addon_backend_script', plugins_url( 'assets/admin/js/kd_addon_backend.js', __FILE__), array( 'wp-color-picker' ), false, true );
		}

	}
}
// Finally initialize code
new KEYDESIGN_ADDON_CLASS();
