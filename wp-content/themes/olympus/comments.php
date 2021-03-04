<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 * @var $commenter array
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$wrap_classes = olympus_is_composer() ? 'container' : '';
?>

<div id="comments" class="<?php echo esc_attr( $wrap_classes ); ?> comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<div class="crumina-module crumina-heading with-title-decoration">
			<h5 class="heading-title">

				<?php
				$comment_count = get_comments_number();
				if ( 1 === $comment_count ) {
					printf(
					/* translators: 1: title. */
						esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'olympus' ), '<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'olympus' ) ), number_format_i18n( $comment_count ), '<span>' . get_the_title() . '</span>'
					);
				}
				?>
			</h5>
		</div><!-- .comments-title -->


		<?php the_comments_navigation(); ?>
		<ul class="comments-list style-3 mb60">
			<?php
			wp_list_comments( array(
				'style'      => 'ul',
				'short_ping' => true,
				'callback'   => 'olympus_comments_style_3'
			) );
			?>
		</ul><!-- .comment-list -->


		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<h5 class="no-comments"><?php esc_html_e( 'Comments are closed.', 'olympus' ); ?></h5>
		<?php
		endif;

	endif; // Check for have_comments().

	$author_field_class = empty( $commenter['comment_author'] ) ? 'is-empty' : '';
	$email_field_class  = empty( $commenter['comment_author_email'] ) ? 'is-empty' : '';

	$fields        = array(
		'author' => '<div class="row"><div class="col-lg-6 col-md-6">
			<div class="form-group label-floating ' . esc_attr( $author_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Name', 'olympus' ) . '</label>
            <input class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" type="text" required>
            </div></div>',
		'email'  => ' <div class="col-lg-6 col-md-6">
            <div class="form-group label-floating ' . esc_attr( $email_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Email', 'olympus' ) . '</label>
            <input class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" type="email" required>
            </div>
            </div>',
		'url'    => '</div>'
	);
	$comments_args = array(
		'class_form'           => 'commentform',
		'class_submit'         => 'd-none',
		'name_submit'          => 'submit',
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'title_reply'          => esc_html__( 'Leave a Comment', 'olympus' ),
		'title_reply_to'       => esc_html__( 'Leave a Comment to %s', 'olympus' ),
		'cancel_reply_link'    => esc_html__( 'Cancel Comment', 'olympus' ),
		'label_submit'         => esc_html__( 'Post Comment', 'olympus' ),
		'title_reply_before'   => '<div class="crumina-module crumina-heading with-title-decoration"><h5 class="heading-title">',
		'title_reply_after'    => '</h5></div>',
		'comment_notes_after'  => '<div class="col-xl-12 col-lg-12 col-md-12"><button class="btn btn-primary btn-lg full-width">' . esc_html__( 'Post your Comment', 'olympus' ) . '</button></div>
			                            </div>',
		'comment_notes_before' => '<p class="comment-notes  mb30">' . esc_html__( 'Your email address will not be published.', 'olympus' ) . '</p>',
		'comment_field'        => '<div class="row"><div class="col-xl-12 col-lg-12 col-md-12"><div class="form-group label-floating is-empty">
            <label class="control-label">' . esc_html__( 'Your Comment', 'olympus' ) . '</label>
            <textarea id="comment" name="comment" class="form-control"></textarea>
            </div></div>',
	);
	if ( comments_open() ) {
		comment_form( $comments_args );
	}
	?>
</div><!-- #comments -->