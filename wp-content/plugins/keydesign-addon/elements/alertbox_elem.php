<?php

if (!class_exists('KD_ELEM_ALERT_BOX')) {

    class KD_ELEM_ALERT_BOX extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_alertbox_init'));
            add_shortcode('tek_alertbox', array($this, 'kd_alertbox_shrt'));
        }

        // Element configuration in admin

        function kd_alertbox_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Alert box", "keydesign"),
                    "description" => esc_html__("Easily display warning, error, info and success messages.", "keydesign"),
                    "base" => "tek_alertbox",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/alert-box.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(
                        array(
                            "type"			=>	"dropdown",
                            "class"			=>	"",
                            "heading"		=>	esc_html__("Box type","keydesign"),
                            "param_name"	=>	"ab_type",
                            "value"			=>	array(
                                    "Warning" => "ab_warning",
                                    "Error" => "ab_error",
                                    "Info" => "ab_info",
                                    "Success" => "ab_success",
                                ),
                            "save_always" => true,
                            "description"	=>	esc_html__("Select box type.", "keydesign")
                        ),
                        array(
                            "type" => "textarea",
                            "class" => "",
                            "heading" => esc_html__("Box message", "keydesign"),
                            "param_name" => "ab_message",
                            "value" => "",
                            "save_always" => true,
                            "description" => esc_html__("Enter box message here.", "keydesign")
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "ab_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                        ),
                    )
                ));
            }
        }



		// Render the element on front-end

        public function kd_alertbox_shrt($atts, $content = null)
        {
            extract(shortcode_atts(array(
                'ab_type' => '',
                'ab_message' => '',
                'ab_extra_class' => '',
            ), $atts));

            $ab_icon = "";

            switch( $ab_type ) {
      				case 'ab_warning':
      					$ab_icon = "far fa-bell";
      				break;

              case 'ab_error':
      					$ab_icon = "fas fa-exclamation-triangle";
      				break;

              case 'ab_info':
      					$ab_icon = "far fa-question-circle";
      				break;

              case 'ab_success':
      					$ab_icon = "fas fa-check";
      				break;

      				default:
      			}

            $output = '<div class="kd-alertbox '.$ab_type.' '.$ab_extra_class.'">
                <div class="ab-icon"><i class="'.$ab_icon.' iconita"></i></div>
                <div class="ab-message">'.$ab_message.'</div>
                <a href="#" class="ab-close"><i class="fas fa-times iconita"></i></a>
            </div>';

            return $output;

        }
    }
}

if (class_exists('KD_ELEM_ALERT_BOX')) {
    $KD_ELEM_ALERT_BOX = new KD_ELEM_ALERT_BOX;
}

?>
