<?php
  /**
  * The template used for displaying meta information for single Blog posts
  */
?>
<?php
  $blog_template = $global_post_meta= $post_content = '';

  $blog_template = ekko_get_option( 'tek-blog-template' );
  $global_post_meta = ekko_get_option( 'tek-post-meta' );

  $post_meta_date = ekko_get_option( 'tek-post-meta-date' );
  $post_meta_author = ekko_get_option( 'tek-post-meta-author' );
  $post_meta_categories = ekko_get_option( 'tek-post-meta-categories' );
  $post_meta_comments = ekko_get_option( 'tek-post-meta-comments' );

  if ( ! class_exists( 'ReduxFramework' ) ) {
    $global_post_meta = $post_meta_date = $post_meta_author = $post_meta_categories = $post_meta_comments = true;
    $blog_template = 'img-top-list';
  }

  // Last updated time
  $post_published = get_the_time('U');
  $post_modified_time = get_the_modified_time('U');
  if ( ($post_modified_time >= $post_published + 86400) && ekko_get_option( 'tek-post-meta-date-format' ) == 'last-updated' ) {
    $post_date_single = apply_filters( 'last-updated-text', esc_html__("Last updated on ", "ekko") ) . get_the_modified_time( get_option('date_format') );
    $post_date_listing = get_the_modified_time( get_option('date_format') );
  } else {
    $post_date_single = get_the_time( get_option('date_format') );
    $post_date_listing = get_the_time( get_option('date_format') );
  }
?>

<?php if ( $global_post_meta == true ) : ?>
  <div class="entry-meta">

    <?php do_action( 'keydesign_post_meta_top' ); ?>
    
    <?php  if ( is_sticky() ) echo '<span class="sticky-post"><span class="fas fa-thumbtack"></span>' . apply_filters( 'sticky-post-text', esc_html__("Sticky", "ekko") ) . '</span>'; ?>

    <?php if ( $blog_template == 'detailed-grid' && !is_single() ) : ?>
      <div class="post-meta-child">
    <?php endif; ?>

    <?php if ( is_single() && $post_meta_date == true ) : ?>
      <span class="published">
        <span class="far fa-clock"></span>
        <?php echo esc_html( $post_date_single ); ?>
      </span>
    <?php elseif ( $post_meta_date == true ) : ?>
      <span class="published">
        <span class="far fa-clock"></span>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
          <?php echo esc_html( $post_date_listing ); ?>
        </a>
      </span>
    <?php endif; ?>

    <?php if ( ( is_single() || $blog_template == 'img-top-list' || $blog_template == 'minimal-list' || $blog_template == 'detailed-grid' ) && $post_meta_author == true ) : ?>
      <span class="author"><span class="far fa-keyboard"></span><?php the_author_posts_link(); ?></span>
    <?php endif; ?>
    
    <?php do_action( 'keydesign_post_meta_bottom' ); ?>

    <?php if ( $blog_template == 'detailed-grid' && !is_single() ) : ?>
    </div><div class="post-meta-child">
    <?php endif; ?>

    <?php if ( $post_meta_categories == true ) : ?>
      <span class="blog-label"><span class="far fa-folder-open"></span><?php the_category(', '); ?></span>
    <?php endif; ?>

    <?php if ( ! is_single() && $post_meta_comments == true && ( $blog_template == 'img-top-list' || $blog_template == 'minimal-list' || $blog_template == 'detailed-grid' ) ) : ?>
      <span class="comment-count"><span class="far fa-comment"></span><?php  comments_popup_link( esc_html__('No comments yet', 'ekko'), esc_html__('1 comment', 'ekko'), esc_html__('% comments', 'ekko') ); ?></span>
    <?php endif; ?>

    <?php if ( $blog_template == 'detailed-grid' && !is_single() ) : ?>
    </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
