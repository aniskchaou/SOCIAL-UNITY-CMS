<?php
/**
 * BuddyPress - Members Single Group Invites
 */

/**
 * Fires before the display of member group invites content.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_invites_content' ); ?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>

	<ul id="yz-groups-list" class="invites item-list">

		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<li>

				<div class="yz-group-data">

					<div class="item-avatar">
						<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
					</div>


					<div class="item">
						<div class="item-title"><?php bp_group_link(); ?></div>
						<div class="item-meta">
							<span><?php printf( _nx( '%d member', '%d members', bp_get_group_total_members( false ),'Group member count', 'youzer' ), bp_get_group_total_members( false )  ); ?></span>
						</div>
					</div>

					<?php

					/**
					 * Fires inside the display of a member group invite item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_invites_item' ); ?>

					<div class="action">
						<a class="button accept" href="<?php bp_group_accept_invite_link(); ?>"><?php _e( 'Accept', 'youzer' ); ?></a> &nbsp;
						<a class="button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><?php _e( 'Reject', 'youzer' ); ?></a>

						<?php

						/**
						 * Fires inside the member group item action markup.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_group_invites_item_action' ); ?>

					</div>
				</div>
			</li>

		<?php endwhile; ?>
	</ul>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'You have no outstanding group invites.', 'youzer' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the display of member group invites content.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_invites_content' );