<?php get_header();?>
	<div <?php post_class('blog-single');?>>
		<div class="container">
			<?php neat_yoast_breadcrumb();?>
			<article class="article-column blog-teaser">
				<?php if( have_posts() ) : the_post();?>
				<?php 
				/**
				 * neat_post_format_content action.
				 * hooked neat_post_format_content, 10;
				 */
				do_action( 'neat_post_format_content' );
				?>
				<div class="title"><h2><?php the_title();?></h2></div>
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
			<?php get_sidebar();?>
	</div>
</div>
<?php get_footer();?>