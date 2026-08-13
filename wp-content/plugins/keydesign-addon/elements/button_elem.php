<?php
if (!class_exists('KD_ELEM_BUTTON')) {
    class KD_ELEM_BUTTON extends KEYDESIGN_ADDON_CLASS {
        function __construct() {
            add_action('init', array($this, 'kd_button_init'));
            add_shortcode('tek_button', array($this, 'kd_button_shrt'));
        }
        // VC Elements render in admin

        function kd_button_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Button", "keydesign"),
                    "description" => esc_html__("Call to action button with extensive settings.", "keydesign"),
                    "base" => "tek_button",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/button.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(

                        array(
                            "type" => "textfield",
                            "class" => "kd-back-desc",
                            "heading" => esc_html__("Button text", "keydesign"),
                            "param_name" => "button_text",
                            "holder" => "div",
                            "value" => "",
                            "description" => esc_html__("Enter button text.", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button style", "keydesign"),
                            "param_name" => "button_style",
                            "value" => array(
                                "Solid" => "tt_primary_button",
                                "Outline" => "tt_secondary_button"
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select button style", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button color scheme", "keydesign"),
                            "param_name" => "button_color_scheme",
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
                            "heading" => esc_html__("Button hover state", "keydesign"),
                            "param_name" => "button_hover_state",
                            "value" => array(
                                "Default" => "",
                                "Solid - Primary color" => "hover_solid_primary",
                                "Solid - Secondary color" => "hover_solid_secondary",
                                "Outline - Primary color" => "hover_outline_primary",
                                "Outline - Secondary color" => "hover_outline_secondary",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select button hover state style.", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button icon settings", "keydesign"),
                            "param_name" => "button_icon_bool",
                            "value" => array(
                                "No icon"         => "no",
                                "Display icon"    => "yes",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Choose to display icon or not.", "keydesign")
                        ),
                         array(
                             "type" => "iconpicker",
                             "class" => "",
                             "heading" => esc_html__("Icon database", "keydesign"),
                             "param_name" => "icons",
                             "dependency" => array(
                                 "element"  => "button_icon_bool",
                                 "value"    => array("yes")
                                 ),
                             "description" => esc_html__("Select your icon.", "keydesign")
                         ),
                         array(
                             "type" => "dropdown",
                             "class" => "",
                             "heading" => esc_html__("Icon position", "keydesign"),
                             "param_name" => "button_icon_position",
                             "value" => array(
                                 "Left" => "icon_left",
                                 "Right" => "icon_right",
                             ),
                             "dependency" => array(
                                 "element" => "button_icon_bool",
                                 "value" => array("yes")
                             ),
                             "save_always" => true,
                             "description" => esc_html__("Select icon position.", "keydesign")
                         ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button action", "keydesign"),
                            "param_name" => "button_action",
                            "value" => array(
                                "Link" => "button-action-link",
                                "Scroll to section" => "button-scroll-to-section",
                                "Trigger Popup Modal"  => "modal-trigger-btn",
                                "Trigger Side Panel" => "panel-trigger-btn",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select button action."),
                        ),
                        array(
            							 "type" => "vc_link",
            							 "class" => "",
            							 "heading" => esc_html__("Link settings", "keydesign"),
            							 "param_name" => "button_link",
            							 "value" => "",
                           "dependency" => array(
                               "element" => "button_action",
                               "value" => array("button-action-link")
                           ),
            							 "description" => esc_html__("Set link address and target.", "keydesign"),
            						),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Section ID", "keydesign"),
                            "param_name" => "button_section_id",
                            "value" => "",
                            "dependency" => array(
                              "element" => "button_action",
                              "value" => array("button-scroll-to-section")
                            ),
                            "description" => esc_html__("Enter the ID of the section you want to scroll smoothly to. ( without # )", "keydesign"),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Button width", "keydesign"),
                            "param_name" => "button_width",
                            "value" => "",
                            "description" => esc_html__("Button width in pixels", "keydesign")
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Button position", "keydesign"),
                            "param_name" => "button_position",
                            "value" => array(
                                "Center" => "button-center",
                                "Left" 	=> "pull-left",
                                "Right" => "pull-right",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Choose button position inside the content area.", "keydesign"),
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("CSS Animation", "keydesign"),
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
                            "admin_label" => true,
                            "description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
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
                                "1s"                => "1000",
                            ),
                            "dependency" =>	array(
                                "element" => "css_animation",
                                "value" => array("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn")
                            ),
                            "admin_label" => true,
                            "description" => esc_html__("Enter animation delay in ms", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "button_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                    )
                ));
            }
        }

        // Render the element on front-end
        public function kd_button_shrt($atts, $content = null) {

           $output = $icons = $link_target = $link_title = $button_link = $animation_delay = $wrapper_class = $button_section_id = '';


            extract(shortcode_atts(array(
                'button_icon_bool' => 'yes',
                'icons' => '',
                'button_icon_position' => '',
                'button_target' => '_self',
                'button_text' => '',
                'button_link' => '',
                'button_style' => '',
                'button_section_id' => '',
                'button_color_scheme' => '',
                'button_hover_state' => '',
                'button_position' => '',
                'button_width' => '',
                'button_action' => '',
                'css_animation' => '',
                'elem_animation_delay' => '',
                'button_extra_class' => '',
            ), $atts));

            $href = vc_build_link($button_link);
            if ($href['target'] == "") { $href['target'] = "_self"; }

      			if ($href['url'] !== '') {
      				$link_target = (isset($href['target']) && ($href['target'] != '')) ? ' target="'.$href['target'].'"' : 'target="_self"';
      				$link_title = (isset($href['title'])) ? ' title="'.$href['title'].'"' : '';
      			}

            if ( $button_icon_bool == 'yes' && strlen( $icons ) > 0 ) {
              wp_enqueue_style( 'font-awesome' );
            }

            //Button Animation
            if ($css_animation == "no_animation") {
                $css_animation = "";
            }

            // Animation delay
            if ($elem_animation_delay) {
                $animation_delay = 'data-animation-delay='.$elem_animation_delay;
            }

            $wrapper_class = implode(' ', array('tt_button', $button_style, $button_color_scheme, $button_hover_state, $button_position, $button_action, $button_icon_position, $css_animation, $button_extra_class));

            if ( $button_action == 'modal-trigger-btn' ) {
              $output .= '<a '.(!empty($button_width) ? 'style="width: '.$button_width.';"' : '').' class="'.trim($wrapper_class).'" data-toggle="modal" data-target="#popup-modal" '.$animation_delay.'>';
            } elseif ( $button_action == 'panel-trigger-btn' ) {
              $output .= '<a '.(!empty($button_width) ? 'style="width: '.$button_width.';"' : '').' class="'.trim($wrapper_class).'" '.$animation_delay.'>';
            } elseif  ( $button_action == 'button-scroll-to-section' ) {
              $output .= '<a '.(!empty($button_width) ? 'style="width: '.$button_width.';"' : '').' href="#'.$button_section_id.'" class="'.trim($wrapper_class).' btn-smooth-scroll" '.$animation_delay.'>';
            } else {
              $output .= '<a '.(!empty($button_width) ? 'style="width: '.$button_width.';"' : '').' href="'.$href['url'].'" '.$link_target.' '.$link_title.' class="'.trim($wrapper_class).'" '.$animation_delay.'>';
            }

            if ($button_icon_bool == 'yes' && $button_icon_position == 'icon_left') {
                $output .= '<span class="'.$icons.' iconita"></span>';
            }
            $output .= '<span class="prim_text">'.$button_text.'</span>';
            if ($button_icon_bool == 'yes' && $button_icon_position == 'icon_right') {
                $output .= '<span class="'.$icons.' iconita"></span>';
            }
            $output .= '</a>';


            return $output;
        }
    }
}


if (class_exists('KD_ELEM_BUTTON')) {
    $KD_ELEM_BUTTON = new KD_ELEM_BUTTON;
}
?>
