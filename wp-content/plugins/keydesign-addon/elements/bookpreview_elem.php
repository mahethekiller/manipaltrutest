<?php

if (!class_exists('KD_ELEM_BOOK_PREVIEW')) {

    class KD_ELEM_BOOK_PREVIEW extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_bookpreview_init'));
            add_shortcode('tek_bookpreview', array($this, 'kd_bookpreview_shrt'));
        }

        // Element configuration in admin

        function kd_bookpreview_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Book Preview", "keydesign"),
                    "description" => esc_html__("Book chapter preview in a device mockup.", "keydesign"),
                    "base" => "tek_bookpreview",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/book-preview.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(
                        array(
                            "type" =>	"dropdown",
                            "class" =>	"",
                            "heading" =>	esc_html__("Enable content scrolling", "keydesign"),
                            "param_name" =>	"bp_scroll",
                            "value"	 =>	array(
                                    "On" => "scroll-on",
                                    "Off" => "scroll-off",
                                ),
                            "save_always" => true,
                            "description" => esc_html__("When active the content in the book mockup area will smoothly scroll down.", "keydesign")
                        ),

                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Mockup image source", "keydesign"),
                            "param_name" => "bp_mockup_source",
                            "value" => array(
                                "Media library" => "media_library",
                                "External link" => "external_link",
                            ),
                            "description" => esc_html__("Select image source.", "keydesign"),
                            "save_always" => true,
                        ),

                        array(
                            "type" => "attach_image",
			                      "class" => "",
                            "heading" => esc_html__("Upload device mockup", "keydesign"),
                            "param_name" => "bp_mockup",
	                          "value" => "",
                            "description" => esc_html__("Upload container mockup.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "bp_mockup_source",
                                "value" => array("media_library")
                            ),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Mockup image external source", "keydesign"),
                            "param_name" => "bp_mockup_ext",
                            "value" => "",
		                        "description" => esc_html__("Enter mockup image external link.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "bp_mockup_source",
                                "value" => array("external_link")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Mockup Image size", "keydesign"),
                            "param_name" => "ext_image_size",
                            "value" => "",
		                        "description" => esc_html__("Enter image size in pixels. Example: 590x700 (Width x Height).", "keydesign"),
                            "dependency" =>	array(
                                "element" => "bp_mockup_source",
                                "value" => array("external_link")
                            ),
                        ),

                        array(
                            "type" => "textarea_html",
                            "class" => "",
                            "heading" => esc_html__("Book text", "keydesign"),
                            "param_name" => "content",
                            "value" => "",
                            "description" => esc_html__("Enter a short presentation of the book. HTML tags are allowed.", "keydesign"),
                        ),

                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "bp_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                    )
                ));
            }
        }



		// Render the element on front-end

        public function kd_bookpreview_shrt( $atts, $content = null ) {
            extract( shortcode_atts( array(
                'bp_mockup_source' => '',
                'bp_mockup' => '',
                'bp_mockup_ext' => '',
                'ext_image_size' => '',
                'bp_scroll' => '',
                'bp_extra_class' => '',
            ), $atts ) );

            $mockup_img = $output = $dimensions = $hwstring = '';

            if ( '' !=  $bp_mockup ) {
                $mockup_img = wpb_getImageBySize( $params = array(
                    'post_id' => NULL,
                    'attach_id' => $bp_mockup,
                    'thumb_size' => 'full',
                    'class' => ""
                ) );
            }

            $default_src = vc_asset_url( 'vc/no_image.png' );

            $dimensions = vc_extract_dimensions( $ext_image_size );
            $hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';
            $bp_mockup_ext = $bp_mockup_ext ? esc_attr( $bp_mockup_ext ) : $default_src;

            if ( $bp_scroll != "scroll-off" ) {
              $output .= '<script type="text/javascript">
                jQuery(document).ready(function($){
                  if ($(".bp-content").length) {
                    setInterval(function(){
                      var pos = $(".bp-content").scrollTop();
                      $(".bp-content").scrollTop(pos + 1);
                    }, 30)
                  }
                });</script>';
            }

            $output .= '<div class="bp-container ' . $bp_extra_class . '">';
              if ($bp_mockup_source == 'external_link' && $bp_mockup_ext != '') {
                $output .= '<img src="'.$bp_mockup_ext.'" '.$hwstring.' />';
              } elseif ($bp_mockup_source == 'media_library' && $mockup_img != '') {
      					$output .= $mockup_img['thumbnail'];
              }
      				$output .= '<div class="bp-content">'.do_shortcode($content).'</div>
      			</div>';

            return $output;

        }
    }
}

if (class_exists('KD_ELEM_BOOK_PREVIEW')) {
    $KD_ELEM_BOOK_PREVIEW = new KD_ELEM_BOOK_PREVIEW;
}

?>
