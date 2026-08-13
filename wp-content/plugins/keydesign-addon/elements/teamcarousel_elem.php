<?php
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_tek_teamcarousel extends WPBakeryShortCodesContainer {
    }
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_tek_teamcarousel_single extends WPBakeryShortCode {
    }
}
if (!class_exists('tek_teamcarousel')) {
    class tek_teamcarousel extends KEYDESIGN_ADDON_CLASS
    {
        function __construct() {
            add_action('init', array($this, 'kd_teamcarousel_init'));
            add_shortcode('tek_teamcarousel', array($this, 'kd_teamcarousel_container'));
            add_shortcode('tek_teamcarousel_single', array($this, 'kd_teamcarousel_single'));
        }
        // Element configuration in admin
        function kd_teamcarousel_init() {
            // Container element configuration
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Team carousel", "keydesign"),
                    "description" => esc_html__("List all your team members in a carousel.", "keydesign"),
                    "base" => "tek_teamcarousel",
                    "class" => "kd-outer-controls",
                    "show_settings_on_create" => true,
                    "content_element" => true,
                    "as_parent" => array('only' => 'tek_teamcarousel_single'),
                    "icon" => plugins_url('assets/element_icons/team-carousel.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "js_view" => 'VcColumnView',
                    "params" => array(
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Design style", "keydesign"),
                            "param_name" => "design_style",
                            "value" => array(
                                esc_html__("Classic", "keydesign") => "classic",
                                esc_html__("Creative", "keydesign") => "creative",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select Team Member box design.", "keydesign")
                        ),

                        array(
                            "type"			=> "dropdown",
                            "class"			=>	"",
                            "heading"		=>	esc_html__("Elements per row", "keydesign"),
                            "param_name"	=>	"tc_elements",
                            "value"			=>	array(
                                    "3 items" => "3",
                                    "4 items" => "4",
                                ),
                            "save_always" => true,
                            "description" => esc_html__("Amount of items displayed at a time with the widest browser width.", "keydesign")
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Navigation color", "keydesign"),
                            "param_name" => "tc_navigation_color",
                            "value" => array(
                                "Black" => "black-navigation",
                                "White" => "white-navigation",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Choose the navigation arrows color.", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Enable loop","keydesign"),
                            "param_name" => "tc_loop",
                            "value" => array(
                                "On" => "loop_on",
                                "Off" => "loop_off",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Infinity loop. Duplicate last and first items to get loop illusion.", "keydesign")
                        ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Enable autoplay","keydesign"),
                            "param_name"    =>  "tc_autoplay",
                            "value"         =>  array(
                                    "Off"   => "auto_off",
                                    "On"    => "auto_on"
                                ),
                            "save_always" => true,
                            "description"   =>  esc_html__("Carousel autoplay settings.", "keydesign")
                        ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Autoplay speed","keydesign"),
                            "param_name"    =>  "tc_autoplay_speed",
                            "value"         =>  array(
                                    "10s"   => "10000",
                                    "9s"   => "9000",
                                    "8s"   => "8000",
                                    "7s"   => "7000",
                                    "6s"   => "6000",
                                    "5s"   => "5000",
                                    "4s"   => "4000",
                                    "3s"   => "3000",
                                ),
                            "save_always" => true,
                            "dependency" =>	array(
                                "element" => "tc_autoplay",
                                "value" => array("auto_on")
                            ),
                            "description"   =>  esc_html__("Carousel autoplay speed.", "keydesign")
                        ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Stop on hover","keydesign"),
                            "param_name"    =>  "tc_stoponhover",
                            "value"         =>  array(
                                    "Off"   => "hover_off",
                                    "On"    => "hover_on"
                                ),
                            "save_always" => true,
                            "dependency" =>	array(
                                "element" => "tc_autoplay",
                                "value" => array("auto_on")
                            ),
                            "description"   =>  esc_html__("Stop sliding carousel on mouse over.", "keydesign")
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "tc_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign")
                        ),
                    )
                ));
                // Shortcode configuration
                vc_map(array(
                    "name" => esc_html__("Team member", "keydesign"),
                    "base" => "tek_teamcarousel_single",
                    "content_element" => true,
                    "as_child" => array('only' => 'tek_teamcarousel'),
                    "icon" => plugins_url('assets/element_icons/team-member.png', dirname(__FILE__)),
                    "params" => array(
                      array(
                          "type" => "textfield",
                          "class" => "kd-back-desc",
                          "heading" => esc_html__("Name", "keydesign"),
                          "param_name" => "title",
                          "admin_label" => true,
                          "value" => "",
                      ),

                      array(
                          "type" => "colorpicker",
                          "class" => "",
                          "heading" => esc_html__("Name text color", "keydesign"),
                          "param_name" => "title_color",
                          "value" => "",
                          "description" => esc_html__("Select name text color. If none selected, the default theme color will be used.", "keydesign"),
                      ),

                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Position", "keydesign"),
                          "param_name" => "position",
                          "value" => "",
                      ),

                      array(
                          "type" => "colorpicker",
                          "class" => "",
                          "heading" => esc_html__("Position text color", "keydesign"),
                          "param_name" => "position_color",
                          "value" => "",
                          "description" => esc_html__("Select position text color. If none selected, the default theme color will be used.", "keydesign"),
                      ),

                      array(
                          "type" => "textarea",
                          "class" => "",
                          "heading" => esc_html__("Description", "keydesign"),
                          "param_name" => "description",
                          "value" => "",
                      ),

                      array(
                          "type" => "colorpicker",
                          "class" => "",
                          "heading" => esc_html__("Description text color", "keydesign"),
                          "param_name" => "description_color",
                          "value" => "",
                          "description" => esc_html__("Select description color. If none selected, the default theme color will be used.", "keydesign"),
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
                      ),

                      array(
                          "type" => "attach_image",
                          "heading" => esc_html__("Image", "keydesign"),
                          "param_name" => "image",
                          "description" => esc_html__("Select or upload your image using the media library."),
                          "dependency" =>	array(
                              "element" => "image_source",
                              "value" => array("media_library")
                          ),
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
                      ),
                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Image size", "keydesign"),
                          "param_name" => "ext_image_size",
                          "value" => "",
                          "description" => esc_html__("Enter image size in pixels. Example: 400x450 (Width x Height).", "keydesign"),
                          "dependency" =>	array(
                              "element" => "image_source",
                              "value" => array("external_link")
                          ),
                      ),

                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Phone", "keydesign"),
                          "param_name" => "tm_phone",
                          "value" => "",
                      ),

                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Email", "keydesign"),
                          "param_name" => "tm_email",
                          "value" => "",
                      ),

                      array(
                          "type" => "dropdown",
                          "class" => "",
                          "heading" => esc_html__("Contact icon effect", "keydesign"),
                          "param_name" => "tm_disable_contact_effect",
                          "value" => array(
                              esc_html__("Enable", "keydesign") => "enable-contact-effect",
                              esc_html__("Disable", "keydesign") => "disable-contact-effect",
                          ),
                          "save_always" => true,
                          "dependency" =>	array(
                              "element" => "design_style",
                              "value" => array("classic")
                          ),
                          "description" => esc_html__("Option used to disable the hover effect on mail and phone icons.", "keydesign"),
                      ),

                      array(
                          "type" => "colorpicker",
                          "class" => "",
                          "heading" => esc_html__("Box background color", "keydesign"),
                          "param_name" => "team_bg_color",
                          "value" => "",
                          "description" => esc_html__("Choose box background color. If none selected, the default theme color will be used.", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Facebook Profile URL", "keydesign"),
                           "param_name" => "facebook_url",
                           "value" => "",
                           "description" => esc_html__("Set Facebook address and target.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Instagram Profile URL", "keydesign"),
                           "param_name" => "instagram_url",
                           "value" => "",
                           "description" => esc_html__("Set Instagram link.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Twitter Profile URL", "keydesign"),
                           "param_name" => "twitter_url",
                           "value" => "",
                           "description" => esc_html__("Set Twitter address and target.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Linkedin Profile URL", "keydesign"),
                           "param_name" => "linkedin_url",
                           "value" => "",
                           "description" => esc_html__("Set Linkedin address and target.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("GitHub Profile URL", "keydesign"),
                           "param_name" => "github_url",
                           "value" => "",
                           "description" => esc_html__("Set GitHub link.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Xing Profile URL", "keydesign"),
                           "param_name" => "xing_url",
                           "value" => "",
                           "description" => esc_html__("Set Xing link.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("VK Profile URL", "keydesign"),
                           "param_name" => "vk_url",
                           "value" => "",
                           "description" => esc_html__("Set VK link.", "keydesign"),
                           "group" => esc_html__("Social", "keydesign"),
                      ),

                      array(
                          "type" => "colorpicker",
                          "class" => "",
                          "heading" => esc_html__("Social icons color", "keydesign"),
                          "param_name" => "social_color",
                          "value" => "",
                          "description" => esc_html__("Choose social icons color. If none selected, the default theme color will be used.", "keydesign"),
                      ),

                      array(
                           "type" => "href",
                           "class" => "",
                           "heading" => esc_html__("Link URL", "keydesign"),
                           "param_name" => "team_external_url",
                           "value" => "",
                           "description" => esc_html__("Set team link.", "keydesign"),
                           "group" => esc_html__("Link", "keydesign"),
                      ),
                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Link text", "keydesign"),
                          "param_name" => "team_link_text",
                          "value" => "",
                          "description" => esc_html__("Set team link text. (eg. Read more)", "keydesign"),
                          "group" => esc_html__("Link", "keydesign"),
                      ),
                      array(
                          'type' => 'dropdown',
                          'heading' => __( 'Link target', 'keydesign' ),
                          'param_name' => 'team_link_target',
                          "value" => array(
                            esc_html__( 'Same window', 'keydesign' ) => '_self',
                            esc_html__( 'New window', 'keydesign' ) => '_blank',
                          ),
                         "group" => esc_html__("Link", "keydesign"),
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
                          "save_always" => true,
                          "admin_label" => true,
                          "description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "keydesign"),
                          "group" => esc_html__( "Extras", "keydesign" ),
                       ),

                       array(
                          "type" => "dropdown",
                          "class" => "",
                          "heading" => esc_html__("Animation Delay", "keydesign"),
                          "param_name" => "elem_animation_delay",
                          "value" => array(
                              "0 ms"              => "",
                              "200 ms"            => "200",
                              "400 ms"            => "400",
                              "600 ms"            => "600",
                              "800 ms"            => "800",
                              "1 s"               => "1000",
                          ),
                          "dependency" =>	array(
                              "element" => "css_animation",
                              "value" => array("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn")
                          ),
                          "save_always" => true,
                          "admin_label" => true,
                          "description" => esc_html__("Enter animation delay in ms", "keydesign"),
                          "group" => esc_html__( "Extras", "keydesign" ),
                      ),

                      array(
                          "type" => "textfield",
                          "class" => "",
                          "heading" => esc_html__("Extra class name", "keydesign"),
                          "param_name" => "team_extra_class",
                          "value" => "",
                          "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                          "group" => esc_html__( "Extras", "keydesign" ),
                      ),

                    )
                ));
            }
        }

        public function kd_teamcarousel_container($atts, $content = null) {
            extract(shortcode_atts(array(
                    'design_style' => '',
                    'tc_elements' => '',
                    'tc_navigation_color' => '',
                    'tc_loop' => '',
                    'tc_autoplay' => '',
                    'tc_autoplay_speed' => '',
                    'tc_stoponhover' => '',
                    'tc_extra_class' => '',
                ), $atts));

                $output = '';

                global $global_design_style;
                $global_design_style = $design_style;

                $kd_tcunique_id = "kd-teamc-".uniqid();

                $output = '
                <div class="team-carousel '.$kd_tcunique_id.' tc-parent '.$tc_navigation_color.' '.$tc_extra_class.'">
                    <div class="tc-content">'.do_shortcode($content).'</div>
                </div>';

                $output .= '<script type="text/javascript">
                  jQuery(document).ready(function($){
                    if ($(".team-carousel.'.$kd_tcunique_id.' .tc-content").length) {
                      $(".team-carousel.'.$kd_tcunique_id.' .tc-content").owlCarousel({
                        stageClass: "owl-wrapper",
                        stageOuterClass: "owl-wrapper-outer",
                        loadedClass: "owl-carousel",';
                        if (is_rtl()) {
                            $output .= 'rtl: true,';
                          }
                          $output .= 'responsive:{
                            0:{
                                items:1,
                            },
                            768:{
                                items:2,
                            },
                            1199:{
                                items:3,
                            },
                            1366:{
                                items: '.$tc_elements.',
                            }
                        },
                        slideBy: 1,';

                        if ( $tc_loop != "loop_off" ) {
                            $output .= 'loop: true,';
                        }

                        $output .= 'nav: true,
                        navSpeed: 500,
                        dots: false,';

                        if ( $tc_autoplay == "auto_on" ) {
                  				$output .= 'autoplay: true,
                          rewind: true,';
                  			}

                        if ( $tc_autoplay_speed !== "" ) {
                  				$output .= 'autoplayTimeout: '.$tc_autoplay_speed.',';
                  			}

                        if ( $tc_autoplay == "auto_on" && $tc_stoponhover == "hover_on" ) {
                            $output .= 'autoplayHoverPause: true,';
                        }

                        $output .='
                        addClassActive: true,
                      });
                    }
                  });
          			</script>';

                return $output;
        }

        public function kd_teamcarousel_single($atts, $content = null) {
            global $global_design_style;

            $output = '';

            if ($GLOBALS['global_design_style'] == 'classic' || $GLOBALS['global_design_style'] == 'creative') {
              require_once(KEYDESIGN_PLUGIN_PATH.'/elements/templates/team-elem/team-'.$GLOBALS['global_design_style'].'.php');
              $template_func = 'kd_team_set_'.$GLOBALS['global_design_style'];
        			$output .= $template_func($atts,$content);
            }

            return $output;
        }
    }
}
if (class_exists('tek_teamcarousel')) {
    $tek_teamcarousel = new tek_teamcarousel;
}
?>
