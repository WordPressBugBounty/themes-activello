<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package activello
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
		<?php
		// get_comments_number() may return an int or a numeric string.
		$activello_comments_number = (int) get_comments_number();
		if ( 1 === $activello_comments_number ) {
			printf(
				/* translators: %s: post title */
				esc_html_x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'activello' ),
				esc_html( get_the_title() )
			);
		} else {
			printf(
				esc_html(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$activello_comments_number,
						'comments title',
						'activello'
					)
				),
				esc_html( number_format_i18n( $activello_comments_number ) ),
				esc_html( get_the_title() )
			);
		}
		?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'activello' ); ?>">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'activello' ); ?></h2>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'activello' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'activello' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'       => 'ol',
						'short_ping'  => true,
						'avatar_size' => 80,
						'callback'    => 'activello_cb_comment',
					)
				);
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'activello' ); ?>">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'activello' ); ?></h2>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'activello' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'activello' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; ?>

	<?php
		// Comments are closed but some exist: say so.
	if ( ! comments_open() && 0 < (int) get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'activello' ); ?></p>
	<?php endif; ?>

	<?php
	$activello_commenter = wp_get_current_commenter();
	// Name and email are required only when Settings > Discussion says so.
	$activello_required = get_option( 'require_name_email' ) ? ' required' : '';

	/*
	 * The fields had placeholders but no labels, so they had no accessible
	 * name once something was typed; they have screen-reader labels now. The
	 * email and website fields are type=email and type=url, so phones offer
	 * the right keyboard and browsers can autofill them.
	 */
	$activello_fields = array(
		'author' => '<div class="row">'
			. '<div class="col-sm-4"><label for="author" class="screen-reader-text">' . esc_html__( 'Name', 'activello' ) . '</label>'
			. '<input id="author" name="author" type="text" value="' . esc_attr( $activello_commenter['comment_author'] ) . '" size="30" autocomplete="name"' . $activello_required . ' placeholder="' . esc_attr__( 'Name', 'activello' ) . '" /></div>',
		'email'  => '<div class="col-sm-4"><label for="email" class="screen-reader-text">' . esc_html__( 'Email', 'activello' ) . '</label>'
			. '<input id="email" name="email" type="email" value="' . esc_attr( $activello_commenter['comment_author_email'] ) . '" size="30" autocomplete="email"' . $activello_required . ' placeholder="' . esc_attr__( 'Email', 'activello' ) . '" /></div>',
		'url'    => '<div class="col-sm-4"><label for="url" class="screen-reader-text">' . esc_html__( 'Website', 'activello' ) . '</label>'
			. '<input id="url" name="url" type="url" value="' . esc_attr( $activello_commenter['comment_author_url'] ) . '" size="30" autocomplete="url" placeholder="' . esc_attr__( 'Website', 'activello' ) . '" /></div>'
			. '</div>',
	);

	comment_form(
		array(
			'fields'               => $activello_fields,
			'label_submit'         => __( 'Post Reply', 'activello' ),
			'comment_notes_before' => '',
			'comment_field'        => '<label for="comment" class="screen-reader-text">' . esc_html_x( 'Comment', 'comment form placeholder', 'activello' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" required placeholder="' . esc_attr_x( 'Comment', 'comment form placeholder', 'activello' ) . '"></textarea>',
		)
	);
	?>

</div><!-- #comments -->
