<?php
/**
 * BuddyPress - Groups Requests Loop
 */

?>

<?php if ( bp_group_has_membership_requests( bp_ajax_querystring( 'membership_requests' ) ) ) : ?>

<ul id="request-list" class="item-list group-request-list">
	<?php while ( bp_group_membership_requests() ) : bp_group_the_membership_request(); ?>

		<li class="item-list">
			<div class="item-data">
				
				<div class="item-avatar"><?php bp_group_request_user_avatar_thumb(); ?></div>

				<div class="item">

					<div class="item-title"><?php bp_group_request_user_link(); ?> </div>
					
					<div class="item-meta">
						<span class="activity"><?php bp_group_request_time_since_requested(); ?></span>
					</div>
		
					<?php

					/**
					 * Fires inside the groups membership request list loop.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_membership_requests_admin_item' ); ?>

				</div>

				<div class="action">

					<?php bp_button( array( 'id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_href' => bp_get_group_request_accept_link(), 'link_text' => __( 'Accept', 'youzer' ) ) ); ?>

					<?php bp_button( array( 'id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_href' => bp_get_group_request_reject_link(), 'link_text' => __( 'Reject', 'youzer' ) ) ); ?>

					<?php

					/**
					 * Fires inside the list of membership request actions.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_membership_requests_admin_item_action' ); ?>

				</div>

			</div>
			<?php global $requests_template; ?>
			<?php if ( ! empty( $requests_template->request->comments ) ): ?>
				<div class="request-comment">
					<span class="request-title"><?php _e( 'Request Comment', 'youzer' ); ?></span>
					<div class="request-msg"><?php bp_group_request_comment(); ?></div>
				</div>
			<?php endif; ?>

		</li>

	<?php endwhile; ?>
</ul>

<div id="pag-bottom" class="pagination">

	<div class="pag-count" id="group-mem-requests-count-bottom">
		<?php bp_group_requests_pagination_count(); ?>
	</div>

	<?php if ( bp_get_group_requests_pagination_links() ) : ?>
	<div class="pagination-links" id="group-mem-requests-pag-bottom">
		<?php bp_group_requests_pagination_links(); ?>
	</div>
	<?php endif; ?>

</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There are no pending membership requests.', 'youzer' ); ?></p>
	</div>

<?php endif;
