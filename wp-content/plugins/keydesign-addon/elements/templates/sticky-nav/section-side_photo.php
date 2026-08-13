<?php
/*
* Template: Sticky navbar sections - Side Photo
*/

if(!function_exists('kd_section_set_side_photo')) {

  // Declare empty vars
  $image = $content_image = $default_src = $dimensions = $hwstring = $animation_delay = $wrapper_class = $width = $height = $media_img = '';

  function kd_section_set_side_photo($atts,$content = null){
  	extract(shortcode_atts(array(
      'featured_image_source' => '',
      'fss_featured_image' => '',
      'featured_ext_image' => '',
      'fss_image_style' => '',
      'fss_image_shadow' => '',
      'fss_click_action' => '',
      'fss_image_link' => '',
      'fss_link_target' => '',
      'css_animation_image' => '',
  	),$atts));

    if ( $fss_click_action == 'open_photoswipe' ) {
      // Load PhotoSwipe resources
      wp_enqueue_style( 'photoswipe' );
      wp_enqueue_script( 'photoswipejs' );
      add_action( 'wp_footer', 'keydesign_photoswipe' );
    }

    $default_src = vc_asset_url( 'vc/no_image.png' );

    if ( $featured_image_source == 'media_library' ) {
        if ( '' != $fss_featured_image && wp_attachment_is_image( $fss_featured_image ) ) {
            $image  = wpb_getImageBySize($params = array(
                'post_id' => NULL,
                'attach_id' => $fss_featured_image,
                'thumb_size' => 'full',
                'class' => ""
            ));
        }
    }

    if ($featured_image_source == 'external_link') {
      $src = $featured_ext_image;
    } elseif ($featured_image_source == 'media_library' && !$image) {
      $src = $default_src;
    } else {
      $link = wp_get_attachment_image_src( $fss_featured_image, 'large' );
      $link = $link[0];
      $src = $link;
    }

    if ( $featured_image_source == 'external_link' && '' != $featured_ext_image ) {
      list($width, $height) = getimagesize($featured_ext_image);
    } elseif ( $featured_image_source == 'media_library' && '' != $image ) {
      $media_img = wp_get_attachment_image_src( $fss_featured_image, 'large' );
      $width = $media_img[1];
      $height = $media_img[2];
    }

    $a_attrs['href'] = $fss_image_link;
    $a_attrs['target'] = $fss_link_target;

    if ($featured_image_source == 'external_link') {
      if (!$featured_ext_image) {
        $content_image ='<img src="'.$default_src.'" class="vc_img-placeholder" />';
      } else {
        if ($fss_click_action == 'open_photoswipe') {
          $content_image = '<a data-size="' . $width. 'x' .$height .'" href='. $featured_ext_image . '><img src="'.$featured_ext_image.'" width="' . $width. '" height="' .$height .'" /></a>';
        } elseif ($fss_click_action == 'custom_link') {
          $content_image = '<a ' . vc_stringify_attributes( $a_attrs ) . '><img src="'.$featured_ext_image.'" width="' . $width. '" height="' .$height .'" /></a>';
        } else {
          $content_image ='<img src="'.$featured_ext_image.'" width="' . $width. '" height="' .$height .'" />';
        }
      }
    } else {
      if (!$fss_featured_image) {
        $content_image ='<img src="'.$default_src.'" class="vc_img-placeholder" />';
      } else {
        if ($fss_click_action == 'open_photoswipe') {
          $content_image = '<a data-size="' . $width. 'x' .$height .'" href='. $src . '>' . $image['thumbnail'] . '</a>';
        } elseif ($fss_click_action == 'custom_link') {
          $content_image = '<a ' . vc_stringify_attributes( $a_attrs ) . '>' . $image['thumbnail'] . '</a>';
        } else {
          $content_image = $image['thumbnail'];
        }
      }
    }

    /* Animation delay */
    if ('' !== $css_animation_image) {
      $animation_delay = 'data-animation-delay="200"';
    } else {
      $animation_delay = '';
    }


    $wrapper_class = implode(' ', array('side-featured-wrapper', $fss_image_style, $css_animation_image, $fss_image_shadow));

    $output = '<div class="' . trim($wrapper_class) . '" ' . $animation_delay . '>
      <div class="featured-image">'.$content_image.'</div>
    </div>';

    return $output;
  }

}
