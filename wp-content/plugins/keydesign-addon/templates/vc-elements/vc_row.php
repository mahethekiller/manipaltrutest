<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = '';
$video_bg = $video_bg_url = $video_bg_parallax = $kd_fixed_background = $kd_image_overlay = $kd_image_overlay_color = $overlay_inline_css = $css_animation = '';
$kd_top_separator = $kd_top_separator_style = $kd_top_separator_bg = $kd_bottom_separator = $kd_bottom_separator_style = $kd_bottom_separator_bg = '';
$top_svg_object = $bottom_svg_object = '';
$skew_left_bottom_svg = $skew_right_bottom_svg = $skew_left_top_svg = $skew_right_top_svg = $arrow_down_bottom_svg = $arrow_up_bottom_svg = $arrow_down_top_svg = $arrow_up_top_svg = $triangle_left_top_svg = $triangle_right_top_svg = $triangle_left_bottom_svg = $triangle_right_bottom_svg = $small_triangle_top_svg = $small_triangle_bottom_svg = '';
$kd_top_separator_height = $kd_bottom_separator_height = '';
$disable_element = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );

$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_row-has-fill';
}


if ( vc_shortcode_custom_css_has_property( $css, array(
		'padding-top',
	) )
) {
	$css_classes[] = 'vc_row-has-padding-top';
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'padding-bottom',
	) )
) {
	$css_classes[] = 'vc_row-has-padding-bottom';
}


if ( vc_shortcode_custom_css_has_property( $css, array(
		'padding-left',
	) )
) {
	$css_classes[] = 'vc_row-has-padding-left';
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'padding-right',
	) )
) {
	$css_classes[] = 'vc_row-has-padding-right';
}


if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $kd_fixed_background ) ) {
	$css_classes[] = 'vc_row-fixed-bg';
}

$rounded_up_top_svg = '<path d="M0 0 0 10 A 90 59, 0, 0, 1, 100 10 L 100 0 Z"></path>';
$rounded_down_top_svg = '<path d="M0 0 C55 180 100 0 100 0 Z"></path>';
$rounded_up_bottom_svg = '<path d="M0 100 C40 0 60 0 100 100 Z"></path>';
$rounded_down_bottom_svg = '<path d="M0 10 0 0 A 90 59, 0, 0, 0, 100 0 L 100 10 Z"></path>';

$skew_left_bottom_svg = '<polygon points="100 0 100 10 0 10"></polygon></svg>';
$skew_right_bottom_svg = '<polygon points="100 10 0 0 0 10"></polygon>';
$skew_left_top_svg = '<polygon points="100 0 0 0 0 10"></polygon>';
$skew_right_top_svg = '<polygon points="100 0 100 10 0 0"></polygon>';
$arrow_down_bottom_svg = '<polygon points="100 0 50 10 0 0 0 10 100 10"></polygon>';
$arrow_up_bottom_svg = '<polygon points="100 10 0 10 50 0"></polygon>';
$arrow_down_top_svg = '<polygon points="100 0 0 0 50 10"></polygon>';
$arrow_up_top_svg = '<polygon points="100 0 0 0 0 10 50 0 100 10"></polygon>';

$triangle_left_top_svg = '<polygon points="100 0 0 0 20 10"></polygon>';
$triangle_right_top_svg = '<polygon points="100 0 0 0 80 10"></polygon>';

$triangle_left_bottom_svg = '<polygon points="100 10 0 10 20 0"></polygon>';
$triangle_right_bottom_svg = '<polygon points="100 10 0 10 80 0"></polygon>';

$small_triangle_top_svg = '<polygon points="148 0 0 0 74 57"></polygon>';
$small_triangle_bottom_svg = '<polygon points="148 57 0 57 74 0"></polygon>';

