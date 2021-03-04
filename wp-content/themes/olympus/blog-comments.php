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

if ( have_comments() ) :
	?>

	<?php the_comments_navigation(); ?>
	<ul id="comments" class="comments-list">
		<?php
		wp_list_comments( array(
			'style'      => 'ul',
			'short_ping' => true,
			'callback'   => 'olympus_comments_post'
		) );
		?>
	</ul><!-- .comment-list -->

	<?php
	the_comments_navigation();

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() ) :
		?>
		<ul id="comments-closed" class="comments-list">
		<li class="comment-item">
			<h5 class="no-comments"><?php esc_html_e( 'Comments are closed.', 'olympus' ); ?></h5>
		</li>
		</ul>
	<?php
	endif;

endif; // Check for have_comments(). 
?>

<?php
$author_field_class = empty( $commenter['comment_author'] ) ? 'is-empty' : '';
$email_field_class  = empty( $commenter['comment_author_email'] ) ? 'is-empty' : '';

$fields        = array(
	'author' => '<div class="row"><div class="col-lg-6 col-md-6">
			<div class="form-group label-floating ' . esc_attr( $author_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Name', 'olympus' ) . '</label>
            <input class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" type="text" name="author" required>
            </div></div>',
	'email'  => ' <div class="col-lg-6 col-md-6">
            <div class="form-group label-floating ' . esc_attr( $email_field_class ) . '">
            <label class="control-label">' . esc_html__( 'Your Email', 'olympus' ) . '</label>
            <input class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" type="email" name="email" required>
            </div>
            </div>',
	'url'    => '</div>'
);
$comments_args = array(
	'class_form'           => 'comment-form comment-form--without-author',
	'class_submit'         => 'd-none',
	'name_submit'          => 'submit',
	'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
	'title_reply'          => esc_html__( 'Leave a Comment', 'olympus' ),
	'title_reply_to'       => esc_html__( 'Leave a Comment to %s', 'olympus' ),
	'cancel_reply_link'    => esc_html__( 'Cancel Comment', 'olympus' ),
	'label_submit'         => esc_html__( 'Post Comment', 'olympus' ),
	'title_reply_before'   => '<div class="more-comments">',
	'title_reply_after'    => '</div>',
	'comment_notes_after'  => '<div class="col-xl-12 col-lg-12 col-md-12"><button class="btn btn-primary btn-md-2">' . esc_html__( 'Post your Comment', 'olympus' ) . '</button></div>
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