<?php
/**
 * BuddyPress - Members Single Messages Notice Loop
 */

/**
 * Fires before the members notices loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_notices_loop' ); ?>

<?php if ( bp_has_message_threads() ) : ?>

	<?php

	/**
	 * Fires after the members notices pagination display.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_notices_pagination' ); ?>
	<?php

	/**
	 * Fires before the members notice items.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_notices' ); ?>

	<table id="message-threads" class="messages-notices sitewide-notices">

		<thead>
			<tr>
				<th></th>
				<th><?php _e( 'Notice', 'youzer' ); ?></th>
				<th><?php _e( 'Action', 'youzer' ); ?></th>
			</tr>
		</thead>
			
		<?php while ( bp_message_threads() ) : bp_message_thread(); ?>
			<tr id="notice-<?php bp_message_notice_id(); ?>" class="<?php bp_message_css_class(); ?>">
				<td width="1%"></td>
				<td width="38%" class="yz-notice-msg">
					<span class="dashicons dashicons-megaphone yz-notice-icon"></span>
					<div class="yz-notice-head">
						<div class="yz-notice-msg-title"><?php bp_message_notice_subject(); ?></div>
						<div class="yz-notice-sent"><?php bp_message_notice_post_date(); ?></div>
					</div>
					<div class="yz-notice-sent">

						<?php if ( bp_messages_is_active_notice() ) : ?>

							<strong><?php bp_messages_is_active_notice(); ?></strong>

						<?php endif; ?>

					</div>
					<?php bp_message_notice_text(); ?>
				</td> 

				<?php

				/**
				 * Fires inside the display of a member notice list item.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_notices_list_item' ); ?>

				<td width="10%" class="thread-options">
					<a class="button" data-yztooltip="<?php bp_message_activate_deactivate_text(); ?>" href="<?php bp_message_activate_deactivate_link(); ?>" class="confirm"><?php echo yz_get_message_activate_deactivate_text(); ?></a>
					<a class="button delete" href="<?php bp_message_notice_delete_link(); ?>" class="confirm" aria-label="<?php esc_attr_e( "Delete Message", 'youzer' ); ?>"><span class="dashicons dashicons-trash"></span></a>
				</td>
			</tr>
		<?php endwhile; ?>
	</table><!-- #message-threads -->

	<?php if ( bp_get_messages_pagination() ) : ?>
	<div class="pagination no-ajax" id="user-pag">

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>

	</div><!-- .pagination -->
	<?php endif; ?>
	
	<?php

	/**
	 * Fires after the members notice items.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_notices' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no notices were found.', 'youzer' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the members notices loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_notices_loop' );
