<?php
/**
 * Helper functions for Ekko Theme.
 *
 * @package Ekko
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Fire the wp_body_open action. Backward compatibility for WordPress versions < 5.2
 */
 if ( ! function_exists( 'wp_body_open' ) ) {
 	function wp_body_open() {
 		do_action( 'wp_body_open' );
   }
 }

/**
 * Return Theme options.
 */
 if ( ! function_exists( 'ekko_get_option' ) ) {
  function ekko_get_option( $option, $default = '' ) {
 	 global $redux_ThemeTek;

 	 if ( empty( $redux_ThemeTek ) ) {
 		 if ( is_multisite() ) {
 			 $redux_ThemeTek = get_blog_option( get_current_blog_id(), 'redux_ThemeTek' );
 		 } else {
 			 $redux_ThemeTek = get_option( 'redux_ThemeTek' );
 		 }
 	 }

 	 if ( ( isset( $redux_ThemeTek[$option] ) && $redux_ThemeTek[$option] === '0') || !empty( $redux_ThemeTek[$option] ) ) {
 		 return $redux_ThemeTek[$option];
 	 } else {
 		 return $default;
 	 }

  }
 }

/**
 * Compress CSS
 */
if ( ! function_exists( 'ekko_compress_css' ) ) {
	function ekko_compress_css( $css = '' ) {
			if ( ! empty( $css ) ) {
				$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
				$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
				$css = str_replace( ', ', ',', $css );
			}
			return $css;
	}
}

/**
 * Font familty formatter
 */
function keydesign_format_font_family( $raw ) {
	if ( $raw === null || $raw === '' ) return '';

	$generics = array(
		'serif','sans-serif','monospace','cursive','fantasy',
		'system-ui','ui-serif','ui-sans-serif','ui-monospace',
		'emoji','math','fangsong'
	);

	$parts = array_map( 'trim', explode( ',', $raw ) );
	$out   = array();

	foreach ( $parts as $p ) {
		if ( $p === '' ) continue;
		$p = trim( $p, "\"'" );

		if ( in_array( strtolower( $p ), $generics, true ) ) {
			$out[] = strtolower( $p );
		} else {
			$out[] = "'" . esc_attr( $p ) . "'";
		}
	}

	return implode( ',', $out );
}

/**
 * Theme activation option
 */
if ( ! function_exists( 'ekko_activation_option' ) ) {
	function ekko_activation_option() {
		add_option( 'keydesign-verify', 'no', '', false );
		add_option( 'envato_purchase_code_23714045', '', '', false );
	}
}
add_action( 'admin_init', 'ekko_activation_option' );

/**
 * Display search form
 */

 if ( ! function_exists( 'ekko_get_search_form' ) ) {
 	function ekko_get_search_form( $echo = true ) {
 		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<label>
				<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'ekko' ) . '</span>
				<input type="search" class="search-field" placeholder="' . apply_filters( 'ekko_search_field_placeholder', esc_attr_x( 'Search &hellip;', 'placeholder', 'ekko' ) ) . '" value="' . get_search_query() . '" name="s" role="search" />';
				if ( 'search-all' != ekko_get_option( 'tek-search-form-content' ) && '' != ekko_get_option( 'tek-search-form-content' ) ) {
					$form .= '<input type="hidden" name="post_type" value="' . ekko_get_option( 'tek-search-form-content' ) . '">';
				}
			$form .= '</label>
			<input type="submit" class="search-submit" value="">
		</form>';

		$result_escaped = apply_filters( 'ekko_get_search_form', $form );

		if ( null === $result_escaped ) {
			$result_escaped = $form;
		}

		// The $result_escaped variable has been safely escaped

		if ( $echo ) {
			echo $result_escaped;
		} else {
			return $result_escaped;
		}

 	}
 }

 /**
 * Display social icons
 */

