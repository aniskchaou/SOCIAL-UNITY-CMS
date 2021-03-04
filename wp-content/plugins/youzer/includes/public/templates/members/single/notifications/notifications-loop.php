<?php
/**
 * BuddyPress - Members Notifications Loop
 */

?>

<form action="" method="post" id="notifications-bulk-management">
	<table class="notifications">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="bulk-select-all"><label class="yz_cs_checkbox_field" for="select-all-notifications"><input id="select-all-notifications" type="checkbox"><div class="yz_field_indication"></div></label></th>
				<th class="title"><?php _e( 'Notification', 'youzer' ); ?></th>
				<th class="date"><?php _e( 'Date Received', 'youzer' ); ?></th>
				<th class="actions"><?php _e( 'Actions',    'youzer' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

				<tr>
					<td></td>
					<td class="bulk-select-check"><label class="yz_cs_checkbox_field" for="<?php bp_the_notification_id(); ?>"><input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"><div class="yz_field_indication"></div><span class="bp-screen-reader-text"><?php
						/* translators: accessibility text */
						_e( 'Select this notification', 'youzer' );
					?></span></label></td>
					<td class="notification-description"><?php bp_the_notification_description();  ?></td>
					<td class="notification-since"><?php bp_the_notification_time_since();   ?></td>
					<td class="notification-actions"><?php bp_the_notification_action_links( array( 'sep' => '' ) ); ?></td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<div class="notifications-options-nav">
		<?php bp_notifications_bulk_management_dropdown(); ?>
	</div><!-- .notifications-options-nav -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
</form>
