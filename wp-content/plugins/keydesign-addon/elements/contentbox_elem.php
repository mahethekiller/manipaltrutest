<?php

if (!class_exists('KD_ELEM_CONTENT_BOX')) {

    class KD_ELEM_CONTENT_BOX extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_contentbox_init'));
            add_shortcode('tek_contentbox', array($this, 'kd_contentbox_shrt'));
        }

        // Element configuration in admin

        function kd_contentbox_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Content box", "keydesign"),
                    "description" => esc_html__("Content box with icon or custom image.", "keydesign"),
                    "base" => "tek_contentbox",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/content-box.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(

                        array(
                            "type" => "textarea",
                            "class" => "kd-back-desc",
                            "heading" => esc_html__("Box title", "keydesign"),
                            "param_name" => "cb_title",
                            "holder" => "div",
                            "value" => "",
                            "description" => esc_html__("Enter a box title text.", "keydesign")
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Title color", "keydesign"),
                            "param_name" => "cb_title_color",
                            "value" => "",
                            "description" => esc_html__("Choose title color. If none selected, the default theme color will be used.", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Box subtitle", "keydesign"),
                            "param_name" => "cb_subtitle",
                            "value" => "",
                            "description" => esc_html__("Enter a box subtitle text.", "keydesign")
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Subtitle color", "keydesign"),
                            "param_name" => "cb_subtitle_color",
                            "value" => "",
                            "description" => esc_html__("Choose subtitle color. If none selected, the default theme color will be used.", "keydesign"),
                        ),

                        array(
                            "type" => "textarea",
                            "class" => "",
                            "heading" => esc_html__("Box content text", "keydesign"),
                            "param_name" => "cb_content_text",
                            "value" => "",
                            "description" => esc_html__("Enter box content text here.", "keydesign")
                        ),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Content text color", "keydesign"),
                            "param_name" => "cb_content_text_color",
                            "value" => "",
                            "description" => esc_html__("Choose content text color. If none selected, the default theme color will be used.", "keydesign"),
                        ),

                        array(
                            "type"			=>	"dropdown",
                            "class"			=>	"",
                            "heading"		=>	esc_html__("Display icon","keydesign"),
                            "param_name"	=>	"icon_type",
                            "value"			=>	array(
                                "Icon Browser" => "icon_browser",
                                "Custom Image" => "custom_icon",
                                "No icon" => "no_icon",
                            ),
                            "save_always" => true,
                            "description"	=>	esc_html__("Select icon/image source. If no icon is selected the box hover effect will be disabled.", "keydesign")
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
  								"element" => "icon_type",
  								"value" => "icon_browser",
  							),
  							"description" => esc_html__( "Select icon from library.", "keydesign" ),
						),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Icon color", "keydesign"),
                            "param_name" => "icon_color",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Choose icon color. If none selected, the default theme color will be used.", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Icon size", "keydesign"),
                            "param_name" => "icon_size",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "icon_type",
                                "value" => array("icon_browser")
                            ),
                            "description" => esc_html__("Enter icon size. (eg. 10px, 1em, 1rem)", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Content alignment", "keydesign"),
                            "param_name" => "cb_content_alignment",
                            "value" => array(
                                "Left" => "cb-cont-left",
                                "Center" => "cb-cont-center",
                            ),
                            "description" => esc_html__("Select content alignment.", "keydesign"),
                            "save_always" => true,
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Custom image source", "keydesign"),
                            "param_name" => "cb_image_source",
                            "value" => array(
                                "Media library" => "media_library",
                                "External link" => "external_link",
                            ),
                            "dependency" => array(
                                "element" => "icon_type",
                                "value" => array("custom_icon"),
                            ),
                            "description" => esc_html__("Select image source.", "keydesign"),
                            "save_always" => true,
                        ),

                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => esc_html__("Upload image icon", "keydesign"),
                            "param_name" => "icon_img",
                            "value" => "",
                            "description" => esc_html__("Upload custom image.", "keydesign"),
                            "dependency" => array(
                                "element" => "cb_image_source",
                                "value" => array("media_library"),
                            ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Image external source", "keydesign"),
                            "param_name" => "cb_ext_image",
                            "value" => "",
                            "description" => esc_html__("Enter image external link.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "cb_image_source",
                                "value" => array("external_link"),
                            ),
                        ),

                        array(
                             "type"	=>	"dropdown",
                             "class" =>	"",
                             "heading" => esc_html__("Box link type", "keydesign"),
                             "param_name" => "cb_custom_link",
                             "value" =>	array(
                                esc_html__( 'No link', 'keydesign' ) => '#',
                                esc_html__( 'Full box link', 'keydesign' )	=> 'box-link',
                            ),
                             "save_always" => true,
                             "description" => esc_html__("You can add or remove the custom link.", "keydesign"),
                        ),

                        array(
                            "type" => "href",
                            "class" => "",
                            "heading" => esc_html__("Link URL", "keydesign"),
                            "param_name" => "cb_box_link",
                            "value" => "",
                            "description" => esc_html__("Enter URL (Note: parameters like \"mailto:\" are also accepted).", "keydesign"),
                            "dependency" => array(
                               "element" => "cb_custom_link",
                               "value"	=> array( "box-link" ),
                           ),
                        ),

                        array(
                  			'type' => 'dropdown',
                  			'heading' => __( 'Link target', 'keydesign' ),
                  			'param_name' => 'cb_link_target',
                            "value" => array(
								esc_html__( 'Same window', 'keydesign' ) => '_self',
								esc_html__( 'New window', 'keydesign' ) => '_blank',
							),
                            "dependency" => array(
                               "element" => "cb_custom_link",
                               "value"	=> array( "box-link" ),
                            ),
                		),

                        array(
  							"type" => "dropdown",
  							"class" => "",
  							"heading" => esc_html__("Background type", "keydesign"),
  							"param_name" =>	"background_type",
  							"value" =>	array(
  								esc_html__( 'None', 'keydesign' ) => 'none',
  								esc_html__( 'Solid color', 'keydesign' )	=> 'custom_bg_color',
                                esc_html__( 'Background image', 'keydesign' )	=> 'custom_bg_image',
  							),
  							"save_always" => true,
  							"description" => esc_html__("Select box background type.", "keydesign"),
						),

                        array(
                            "type" => "colorpicker",
                            "class" => "",
                            "heading" => esc_html__("Background color", "keydesign"),
                            "param_name" => "background_color",
                            "value" => "",
                            "dependency" =>	array(
								"element" => "background_type",
								"value" => array( "custom_bg_color" ),
					        ),
                            "description" => esc_html__("Choose box background color.", "keydesign"),
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Background image source", "keydesign"),
                            "param_name" => "background_image_source",
                            "value" => array(
                                "Media library" => "media_library",
                                "External link" => "external_link",
                            ),
                            "dependency" => array(
                                "element" => "background_type",
                                "value" => array("custom_bg_image"),
                            ),
                            "description" => esc_html__("Select image source.", "keydesign"),
                            "save_always" => true,
                        ),

                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => esc_html__("Image", "keydesign"),
                            "param_name" => "background_image",
                            "value" => "",
                            "description" => esc_html__("Upload custom image.", "keydesign"),
                            "dependency" => array(
                                "element" => "background_image_source",
                                "value" => array("media_library"),
                            ),
                            "save_always" => true,
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Image external source", "keydesign"),
                            "param_name" => "background_ext_image",
                            "value" => "",
                            "description" => esc_html__("Enter image external link.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "background_image_source",
                                "value" => array("external_link"),
                            ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Top padding", "keydesign"),
                            "param_name" => "top_padding",
                            "value" => "",
                            "description" => esc_html__("Add extra spacing between the top edge of the element and the inner contents.", "keydesign"),
                            "group" => esc_html__( "Design", "keydesign" ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Bottom padding", "keydesign"),
                            "param_name" => "bottom_padding",
                            "value" => "",
                            "description" => esc_html__("Add extra spacing between the bottom edge of the element and the inner contents.", "keydesign"),
                            "group" => esc_html__( "Design", "keydesign" ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Left padding", "keydesign"),
                            "param_name" => "left_padding",
                            "value" => "",
                            "description" => esc_html__("Add extra spacing between the left edge of the element and the inner contents.", "keydesign"),
                            "group" => esc_html__( "Design", "keydesign" ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Right padding", "keydesign"),
                            "param_name" => "right_padding",
                            "value" => "",
                            "description" => esc_html__("Add extra spacing between the right edge of the element and the inner contents.", "keydesign"),
                            "group" => esc_html__( "Design", "keydesign" ),
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
                                "0 ms" => "",
                                "200 ms" => "200",
                                "400 ms" => "400",
                                "600 ms" => "600",
                                "800 ms" => "800",
                                "1 s" => "1000",
                            ),
                            "dependency" => array(
                                "element" => "css_animation",
                                "value" => array ("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn"),
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
                            "param_name" => "cb_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),

                    )
                ));
            }
        }



		// Render the element on front-end

        public function kd_contentbox_shrt($atts, $content = null) {
            // Declare empty vars
            $output = $content_icon = $icon_color_style = $icon_size_style = $src = $link = $image = $animation_delay = $icons = $a_attrs = $subtitle_class = $no_icon_class_fix = $wrapper_class = $bg_image_class =
            $exploded = $iconsmind_cat = $box_wrapper_style = '';

            extract( shortcode_atts( array(
                'icon_type' => '',
                'icon_iconsmind' => '',
                'icon_color' => '',
                'icon_size' => '',
                'cb_image_source' => '',
                'icon_img' => '',
                'cb_ext_image' => '',
                'cb_title' => '',
                'cb_title_color' => '',
                'cb_subtitle' => '',
                'cb_subtitle_color' => '',
                'cb_content_text' => '',
                'cb_content_text_color' => '',
                'cb_custom_link' => '',
                'cb_box_link' => '',
                'cb_link_target' => '',
                'cb_button_text' => '',
                'background_type' => '',
                'background_color' => '',
                'background_image_source' => '',
                'background_image' => '',
                'background_ext_image' => '',
                'cb_content_alignment' => '',
                'top_padding' => '',
                'bottom_padding' => '',
                'left_padding' => '',
                'right_padding' => '',
                'css_animation' => '',
                'elem_animation_delay' => '',
                'cb_extra_class' => '',
            ), $atts ) );

            if ( $icon_type == 'icon_browser' ) {
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

            if (strlen($icon_iconsmind) > 0) {
                $icons = $icon_iconsmind;
            }

            if ($icon_color !== '') {
              $icon_color_style = 'color: '.$icon_color.';';
            }

            if ($icon_size !== '') {
              $icon_size_style = 'font-size: '.$icon_size.';';
            }
            if ( '' != $icon_img ) {
                $image = wpb_getImageBySize($params = array(
                    'post_id' => NULL,
                    'attach_id' => $icon_img,
                    'thumb_size' => 'full',
                    'class' => ""
                ));
            }

            if ( $cb_image_source == 'external_link' && !empty( $cb_ext_image ) ) {
              $src = $cb_ext_image;
              list( $width, $height ) = getimagesize( $src );
            } elseif ( $cb_image_source == 'media_library' && !empty( $icon_img ) ) {
              $link = wp_get_attachment_image_src( $icon_img, 'large' );
              $link = $link[0];
              $src = $link;
            }

            if ( $icon_type == 'icon_browser' && !empty($icons) ) {
  				$content_icon = '<i class="'.$icons .'" style="'.$icon_size_style.' '.$icon_color_style.'"></i> ';
  			} elseif( $icon_type == 'custom_icon' ) {
                if ($cb_image_source == 'external_link' && $cb_ext_image != '') {
                    $content_icon = '<div class="cb-customimg"><img src="'.$cb_ext_image.'" width="'.$width.'" height="'.$height.'" /></div>';
                } elseif ( $cb_image_source == 'media_library' && !empty( $icon_img ) ) {
                    $content_icon = '<div class="cb-customimg">'.$image['thumbnail'].'</div>';
                }
  			}

            // Box background image settings

            if ( $background_image_source == 'media_library' && '' != $background_image ) {
                $bg_image = intval( $background_image );
            	$bg_image_data = wp_get_attachment_image_src( $bg_image, 'large' );
            	$bg_image_src = $bg_image_data[0];
            }

            if ( $background_image_source == 'external_link' && '' != $background_ext_image ) {
                $box_bg_image = $background_ext_image;
            } elseif ( $background_image_source == 'media_library' && '' != $background_image ) {
                $box_bg_image = $bg_image_src;
            }

            if ( !empty( $box_bg_image ) ) {
              $bg_image_class = 'with-bg-img';
            } else {
              $bg_image_class = '';
            }

            //Background type
            switch( $background_type ) {
  				case 'none':
  					$normal_bg = 'background-color:#fff;';
  				break;

  				case 'custom_bg_color':
                    if ( $background_color != '' ) {
                        $normal_bg = 'background-color: '.$background_color . ';';
                    } else {
                        $normal_bg = 'background-color:#fff;';
                    }
  				break;

                case 'custom_bg_image':
                    if ( $box_bg_image != '' ) {
                        $normal_bg = 'background-image: url('.$box_bg_image.');';
                    } else {
                        $normal_bg = 'background-color:#fff;';
                    }
                break;

  				default:
  			}

            //CSS Animation
            if ( $css_animation == "no_animation" ) {
                $css_animation = "";
            }

            // Animation delay
            if ( $elem_animation_delay ) {
                $animation_delay = 'data-animation-delay='.$elem_animation_delay;
            }

            if ( $cb_subtitle ) {
                $subtitle_class = 'with-subtitle';
            }

            if ( $icon_type == "no_icon" ) {
              $no_icon_class_fix = 'cb-no-icon';
            }

            // Box wrapper spacing
            if ( $top_padding ) {
                $box_wrapper_style .= 'padding-top:'.$top_padding.';';
            }

            if ( $bottom_padding ) {
                $box_wrapper_style .= 'padding-bottom:'.$bottom_padding.';';
            }

            if ( $left_padding ) {
                $box_wrapper_style .= 'padding-left:'.$left_padding.';';
            }

            if ( $right_padding ) {
                $box_wrapper_style .= 'padding-right:'.$right_padding.';';
            }

            if ( !empty( $background_type ) ) {
                $box_wrapper_style .= $normal_bg;
            }

            $wrapper_class = implode( ' ', array( 'cb-container', $cb_content_alignment, $no_icon_class_fix, $bg_image_class, $css_animation, $cb_extra_class ) );

            $output .= '<div class="'.trim( $wrapper_class ).'" '.(!empty( $box_wrapper_style ) ? 'style="'.$box_wrapper_style.'"' : '').' '.$animation_delay.'>';

                if ( $cb_custom_link == "box-link" && $cb_box_link != '' ) {
                    $output .= '<a href="'.$cb_box_link.'" target="'.$cb_link_target.'">';
                }

                if ( $icon_type != "no_icon" ) {
                  $output .= '<div class="cb-img-area">'.$content_icon.'</div>';
                }
                $output .= '<div class="cb-text-area">
                    <h4 class="cb-heading '.$subtitle_class.'" '.( !empty( $cb_title_color ) ? 'style="color: '.$cb_title_color.';"' : '').'>'.$cb_title.'</h4>';
                    if ($cb_subtitle) {
                      $output .= '<h5 class="cb-subtitle" '.( !empty( $cb_subtitle_color ) ? 'style="color: '.$cb_subtitle_color.';"' : '').'>'.$cb_subtitle.'</h5>';
                    }
                    $output .= '<p '.( !empty( $cb_content_text_color ) ? 'style="color: '.$cb_content_text_color.';"' : '').'>'.$cb_content_text.'</p>';
                $output .= '</div>';

                if ( $cb_custom_link == "box-link" && $cb_box_link != '' ) {
                    $output .= '</a>';
                }

            $output .= '</div>';

            return $output;

        }
    }
}

if ( class_exists( 'KD_ELEM_CONTENT_BOX' ) ) {
    $KD_ELEM_CONTENT_BOX = new KD_ELEM_CONTENT_BOX;
}

?>
