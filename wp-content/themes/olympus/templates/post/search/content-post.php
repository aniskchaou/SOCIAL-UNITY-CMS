<?php
$author_id  = get_the_author_meta( 'ID' );
$profession = get_the_author_meta( 'user_profession' );
$query      = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
?>

<?php echo olympus_post_category_list( null, null, true ); ?>
<h2><a href="<?php the_permalink(); ?>"><?php echo olympus_highlight_searched( $query, get_the_title() ); ?></a></h2>
<div class="single-post-additional inline-items">
    <div class="post__author author vcard inline-items">
		<?php echo get_avatar( $author_id, 26 ); ?>
        <div class="author-date not-uppercase">
            <a class="h6 post__author-name fn"
               href="<?php the_author_meta( 'url' ); ?>"><?php the_author_meta( 'display_name' ); ?></a>
			<?php if ( $profession ) { ?>
                <div class="author_prof">
					<?php olympus_render( $profession ); ?>
                </div>
			<?php } ?>
        </div>
    </div>
    <div class="post-date-wrap inline-items">
        <i class="olympus-icon-Week-Calendar-Icon"></i>
        <div class="post-date">
            <a class="h6 date" href="#"><?php the_modified_time( 'F jS, Y' ); ?></a>
            <span>Last Updated</span>
        </div>
    </div>
</div>

<p>
	<?php echo olympus_highlight_searched( $query, get_the_excerpt() ); ?>
</p>

<div class="post-additional-info no-border inline-items">
	<?php echo olympus_get_post_reactions( 'compact' ); ?>
    <div class="comments-shared">
		<?php olympus_comments_count(); ?>
    </div>
</div>
