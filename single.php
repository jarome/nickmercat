<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>
	<div <?php post_class();?>>
		<div class="container">
			<?php neat_yoast_breadcrumb();?>		
			<?php if( $bloglayout == 'l_sidebar' ):?><?php get_sidebar();?><?php endif;?>
			<article class="article-column blog-teaser <?php print ($bloglayout == 'fullwidth') ? 'template-fullwidth' : '';?>">
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
				<?php if( get_post_format() != 'quote' ):?><div class="title"><h2><?php the_title();?></h2></div><?php endif;?>				
				<?php 
				/**
				 * neat_post_meta action.
				 * hooked neat_post_meta, 15
				 */
				do_action( 'neat_post_meta' );
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
			</article>
			<?php if( $bloglayout == 'r_sidebar' ):?><?php get_sidebar();?><?php endif;?>
	</div>
</div>
<?php get_footer();?>