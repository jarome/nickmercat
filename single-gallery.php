<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>
	<div <?php post_class();?>>
    <div class="full-width-header full-width-header__gallery"><h2>GALLERY</h2></div>

    <div class="gallery-page">
      <div class="gallery-page-hero">
        <div class="container">
          <?php neat_yoast_breadcrumb();?>
          <article class="article-column blog-teaser template-fullwidth">
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

            <?php if( get_post_format() != 'quote' ):?><h4><?php the_title();?></h4><?php endif;?>

            <p><?php the_field('information'); ?></p>
          </article>
      </div>
    </div>
  </div>
<?php get_footer();?>