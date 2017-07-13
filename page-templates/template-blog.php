<?php
/**
 * Template Name: Blog Page
 */
get_header();
$bloglayout	=	neat_get_blog_layout();
?>
<div class="blog-single">

  <div class="full-width-header full-width-header__news"><h2>News</h2></div>

	<div class="container">

		<?php if( $bloglayout == 'l_sidebar' ):?><?php get_sidebar();?><?php endif;?>
		<div class="main-column" id="content">
			<?php
				// get the post.
				global $more;
      $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				$more = 0;
				$args	=	array(
					'post_type'		=>	'post',
					'post_status'	=>	'publish',
            'paged'          => $paged
				);
				query_posts( apply_filters( 'neat_blogpage_args' , $args) );
				if( have_posts() ):
					// loop the post.
					while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
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
		<?php if( $bloglayout == 'r_sidebar' ):?><?php get_sidebar();?><?php endif;?>
	</div>
</div>
<?php get_footer();?>