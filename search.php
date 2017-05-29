<?php get_header();
global $neattheme;
$bloglayout	=	!empty( $neattheme['bloglayout'] ) ? esc_attr( $neattheme['bloglayout'] ) : apply_filters( 'neat_blogpage_template' , 'r_sidebar');
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
					while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
					endwhile;
					
				else:	
					print '<p>'.__('Sorry, but nothing matched your search terms. Please try again with some different keywords.','neat') . '</p>';
					print '<p>'.__('Type and hit enter...','neat') . '</p>';
					get_search_form();
					$args	=	array();
					wp_tag_cloud( apply_filters( 'neat_tag_cloud_args' , $args) );
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