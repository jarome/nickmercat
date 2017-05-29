<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>
<div class="blog-single">
	<div class="container">
		<?php 
		/**
		 * neat_page_heading action.
		 */
		do_action( 'neat_page_heading' );
		?>
		<?php if( $bloglayout == 'l_sidebar' ):?><?php get_sidebar();?><?php endif;?>
		<div class="main-column">
			<?php 
				// get the post.
				if( have_posts() ):
					// loop the post.
					woocommerce_content();
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
		<?php if( $bloglayout == 'r_sidebar' ):?><?php get_sidebar();?><?php endif;?>
	</div>
</div>
<?php get_footer();?>
