<?php
if (!class_exists('KD_ELEM_VIDEO')) {
    class KD_ELEM_VIDEO extends KEYDESIGN_ADDON_CLASS {
        function __construct() {
            add_action('init', array($this, 'kd_video_init'));
            add_shortcode('tek_video', array($this, 'kd_video_shrt'));
        }

        // Element configuration in admin
        function kd_video_init() {
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name" => esc_html__("Video Modal", "keydesign"),
                    "description" => esc_html__("Video modal", "keydesign"),
                    "base" => "tek_video",
                    "class" => "",
                    "icon" => plugins_url('assets/element_icons/video-modal.png', dirname(__FILE__)),
                    "category" => esc_html__("KeyDesign Elements", "keydesign"),
                    "params" => array(
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Video source", "keydesign"),
                            "param_name" => "video_source",
                            "value" => array(
                                "YouTube/Vimeo" => "yt-vimeo-video",
                                "HTML5 Video" => "html-video",
                            ),
                            "save_always" => true,
                        ),
						array(
							"type" => "kd_param_notice",
							"text" => "<span style='display: block;'>Please use the YouTube embed link for the video - see the following <a href='".plugins_url('assets/img/youtube-embed.png', dirname(__FILE__))."' target='_blank'>image</a>.</span>",
							"param_name" => "notification",
							"edit_field_class" => "vc_column vc_col-sm-12",
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("yt-vimeo-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video link", "keydesign"),
                            "param_name" => "video_url",
                            "value" => "",
	                        "description" => esc_html__("Enter link to video.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("yt-vimeo-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video title", "keydesign"),
                            "param_name" => "video_title",
                            "value" => "",
	                        "description" => esc_html__("Enter video title.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("html-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video URL MP4", "keydesign"),
                            "param_name" => "video_url_mp4",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("html-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video URL OGG", "keydesign"),
                            "param_name" => "video_url_ogg",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("html-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video URL WEBM", "keydesign"),
                            "param_name" => "video_url_webm",
                            "value" => "",
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("html-video")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Video height", "keydesign"),
                            "param_name" => "video_height",
                            "value" => "",
		                        "description" => esc_html__("Enter video height. Default value is 400 pixels.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_source",
                                "value" => array("html-video")
                            ),
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Cover image", "keydesign"),
                            "param_name" => "video_image_source",
                            "value" => array(
                                "Media library" => "media_library",
                                "External link" => "external_link",
                            ),
                            "description" => esc_html__("Select video preview image source.", "keydesign"),
                            "save_always" => true,
                        ),
                        array(
                            "type" => "attach_image",
                            "heading" => esc_html__("Image", "keydesign"),
                            "param_name" => "video_image",
                            "description" => esc_html__("Select image from media library.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_image_source",
                                "value" => array("media_library")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Image external link", "keydesign"),
                            "param_name" => "video_image_ext",
                            "value" => "",
		                        "description" => esc_html__("Enter image external link.", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_image_source",
                                "value" => array("external_link")
                            ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Image size", "keydesign"),
                            "param_name" => "ext_image_size",
                            "value" => "",
		                        "description" => esc_html__("Enter image size in pixels. Example: 200x100 (Width x Height).", "keydesign"),
                            "dependency" =>	array(
                                "element" => "video_image_source",
                                "value" => array("external_link")
                            ),
                        ),
                        array(
                            "type"			=>	"dropdown",
                            "class"			=>	"",
                            "heading"		=>	esc_html__("Play button style","keydesign"),
                            "param_name"	=>	"video_btn_style",
                            "value"			=>	array(
                                    "White" => "",
                                    "Primary theme color" => "play-btn-primary-color",
                                    "Secondary theme color" => "play-btn-secondary-color",
                                ),
                            "save_always" => true,
                            "description"	=>	esc_html__("Select play button color scheme.", "keydesign"),
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Play button align", "keydesign"),
                            "param_name" => "video_play_align",
                            "value" => array(
                                "Center" => "play-button-center",
                                "Left" => "play-button-left",
                            ),
                            "save_always" => true,
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Open video in", "keydesign"),
                            "param_name" => "video_location",
                            "value" => array(
                                "Modal" => "",
                                "New window" => "video_location_new",
                            ),
                            "save_always" => true,
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("CSS animation", "keydesign"),
                            "param_name" => "css_animation",
                            "value" => array(
                                "No" => "",
                                "Fade In" => "kd-animated fadeIn",
                                "Fade In Down" => "kd-animated fadeInDown",
                                "Fade In Left" => "kd-animated fadeInLeft",
                                "Fade In Right" => "kd-animated fadeInRight",
                                "Fade In Up" => "kd-animated fadeInUp",
                                "Zoom In" => "kd-animated zoomIn",
                            ),
                            "save_always" => true,
                            "admin_label" => true,
                            "description" => esc_html__("Select type of animation for element to be animated when it enters the browsers viewport (Note: works only in modern browsers).", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                        array(
                            "type" => "dropdown",
                            "class" => "",
                            "heading" => esc_html__("Animation delay", "keydesign"),
                            "param_name" => "animation_delay",
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
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                        array(
                            "type" => "textfield",
                            "class" => "",
                            "heading" => esc_html__("Extra class name", "keydesign"),
                            "param_name" => "video_extra_class",
                            "value" => "",
                            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "keydesign"),
                            "group" => esc_html__( "Extras", "keydesign" ),
                        ),
                    )
                ));
            }
        }



		// Render the element on front-end
        public function kd_video_shrt( $atts, $content = null ) {

            $video_id = $dimensions = $hwstring = $default_src = $img = $image_media = $image_html = $animation_delay = $image = '';
            extract( shortcode_atts( array(
                'video_source' => '',
                'video_title' => '',
                'video_url' => '',
                'video_url_mp4' => '',
                'video_url_ogg' => '',
                'video_url_webm' => '',
                'video_height' => '',
                'video_image_source' => '',
                'video_image' => '',
                'video_image_ext' => '',
                'ext_image_size' => '',
                'video_btn_style' => '',
                'video_play_align' => '',
                'video_location' => '',
                'css_animation' => '',
                'video_animation_delay' => '',
                'video_extra_class' => '',
            ), $atts ) );

            if ( $video_image_source == 'media_library' ) {
                if ( '' != $video_image && wp_attachment_is_image( $video_image ) ) {
                    $image = wpb_getImageBySize($params = array(
                        'post_id' => NULL,
                        'attach_id' => $video_image,
                        'thumb_size' => 'full',
                        'class' => ""
                    ));
                }
            }

            $default_src = vc_asset_url( 'vc/no_image.png' );

            $video_id .= 'kd-video-modal-'.uniqid();
            $vheight = ! empty( $video_height ) ? $video_height : '400px';

            if ( $video_image_source == 'external_link' ) {
                $dimensions = vc_extract_dimensions( $ext_image_size );
          		$hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

          		$video_image_ext = $video_image_ext ? esc_attr( $video_image_ext ) : $default_src;

          		$image_media .= '<img src="'.$video_image_ext.'" '.$hwstring.' />';
                $image_html = wp_get_attachment_url( $video_image );
            } else {
                if ( '' != $image ) {
                    $image_media .= $image["thumbnail"];
                }
                $image_html = $video_image_ext;
            }

            // Animation delay
            if ( $video_animation_delay ) {
                $animation_delay = 'data-animation-delay='.$video_animation_delay;
            }

            $wrapper_class = implode(' ', array('video-container', $video_play_align, $css_animation, $video_extra_class));

            $output = '<div class="' . trim($wrapper_class) . '" ' . $animation_delay . '>';
            $output .= $image_media;
            if ($video_location == 'video_location_new')  {
              $output .='<a href="'.$video_url.'" class="'.$video_btn_style.'" target="_blank">';
            } else {
              $output .='<a data-toggle="modal" data-target="#video-modal-'.$video_id.'" data-src="'.$video_url.'" data-backdrop="true" class="'.$video_btn_style.'">';
            }

            $output .='<span class="play-video"><span class="fas fa-play"></span></span></a></div>';

            if ($video_location != 'video_location_new')  {
            $output .= '<div class="modal fade video-modal" id="video-modal-'.$video_id.'" role="dialog">
                            <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <div class="row">';
                            if ( $video_source == "html-video" && !empty($video_url_mp4) ) {
                              $output .= '<video class="video-modal-local" title="'.$video_title.'" poster="'.$image_html.'" height="'.$vheight.'" controls>
                              <source src="'.$video_url_mp4.'" type="video/mp4">';
                              if (!empty($video_url_ogg)) {
                                $output .= '<source src="'.$video_url_ogg.'" type="video/ogg">';
                              }
                              if (!empty($video_url_webm)) {
                                $output .= '<source src="'.$video_url_webm.'" type="video/webm">';
                              }
                              $output .= '<img alt="" src="'.$image_html.'" title="Video playback is not supported by your browser" />
                              </video>';
                            } else {
                             $output .= '<iframe width="1024" height="576" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                           }
                            $output .= '</div>
                            </div>
                        </div>';
            }
            return $output;
        }
    }
}

if ( class_exists( 'KD_ELEM_VIDEO' ) ) {
    $KD_ELEM_VIDEO = new KD_ELEM_VIDEO;
}