if ( ! function_exists( 'ekko_social_icons' ) ) {
	function ekko_social_icons( $echo = true ) {
	 	$social_items = ekko_get_option( 'tek-social-profiles' );
	 	$output = $output_escaped = '';
	 	if ( class_exists( 'ReduxFramework' ) ) {
		 	$output = '<ul class="redux-social-media-list clearfix">';
		 	if ( is_array( $social_items ) ) {
			 	foreach ( $social_items as $key => $social_item ) {
				 	if ( $social_item[ 'enabled' ] ) {
					 	$icon = $social_item[ 'icon' ];
					 	$base_url = $social_item[ 'url' ];

					 	$output .= '<li>';
					 	$output .= '<a target="_blank" href="'. $base_url . '">';
					 	$output .= '<i class="fab ' . $icon . '"></i>';
					 	$output .= "</a>";
					 	$output .= "</li>";
				 	}
			 	}
		 	}
		 	$output .= '</ul>';
		 	$output_escaped = $output;
	 	}

	 	// The $output_escaped variable has been safely escaped
	 	if ( $echo ) {
		 	echo "" . $output_escaped;
	 	} else {
		 	return $output_escaped;
	 	}
	}
}

/**
 * Allowed HTML tags
 */
if ( ! function_exists( 'ekko_allowed_html_tags' ) ) {
	function ekko_allowed_html_tags() {
		$allowed_tags = array(
			 'a' => array(
				 'class' => array(),
				 'href'  => array(),
				 'rel'   => array(),
				 'title' => array(),
				 'target' => array(),
			 ),
			 'b' => array(),
			 'br' => array(),
			 'div' => array(
				 'class' => array(),
				 'title' => array(),
				 'style' => array(),
			 ),
			 'em' => array(),
			 'h1' => array(),
			 'h2' => array(),
			 'h3' => array(),
			 'h4' => array(),
			 'h5' => array(),
			 'h6' => array(),
			 'i' => array(),
			 'img' => array(
				 'alt'    => array(),
				 'class'  => array(),
				 'height' => array(),
				 'src'    => array(),
				 'width'  => array(),
			 ),
			 'p' => array(
				 'class' => array(),
			 ),
			 'span' => array(
				 'class' => array(),
				 'title' => array(),
				 'style' => array(),
			 ),
			 'strong' => array(),
		 );

		 return $allowed_tags;
	 }
 }

 if ( ! function_exists( 'ekko_collapsible_faq' ) ) {
	 function ekko_collapsible_faq( $classes ) {
		 $classes[] = '';
		 if ( ekko_get_option( 'tek-faq-collapsible' ) == true) {
			 $classes[] = 'collapsible-faq';
		 }
		 return $classes;
	 }
 }
 add_filter( 'body_class','ekko_collapsible_faq' );

 /**
 * Render footer copyright markup
 */
 if ( ! function_exists( 'ekko_footer_copyright' ) ) {
	 function ekko_footer_copyright() {
		 $content = ekko_get_option( 'tek-footer-text' );
		 if ( $content ) {
			 $content = str_replace( '[copyright]', '&copy;', $content );
			 $content = str_replace( '[current_year]', gmdate( 'Y' ), $content );
			 $content = str_replace( '[site_title]', get_bloginfo( 'name' ), $content );
			 $output = wp_kses( $content, ekko_allowed_html_tags() );
			 echo apply_filters( 'ekko_footer_copyright_output', $output );
		 } else {
			 if ( ! class_exists( 'ReduxFramework' ) ) {
				 echo esc_html__( 'Ekko by KeyDesign. All rights reserved.', 'ekko' );
			 }
		 }
	 }
 }

 /**
 * Main menu display description
 */
if ( ! function_exists( 'ekko_add_menu_description_args' ) ) {
	function ekko_add_menu_description_args( $args, $item, $depth ) {
	 	if ( '</span>' !== $args->link_after ) {
	 		$args->link_after = '';
	 	}

	 	if ( 0 === $depth && isset( $item->description ) && $item->description ) {
	 		// The extra <span> element is here for styling purposes: Allows the description to not be underlined on hover.
	 		$args->link_after = '<p class="menu-item-description"><span>' . $item->description . '</span></p>';
	 	}

	 	return $args;
 	}
}
add_filter( 'nav_menu_item_args', 'ekko_add_menu_description_args', 10, 3 );

function ekko_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'ekko_pingback_header' );
