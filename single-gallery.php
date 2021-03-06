<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>
	<div <?php post_class();?>>
    <div class="full-width-header full-width-header__gallery"><h2>GALLERY</h2></div>

    <div class="gallery-page">
      <div class="gallery-page-hero">
        <div class="container">
          <div id="content">
            <?php neat_yoast_breadcrumb();?>
            <div class="blog-teaser">
              <section class="vc_row wpb_row vc_row-fluid">
              <?php if( have_posts() ) : the_post();?>
              <?php
              /**
               * hooked neat_add_facebook_likeshare_button, 10
               */
              //do_action( 'neat_share_section', get_the_ID() );
              ?>
              <?php
              /**
               * neat_post_format_content action.
               * hooked neat_add_facebook_likeshare_button, 10
               * hooked neat_post_format_content, 100;
               */
              do_action( 'neat_post_format_content' );
              ?>
              <?php
              /**
               * hooked, neat_wp_link_pages, 10
               * hooked, neat_post_tags, 20
               * hooked, neat_author_box, 30
               * hooked, neat_related_posts, 40
               */
              if( get_post_format( get_the_ID() ) != 'quote' )
                the_content();
              /**
               * neat_comment_system filter.
               * hooked neat_hide_default_comment, 10
               */

              if( comments_open() && apply_filters( 'neat_comment_system' , true) === true ){
                comments_template();
              }


              endif;?>

              </section>
            </div>

            <?php if( get_post_format() != 'quote' ):?><h4><?php the_title();?></h4><?php endif;?>

            <p><?php the_field('information'); ?></p>

          </div>
      </div>
    </div>

      <div class="gallery-page-list">
        <div class="container">
          <div class="gallery-list-container">
              <?php
              // get the post.
              global $more;
              $more = 0;
              $args	=	array(
                  'post_type'		=>	'gallery',
                  'post_status'	=>	'publish',
                  'posts_per_page' => -1
              );

              $current_post_id = $post->ID;

              query_posts( apply_filters( 'neat_blogpage_args' , $args) );
              if( have_posts() ):
                  // loop the post.
                  while ( have_posts() ) : the_post();
                      ?>

                    <div class="gallery-list-item <?php if( $current_post_id == $post->ID ) { echo 'current'; } ?>">

                        <?php

                        $image = get_field('gallery_thumbnail');

                        if( !empty($image) ):

                            // vars
                            $url = $image['url'];
                            $title = $image['title'];
                            $alt = $image['alt'];

                            // thumbnail
                            $size = 'medium';
                            $thumb = $image['sizes'][ $size ];
                            $width = $image['sizes'][ $size . '-width' ];
                            $height = $image['sizes'][ $size . '-height' ];

                            ?>

                            <div class="gallery-list-item__thumb">

                                <a href="<?php the_permalink();?>" title="<?php echo $title; ?>">

                                    <img src="<?php echo $thumb; ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />

                                </a>
                            </div>

                        <?php endif; ?>


                      <div class="gallery-list-item__teaser">
                        <div class="meta">

                          <span class="date"><i class="fa fa-clock-o"></i><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
                        </div>
                        <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>

                      </div>

                    </div>
                      <?php
                  endwhile;
              else:
                  // nothing found.
                  get_template_part( 'content', 'none' );
              endif;
              ?>
          </div>
        </div>
      </div>
  </div>
<?php get_footer();?>