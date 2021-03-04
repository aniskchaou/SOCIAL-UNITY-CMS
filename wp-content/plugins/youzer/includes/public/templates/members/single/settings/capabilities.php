<?php
/**
 * BuddyPress - Members Settings Capabilities
 *
 */
?>

<?php

/**
 * Fires before the display of the submit button for user capabilities saving.
 *
 * @since 1.6.0
 */
do_action( 'bp_members_capabilities_account_before_submit' ); ?>
<div class="yz-capabilities-account-item">
	
<label for="user-spammer">
	<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
	 <?php _e( 'This user is a spammer.', 'youzer' ); ?>
</label>

</div>

<?php

/**
 * Fires after the display of the submit button for user capabilities saving.
 *
 * @since 1.6.0
 */
do_action( 'bp_members_capabilities_account_after_submit' ); ?>

<?php wp_nonce_field( 'capabilities' );?>