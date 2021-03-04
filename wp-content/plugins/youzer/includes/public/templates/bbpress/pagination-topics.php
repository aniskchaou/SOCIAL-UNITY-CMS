<?php

/**
 * Pagination for pages of topics (when viewing a forum)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_pagination_loop' ); ?>
<?php if ( bbp_get_forum_pagination_links() ) : ?>
<div class="bbp-pagination">
	<div class="bbp-pagination-count">

		<?php bbp_forum_pagination_count(); ?>

	</div>

	<div class="bbp-pagination-links">

		<?php bbp_forum_pagination_links(); ?>

	</div>
</div>
<?php endif; ?>
<?php do_action( 'bbp_template_after_pagination_loop' ); ?>
