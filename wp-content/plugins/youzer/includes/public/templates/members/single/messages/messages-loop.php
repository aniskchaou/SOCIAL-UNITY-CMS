<?php
/**
 * BuddyPress - Members Messages Loop
 */

/**
 * Fires before the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

	<?php

	/**
	 * Fires before the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_messages_threads' ); ?>

	<form action="<?php echo bp_displayed_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ?>/bulk-manage/" method="post" id="messages-bulk-management">

		<table id="message-threads" class="messages-notices">

			<thead>
				<tr>
					<th scope="col" class="thread-checkbox bulk-select-all"><label class="yz_cs_checkbox_field" for="select-all-messages"><input id="select-all-messages" type="checkbox"><div class="yz_field_indication"></div></label></th>

					<th scope="col" class="thread-from"><?php _e( 'From', 'youzer' ); ?></th>
					<th scope="col" class="thread-info"><?php _e( 'Subject', 'youzer' ); ?></th>

					<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
						<th scope="col" class="thread-star"><span class="message-action-star"><span class="icon"></span> <span class="screen-reader-text"><?php
							/* translators: accessibility text */
							_e( 'Star', 'youzer' );
						?></span></span></th>
					<?php endif; ?>

					<?php

					/**
					 * Fires inside the messages box table header to add a new column.
					 *
					 * This is to primarily add a <th> cell to the messages box table header. Use
					 * the related 'bp_messages_inbox_list_item' hook to add a <td> cell.
					 *
					 * @since 2.3.0
					 */
					do_action( 'bp_messages_inbox_list_header' ); ?>

					<th scope="col" class="thread-options"><?php _e( 'Actions', 'youzer' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

					<tr id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?> unread<?php else: ?> read<?php endif; ?>">
						<td class="bulk-select-check">
							<label class="yz_cs_checkbox_field" for="bp-message-thread-<?php bp_message_thread_id(); ?>"><input type="checkbox" name="message_ids[]" id="bp-message-thread-<?php bp_message_thread_id(); ?>" class="message-check" value="<?php bp_message_thread_id(); ?>" /><div class="yz_field_indication"></div></label>
						</td>

						<td class="thread-sender">
						<?php if ( 'sentbox' != bp_current_action() ) : ?>
								<div class="thread-avatar">
									<?php bp_message_thread_avatar( array( 'width' => 40, 'height' => 40 ) ); ?>
								</div>

								<div class="thread-from">
									<div class="from">
										<?php bp_message_thread_from(); ?>
										<?php bp_message_thread_total_and_unread_count(); ?>
									</div>
									<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
								</div>

						<?php else: ?>

								<div class="thread-avatars">
									<?php yz_get_thread_recipients( bp_get_message_thread_id() ); ?>
								</div>
								
								<div class="thread-from">
									<span class="to"></span>
									<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
								</div>
						<?php endif; ?>
						
						</td>

						<td class="thread-info">
							<p><a href="<?php bp_message_thread_view_link( bp_get_message_thread_id(), bp_displayed_user_id() ); ?>" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e( "View Message", 'youzer' ); ?>" aria-label="<?php esc_attr_e( "View Message", 'youzer' ); ?>"><?php bp_message_thread_subject(); ?></a></p>
							<p class="thread-excerpt"><?php bp_message_thread_excerpt(); ?></p>
						</td>

						<?php

						/**
						 * Fires inside the messages box table row to add a new column.
						 *
						 * This is to primarily add a <td> cell to the message box table. Use the
						 * related 'bp_messages_inbox_list_header' hook to add a <th> header cell.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_messages_inbox_list_item' ); ?>

						<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
							<td class="thread-star">
								<?php bp_the_message_star_action_link( array( 'thread_id' => bp_get_message_thread_id() ) ); ?>
							</td>
						<?php endif; ?>
						
						<td class="thread-options">

							<?php do_action( 'yz_messages_list_item_thread_options' ); ?>

							<?php if ( bp_message_thread_has_unread() ) : ?>
								<a class="read" href="<?php bp_the_message_thread_mark_read_url( bp_displayed_user_id() );?>" data-yztooltip="<?php _e( 'Mark as Read', 'youzer' ); ?>"><span class="dashicons dashicons-visibility"></span></a>
							<?php else : ?>
								<a class="unread" href="<?php bp_the_message_thread_mark_unread_url( bp_displayed_user_id() );?>" data-yztooltip="<?php _e( 'Mark as Unread', 'youzer' ); ?>"><span class="dashicons dashicons-hidden"></span></a>
							<?php endif; ?>
							
							<a class="delete" href="<?php bp_message_thread_delete_link( bp_displayed_user_id() ); ?>"><span class="dashicons dashicons-trash"></span></a>

							<?php

							/**
							 * Fires after the thread options links for each message in the messages loop list.
							 *
							 * @since 2.5.0
							 */
							do_action( 'bp_messages_thread_options' ); ?>
						</td>
					</tr>

				<?php endwhile; ?>

			</tbody>

		</table><!-- #message-threads -->

		<div class="messages-options-nav">
			<?php bp_messages_bulk_management_dropdown(); ?>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>
	</form>

	<?php

	/**
	 * Fires after the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_threads' ); ?>

	<?php if ( bp_get_messages_pagination() ) : ?>
	<div class="pagination no-ajax" id="user-pag">
		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>
	</div><!-- .pagination -->
	<?php endif; ?>
	
	<?php

	/**
	 * Fires after the members messages pagination display.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_pagination' ); ?>

	<?php

	/**
	 * Fires and displays member messages options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found.', 'youzer' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_messages_loop' );