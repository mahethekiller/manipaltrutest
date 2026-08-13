<?php

if (!class_exists('KD_ELEM_PRICING_TABLE')) {
    class KD_ELEM_PRICING_TABLE extends KEYDESIGN_ADDON_CLASS {
        function __construct() {
            add_action('init', array($this, 'kd_pricingtable_init'));
            add_shortcode('tek_pricing', array($this, 'kd_pricingtable_shrt'));
        }

        // Element configuration in admin
        function kd_pricingtable_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Pricing table", "keydesign"),
                    "description" => esc_html__("Pricing table with extended settings.", "keydesign"),
                    "base" => "tek_pricing",
                    "class" => "",
                    "icon" => plugins_url("assets/element_icons/pricing-table.png", dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Plan title", "keydesign"),
                            "param_name" => "pricing_title",
                            "admin_label" => true,
                            "value" => "",
                            "description" => esc_html__("Enter your pricing plan title.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Plan value", "keydesign"),
                            "param_name" => "pricing_price",
                            "value" => "",
                            "description" => esc_html__("Enter price for this plan.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("On sale?", "keydesign"),
                            "param_name" => "sale_settings",
                            "value" => array(
                                "No" => "sale-no",
                                "Yes" => "sale-yes",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Enable to display discounted price.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Sale price", "keydesign"),
                            "param_name" => "sale_price",
                            "value" => "",
                            "description" => esc_html__("Enter sale price for this plan.", "keydesign"),
                            "dependency" => array(
              								"element" => "sale_settings",
              								"value" => "sale-yes",
              							),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Plan period", "keydesign"),
                            "param_name" => "pricing_time",
                            "value" => "",
                            "description" => esc_html__("Enter your pricing plan period (ex. /month)", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Plan currency", "keydesign"),
                            "param_name" => "pricing_currency",
                            "value" => array(
                                "Dollar" => "currency-dollar",
                                "Euro" => "currency-euro",
                                "Pound" => "currency-pound",
                                "No Currency" => "no-currency"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select pricing plan currency.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Other currency", "keydesign"),
                            "param_name" => "pricing_other_currency",
                            "value" => "",
                            "description" => esc_html__("Pricing plan custom currency.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Currency position", "keydesign"),
                            "param_name" => "pricing_currency_position",
                            "value" => array(
                                "Left" => "currency-position-left",
                                "Right" => "currency-position-right"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select pricing plan currency.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),
                        array(
                            "type" =>	"dropdown",
                            "class" =>	"",
                            "heading" =>	esc_html__("Display icon/image","keydesign"),
                            "param_name" =>	"pricing_icon_type",
                            "value" =>	array(
                                "Icon browser" => "icon_browser",
                                "Media library" => "custom_image",
                                "External image" => "external_link",
                                "No icon" => "no_icon",
                            ),
                            "save_always" => true,
                            "description"	=> esc_html__("Select icon source.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

            						array(
              							"type" => "iconpicker",
              							"heading" => esc_html__( "Icon", "keydesign" ),
              							"param_name" => "icon_iconsmind",
                            "settings" => array(
                        				"type" => "iconsmind",
                        				"iconsPerPage" => 50,
                        		),
              							"dependency" => array(
              								"element" => "pricing_icon_type",
              								"value" => "icon_browser",
              							),
              							"description" => esc_html__( "Select icon from library.", "keydesign" ),
                            "group" => esc_html__("Content", "keydesign"),
            						),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Icon color", "keydesign"),
                            "param_name" => "pricing_icon_color",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "pricing_icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Choose icon color. If none selected, the default theme color will be used.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Icon size", "keydesign"),
                            "param_name" => "pricing_icon_size",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "pricing_icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Enter icon size.", "keydesign"),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => esc_html__("Upload custom image", "keydesign"),
                            "param_name" => "pricing_img",
                            "value" => "",
                            "description" => esc_html__("Upload your own custom image.", "keydesign"),
                            "dependency" => array(
                                "element" => "pricing_icon_type",
                                "value" => array("custom_image"),
                            ),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Image external source", "keydesign"),
                            "param_name" => "ext_image",
                            "value" => "",
                            "description" => esc_html__("Enter image external link.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "pricing_icon_type",
                                "value" => array("external_link")
                            ),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("External image size", "keydesign"),
                            "param_name" => "ext_image_size",
                            "value" => "",
                            "description" => esc_html__("Enter image size in pixels. Example: 300x160 (Width x Height).", "keydesign"),
                            "dependency" =>	array(
                                "element" => "pricing_icon_type",
                                "value" => array("external_link")
                            ),
                            "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "param_group",
                            "class" => "",
                            "heading" => esc_html__("Pricing option", "keydesign"),
                            "value" => "",
                            "param_name" => "pricing_option",
                            "params" => array(
        												array(
          													"type" => "textfield",
          													"heading" => __("Option value","keydesign"),
          													"param_name" => "pricing_row",
          													"description" =>"",
		                                "admin_label" => true,
        												),
                                array(
          													"type" => "textfield",
          													"heading" => __("Secondary option value","keydesign"),
                                    "description" => esc_html__("This option is used with the Price Switcher module.", "keydesign"),
          													"param_name" => "secondary_pricing_row",
          													"description" =>"",
        												),
							                  array(
          													"type" => "dropdown",
          													"heading" => __("Option icon","keydesign"),
          													"param_name" => "pricing_row_icon",
      															"value" => array(
      																esc_html__( 'Check icon', 'keydesign' ) => 'pricing-opt-check-icon',
      																esc_html__( 'X icon', 'keydesign' ) => 'pricing-opt-x-icon',
      																esc_html__( 'No icon', 'keydesign' ) => 'pricing-opt-no-icon',
      															),
        												),
          								),
                          "group" => esc_html__("Content", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Enable button", "keydesign"),
                            "param_name" => "button_settings",
                            "value" => array(
                                "On" => "enable-button",
                                "Off"  => "disable-button",
                            ),
                            "save_always" => true,
                            "group" => esc_html__("Button settings", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Button text", "keydesign"),
                            "param_name" => "pricing_button_text",
                            "value" => "",
                            "description" => esc_html__("Pricing table button text.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "button_settings",
                                "value" => array("enable-button")
                            ),
                            "group" => esc_html__("Button settings", "keydesign"),
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button action", "keydesign"),
                            "param_name" => "button_action",
                            "value" => array(
                                "Link" => "button-action-link",
                                "Trigger Popup Modal"  => "modal-trigger-btn",
                                "Trigger Side Panel" => "panel-trigger-btn",
                            ),
                            "save_always" => true,
                            "group" => esc_html__("Button settings", "keydesign"),
                            "dependency" =>	array(
                                "element" => "button_settings",
                                "value" => array("enable-button")
                            ),
                            "description" => esc_html__("Select button action."),
                        ),
                        array(
                            "type" => "href",
                            "class" => "",
                            "heading" => esc_html__("Button link", "keydesign"),
                            "param_name" => "pricing_button_link",
                            "value" => "",
                            "description" => esc_html__("Add link to button.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "button_action",
                                "value" => array("button-action-link")
                            ),
                            "group" => esc_html__("Button settings", "keydesign"),
                        ),
                        array(
                      			"type" => "dropdown",
                      			"heading" => __( "Link target", "keydesign" ),
                      			"param_name" => "pricing_link_target",
                            "value" => array(
            									esc_html__( 'Same window', 'keydesign' ) => '_self',
            									esc_html__( 'New window', 'keydesign' ) => '_blank',
            								),
                            "save_always" => true,
                            "dependency" =>	array(
                                "element" => "button_action",
                                "value" => array("button-action-link")
                            ),
                            "group" => esc_html__("Button settings", "keydesign"),
                    		),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button icon settings", "keydesign"),
                            "param_name" => "pricing_button_enable_icon",
                            "value" => array(
                                "No icon" => "no",
                                "Display icon" => "yes",
                            ),
                            "save_always" => true,
                            "dependency" =>	array(
                                "element" => "button_settings",
                                "value" => array("enable-button")
                            ),
                            "group" => esc_html__("Button settings", "keydesign"),
                        ),
                         array(
                             "type" => "iconpicker",
                             "class" => "",
                             "heading" => esc_html__("Icon database", "keydesign"),
                             "param_name" => "pricing_button_icon",
                             "dependency" => array(
                                 "element"  => "pricing_button_enable_icon",
                                 "value"    => array("yes")
                                 ),
                             "description" => esc_html__("Select your icon.", "keydesign"),
                             "group" => esc_html__("Button settings", "keydesign"),
                         ),
                         array(
                             "type" => "dropdown",
                             "class" => "",
                             "heading" => esc_html__("Icon position", "keydesign"),
                             "param_name" => "pricing_button_icon_position",
                             "value" => array(
                                 "Left" => "icon_left",
                                 "Right" => "icon_right",
                             ),
                             "dependency" => array(
                                 "element" => "pricing_button_enable_icon",
                                 "value" => array("yes")
                             ),
                             "save_always" => true,
                             "description" => esc_html__("Select icon position.", "keydesign"),
                             "group" => esc_html__("Button settings", "keydesign"),
                         ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Pricing table style", "keydesign"),
                            "param_name" => "pricing_scheme",
                            "value" => array(
                                "Minimal style" => "MinimalStyle",
                                "Detailed style" => "DetailedStyle"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select pricing plan template style.", "keydesign"),
                            "group" => esc_html__("Design options", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Highlight plan", "keydesign"),
                            "param_name" => "highlight_plan",
                            "value" => array(
                                "No" => "",
                                "Yes" => "active"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select if pricing plan is highlighted", "keydesign"),
                            "group" => esc_html__("Design options", "keydesign"),
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Text color", "keydesign"),
                            "param_name" => "pricing_text_color",
                            "value" => "",
                            "description" => esc_html__("Select text color.", "keydesign"),
                            "group" => esc_html__("Design options", "keydesign"),
                        ),

                        array(
            							"type" => "kd_param_notice",
            							"text" => "<span style='display: block;'>These options will be used with the Price Switcher module.</span>",
            							"param_name" => "notification",
            							"edit_field_class" => "vc_column vc_col-sm-12",
                          "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Secondary plan value", "keydesign"),
                            "param_name" => "secondary_plan_value",
                            "value" => "",
                            "description" => esc_html__("Enter secondary price for this plan.", "keydesign"),
                            "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("On sale?", "keydesign"),
                            "param_name" => "secondary_sale_settings",
                            "value" => array(
                                "No" => "sale-no",
                                "Yes" => "sale-yes",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Enable to display discounted price.", "keydesign"),
                            "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Secondary sale value", "keydesign"),
                            "param_name" => "secondary_sale_value",
                            "value" => "",
                            "description" => esc_html__("Enter secondary sale price for this plan.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "secondary_sale_settings",
                                "value" => array("sale-yes")
                            ),
                            "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Secondary plan period", "keydesign"),
                            "param_name" => "secondary_plan_period",
                            "value" => "",
                            "description" => esc_html__("Enter secondary pricing plan period (ex. /year)", "keydesign"),
                            "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "href",
                            "class" => "",
                            "heading" => esc_html__("Secondary button link", "keydesign"),
                            "param_name" => "secondary_pricing_button_link",
                            "value" => "",
                            "description" => esc_html__("Add secondary link to button.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "button_action",
                                "value" => array("button-action-link")
                            ),
                            "group" => esc_html__("Price switch", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("CSS animation", "keydesign"),
                            "param_name" => "css_animation",
                            "value" => array(
                                "No"              => "",
                                "Fade In"         => "kd-animated fadeIn",
                                "Fade In Down"    => "kd-animated fadeInDown",
                                "Fade In Left"    => "kd-animated fadeInLeft",
                                "Fade In Right"   => "kd-animated fadeInRight",
                                "Fade In Up"      => "kd-animated fadeInUp",
                                "Zoom In"         => "kd-animated zoomIn",
                            ),
                            "save_always" => true,
                            "admin_label" => true,
                            "description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "keydesign"),
                            "group" => esc_html__("Extras", "keydesign"),
                        ),

                         array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Animation delay", "keydesign"),
                            "param_name" => "elem_animation_delay",
                            "value" => array(
                                "0 ms"              => "",
                                "200 ms"            => "200",
                                "400 ms"            => "400",
                                "600 ms"            => "600",
                                "800 ms"            => "800",
                                "1 s"               => "1000",
                            ),
                            "save_always" => true,
                            "admin_label" => true,
                            "dependency" =>	array(
                                "element" => "css_animation",
                                "value" => array("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn")
                            ),
                            "description" => esc_html__("Enter animation delay in ms.", "keydesign"),
                            "group" => esc_html__("Extras", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "pricing_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__("Extras", "keydesign"),
                        ),
                    )
                ));
            }
        }

		// Render the element on front-end
        public function kd_pricingtable_shrt( $atts, $content = null ) {

            $output = $link_target = $link_title = $animation_delay = $wrapper_class = $a_attrs = $data_secondary_link = $pricing_img_array = '';
            $currency_symbol = $icon_custom_style = $icons = $content_icon = $pricing_options_container = $dimensions = $hwstring = $button_style_class = $pricing_opt_icon_selector = '';

            extract( shortcode_atts( array(
                'pricing_title' => '',
                'pricing_price' => '',
                'sale_settings' => '',
                'sale_price' => '',
                'pricing_time' => '',
                'pricing_currency' => '',
                'pricing_other_currency' => '',
                'pricing_currency_position' => '',
                'pricing_icon_type' => '',
                'icon_iconsmind' => '',
                'pricing_icon_color' => '',
                'pricing_icon_size' => '',
                'pricing_img' => '',
                'ext_image' => '',
                'ext_image_size' => '',
                'pricing_option' => '',
                'button_settings' => '',
                'pricing_button_text' => '',
                'button_action' => '',
                'pricing_button_link' => '',
                'pricing_link_target' => '',
                'pricing_button_enable_icon' => '',
                'pricing_button_icon' => '',
                'pricing_button_icon_position' => '',
                'pricing_scheme' => '',
                'highlight_plan' => '',
                'pricing_text_color' => '',
                'secondary_plan_value' => '',
                'secondary_sale_settings' => '',
                'secondary_sale_value' => '',
                'secondary_plan_period' => '',
                'secondary_pricing_button_link' => '',
                'css_animation' => '',
                'elem_animation_delay' => '',
                'pricing_extra_class' => '',
            ), $atts ) );

            if ( $pricing_icon_type == 'icon_browser' && strlen( $icon_iconsmind ) > 0 ) {
              $exploded = explode( ' ', $icon_iconsmind );
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

            if ( $pricing_button_enable_icon == 'yes' && strlen( $pricing_button_icon ) > 0 ) {
              wp_enqueue_style( 'font-awesome' );
            }

            switch($pricing_currency){
              case 'currency-dollar':
                  $currency_symbol = "&#36;";
              break;

              case 'currency-euro':
                  $currency_symbol = "&#128;";
              break;

              case 'currency-pound':
                  $currency_symbol = "&#163;";
              break;

              case 'no-currency':
                  $currency_symbol = "";
              break;

              default:
            }

            if (strlen($icon_iconsmind) > 0) {
                $icons = $icon_iconsmind;
            }

            if ($pricing_icon_color !== '') {
              $icon_custom_style .= 'color: '.$pricing_icon_color.';';
            }

            if ($pricing_icon_size !== '') {
              $icon_custom_style .= 'font-size: '.$pricing_icon_size.';';
            }

            if (!empty($pricing_other_currency)) {
              $currency_symbol = $pricing_other_currency;
            }

            $dimensions = vc_extract_dimensions( $ext_image_size );
            $hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

            if ( $pricing_icon_type == 'icon_browser' ) {
                $content_icon .= '<i class="' . $icons . '" style="' . $icon_custom_style . '"></i> ';
            } elseif ( $pricing_icon_type == 'custom_image' ) {
                if ( '' != $pricing_img && wp_attachment_is_image( $pricing_img ) ) {
                    $pricing_img_array = wpb_getImageBySize ( $params = array( 'post_id' => NULL, 'attach_id' => $pricing_img, 'thumb_size' => 'full', 'class' => "" ) );
    	            $content_icon .= '<div class="pricing-image">'.$pricing_img_array['thumbnail'].'</div>';
                }
            } elseif ( $pricing_icon_type == 'external_link' && !empty($ext_image) ) {
                $content_icon .= '<div class="pricing-image"><img src="'.$ext_image.'" '.$hwstring.' /></div>';
            }

            //Button class
            if ($highlight_plan == "active") {
              $button_style_class = "tt_primary_button btn_primary_color";
            } else {
              $button_style_class = "tt_secondary_button";
            }

            //CSS Animation
            if ($css_animation == "no_animation") {
                $css_animation = "";
            }

            // Animation delay
            if ($elem_animation_delay) {
                $animation_delay = 'data-animation-delay='.$elem_animation_delay;
            }

            $wrapper_class = implode(' ', array('pricing-table', $highlight_plan, $pricing_scheme, $css_animation, $pricing_extra_class));

            // Pricing options container
            $pricing_options_container .= '<div class="pricing-options-container ' . $pricing_scheme . '" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>';
            $pricing_option = json_decode( urldecode( $pricing_option ), true );

            if( isset( $pricing_option ) ) {
		            foreach ( $pricing_option as $pricing_option_data ) {
                    $pricing_options_container .= '<div class="pricing-row"><span class="pricing-value"><span class="pricing-option '.(isset($pricing_option_data["pricing_row_icon"]) ? $pricing_option_data["pricing_row_icon"] : '').'">'.$pricing_opt_icon_selector;
                    if ( isset( $pricing_option_data["pricing_row_icon"] ) ){
	                     if ($pricing_option_data["pricing_row_icon"] == 'pricing-opt-x-icon') {
		                       $pricing_options_container .= '<i class="fas fa-times"></i>';
		                   } elseif ($pricing_option_data["pricing_row_icon"] != 'pricing-opt-no-icon') {
		                       $pricing_options_container .= '<i class="fas fa-check"></i>';
	                     }
                    } else {
		                    $pricing_options_container .= '<i class="fas fa-check"></i>';
                    }
	                  if ( isset( $pricing_option_data["pricing_row"] ) ){
				                $pricing_options_container .= '<span class="pricing-option-text default-plan">'.$pricing_option_data["pricing_row"].'</span>';
                    }
                    if ( isset( $pricing_option_data["secondary_pricing_row"] ) ){
				                $pricing_options_container .= '<span class="pricing-option-text secondary-plan">'.$pricing_option_data["secondary_pricing_row"].'</span>';
                    } else {
                        $pricing_options_container .= '<span class="pricing-option-text secondary-plan">'.$pricing_option_data["pricing_row"].'</span>';
                    }
                    $pricing_options_container .= '</span></span></div>';
                }
            }

					  $pricing_options_container .= '</div>';

            // Secondary button link
            if ( '' != $secondary_pricing_button_link ) {
              $data_secondary_link = 'data-secondary-link="'. esc_url( $secondary_pricing_button_link ) .'"';
            }

            // Begin element output
            $output = '<div class="pricing-wrapper">
  				<div class="'.trim($wrapper_class).'" '.$animation_delay.'>
                    <div class="row pricing-title">
	                   <h5 class="pricing-title-content" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>'.$pricing_title.'</h5>
  					</div>';
                    if ( '' != $content_icon ) {
                        $output .= '<div class="pricing-img">'.$content_icon.'</div>';
                    }

  					$output .= '<div class="row pricing">';
                    // START Price and currency section
                    if ( '' != $pricing_price ) {
  						$output .= '<div class="col-lg-3">
  							<div class="row">';
                                if ( $pricing_currency_position == "currency-position-left" ) {
                                    $output .= '<span class="pricing-price default-plan '.$sale_settings.'" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>
                                        <span class="pt-normal-price">
                                            <span class="currency">'.$currency_symbol.'</span>'.$pricing_price.'
                                        </span>';
                                        if ( $sale_settings == "sale-yes" && $sale_price ) {
                                            $output .= '<span class="pt-sale-price">
                                                <span class="currency">'.$currency_symbol.'</span>'.$sale_price.'
                                            </span>';
                                        }
                                    $output .= '</span>';
                                if ( '' != $secondary_plan_value ) {
                                $output .= '<span class="pricing-price secondary-plan '.$secondary_sale_settings.'" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>
                                    <span class="pt-normal-price">
                                        <span class="currency">'.$currency_symbol.'</span>'.$secondary_plan_value.'
                                    </span>';
                                }
                                if ( $secondary_sale_settings == "sale-yes" && $secondary_sale_value ) {
                                    $output .= '<span class="pt-sale-price">
                                        <span class="currency">'.$currency_symbol.'</span>'.$secondary_sale_value.'
                                    </span>';
                                }
                            $output .= '</span>';
                    } elseif ($pricing_currency_position == "currency-position-right") {
                      $output .= '<span class="pricing-price default-plan '.$sale_settings.'" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>
                        <span class="pt-normal-price">
                          '.$pricing_price.'<span class="currency">'.$currency_symbol.'</span>
                        </span>';
                        if ( $sale_settings == "sale-yes" && $sale_price ) {
                          $output .= '<span class="pt-sale-price">
                            '.$sale_price.'<span class="currency">'.$currency_symbol.'</span>
                          </span>';
                        }
                      $output .= '</span>';
                      if ('' != $secondary_plan_value ) {
                        $output .= '<span class="pricing-price secondary-plan '.$secondary_sale_settings.'" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>
                          <span class="pt-normal-price">
                            '.$secondary_plan_value.'<span class="currency">'.$currency_symbol.'</span>
                          </span>';
                          if ( $secondary_sale_settings == "sale-yes" && $secondary_sale_value ) {
                            $output .= '<span class="pt-sale-price">
                              '.$secondary_sale_value.'<span class="currency">'.$currency_symbol.'</span>
                            </span>';
                          }
                          $output .= '</span>';
                      }
                    }
      		            $output .= '<div class="pricing-meta">
                         <span class="pricing-time default-plan" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>'.$pricing_time.'</span>';
                         if ('' != $secondary_plan_period ) {
                           $output .= '<span class="pricing-time secondary-plan" '.(!empty($pricing_text_color) ? 'style="color: '.$pricing_text_color.';"' : '').'>'.$secondary_plan_period.'</span>';
                         }
                      $output .= '</div>
                    </div>
      						</div>';
                    }
                    // END Price and currency section
                $output .= $pricing_options_container;

            if ( $pricing_button_text && 'disable-button' != $button_settings ) {
              if ( $button_action == 'modal-trigger-btn' ) {
                $output .= '<a class="tt_button '.$pricing_button_icon_position.' '.$button_style_class.'" data-toggle="modal" data-target="#popup-modal">';
              } elseif ( $button_action == 'panel-trigger-btn' ) {
                $output .= '<a class="tt_button panel-trigger-btn '.$pricing_button_icon_position.' '.$button_style_class.'">';
              } else {
                $output .= '<a href="'. esc_url( $pricing_button_link ) .'" '.$data_secondary_link.' target="'.$pricing_link_target.'" class="tt_button '.$pricing_button_icon_position.' '.$button_style_class.'">';
              }

              if ($pricing_button_enable_icon == 'yes' && $pricing_button_icon_position == 'icon_left') {
                  $output .= '<i class="'.$pricing_button_icon.' iconita"></i>';
              }
              $output .= '<span class="prim_text">'.$pricing_button_text.'</span>';
              if ($pricing_button_enable_icon == 'yes' && $pricing_button_icon_position == 'icon_right') {
                  $output .= '<i class="'.$pricing_button_icon.' iconita"></i>';
              }
              $output .= '</a>';
            }

		       $output .= '</div>
	         </div>';
          $output .= '</div>';

	        return $output;
        }
    }
}

if ( class_exists( 'KD_ELEM_PRICING_TABLE' ) ) {
    $KD_ELEM_PRICING_TABLE = new KD_ELEM_PRICING_TABLE;
}
