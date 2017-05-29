<?php if( !defined('ABSPATH') ) exit;?>
<?php
wp_reset_query();
if ( post_password_required() ) {
	return;
}
?>
<div class="title">
    <h3><?php print neat_comments_number_text( get_the_ID() );?> <a href="<?php print apply_filters( 'neat_comment_section_html_id' , '#comment-form');?>"><?php _e('Add yours.','neat');?></a></h3>
    <hr class="small">
</div>
<?php if( have_comments() ):?>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="neat">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'neat' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'neat' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'neat' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>
		<ul class="comments">
			<?php
				$args = array(
					'walker'            => null,
					'style'             => 'ul',
					'callback'          => function_exists( 'neat_comments_callback' ) ? 'neat_comments_callback' : null,
					'end-callback'      => null,
					'type'              => 'all',
					'reply_text'        => '<i class="fa fa-reply"></i> '.__('Reply','neat'),
					'avatar_size'       => 80,
					'reverse_top_level' => null,
					'format'            => 'html5', //or xhtml if no HTML5 theme support
					'short_ping'        => false // @since 3.6
				); 										 
				wp_list_comments( apply_filters( 'neat_wp_list_comments_args' , $args) );
			?>
		</ul>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="neat">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'floriel' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'neat' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'neat' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>	
<?php endif;?>
<?php 
		$required_text = null;

		$textarea_wrapper_before = ( !get_current_user_id() ) ? '<div class="form-right">' : '';
		$textarea_wrapper_after = ( !get_current_user_id() ) ? '</div>' : '';
	
		$args = array(
		  'id_form'           => 'comment-form',
		  'id_submit'         => 'submit',
		  'title_reply'       => __( 'Add your comment','neat' ),
		  'title_reply_to'    => __( 'Leave a Reply to %s','neat' ),
		  'cancel_reply_link' => __( 'Cancel Reply','neat' ),
		  'label_submit'      => __( 'Submit','neat' ),
		  'comment_field'	=>	'
				'.$textarea_wrapper_before.'
                <p><label for="comment">'.__('Message','neat').'</label>
                <textarea aria-required="true" id="comment" name="comment" rows="10" placeholder="'.__('Comment away...','neat').'"></textarea></p>
				'.$textarea_wrapper_after.'
		  ',
		  'must_log_in' => '<p class="must-log-in">' .
		    sprintf(
		      __( 'You must be <a href="%s">logged in</a> to post a comment.','neat' ),
		      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
		    ) . '</p>',
		
		  'logged_in_as' => '<p class="logged-in-as">' .
		    sprintf(
		    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','neat' ),
		      admin_url( 'profile.php' ),
		      $user_identity,
		      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
		    ) . '</p>',
		
		  'comment_notes_before' => '<p class="comment-notes">' .
		    __( 'Your email address will not be published.','neat' ) . ( $req ? $required_text : '' ) .
		    '</p>',
		
		  'comment_notes_after' => null,
		
		  'fields' => apply_filters( 'comment_form_default_fields', array(
			'author'	=>	'
                <p><label for="author">'.__('Name','neat').'</label>
                <input type="text" id="author" name="author" placeholder="'.__('Name...','neat').'"></p>
			',
			'email'	=>	'
                <p><label for="email">'.__('E-mail','neat').'</label>
                <input type="email"  id="email" name="email" placeholder="'.__('E-mail...','neat').'"></p>
			',
			'url'	=>	'
                <p><label for="url">'.__('Website','neat').'</label>
                <input type="text" id="url" name="url" placeholder="'.__('Website...','neat').'"></p>
			'
		    )
		  ),
		);
		comment_form($args);
		?>