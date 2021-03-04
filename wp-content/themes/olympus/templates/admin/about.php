<?php
wp_enqueue_script( 'jquery-ui-core' );
?>

<div class="wrap">
	<h1 class="title-hidden"><?php echo get_admin_page_title(); ?></h1>

	<div class="content">

		<div class="update-flex main-block">

			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/guy-update.png'); ?>" alt="OlympusV2" width="328" height="253">

			<div class="update-content">
				<h2><?php esc_html_e( 'Welcome to Olympus 2.0!', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'Learn how to update the theme in just a few steps', 'olympus' ); ?></p>

				<h3><?php esc_html_e( 'After you update the theme, you need to perform the following steps:', 'olympus' ); ?></h3>
			</div>
		</div>

		<div class="update-flex">
			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/step1.jpg'); ?>" alt="Step 1" width="670" height="329">

			<div class="update-content">
				<h2><?php esc_html_e( 'Step 01', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'Install required plugins, by clicking "Begin installing plugin". It will redirect you to the required plugins page. ', 'olympus' ); ?></p>
			</div>
		</div>

		<div class="update-flex">
			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/step2.jpg'); ?>" alt="Step 2" width="670" height="329">

			<div class="update-content">
				<h2><?php esc_html_e( 'Step 02', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'Click on the "Return to the required plugins page" link and activate the required plugins', 'olympus' ); ?></p>
			</div>
		</div>

		<div class="update-flex">
			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/step3.jpg'); ?>" alt="Step 3" width="670" height="262">

			<div class="update-content">
				<h2><?php esc_html_e( 'Step 03', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'After plugins activation, begin installing theme compatible extensions', 'olympus' ); ?></p>
			</div>
		</div>

		<div class="update-flex">
			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/step4.jpg'); ?>" alt="Step 4" width="670" height="485">

			<div class="update-content">
				<h2><?php esc_html_e( 'Important!: ', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'Since the stunning header had been moved in an extension in the Olympus version 2.0, all settings for stunning header were reset. Please, configure stunning header options for your website once more.', 'olympus' ); ?></p>
			</div>
		</div>

		<div class="update-flex">
			<img loading="lazy" class="update-image" src="<?php echo get_theme_file_uri('images/admin/step5.jpg'); ?>" alt="Step 4" width="670" height="247">

			<div class="update-content">
				<h2><?php esc_html_e( 'Step 04', 'olympus' ); ?></h2>
				<p><?php esc_html_e( 'Edit the Home Page and replace the "Crumina Registration Form" shortcode with the new "Sign In/Sign Up Form" Shortcode.', 'olympus' ); ?></p>
			</div>
		</div>

	</div>
</div>
