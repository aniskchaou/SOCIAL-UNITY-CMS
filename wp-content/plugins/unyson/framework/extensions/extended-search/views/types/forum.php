<?php
/**
 * @var object $the_query
 * @var int $found_posts
 */

$bbp = bbpress();
$found_topics = (int) isset( $bbp->topic_query->found_posts ) ? $bbp->topic_query->found_posts : $bbp->topic_query->post_count;
?>

<div class="search-help-result">
	<h3 class="search-help-result-title">
		<span class="count-result"><?php olympus_render( $found_topics, ' ', _n( 'topic', 'topics', $found_topics, 'crum-ext-extended-search' ) ); ?></span>
		<?php esc_html_e( 'found', 'crum-ext-extended-search' ); ?>
	</h3>
    <?php if ( $found_posts != 0 ) { ?>
    <div id="bbpress-forums">
		<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">
        <li class="bbp-body">
        <?php while ( bbp_topics($the_query) ) : bbp_the_topic(); ?>
            <?php bbp_get_template_part( 'loop', 'single-topic' ); ?>
        <?php endwhile; ?>
        </li>
        </ul>
    </div>
    <?php
	} else {
		?>
		<div class="row">
			<?php get_template_part( 'templates/content/content-search-none' ); ?>
		</div>
		<?php
	}
	?>
</div>