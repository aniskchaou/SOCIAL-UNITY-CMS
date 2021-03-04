<?php do_action( 'youzer_account_before_content' ); ?>

<div id="youzer">
	
	<div id="<?php echo apply_filters( 'yz_account_template_id', 'yz-bp' ); ?>" class="youzer yz-page yz-account-page">

		<?php do_action( 'youzer_account_before_main' ); ?>

		<main class="yz-page-main-content">
			

			<aside class="youzer-sidebar yz-settings-sidebar">

				<?php do_action( 'youzer_settings_menus' ); ?>

			</aside>

			<div class="youzer-main-content settings-main-content">
	        
				<?php do_action( 'bp_before_member_settings_template' ); ?>

				<div id="template-notices" role="alert" aria-atomic="true">
					<?php

					/**
					 * Fires towards the top of template pages for notice display.
					 *
					 * @since 1.0.0
					 */
					do_action( 'template_notices' ); ?>

				</div>
				
				<div class="youzer-inner-content settings-inner-content">

	                <?php do_action( 'youzer_account_before_form'); ?>

	                <?php do_action( 'youzer_profile_settings' ); ?>

	                <?php do_action( 'youzer_account_after_form' ); ?>

				</div>

			</div>

		</main>

		<?php do_action( 'youzer_account_footer'); ?>

	</div>

</div>