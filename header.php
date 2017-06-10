<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head();?>
	</head>
	<body <?php body_class();?>>
		<!-- ///// NAVIGATION HEADER ///// -->
		<header id="navigation" class="navigation headroom has-shadow">
			<div class="navigation-wrapper">
		    	<?php if( get_header_image() ):?>
		    		<a title="<?php bloginfo( 'description' );?>" href="<?php print home_url();?>" class="logo"><img src="<?php header_image(); ?>" alt="<?php bloginfo('description');?>"></a>
		    	<?php else:?>
		    		<a class="logo js-logo" href="<?php print home_url();?>"><!--<img src="<?php echo get_template_directory_uri(); ?>/assets/img/Logo_icon.svg" alt="<?php bloginfo('description');?>">--></a>
		    	<?php endif;?>
				<!-- mobile menu toggle -->
		    	<a href="" class="navigation-menu-button" id="js-mobile-menu"><?php _e('MENU','neat');?></a>
		    	<?php 
		    	/**
		    	 * neat_page_navigation action.
		    	 * hooked neat_page_navigation, 10
		    	 */
		    	do_action( 'neat_page_navigation' );
		    	?>	    
		  	</div>
		</header>