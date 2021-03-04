<?php
/**
 * BuddyPress - Group Invites Loop
 */

?>

<div class="left-menu">

	<div id="invite-list">
		<div class="list-title"><i class="fas fa-paper-plane"></i><?php _e( 'Invite Your friends', 'youzer' ); ?></div>
		<ul>
			<?php bp_new_group_invite_friend_list(); ?>
		</ul>

		<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>

	</div>

</div><!-- .left-menu -->

<div class="main-column">

	<?php

	/**
	 * Fires before the display of the group send invites list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_send_invites_list' ); ?>

	<?php if ( bp_group_has_invites( bp_ajax_querystring( 'invite' ) ) ) : ?>

		<h3 class="section-header"><?php _e( 'Sent Invites', 'youzer' ); ?></h3>

		<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
		<ul id="friend-list" class="item-list group-request-list">

		<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

			<li id="<?php bp_group_invite_item_id(); ?>">

				<div class="item-data">
					
					<div class="item-avatar"><?php bp_group_invite_user_avatar(); ?></div>

					<div class="item">

						<div class="item-title"><?php bp_group_invite_user_link(); ?></div>
						<div class="item-meta">
						<span class="activity"><?php bp_group_invite_user_last_active(); ?></span>
						</div>

						<?php

						/**
						 * Fires inside the invite item listing.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_send_invites_item' ); ?>

					</div>

					<div class="action">
						<a class="button remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', 'youzer' ); ?></a>

						<?php

						/**
						 * Fires inside the action area for a send invites item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_send_invites_item_action' ); ?>
					</div>
				</div>
			</li>

		<?php endwhile; ?>

		</ul><!-- #friend-list -->

		<div id="pag-bottom" class="pagination">

			<div class="pag-count" id="group-invite-count-bottom">

				<?php bp_group_invite_pagination_count(); ?>

			</div>
			<?php if ( bp_get_group_invite_pagination_links() ) : ?>
			<div class="pagination-links" id="group-invite-pag-bottom">
				<?php bp_group_invite_pagination_links(); ?>
			</div>
			<?php endif; ?>

		</div>

	<?php else : ?>

		<div id="message" class="info">
			<p><?php _e( 'Select friends to invite.', 'youzer' ); ?></p>
		</div>

	<?php endif; ?>

<?php

/**
 * Fires after the display of the group send invites list.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_send_invites_list' ); ?>

</div><!-- .main-column -->