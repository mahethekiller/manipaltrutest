<?php

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_tek_testimonials extends WPBakeryShortCodesContainer {
    }
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_tek_testimonials_single extends WPBakeryShortCode {
    }
}

if (!class_exists('tek_testimonials')) {
    class tek_testimonials extends KEYDESIGN_ADDON_CLASS
    {
        function __construct() {
            add_action('init', array($this, 'kd_testimonials_init'));
            add_shortcode('tek_testimonials', array($this, 'kd_testimonials_container'));
            add_shortcode('tek_testimonials_single', array($this, 'kd_testimonials_single'));
        }
        // Element configuration in admin

        function kd_testimonials_init() {
            // Container element configuration
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Testimonial slider", "keydesign"),
                    "description" => esc_html__("Sliding testimonials with author image.", "keydesign"),
                    "base" => "tek_testimonials",
                    "class" => "kd-outer-controls",
                    "show_settings_on_create" => true,
                    "content_element" => true,
                    "as_parent" => array('only' => 'tek_testimonials_single'),
                    "icon" => plugins_url('assets/element_icons/testimonials.png', dirname(__FILE__)),
                    "category" => esc_html("KeyDesign Elements", "keydesign"),
                    "params" => array(
                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Testimonials layout","keydesign"),
                            "param_name"    =>  "tt_image_layout",
                            "value"         =>  array(
                                    "With image" => "with-image",
                                    "No image" => "without-image",
                                ),
                            "save_always" => true,
                            "description"   =>  esc_html__("Select layout - with or without image.", "keydesign")
                        ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Navigation style","keydesign"),
                            "param_name"    =>  "tt_nav_style",
                            "value"         =>  array(
                                    "Arrows" => "nav-arrows",
                                    "Dots" => "nav-dots",
                                    "Arrows and dots" => "nav-arrows-dots",
                                ),
                            "save_always" => true,
                            "description"   =>  esc_html__("Select navigation style.", "keydesign")
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Navigation color", "keydesign"),
                            "param_name" => "tt_navigation_color",
                            "value" => array(
                                "Black" => "black-navigation",
                                "White" => "white-navigation",
                            ),
                            "save_always" => true,
                            "description" => esc_html__("Select the navigation color.", "keydesign"),
                         ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Enable autoplay","keydesign"),
                            "param_name"    =>  "tt_autoplay",
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
                            "param_name"    =>  "tt_autoplay_speed",
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
                                "element" => "tt_autoplay",
                                "value" => array("auto_on")
                            ),
                            "description"   =>  esc_html__("Carousel autoplay speed.", "keydesign")
                        ),

                        array(
                            "type"          =>  "dropdown",
                            "class"         =>  "",
                            "heading"       =>  esc_html__("Stop on hover","keydesign"),
                            "param_name"    =>  "tt_stoponhover",
                            "value"         =>  array(
                                    "Off"   => "hover_off",
                                    "On"    => "hover_on"
                                ),
                            "save_always" => true,
                            "dependency" =>	array(
                                "element" => "tt_autoplay",
                                "value" => array("auto_on")
                            ),
                            "description"   =>  esc_html__("Stop sliding carousel on mouse over.", "keydesign")
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("CSS Animation", "keydesign"),
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
                            "param_name" => "tt_animation_delay",
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
                            "param_name" => "tt_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),

                    ),
                    "js_view" => 'VcColumnView'
                ));

                // Shortcode configuration

                vc_map(array(
                    "name" => esc_html__("Testimonial slide", "keydesign"),
                    "base" => "tek_testimonials_single",
                    "content_element" => true,
                    "as_child" => array('only' => 'tek_testimonials'),
                    "icon" => plugins_url('assets/element_icons/testimonials.png', dirname(__FILE__)),
                    "params" => array(

                        array(
                            "type" => "textarea",
                            "heading" => esc_html__("Testimonial text", "keydesign"),
                            "param_name" => "tt_quote",
                            "description" => esc_html__("Testimonial author quote.", "keydesign")
                        ),

                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Author name", "keydesign"),
                            "param_name" => "tt_title",
                            "admin_label" => true,
                            "description" => esc_html__("Testimonial author name.", "keydesign")
                        ),

                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Author description", "keydesign"),
                            "param_name" => "tt_position",
                            "description" => esc_html__("Testimonial author description.", "keydesign")
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Text font size", "keydesign"),
                            "param_name" => "tt_text_size",
                            "value" => "",
                            "description" => esc_html__("Enter text font size. (In px - E.g. 24px)", "keydesign"),
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Text color", "keydesign"),
                            "param_name" => "tt_text_color",
                            "value" => "",
                            "description" => esc_html__("Select text color.", "keydesign")
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Box background color", "keydesign"),
                            "param_name" => "tt_bg_color",
                            "value" => "",
                            "description" => esc_html__("Select box background color.", "keydesign")
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Author image source", "keydesign"),
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
                            "heading" => esc_html__("Author image", "keydesign"),
                            "param_name" => "tt_image",
                            "description" => esc_html__("Display testimonial author image.", "keydesign"),
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
                            "description" => esc_html__("Enter image size in pixels. Example: 1000x500 (Width x Height).", "keydesign"),
                            "dependency" =>	array(
                                "element" => "image_source",
                                "value" => array("external_link")
                            ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "ttc_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                    )
                ));
            }
        }



        public function kd_testimonials_container($atts, $content = null) {

            extract(shortcode_atts(array(
                'tt_image_layout' => '',
                'tt_nav_style' => '',
                'tt_navigation_color' => '',
                'tt_autoplay' => '',
                'tt_autoplay_speed' => '',
                'tt_stoponhover' => '',
                'css_animation' => '',
                'tt_animation_delay' => '',
                'tt_extra_class' => '',
              ), $atts));



            $output = $animation_delay = $wrapper_class = '';

            // Animation delay
            if ($tt_animation_delay) {
                $animation_delay = 'data-animation-delay='.$tt_animation_delay;
            }

            $kd_ttunique_id = "kd-testimonial-".uniqid();

            $wrapper_class = implode(' ', array('slider', 'testimonials', $tt_navigation_color, $kd_ttunique_id, $tt_extra_class, $tt_image_layout, $css_animation));

            $output .= '<div class="'.trim($wrapper_class).'" '.$animation_delay.'>'.do_shortcode($content).'</div>';

            $output .= '<script type="text/javascript">
              jQuery(document).ready(function($){
                if ($(".slider.testimonials.'.$kd_ttunique_id.'").length) {
                  $(".slider.testimonials.'.$kd_ttunique_id.'").owlCarousel({
                    stageClass: "owl-wrapper",
                    stageOuterClass: "owl-wrapper-outer",
                    loadedClass: "owl-carousel",';
                    if (is_rtl()) {
                        $output .= 'rtl: true,';
                    }
                    $output .= 'responsive:{
                        0:{
                            dots: false,
                            nav: true,
                            navSpeed: 500,
                        },
                        960:{';
                            if($tt_nav_style == "nav-arrows") {
                              $output .= 'nav: true,
                              navSpeed: 500,
                              dots: false,';
                            } elseif ($tt_nav_style == "nav-dots") {
                              $output .='dots: true,
                              nav: false,
                              dotsSpeed: 500,';
                            } elseif ($tt_nav_style == "nav-arrows-dots") {
                              $output .= "\n".'nav: true,
                              navSpeed: 500,
                              dots: true,
                              dotsSpeed: 500,
                              dotsEach: true,';
                            }
                        $output .= '}
                    },
                    items: 1,
                    rewind: true,
                    loop: true,';

                    if($tt_autoplay == "auto_on") {
                      $output .= 'autoplay: true,';
                    } else {
                      $output .= 'autoplay: false,';
                    }

                    if($tt_autoplay_speed !== "") {
                      $output .= 'autoplayTimeout: '.$tt_autoplay_speed.',';
                    }

                    if($tt_autoplay == "auto_on" && $tt_stoponhover == "hover_on") {
                      $output .= 'autoplayHoverPause: true,';
                    } else {
                      $output .= 'autoplayHoverPause: false,';
                    }


                    $output .='
                  });
                }
              });
            </script>';

            return $output;
        }



        public function kd_testimonials_single( $atts, $content = null ) {

            extract( shortcode_atts( array(
                'tt_title' => '',
                'tt_quote' => '',
                'tt_position' => '',
                'tt_text_size' => '',
                'tt_text_color' => '',
                'tt_bg_color' => '',
                'image_source' => '',
                'tt_image' => '',
                'ext_image' => '',
                'ext_image_size' => '',
                'ttc_extra_class' => '',
            ), $atts ) );

            $content_image = $default_src = $dimensions = $hwstring = $tt_color_style = $tt_bg_style = $tt_text_style = '';

            if ( $image_source == 'media_library' ) {
                if ( '' != $tt_image && wp_attachment_is_image( $tt_image ) ) {
                    $image  = wpb_getImageBySize($params = array(
                        'post_id' => NULL,
                        'attach_id' => $tt_image,
                        'thumb_size' => 'full',
                        'class' => ""
                    ));
                    $content_image = $image['thumbnail'];
                }
            }

            if ( $image_source == 'external_link' ) {
                if ( !$ext_image ) {
                    $content_image = '<img src="'.$default_src.'" class="vc_img-placeholder" />';
                } else {
                    $content_image = '<img src="'.$ext_image.'" '.$hwstring.' />';
                }
            }

            if ( $tt_text_size !== '' ) {
              $tt_text_style = 'font-size: '.$tt_text_size.';';
            }

            if ( $tt_text_color !== '' ) {
              $tt_color_style = 'color: '.$tt_text_color.';';
            }

            if ( $tt_bg_color !== '' ) {
              $tt_bg_style = 'background-color: '.$tt_bg_color.';';
            }

            $output = '<div class="tt-content '.$ttc_extra_class.'">
              <div class="tt-content-inner">
                <div class="tt-container" style="'.$tt_color_style.' '.$tt_bg_style.'">
                    <h6 style="'. $tt_color_style . ' ' . $tt_text_style .'"">'.$tt_quote.'</h6>
                    <span class="author" '.( !empty( $tt_text_color ) ? 'style="color: ' . $tt_text_color . ';"' : '').'>'.$tt_title.'</span>
                    <span class="content" '.( !empty( $tt_text_color ) ? 'style="color: ' . $tt_text_color . ';"' : '').'>'.$tt_position.'</span>
                </div>
                <div class="tt-image">'.$content_image.'</div>
                </div>
            </div>';

            return $output;
        }
    }
}

if (class_exists('tek_testimonials')) {
    $tek_testimonials = new tek_testimonials;
}

?>
