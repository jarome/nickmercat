<?php get_header();
$bloglayout	=	neat_get_blog_layout();
?>
<div class="blog-single">
	<div class="container">
		<?php 
		/**
		 * neat_page_heading action.
		 * hooked neat_page_heading, 10
		 */
		do_action( 'neat_page_heading' );
		?>		
		<?php if( $bloglayout == 'l_sidebar' ):?><?php get_sidebar();?><?php endif;?>
		<div class="main-column">
			<?php 
				// get the post.
				if( have_posts() ):
					// loop the post.
					while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
					endwhile;
					
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