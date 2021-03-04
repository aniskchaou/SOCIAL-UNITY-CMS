<?php
/**
 * BuddyPress - Members Unread Notifications
 */

?>

<?php if ( bp_has_notifications() ) : ?>

	<?php bp_get_template_part( 'members/single/notifications/notifications-loop' ); ?>

	<?php if ( bp_get_notifications_pagination_links() ) : ?>
	<div class="pagination no-ajax">
		<div class="pagination-links" id="notifications-pag-bottom">
			<?php bp_notifications_pagination_links(); ?>
		</div>
	</div>
	<?php endif; ?>

<?php else : ?>

	<?php bp_get_template_part( 'members/single/notifications/feedback-no-notifications' ); ?>

<?php endif;
