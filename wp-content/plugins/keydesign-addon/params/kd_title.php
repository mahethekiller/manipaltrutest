<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if(!class_exists('KEYDESIGN_Title_Param')) {
	class KEYDESIGN_Title_Param {
		function __construct() {
			if(defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, 4.8) >= 0) {
				if(function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('kd_param_title' , array($this, 'kd_param_title_callback'));
				}
			}
			else {
				if(function_exists('add_shortcode_param')) {
					add_shortcode_param('kd_param_title' , array($this, 'kd_param_title_callback'));
				}
			}
		}

		function kd_param_title_callback($settings, $value) {
			$dependency = '';
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$class = isset($settings['class']) ? $settings['class'] : '';
			$text = isset($settings['text']) ? $settings['text'] : '';
			$output = '<h3 '.$dependency.' class="wpb_vc_param_value '.$class.'">'.$text.'</h3>';
			return $output;
		}

	}
}

if(class_exists('KEYDESIGN_Title_Param')) {
	$KEYDESIGN_Title_Param = new KEYDESIGN_Title_Param();
}
