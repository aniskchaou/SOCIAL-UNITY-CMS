<?php
/**
 * BuddyPress - Members Activate
 */

?>

<?php global $Logy; ?>

<?php $attributes = $Logy->form->get_attributes( 'login' ); ?>

<div class="logy logy-page-box yz-page">
	
	<div class="<?php echo $Logy->form->get_form_class( $attributes ); ?>">
	
	<?php $Logy->form->get_form_header( 'activate' ); ?>
	<?php $Logy->form->get_form_messages( $attributes ); ?>

	<?php

	/**
	 * Fires before the display of the member activation page.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_activation_page' ); ?>

	<div class="page" id="activate-page">

		<?php

		/**
		 * Fires before the display of the member activation page content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_activate_content' ); ?>

		<?php if ( bp_account_was_activated() ) : ?>

		<div id="template-notices" class="logy-form-message logy-success-msg" role="alert" aria-atomic="true" style="margin-bottom: 35px;">
			<?php if ( isset( $_GET['e'] ) ) : ?>
				<p><?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', 'youzer' ); ?></p>
			<?php else : ?>
				<p><?php printf( __( 'Your account was activated successfully! You can now <a href="%s">log in</a> with the username and password you provided when you signed up.', 'youzer' ), logy_page_url( 'login' ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php else : ?>

			<div id="template-notices" role="alert" aria-atomic="true">
				<?php

				/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
				do_action( 'template_notices' ); ?>

			</div>
			
			<div id="logy-form">
					
			<form action="" method="get" class="standard-form" id="activation-form">
				<?php if ( ! bp_get_current_activation_key() ) : ?>
				<p class="logy-field-info"><?php _e( 'Please provide a valid activation key.', 'youzer' ); ?></p>
			<?php endif; ?>

				<?php $elements = $Logy->form->get_form_elements( 'activate' ); ?>

				<?php $Logy->form->generate_form_fields( $elements['fields'], $attributes ); ?>
				<?php $Logy->form->generate_form_actions( $elements['actions'], $attributes ); ?>
				
				<?php do_action( 'logy_after_activate_buttons' ); ?>
				
			</form>
			</div>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of the member activation page content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_activate_content' ); ?>

	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_activation_page' ); ?>

	</div>

</div><!-- .logy-page -->
