<?php
/*
* Template: Team Members Classic
*/

if ( !function_exists( 'kd_team_set_classic' ) ) {
    function kd_team_set_classic( $atts,$content = null ) {

        $output = $image = '';

      	extract( shortcode_atts( array(
            'title' => '',
            'title_color' => '',
            'position' => '',
            'position_color' => '',
            'description' => '',
            'description_color' => '',
            'image_source' => '',
            'image' => '',
            'ext_image' => '',
            'ext_image_size' => '',
            'tm_phone' => '',
            'tm_email' => '',
            'tm_disable_contact_effect' => '',
            'team_bg_color' => '',
            'facebook_url' => '',
            'instagram_url' => '',
            'twitter_url' => '',
            'linkedin_url' => '',
            'github_url' => '',
            'xing_url' => '',
            'vk_url' => '',
            'social_color' => '',
            'team_external_url' => '',
            'team_link_text' => '',
            'team_link_target' => '',
            'css_animation' => '',
            'elem_animation_delay' => '',
            'team_extra_class' => '',
      	),$atts ) );

    $animation_delay = $default_src = $dimensions = $hwstring = $a_attrs = $socials_disabled = $wrapper_class = $disable_contact_effect = '';

    if ( $image_source == 'media_library' ) {
        if ( '' != $image && wp_attachment_is_image( $image ) ) {
            $image = wpb_getImageBySize( $params = array(
                'post_id' => NULL,
                'attach_id' => $image,
                'thumb_size' => 'full',
                'class' => ""
            ));
        }
    }

    $default_src = vc_asset_url( 'vc/no_image.png' );
    $dimensions = vc_extract_dimensions( $ext_image_size );
    $hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

    // Display helper class if socials are disabled
    if ( empty( $facebook_url ) && empty( $instagram_url ) && empty( $twitter_url ) && empty( $linkedin_url ) && empty( $github_url ) && empty( $xing_url ) && empty( $vk_url ) ) {
      $socials_disabled = 'socials-disabled';
    }

    //CSS Animation
    if ( $css_animation == "no_animation" ) {
        $css_animation = "";
    }

    // Animation delay
    if ( $elem_animation_delay ) {
        $animation_delay = 'data-animation-delay='.$elem_animation_delay;
    }

    $wrapper_class = implode(' ', array('team-member', 'design-classic', $css_animation, $socials_disabled, $tm_disable_contact_effect, $team_extra_class));

    $output = '<div class="'.trim($wrapper_class).'" '.$animation_delay.'>
                    <div class="team-content">
                        <div class="team-image-overlay"></div>
                        <div class="team-image">';
                        if(isset($team_external_url) && $team_external_url !== '') {
                          $output .='<a href="'.$team_external_url.'" target="'.$team_link_target.'">';
                        }
                        if ($image_source == 'external_link') {
                          if (!$ext_image) {
                            $output .='<img src="'.$default_src.'" class="vc_img-placeholder" />';
                          } else {
                            $output .='<img src="'.$ext_image.'" '.$hwstring.' />';
                          }
                        } else {
                          if (!is_array($image) || !isset($image['thumbnail'])) {
                            $output .= '<img src="'.$default_src.'" class="vc_img-placeholder" />';
                          } else {
                            $output .= $image['thumbnail'];
                          }
                        }
                        if(isset($team_external_url) && $team_external_url !== '') {
                          $output .='</a>';
                        }

                        $output .= '</div>
                        <div class="team-content-text" '.(!empty($team_bg_color) ? 'style="background-color: '.$team_bg_color.';"' : '').'>
                        <div class="team-content-text-inner">
                        <h5 '.(!empty($title_color) ? 'style="color: '.$title_color.';"' : '').'>'.$title.'</h5>
                        <span class="team-subtitle" '.(!empty($position_color) ? 'style="color: '.$position_color.';"' : '').'>'.$position.'</span>
                        <p '.(!empty($description_color) ? 'style="color: '.$description_color.';"' : '').'>'.$description.'</p>';
                        if ($team_external_url && $team_link_text) {
                          $output .= '<p class="team-link"><a href="'.$team_external_url.'" target="'.$team_link_target.'">'.$team_link_text.'</a></p>';
                        }
                        if ($tm_phone || $tm_email) {
                          $output .= '<div class="kd-team-contact">';
                          if ($tm_phone) {
                            $output .= '<div class="kd-team-phone"><a href="tel:'.$tm_phone.'">'.(!empty($tm_phone_label) ? '<span class="team-phone-label">'.$tm_phone_label.'</span>' : '').'<span class="fa fa-phone-alt"></span>'.$tm_phone.'</a></div>';
                          }

                          if ($tm_email) {
                            $output .= '<div class="kd-team-email"><a href="mailto:'.$tm_email.'">'.(!empty($tm_email_label) ? '<span class="team-email-label">'.$tm_email_label.'</span>' : '').'<span class="fa fa-envelope"></span>'.$tm_email.'</a></div>';
                          }
                          $output .= '</div>';
                        }

                        $output .= '</div>';
                        if ( $facebook_url || $instagram_url || $twitter_url || $linkedin_url || $github_url || $xing_url || $vk_url ) {
                          $output .= '<div class="team-socials">';
                            if(isset($facebook_url) && $facebook_url !== '') {
                              $output .='<a href="'.$facebook_url.'" target="_blank"><span class="fab fa-facebook-f" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($instagram_url) && $instagram_url !== '') {
                              $output .='<a href="'.$instagram_url.'" target="_blank"><span class="fab fa-instagram" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($twitter_url) && $twitter_url !== '') {
                              $output .='<a href="'.$twitter_url.'" target="_blank"><span class="fab fa-twitter" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($linkedin_url) && $linkedin_url !== '') {
                              $output .='<a href="'.$linkedin_url.'" target="_blank"><span class="fab fa-linkedin-in" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($github_url) && $github_url !== '') {
                              $output .='<a href="'.$github_url.'" target="_blank"><span class="fab fa-github-alt" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($xing_url) && $xing_url !== '') {
                              $output .='<a href="'.$xing_url.'" target="_blank"><span class="fab fa-xing" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                            if(isset($vk_url) && $vk_url !== '') {
                              $output .='<a href="'.$vk_url.'" target="_blank"><span class="fab fa-vk" '.(!empty($social_color) ? 'style="color: '.$social_color.';"' : '').'></span></a>';
                            }
                          $output .='</div>';
                        }
                    $output .='</div>
                    </div>
                </div>';
    return $output;
  }
}
