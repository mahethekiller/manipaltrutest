<?php

if (!class_exists('KD_ELEM_CALL_TO_ACTION')) {

    class KD_ELEM_CALL_TO_ACTION extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_calltoaction_init'));
            add_shortcode('tek_calltoaction', array($this, 'kd_calltoaction_shrt'));
        }

        // Element configuration in admin

        function kd_calltoaction_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Call to action", "keydesign"),
                    "description" => esc_html__("Call to action section with button.", "keydesign"),
                    "base" => "tek_calltoaction",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/calltoaction-box.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Title", "keydesign"),
                            "param_name" => "cta_title",
                            "value" => "",
                            "admin_label" => true,
                            "description" => esc_html__("Enter call to action title here.", "keydesign")
                        ),
                        array(
                            "type" => "textarea",
                            "class" => "",
                            "heading" => esc_html__("Subtitle", "keydesign"),
                            "param_name" => "cta_subtitle",
                            "value" => "",
                            "description" => esc_html__("This text will be displayed under the title.", "keydesign")
                        ),
                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Text color", "keydesign"),
                            "param_name" => "cta_text_color",
                            "value" => "",
                            "description" => esc_html__("Choose text color.", "keydesign")
                        ),
                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Box color", "keydesign"),
                            "param_name" => "cta_box_color",
                            "value" => "",
                            "description" => esc_html__("Choose box color.", "keydesign")
                        ),
                        array(
                            "type"			=>	"dropdown",
                            "class"			=>	"",
                            "heading"		=>	esc_html__("Display icon","keydesign"),
                            "param_name"	=>	"cta_icon_type",
                            "value"			=>	array(
                                    "No" => "no_icon",
                                    "Icon Browser" => "icon_browser",
                                    "Custom Icon" => "custom_image",
                                ),
                            "save_always" => true,
                            "description"	=>	esc_html__("Select icon source.", "keydesign")
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
              								"element" => "cta_icon_type",
              								"value" => "icon_browser",
              							),
              							"description" => esc_html__( "Select icon from library.", "keydesign" ),
            						),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Icon color", "keydesign"),
                            "param_name" => "cta_icon_color",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "cta_icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Choose icon color. If none selected, the default theme color will be used.", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Icon size", "keydesign"),
                            "param_name" => "cta_icon_size",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "cta_icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Enter icon size. (eg. 10px, 1em, 1rem)", "keydesign"),
                        ),

                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => esc_html__("Upload custom image", "keydesign"),
                            "param_name" => "cta_image",
                            "value" => "",
                            "description" => esc_html__("Upload your own custom image.", "keydesign"),
                            "dependency" => array(
                                "element" => "cta_icon_type",
                                "value" => array("custom_image"),
                            ),
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
                            "description" => esc_html__("Select button action."),
                        ),
                        array(
                             "type"	=>	"vc_link",
                             "class" =>	"",
                             "heading" => esc_html__("Button link", "keydesign"),
                             "param_name" => "cta_button_link",
                             "value" =>	"",
                             "dependency" => array(
                 								"element" => "button_action",
                 								"value" => "button-action-link",
							                ),
                             "description" => esc_html__("You can add or remove the existing link from here.", "keydesign"),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Button text", "keydesign"),
                            "param_name" => "cta_button_text",
                            "value" => "",
                            "description" => esc_html__("Write the text displayed on the button.", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button style", "keydesign"),
                            "param_name" => "cta_button_style",
                            "value" => array(
                                "Solid color" => "tt_primary_button",
                                "Outline" => "tt_secondary_button"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select button style", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button color scheme", "keydesign"),
                            "param_name" => "cta_button_color_scheme",
                            "value" => array(
                                "Primary color" => "btn_primary_color",
                                "Secondary color" => "btn_secondary_color"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select button predefined color scheme.", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Text CSS animation", "keydesign"),
                            "param_name" => "css_animation_text",
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
                            "heading" => esc_html__("Button CSS animation", "keydesign"),
                            "param_name" => "css_animation_button",
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
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "cta_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),

                    )
                ));
            }
        }



		// Render the element on front-end

        public function kd_calltoaction_shrt($atts, $content = null)
        {
            extract(shortcode_atts(array(
                'cta_icon_type' => '',
                'icon_iconsmind' => '',
                'cta_icon_color' => '',
                'cta_icon_size' => '',
                'cta_image' => '',
                'cta_title' => '',
                'cta_subtitle' => '',
                'cta_text_color' => '',
                'cta_box_color' => '',
                'button_action' => '',
                'cta_button_link' => '',
                'cta_button_text' => '',
                'cta_button_style' => '',
                'cta_button_color_scheme' => '',
                'css_animation_text' => '',
                'css_animation_button' => '',
                'cta_extra_class' => '',
            ), $atts));

            $content_icon = $icon_color_style = $icon_size_style = $cta_img_array = $href = $link_target = $link_title = $animation_delay_text = $animation_delay_button = '';

            if ( $cta_icon_type == 'icon_browser' && strlen( $icon_iconsmind ) > 0 ) {
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

            if ( strlen($icon_iconsmind) > 0 ) {
                $cta_icon = $icon_iconsmind;
            }

            if ( $cta_icon_color !== '' ) {
                $icon_color_style = 'color: '.$cta_icon_color.';';
            }

            if ( $cta_icon_size !== '' ) {
                $icon_size_style = 'font-size: '.$cta_icon_size.';';
            }

            if ( $cta_icon_type == 'icon_browser' && !empty($cta_icon) ) {
	            $content_icon = '<i class="'.$cta_icon.'" style="'.$icon_size_style.' '.$icon_color_style.'"></i> ';
            } elseif ( $cta_icon_type == 'custom_image' && !empty( $cta_image ) ) {
				$cta_img_array = wpb_getImageBySize ( $params = array( 'post_id' => NULL, 'attach_id' => $cta_image, 'thumb_size' => 'full', 'class' => "" ) );
				$content_icon = $cta_img_array['thumbnail'];
  			}

            $href = vc_build_link($cta_button_link);
      			if($href['url'] !== '') {
        				$link_target = (isset($href['target']) && ($href['target'] != '')) ? 'target="'.$href['target'].'"' : '';
        				$link_title = (isset($href['title'])) ? 'title="'.$href['title'].'"' : '';
      			}

            //Animation delay
            if ('' != $css_animation_text) {
                $animation_delay_text = 'data-animation-delay="200"';
            }
            if ('' != $css_animation_button) {
                $animation_delay_button = 'data-animation-delay="200"';
            }

            $output = '<div class="kd-calltoaction '.$cta_icon_type.' '.$cta_extra_class.'" '.(!empty($cta_box_color) ? 'style="background-color: '.$cta_box_color.';"' : '').'>
                <div class="container">
                  <div class="cta-text-container ' . $css_animation_text . '" ' . $animation_delay_text . '>';
                    if ( $cta_icon_type != "no_icon" && strlen( $icon_iconsmind ) > 0 ) {
                        $output .= '<div class="cta-icon">'.$content_icon.'</div>';
                    }
                    $output .= '<div class="cta-text">
                        <h3 '.(!empty($cta_text_color) ? 'style="color: '.$cta_text_color.';"' : '').'>'.$cta_title.'</h3>';
                        if ( '' != $cta_subtitle ) {
                          $output .= '<p '.(!empty($cta_text_color) ? 'style="color: '.$cta_text_color.';"' : '').'>'.$cta_subtitle.'</p>';
                        }
                    $output .= '</div>
                    </div>
                    <div class="cta-btncontainer">';
                      if ( $button_action == 'modal-trigger-btn' ) {
                        $output .= '<a class="tt_button '.$cta_button_style.' '.$button_action.' '.$cta_button_color_scheme.' ' . $css_animation_button . '" ' . $animation_delay_button . ' data-toggle="modal" data-target="#popup-modal">'.$cta_button_text.'</a>';
                      } elseif ( $button_action == 'panel-trigger-btn' ) {
                        $output .= '<a class="tt_button '.$cta_button_style.' '.$button_action.' '.$cta_button_color_scheme.' ' . $css_animation_button . '" ' . $animation_delay_button .'>'.$cta_button_text.'</a>';
                      } else {
                        $output .= '<a href="'.$href['url'].'" '.$link_target.' '.$link_title.' class="tt_button '.$cta_button_style.' '.$button_action.' '.$cta_button_color_scheme.' ' . $css_animation_button . '" ' . $animation_delay_button .'>'.$cta_button_text.'</a>';
                      }
                    $output .= '</div>
                </div>
            </div>';

            return $output;

        }
    }
}

if (class_exists('KD_ELEM_CALL_TO_ACTION')) {
    $KD_ELEM_CALL_TO_ACTION = new KD_ELEM_CALL_TO_ACTION;
}

?>
