<?php 
/**
 * Template Name: Page FullWidth
 */
?>
<?php get_header();?>
	<div <?php post_class('blog-single');?>>
      <?php
      if (is_page( 'meet-nick' ) ): ?>
        <div class="full-width-header full-width-header__meetnick"></div>
      <?php endif;
      ?>


		<div class="container">
			<?php neat_yoast_breadcrumb();?>
			<article class="article-column template-fullwidth">
				<?php if( have_posts() ) : the_post();?>
				<?php 
				/**
				 * neat_post_format_content action.
				 * hooked neat_post_format_content, 10;
				 */
				do_action( 'neat_post_format_content' );
				?>

				<?php
				/**
				 * hooked, neat_wp_link_pages, 10
				 * hooked, neat_post_tags, 20
				 * hooked, neat_author_box, 30
				 */ 
				the_content();
			
				if( comments_open() && apply_filters( 'neat_comment_system' , true) === true ){
					comments_template();
				}
				
				endif;?>
			</article>
	</div>
</div>
<?php get_footer();?>