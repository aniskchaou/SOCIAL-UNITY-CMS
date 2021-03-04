<?php
/**
 * BuddyPress - Members Friends Requests
 */

/**
 * Fires before the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_friend_requests_content' ); ?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>

	<ul id="friend-list" class="item-list">
		<?php while ( bp_members() ) : bp_the_member(); ?>

			<li id="friendship-<?php bp_friend_friendship_id(); ?>">
				<div class="item-avatar">
					<a href="<?php bp_member_link(); ?>"><?php bp_member_avatar(); ?></a>
				</div>

				<div class="item">
					<div class="item-title"><a href="<?php bp_member_link(); ?>"><?php bp_member_name(); ?></a></div>
					<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>

					<?php
					/**
					 * Fires inside the display of a member friend request item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_friend_requests_item' );
					?>
				</div>

				<div class="action">
					<a class="button accept" href="<?php bp_friend_accept_request_link(); ?>"><?php _e( 'Accept', 'youzer' ); ?></a>
					<a class="button reject" href="<?php bp_friend_reject_request_link(); ?>"><?php _e( 'Reject', 'youzer' ); ?></a>

					<?php

					/**
					 * Fires inside the member friend request actions markup.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_friend_requests_item_action' ); ?>
				</div>
			</li>

		<?php endwhile; ?>
	</ul>

	<?php

	/**
	 * Fires and displays the member friend requests content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_friend_requests_content' ); ?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-dir-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>
		
		<?php if ( bp_get_members_pagination_links() ) : ?>
		<div class="pagination-links" id="member-dir-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'You have no pending friendship requests.', 'youzer' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the display of member friend requests content.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_friend_requests_content' );
