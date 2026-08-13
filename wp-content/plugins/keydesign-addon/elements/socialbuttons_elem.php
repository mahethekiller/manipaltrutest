<?php

if (!class_exists('KD_ELEM_SOCIAL_BUTTONS')) {

    class KD_ELEM_SOCIAL_BUTTONS extends KEYDESIGN_ADDON_CLASS {

        function __construct() {
            add_action('init', array($this, 'kd_socialbuttons_init'));
            add_shortcode('tek_socialbuttons', array($this, 'kd_socialbuttons_shrt'));
        }

        // Element configuration in admin

        function kd_socialbuttons_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Social buttons", "keydesign"),
                    "description" => esc_html__("Display social sharing buttons.", "keydesign"),
                    "base" => "tek_socialbuttons",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/social-buttons.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(
                      array(
                        "type" => "kd_param_notice",
                        "text" => '<span style="display: block;">Control which buttons are displayed in <a href="' . admin_url ( 'admin.php?page=theme-options&tab=17' ) .'">Blog > Blog Single Post > Social Sharing Buttons</a>.</span>',
                        "param_name" => "notification",
                        "edit_field_class" => "vc_column vc_col-sm-12",
                      ),
                    )
                ));
            }
        }

    		// Render the element on front-end

        public function kd_socialbuttons_shrt($atts = null, $content = null) {

            $output = kd_output_post_socials();

            return $output;
        }
    }
}

if (class_exists('KD_ELEM_SOCIAL_BUTTONS')) {
    $KD_ELEM_SOCIAL_BUTTONS = new KD_ELEM_SOCIAL_BUTTONS;
}

?>
