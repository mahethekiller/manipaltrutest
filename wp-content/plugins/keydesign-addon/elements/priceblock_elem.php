<?php

if (!class_exists('KD_ELEM_PRICE_BLOCK')) {

    class KD_ELEM_PRICE_BLOCK extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_priceblock_init'));
            add_shortcode('tek_priceblock', array($this, 'kd_priceblock_shrt'));
        }

        // Element configuration in admin

        function kd_priceblock_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Price block", "keydesign"),
                    "description" => esc_html__("Price block with thumb image.", "keydesign"),
                    "base" => "tek_priceblock",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/price-block.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(

                         array(
                             "type" => "textfield",
                             "class" => "kd-back-desc",
                             "heading" => esc_html__("Title", "keydesign"),
                             "param_name" => "pb_title",
                             "holder" => "div",
                             "value" => "",
		                         "description" => esc_html__("Price block title.", "keydesign"),
                             "group" => esc_html__("Content", "viva-addon"),
                         ),
                         array(
                          "type" => "checkbox",
                          "heading" => esc_html__( "Add link on title?", "viva-addon" ),
                          "param_name" => "pb_link_settings",
                          "value" => array( esc_html__( "Yes", "viva-addon" ) => "yes" ),
                          "group" => esc_html__("Content", "viva-addon"),
                        ),
                         array(
                             "type" => "href",
                             "class" => "",
                             "heading" => esc_html__("Link URL", "viva-addon"),
                             "param_name" => "pb_title_link",
                             "value" => "",
                             "description" => esc_html__("Enter URL (Note: parameters like \"mailto:\" are also accepted).", "viva-addon"),
                             "dependency" => array(
                                "element" => "pb_link_settings",
                                "value"	=> array( "yes" ),
                            ),
                            "group" => esc_html__("Content", "viva-addon"),
                         ),

                         array(
                            'type' => 'dropdown',
                            'heading' => __( 'Link target', 'viva-addon' ),
                            'param_name' => 'pb_link_target',
                             "value" => array(
                              esc_html__( 'Same window', 'viva-addon' ) => '_self',
                              esc_html__( 'New window', 'viva-addon' ) => '_blank',
                            ),
                             "save_always" => true,
                             "dependency" => array(
                               "element" => "pb_link_settings",
                               "value"	=> array( "yes" ),
                            ),
                            "group" => esc_html__("Content", "viva-addon"),
                        ),
                          array(
                              "type" => "textarea",
                              "class" => "",
                              "heading" => esc_html__("Description", "keydesign"),
                              "param_name" => "pb_description",
                              "value" => "",
                              "description" => esc_html__("Price block description.", "keydesign"),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "dropdown",
                              "class" => "",
                              "heading" => esc_html__("Image settings", "keydesign"),
                              "param_name" => "image_source",
                              "value" => array(
                                  "Media library" => "media_library",
                                  "External link" => "external_link",
                              ),
                              "description" => esc_html__("Select image source.", "keydesign"),
                              "save_always" => true,
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "attach_image",
                              "class" => "",
                              "heading" => esc_html__("Image", "keydesign"),
                              "param_name" => "pb_image",
                              "value" => "",
                              "description" => esc_html__("Select or upload your image using the media library.", "keydesign"),
                              "dependency" =>	array(
                                  "element" => "image_source",
                                  "value" => array("media_library")
                              ),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "textfield",
                              "class" => "",
                              "heading" => esc_html__("External image", "keydesign"),
                              "param_name" => "ext_image",
                              "value" => "",
                              "description" => esc_html__("Enter image external link.", "keydesign"),
                              "dependency" =>	array(
                                  "element" => "image_source",
                                  "value" => array("external_link")
                              ),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "kd_param_title",
                              "title" => "Price options",
                              "param_name" => "price_options_section",
                              "group" => esc_html__( "Content", "viva-addon" ),
                          ),
                          array(
                              "type" => "textfield",
                              "class" => "",
                              "heading" => esc_html__("Price", "keydesign"),
                              "param_name" => "pb_price",
                              "admin_label" => true,
                              "value" => "",
 		                         "description" => esc_html__("Enter product price.", "keydesign"),
                             "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "dropdown",
                              "class" => "",
                              "heading" => esc_html__("Currency", "keydesign"),
                              "param_name" => "pb_currency",
                              "value" => array(
                                  "Dollar" => "currency-dollar",
                                  "Euro" => "currency-euro",
                                  "Pound" => "currency-pound",
                                  "Other" => "currency-other",
                              ),
                              "save_always" => true,
                              "description" => esc_html__("Select price currency.", "keydesign"),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "textfield",
                              "class" => "",
                              "heading" => esc_html__("Other currency", "keydesign"),
                              "param_name" => "pb_other_currency",
                              "value" => "",
                              "dependency" =>	array(
                                  "element" => "pb_currency",
                                  "value" => array( "currency-other" ),
                              ),
                              "description" => esc_html__("Pricing block custom currency.", "keydesign"),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "dropdown",
                              "class" => "",
                              "heading" => esc_html__("Currency position", "keydesign"),
                              "param_name" => "pb_currency_position",
                              "value" => array(
                                  "Left" => "currency-position-left",
                                  "Right" => "currency-position-right"
                              ),
                              "save_always" => true,
                              "description" => esc_html__("Select price block currency symbol position.", "keydesign"),
                              "group" => esc_html__("Content", "viva-addon"),
                          ),
                          array(
                              "type" => "colorpicker",
                              "class" => "",
                              "heading" => esc_html__("Title color", "keydesign"),
                              "param_name" => "pb_title_color",
                              "value" => "",
                              "description" => esc_html__("Select title color. If none selected, the default theme color will be used.", "keydesign"),
                              "group" => esc_html__("Style", "viva-addon"),
                           ),
                           array(
                               "type" => "colorpicker",
                               "class" => "",
                               "heading" => esc_html__("Description color", "keydesign"),
                               "param_name" => "pb_description_color",
                               "value" => "",
                               "description" => esc_html__("Select description color. If none selected, the default theme color will be used.", "keydesign"),
                               "group" => esc_html__("Style", "viva-addon"),
                            ),
                          array(
                              "type" => "colorpicker",
                              "class" => "",
                              "heading" => esc_html__("Price color", "keydesign"),
                              "param_name" => "pb_price_color",
                              "value" => "",
                              "description" => esc_html__("Select pret color. If none selected, the default theme color will be used.", "keydesign"),
                              "group" => esc_html__("Style", "viva-addon"),
                          ),
                          array(
                              "type" => "colorpicker",
                              "class" => "",
                              "heading" => esc_html__("Box background color", "keydesign"),
                              "param_name" => "pb_background_color",
                              "value" => "",
                              "description" => esc_html__("Select box background color. If none selected, the default theme color will be used.", "keydesign"),
                              "group" => esc_html__("Style", "viva-addon"),
                          ),
                          array(
                              'type' => 'css_editor',
                              'heading' => esc_html__( 'Css', 'keydesign' ),
                              'param_name' => 'css',
                              'group' => esc_html__( 'Design options', 'keydesign' ),
                          ),
                          array(
                              "type" => "dropdown",
                              "class" => "",
                              "heading" => esc_html__("CSS animation", "keydesign"),
                              "param_name" => "css_animation",
                              "value" => array(
                                  "No"              => "no_animation",
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
                                  "0 ms" => "",
                                  "200 ms" => "200",
                                  "400 ms" => "400",
                                  "600 ms" => "600",
                                  "800 ms" => "800",
                                  "1 s" => "1000",
                              ),
                              "dependency" =>	array(
                                  "element" => "css_animation",
                                  "value" => array("kd-animated fadeIn", "kd-animated fadeInDown", "kd-animated fadeInLeft", "kd-animated fadeInRight", "kd-animated fadeInUp", "kd-animated zoomIn")
                              ),
                              "save_always" => true,
                              "admin_label" => true,
                              "description" => esc_html__("Enter animation delay in ms", "keydesign"),
                              "group" => esc_html__("Extras", "keydesign"),
                          ),
                          array(
                              "type" => "textfield",
                              "class" => "",
                              "heading" => esc_html__("Extra class name", "keydesign"),
                              "param_name" => "pb_extra_class",
                              "value" => "",
                              "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                              "group" => esc_html__("Extras", "keydesign"),
                          ),
                    )
                ));
            }
        }



		// Render the element on front-end

        public function kd_priceblock_shrt($atts, $content = null) {

            // Include required JS and CSS files
	          wp_enqueue_script('kd_jquery_appear');

            // Declare empty vars
            $output = $pb_img_array = $product_image = $currency_symbol = $content_image = $animation_delay = $css_class = $wrapper_class = '';
            $title_output_start = $title_output_end = $title_link_output_start = $title_link_output_end = $block_img_array = '';

            extract(shortcode_atts(array(
              'pb_title' => '',
              'pb_link_settings' => '',
              'pb_title_link' => '',
              'pb_link_target' => '',
              'pb_title_color' => '',
              'pb_description' => '',
              'pb_description_color' => '',
              'image_source' => '',
              'pb_image' => '',
              'ext_image' => '',
              'pb_price' => '',
              'pb_currency' => '',
              'pb_other_currency' => '',
              'pb_currency_position' => '',
              'pb_price_color' => '',
              'pb_background_color' => '',
              'css' => '',
              'css_animation' => '',
              'elem_animation_delay' => '',
              'pb_extra_class' => '',
            ), $atts));

            $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $atts );

            switch( $pb_currency ) {
              case 'currency-dollar':
                $currency_symbol = "&#36;";
              break;

              case 'currency-euro':
                $currency_symbol = "&#128;";
              break;

              case 'currency-pound':
                $currency_symbol = "&#163;";
              break;

              case 'currency-other':
                $currency_symbol = $pb_other_currency;
              break;

              default:
            }

            $default_src = vc_asset_url( 'vc/no_image.png' );
            if ( $image_source == 'external_link' ) {
                if ( !$ext_image ) {
                    $content_image .='<img src="'.$default_src.'" class="vc_img-placeholder" />';
                } else {
                    $content_image .='<img src="'.$ext_image.'" />';
                }
            } else {
                if ( '' != $pb_image && wp_attachment_is_image( $pb_image ) ) {
                    $block_img_array = wpb_getImageBySize ( $params = array( 'post_id' => NULL, 'attach_id' => $pb_image, 'thumb_size' => 'full', 'class' => "" ) );
    				$content_image = $block_img_array['thumbnail'];
                }
            }

            // Title output
            if ( '' != $pb_title ) {
              $title_output_start = '<h5 '.(!empty($pb_title_color) ? 'style="color: '.$pb_title_color.';"' : '').'>';
              $title_output_end = '</h5>';
            }

            // Title link output
            if ( !empty( $pb_link_settings ) && $pb_title_link != '' ) {
              $title_link_output_start = '<a href="'.$pb_title_link.'" target="'.$pb_link_target.'" '.(!empty($pb_title_color) ? 'style="color: '.$pb_title_color.';"' : '').'>';
              $title_link_output_end = '</a>';
            }

            //CSS Animation
            if ( $css_animation == "no_animation" ) {
                $css_animation = "";
            }

            // Animation delay
            if ( $elem_animation_delay ) {
                $animation_delay = 'data-animation-delay='.$elem_animation_delay;
            }

            $wrapper_class = implode( ' ', array( 'kd-price-block', $css_animation, $css_class, $pb_extra_class ) );

            $output = '<div class="'.trim( $wrapper_class ).'" '.(!empty($pb_background_color) ? 'style="background-color: '.$pb_background_color.';"' : '').' '.$animation_delay.'>';
                if ($content_image != '') {
                    $output .= '<div class="pb-image-wrap">'.$content_image.'</div>';
                }
                $output .= '<div class="pb-content-wrap">';
                    $output .= $title_output_start . $title_link_output_start . $pb_title . $title_link_output_end . $title_output_end;
                    $output .= '<div class="pb-dots"></div>';
                    if ($pb_currency_position == "currency-position-left") {
    				          $output .= '<div class="pb-pricing-wrap"><h5 class="pb-price" '.(!empty($pb_price_color) ? 'style="color: '.$pb_price_color.';"' : '').'><span class="pb-currency">'.$currency_symbol.'</span>'.$pb_price.'</h5></div>';
                    } elseif ($pb_currency_position == "currency-position-right") {
                      $output .= '<div class="pb-pricing-wrap"><h5 class="pb-price" '.(!empty($pb_price_color) ? 'style="color: '.$pb_price_color.';"' : '').'>'.$pb_price.'<span class="pb-currency">'.$currency_symbol.'</span></h5></div>';
                    }
                $output .= '</div>';
                $output .= '<div class="pb-desc-wrap" '.(!empty($pb_description_color) ? 'style="color: '.$pb_description_color.';"' : '').'>'.$pb_description.'</div>
            </div>';

            return $output;

        }
    }
}

if (class_exists('KD_ELEM_PRICE_BLOCK')) {
    $KD_ELEM_PRICE_BLOCK = new KD_ELEM_PRICE_BLOCK;
}

?>