if ( ! empty( $kd_top_separator ) ) {
	$top_separator_inline_css = ( '' !== $kd_top_separator_bg ) ? ' style="fill:' . $kd_top_separator_bg . ';"' : '';
	if ( 'rounded-up' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $rounded_up_top_svg .'</svg>';
	} elseif ( 'rounded-down' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $rounded_down_top_svg .'</svg>';
	} elseif ( 'skew-left' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $skew_left_top_svg .'</svg>';
	} elseif ( 'skew-right' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $skew_right_top_svg .'</svg>';
	} elseif ( 'arrow-down' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $arrow_down_top_svg .'</svg>';
	} elseif ( 'arrow-up' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $arrow_up_top_svg .'</svg>';
	} elseif ( 'triangle-left' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $triangle_left_top_svg .'</svg>';
	} elseif ( 'triangle-right' === $kd_top_separator_style ) {
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $triangle_right_top_svg .'</svg>';
	} elseif ( 'small-triangle' === $kd_top_separator_style ) {
		$css_classes[] = 'vc_row-small-triangle-sep';
		$top_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 148 57" preserveAspectRatio="none" '.(!empty($kd_top_separator_bg) ? 'style="fill: '.$kd_top_separator_bg.';"' : '').'>' . $small_triangle_top_svg .'</svg>';
	}
}

if ( ! empty( $kd_bottom_separator ) ) {
	$bottom_separator_inline_css = ( '' !== $kd_bottom_separator_bg ) ? ' style="fill:' . $kd_bottom_separator_bg . ';"' : '';
	if ( 'rounded-up' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $rounded_up_bottom_svg .'</svg>';
	} elseif ( 'rounded-down' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $rounded_down_bottom_svg .'</svg>';
	} elseif ( 'skew-left' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $skew_left_bottom_svg .'</svg>';
	} elseif ( 'skew-right' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $skew_right_bottom_svg .'</svg>';
	} elseif ( 'arrow-down' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $arrow_down_bottom_svg .'</svg>';
	} elseif ( 'arrow-up' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $arrow_up_bottom_svg .'</svg>';
	} elseif ( 'triangle-left' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $triangle_left_bottom_svg .'</svg>';
	} elseif ( 'triangle-right' === $kd_bottom_separator_style ) {
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 10" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $triangle_right_bottom_svg .'</svg>';
	} elseif ( 'small-triangle' === $kd_bottom_separator_style ) {
		$css_classes[] = 'vc_row-small-triangle-sep';
		$bottom_svg_object = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 148 57" preserveAspectRatio="none" '.(!empty($kd_bottom_separator_bg) ? 'style="fill: '.$kd_bottom_separator_bg.';"' : '').'>' . $small_triangle_bottom_svg .'</svg>';
	}
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$css_classes[] = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	//wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-kd-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general kd_vc_parallax kd_vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

if ( ! empty( $kd_image_overlay ) ) {
	$overlay_inline_css = ( '' !== $kd_image_overlay_color ) ? ' style="background-color:' . $kd_image_overlay_color . ';"' : '';
}


$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';

if ( ! empty( $parallax ) ) {
	$output .= '<div class="parallax-overlay" data-vc-kd-parallax="' . esc_attr( $parallax_speed ) . '"></div>';
}

if ( !empty( $kd_image_overlay ) && !empty($kd_image_overlay_color) ) {
	$output .= '<div class="kd-overlay" '. $overlay_inline_css . '></div>';
}

if ( !empty( $kd_top_separator) && $kd_top_separator_style != 'rounded' ) {
	$output .= '<div class="kd-row-separator kd-row-separator-top ' . esc_attr( $kd_top_separator_height ) . '">';
	$output .= $top_svg_object;
	$output .= '</div>';
	$output .= '<div class="kd-row-separator-clear"></div>';
}

$output .= wpb_js_remove_wpautop( $content );

if ( !empty( $kd_bottom_separator) && $kd_bottom_separator_style != 'rounded' ) {
	$output .= '<div class="kd-row-separator kd-row-separator-bottom ' . esc_attr( $kd_bottom_separator_height ) . '">';
	$output .= $bottom_svg_object;
	$output .= '</div>';
	$output .= '<div class="kd-row-separator-clear"></div>';
}

$output .= '</div>';
$output .= $after_output;

echo $output;
