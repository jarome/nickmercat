<?php
/**
 * Custom header
 */
if( !function_exists('neat_custom_header_setup') ){
	function neat_custom_header_setup() {
		add_theme_support( 'custom-header', apply_filters( 'neat_custom_header_args', array(
			'default-text-color'     => '000',
			'width'                  => 125,
			'height'                 => 96,
			'flex-height'            => true,
			'flex-width'             => true,
			'header-text'			 =>	false,
			'wp-head-callback'       => 'neat_header_style',
			'admin-head-callback'    => 'neat_admin_header_style',
			'admin-preview-callback' => 'neat_admin_header_image',
		) ) );
	}
	add_action( 'after_setup_theme', 'neat_custom_header_setup' );	
}

if ( ! function_exists( 'neat_header_style' ) ) {
	function neat_header_style() {
		$text_color = get_header_textcolor();
	
		// If no custom color for text is set, let's bail.
		if ( display_header_text() && $text_color === get_theme_support( 'custom-header', 'default-text-color' ) )
			return;
	
		// If we get this far, we have custom styles.
		?>
		<style type="text/css" id="neat-header-css">
		<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
		?>
			.site-title,
			.site-description {
				clip: rect(1px 1px 1px 1px); /* IE7 */
				clip: rect(1px, 1px, 1px, 1px);
				position: absolute;
			}
		<?php
			// If the user has set a custom color for the text, use that.
			elseif ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) :
		?>
			.site-title a {
				color: #<?php echo esc_attr( $text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
}

if ( ! function_exists( 'neat_admin_header_style' ) ){
	function neat_admin_header_style() {
		?>
		<style type="text/css" id="neat-admin-header-css">
		.appearance_page_custom-header #headimg {
			background-color: #eef1f2;
			border: none;
			max-width: 1260px;
			min-height: 48px;
		}
		#headimg h1 {
			font-size: 35px;
			font-family: Roboto Slab;
			color: #000;
			line-height: 47px;	
		}
		#headimg img {
			vertical-align: middle;
		}
		</style>
	<?php
	}
}

if ( ! function_exists( 'neat_admin_header_image' ) ) {
	function neat_admin_header_image() {
		?>
		<div id="headimg">
			<?php if ( get_header_image() ) : ?>
				<img src="<?php header_image(); ?>" alt="logo">
			<?php endif; ?>
		</div>
	<?php
	}
}