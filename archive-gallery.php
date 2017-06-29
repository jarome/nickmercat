<?php
/**
 * Template Name: Blog Page
 */
get_header();
$bloglayout	=	neat_get_blog_layout();
?>
<div class="blog-single">

  <div class="full-width-header full-width-header__gallery"><h2>Gallery</h2></div>

  <div class="gallery-page">
    <div class="gallery-page-hero">
      <div class="container">

        <?php if( $bloglayout == 'l_sidebar' ):?><?php get_sidebar();?><?php endif;?>
        <div id="content">
          <?php
            // get the post.
            global $more;
            $more = 0;
            $args	=	array(
              'post_type'		=>	'gallery',
              'post_status'	=>	'publish',
              'posts_per_page' => 4
            );
            query_posts( apply_filters( 'neat_blogpage_args' , $args) );
            if( have_posts() ):
              // loop the post.
              while ( have_posts() ) : the_post();
                ?>
                <div <?php post_class();?>>
                  <div class="blog-teaser">
                    <?php the_content();?>
                  </div>
                </div>

                <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>

                <p><?php the_field('information'); ?></p>
          <?php
              endwhile;
            else:
              // nothing found.
              get_template_part( 'content', 'none' );
            endif;
          ?>
          <?php
          /**
           * neat_pagination action.
           * hooked neat_pagination, 10
           */
          do_action( 'neat_pagination' );
          ?>
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
              'post_status'	=>	'publish'
          );
          query_posts( apply_filters( 'neat_blogpage_args' , $args) );
          if( have_posts() ):
              // loop the post.
              while ( have_posts() ) : the_post();
                  ?>

                  <div class="gallery-list-item">
                      <?php $image = get_field('gallery_thumbnail'); ?>
                    <div class="gallery-list-item__thumb">
                      <img src="<?php echo $image['url']; ?>" />
                    </div>

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
          <?php
          /**
           * neat_pagination action.
           * hooked neat_pagination, 10
           */
          do_action( 'neat_pagination' );
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer();?>