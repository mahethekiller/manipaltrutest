<?php

if (class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_tek_extended_tabs extends WPBakeryShortCodesContainer {
	}
}

if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_tek_extended_tabs_single extends WPBakeryShortCode {
	}
}

if (!class_exists('tek_extended_tabs')) {
	class tek_extended_tabs extends KEYDESIGN_ADDON_CLASS {
		function __construct() {
			add_action('init', array($this, 'kd_extended_tabs_init'));
			add_shortcode('tek_extended_tabs', array($this, 'kd_extended_tabs_container'));
			add_shortcode('tek_extended_tabs_single', array( $this, 'kd_extended_tabs_single'));
			add_action('wp_enqueue_scripts', array($this, 'kd_load_element_scripts'));
		}

		function kd_load_element_scripts() {
			wp_enqueue_script("kd_easytabs_script");
		}

		/* VC Elements render in admin */
		function kd_extended_tabs_init() {
			if (function_exists('vc_map')) {
				vc_map(
					array(
						"name" => esc_html__("Extended tabs", "keydesign"),
						"description" => __("Vertical tabs with extended features.", "keydesign"),
						"base" => "tek_extended_tabs",
						"class" => "kd-outer-controls",
						"show_settings_on_create" => true,
						"content_element" => true,
						"as_parent" => array('only' => 'tek_extended_tabs_single'),
						"icon" => plugins_url('assets/element_icons/extended-tabs.png', dirname(__FILE__)),
						"category" => esc_html__("KeyDesign Elements", "keydesign"),
						"js_view" => 'VcColumnView',
						"params" => array(
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => esc_html__("Tabs alignment","keydesign"),
								"param_name" => "extended_tabs_alignment",
								"value" => array(
									"Left image" => "tabs-image-left",
									"Right image" => "tabs-image-right",
								),
								"save_always" => true,
								"description"   =>  esc_html__("Select tabs image alignment.", "keydesign"),
							),

							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => esc_html__("Image layout","keydesign"),
								"param_name" => "extended_tabs_img_layout",
								"value" => array(
									"Contained" => "tab-img-contained",
									"Full width" => "tab-img-fullwidth",
								),
								"save_always" => true,
								"description"   =>  esc_html__("Select tabs image alignment.", "keydesign"),
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => esc_html__("CSS animation", "keydesign"),
								"param_name" => "css_animation",
								"value" => array(
									"None" => "",
									"Fade In" => "kd-animated fadeIn",
									"Fade In Down" => "kd-animated fadeInDown",
									"Fade In Left" => "kd-animated fadeInLeft",
									"Fade In Right" => "kd-animated fadeInRight",
									"Fade In Up" => "kd-animated fadeInUp",
									"Zoom In" => "kd-animated zoomIn",
								),
								"save_always" => true,
								"admin_label" => true,
								"description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "keydesign"),
								"group" => esc_html__( "Extras", "keydesign" ),
							),

							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => esc_html__("Animation delay", "keydesign"),
								"param_name" => "et_animation_delay",
								"value" => array(
									"0 ms" => "",
									"200 ms" => "200",
									"400 ms" => "400",
									"600 ms" => "600",
									"800 ms" => "800",
									"1 s" => "1000",
								),
								"save_always" => true,
								"admin_label" => true,
								"dependency" =>	array(
									"element" => "css_animation",
									"value" => array("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn")
								),
								"description" => esc_html__("Enter animation delay in ms.", "keydesign"),
								"group" => esc_html__( "Extras", "keydesign" ),
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => esc_html__("Extra class name", "keydesign"),
								"param_name" => "extended_tabs_extra_class",
								"value" => "",
								"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
								"group" => esc_html__( "Extras", "keydesign" ),
							),
						)
					)
				);

				vc_map(array(
					"name" => esc_html__("Tab", "keydesign"),
					"base" => "tek_extended_tabs_single",
					"content_element" => true,
					"as_child" => array('only' => 'tek_extended_tabs'),
					"icon" => plugins_url('assets/element_icons/child-tabs.png', dirname(__FILE__)),
					"params" => array(
						array(
							"type" => "textfield",
							"heading" => esc_html__("Tab title", "keydesign"),
							"param_name" => "tab_title",
							"value" => "",
							"admin_label" => true,
							"description" => esc_html__("Add the tab title here.", "keydesign"),
							"group" => esc_html__( "Tab", "keydesign" ),
						),
						array(
                "type" => "dropdown",
                "class" => "",
                "heading" => esc_html__("Text tags", "keydesign"),
                "param_name" => "tab_title_tag",
                "value" => array(
                    "Default" => "h2",
                    "h1" => "h1",
                    "h2" => "h2",
                    "h3" => "h3",
                    "h4" => "h4",
                    "h5" => "h5",
                    "h6" => "h6",
                ),
                "save_always" => true,
                "description" => esc_html__("Select title tag.", "keydesign"),
								"group" => esc_html__( "Tab", "keydesign" ),
            ),

						array(
							"type"			=>	"dropdown",
							"class"			=>	"",
							"heading"		=>	esc_html__("Display tab icon","keydesign"),
							"param_name"	=>	"tab_icon_type",
							"value"			=>	array(
								"No icon" => "no_icon",
								"Icon browser" => "icon_browser",
							),
							"save_always" => true,
							"description"	=>	esc_html__("Select icon source.", "keydesign"),
							"group" => esc_html__( "Tab", "keydesign" ),
						),

						array(
							"type" => "iconpicker",
							"heading" => esc_html__( "Tab Icon", "keydesign" ),
							"param_name" => "tab_icon_iconsmind",
							"settings" => array(
								"type" => "iconsmind",
								"iconsPerPage" => 50,
							),
							"dependency" => array(
								"element" => "tab_icon_type",
								"value" => "icon_browser",
							),
							"description" => esc_html__( "Select icon from library.", "keydesign" ),
							"group" => esc_html__( "Tab", "keydesign" ),
						),

						array(
							"type" => "textarea_html",
							"heading" => esc_html__("Tab content", "keydesign"),
							"param_name" => "content",
							"value" => "",
							"description" => esc_html__("Add the tab description here. This field accepts HTML tags.", "keydesign"),
							"group" => esc_html__( "Tab", "keydesign" ),
						),


						array(
							"type" => "dropdown",
							"class" => "",
							"heading" => esc_html__("Image source", "keydesign"),
							"param_name" => "image_source",
							"value" => array(
								"Media library" => "media_library",
								"External link" => "external_link",
							),
							"description" => esc_html__("Select image source.", "keydesign"),
							"save_always" => true,
							"group" => esc_html__( "Image", "keydesign" ),
						),

						array(
							"type" => "attach_image",
							"heading" => esc_html__("Image", "keydesign"),
							"param_name" => "tab_image",
							"value" => "",
							"description" => esc_html__("Select or upload your image using the media library.", "keydesign"),
							"dependency" =>	array(
								"element" => "image_source",
								"value" => array("media_library")
							),
							"group" => esc_html__( "Image", "keydesign" ),
						),

						array(
							"type" => "textfield",
							"class" => "",
							"heading" => esc_html__("Image external source", "keydesign"),
							"param_name" => "ext_image",
							"value" => "",
							"description" => esc_html__("Enter image external link.", "keydesign"),
							"dependency" =>	array(
								"element" => "image_source",
								"value" => array("external_link")
							),
							"group" => esc_html__( "Image", "keydesign" ),
						),

						array(
							"type" => "textfield",
							"class" => "",
							"heading" => esc_html__("Image size", "keydesign"),
							"param_name" => "ext_image_size",
							"value" => "",
							"description" => esc_html__("Enter image size in pixels. Example: 750x500 (Width x Height).", "keydesign"),
							"dependency" =>	array(
								"element" => "image_source",
								"value" => array("external_link")
							),
							"group" => esc_html__( "Image", "keydesign" ),
						),

						array(
							"type" => "dropdown",
							"class" => "",
							"heading" => esc_html__("Image link", "keydesign"),
							"param_name" => "link_settings",
							"value" => array(
								esc_html__( 'None', 'keydesign' ) => "",
								esc_html__( 'Custom URL', 'keydesign' ) => "custom-url",
							),
							"description" => esc_html__("Select image source.", "keydesign"),
							"save_always" => true,
							"group" => esc_html__( "Image", "keydesign" ),
						),

						array(
								"type" => "href",
								"class" => "",
								"heading" => esc_html__("Link", "keydesign"),
								"param_name" => "image_link",
								"value" => "",
								"description" => esc_html__("Enter image URL.", "keydesign"),
								"dependency" => array(
									 "element" => "link_settings",
									 "value"	=> array( "custom-url" ),
							 ),
							 "group" => esc_html__( "Image", "keydesign" ),
						),

						array(
								'type' => 'dropdown',
								'heading' => __( 'Link target', 'keydesign' ),
								'param_name' => 'link_target',
								"value" => array(
									esc_html__( 'Same window', 'keydesign' ) => '_self',
									esc_html__( 'New window', 'keydesign' ) => '_blank',
								),
								"dependency" => array(
									 "element" => "link_settings",
									 "value"	=> array( "custom-url" ),
							 ),
							 "group" => esc_html__( "Image", "keydesign" ),
						),
					)
				));
			}
		}

		public function kd_extended_tabs_container($atts, $content = null) {

			extract(shortcode_atts(array(
				'extended_tabs_alignment' => '',
				'extended_tabs_img_layout' => '',
				'css_animation' => '',
				'et_animation_delay' => '',
				'extended_tabs_extra_class' => '',
			), $atts));

			$output = $animation_delay = $wrapper_class = '';

			$kd_tabs_id = "kd-tabswrapper-".uniqid(rand(10000,99999));

			// Animation delay
			if ($et_animation_delay) {
				$animation_delay = 'data-animation-delay='.$et_animation_delay;
			}

			$wrapper_class = implode(' ', array('features-tabs', $kd_tabs_id, $extended_tabs_alignment, $extended_tabs_img_layout, $css_animation, $extended_tabs_extra_class));

			$output = '<div class="'.trim($wrapper_class).'" '.$animation_delay.'>
				' . do_shortcode($content) . '
				<ul class="tab-controls"></ul>
			</div>';

			if  ($content != '' ) {
				return $output;
			}
		}

		public function kd_extended_tabs_single($atts, $content = null) {
			extract(shortcode_atts(array(
				'tab_title' => '',
				'tab_title_tag' => '',
				'tab_icon_type' => '',
				'tab_icon_iconsmind' => '',
				'image_source' => '',
				'tab_image' => '',
				'ext_image' => '',
				'ext_image_size' => '',
				'link_settings' => '',
				'image_link' => '',
				'link_target' => '',
			), $atts));

			$output = $default_src = $dimensions = $hwstring = $kd_extendtabs_id = $content_icon = $icons = $image = '';

			if ( $tab_icon_type == 'icon_browser' && strlen( $tab_icon_iconsmind ) > 0 ) {
				$exploded = explode( ' ', $tab_icon_iconsmind );
				$iconsmind_cat = end( $exploded );

				if ( strpos( $exploded[0], 'iconsmind-' ) === 0 ) {
					if ( count( $exploded ) < 2 ) {
						wp_enqueue_style( 'keydesign-iconsmind', plugin_dir_url( __DIR__ ).'assets/css/iconsmind.min.css' );
					} else {
						wp_enqueue_style( 'iconsmind-'.$iconsmind_cat, plugin_dir_url( __DIR__ ).'assets/css/iconsmind/'.$iconsmind_cat.'.css' );
					}
				} elseif ( strpos( $exploded[1], 'fa-' ) === 0 ) {
					wp_enqueue_style( 'font-awesome' );
				}
			}

			if ( strlen( $tab_icon_iconsmind ) > 0) {
				$icons = $tab_icon_iconsmind;
			}

			if ( $tab_icon_type == 'icon_browser' && $icons != '' ) {
				$content_icon = '<i class="'.$icons .'"></i> ';
			}
			if ( '' != $tab_image ) {
				$image = wpb_getImageBySize($params = array( 'post_id' => NULL, 'attach_id' => $tab_image, 'thumb_size' => 'full', 'class' => "" ));
			}

			$default_src = vc_asset_url( 'vc/no_image.png' );

			$dimensions = vc_extract_dimensions( $ext_image_size );
			$hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';
			$ext_image = $ext_image ? esc_attr( $ext_image ) : $default_src;

			if ( !$tab_title_tag || $tab_title_tag == '' ) {
				$tab_title_tag = 'h2';
			}

			$kd_extendtabs_id = uniqid('extab-');

			$output .= '<div id="'.$kd_extendtabs_id.'">
				<div class="tab-image-container">';

				if ( $link_settings == 'custom-url' && $image_link != '' ) {
					$output .= '<a href="'.esc_url( $image_link ).'" target="' . esc_attr( $link_target ) . '">';
				}

				if ( $image_source == 'external_link' ) {
					if ( !$ext_image ) {
						$output .='<img src="'.$default_src.'" class="vc_img-placeholder" />';
					} else {
						$output .='<img class="ext-tab-img" src="'.$ext_image.'" '.$hwstring.' />';
					}
				} else {
					if ( !$image ) {
						$output .='<img src="'.$default_src.'" class="vc_img-placeholder" />';
					} else {
						$output .= $image['thumbnail'];
					}
				}

				if ( $link_settings == 'custom-url' && $image_link != '' ) {
					$output .= '<div class="ext-tab-overlay">
						<div class="ext-tab-arrow"></div>
					</div>';
					$output .= '</a>';
				}

				$output .= '</div>
				<div class="tab-text-container">
					<'.$tab_title_tag.' class="tab-title">' . $tab_title . '</'.$tab_title_tag.'>';
					if ($content != '') {
						$output .= '<p class="tab-content">'.do_shortcode($content).'</p>';
					}
					$output .= '</div>
				</div>
				<li class="tab-control-item">
					<a href="#' . $kd_extendtabs_id . '">'.$content_icon.' ' . $tab_title . '</a>
				</li>';

				return $output;
		}
	}
}

 if ( class_exists('tek_extended_tabs') ) {
    $tek_extended_tabs = new tek_extended_tabs;
}
