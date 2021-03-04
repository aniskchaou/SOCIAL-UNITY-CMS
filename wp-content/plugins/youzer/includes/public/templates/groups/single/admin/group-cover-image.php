<?php
/**
 * BuddyPress - Groups Admin - Group Cover Image Settings
 */

?>

<div class="yz-group-settings-tab yz-group-cover-image-settings">

	<?php

	/**
	 * Fires before the display of profile cover image upload content.
	 *
	 * @since 2.4.0
	 */
	do_action( 'bp_before_group_settings_cover_image' ); ?>

	<p><?php _e( 'The Cover Image will be used to customize the header of your group.', 'youzer' ); ?></p>

	<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>

	<?php

	/**
	 * Fires after the display of group cover image upload content.
	 *
	 * @since 2.4.0
	 */
	do_action( 'bp_after_group_settings_cover_image' ); ?>
	
</div>