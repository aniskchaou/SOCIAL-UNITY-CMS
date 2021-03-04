<?php
/**
 * BuddyPress - Groups Members
 */

?>

<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' ); ?>

	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<ul id="yz-members-list" class="item-list">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li>
				<div class="yzm-user-data">

				<div class="yz-item-avatar">
					<a href="<?php bp_group_member_domain(); ?>">
						<?php bp_group_member_avatar_thumb(); ?>
					</a>
				</div>

				<div class="item">

					<div class="item-title"><?php bp_group_member_link(); ?></div>

					<div class="item-meta">
						<span class="yz-name">@<?php echo bp_core_get_username( bp_get_group_member_id() ); ?></span>
					</div>
					
						<?php

						/**
						 * Fires inside the listing of an individual group member listing item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_members_list_item' ); ?>
					</div>

					<?php if ( bp_is_active( 'friends' ) ) : ?>

						<div class="yzm-user-actions">

							<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

							<?php

							/**
							 * Fires inside the action section of an individual group member listing item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_group_members_list_item_action' ); ?>

						</div>

					<?php endif; ?>
				</div>
			</li>

		<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>
		
		<?php if ( bp_get_members_pagination_links() ) : ?>
		<div class="pagination-links" id="member-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>
		<?php endif; ?>

	</div>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'youzer' ); ?></p>
	</div>

<?php endif;
