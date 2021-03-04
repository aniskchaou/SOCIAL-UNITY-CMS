<?php

/**
 * Anonymous User
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( bbp_current_user_can_access_anonymous_user_form() ) : ?>

	<?php do_action( 'bbp_theme_before_anonymous_form' ); ?>

	<div class="bbp-form yz-bbp-box">

		<?php do_action( 'bbp_theme_anonymous_form_extras_top' ); ?>

		<div class="yz-bbp-form-item yz-bbp-form-item-text">
			<label for="bbp_anonymous_author"><?php _e( 'Name (required):', 'youzer' ); ?></label>
			<input type="text" id="bbp_anonymous_author"  value="<?php bbp_author_display_name(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_name" />
		</div>

		<div class="yz-bbp-form-item yz-bbp-form-item-text">
			<label for="bbp_anonymous_email"><?php _e( 'Mail (will not be published) (required):', 'youzer' ); ?></label>
			<input type="text" id="bbp_anonymous_email"   value="<?php bbp_author_email(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_email" />
		</div>

		<div class="yz-bbp-form-item yz-bbp-form-item-text">
			<label for="bbp_anonymous_website"><?php _e( 'Website:', 'youzer' ); ?></label>
			<input type="text" id="bbp_anonymous_website" value="<?php bbp_author_url(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_website" />
		</div>

		<?php do_action( 'bbp_theme_anonymous_form_extras_bottom' ); ?>

	</div>

	<?php do_action( 'bbp_theme_after_anonymous_form' ); ?>

<?php endif; ?>
