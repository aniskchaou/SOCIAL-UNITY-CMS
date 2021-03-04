<?php
/**
 * Template Name: Youzer Profile Template
 */
?>

<div id="youzer">

<?php do_action( 'youzer_profile_before_profile' ); ?>

<div id="<?php echo apply_filters( 'yz_profile_template_id', 'yz-bp' ); ?>" class="youzer noLightbox yz-page yz-profile <?php echo yz_get_profile_class(); ?>">

	<?php do_action( 'youzer_profile_before_content' ); ?>

	<div class="yz-content">

		<?php do_action( 'youzer_profile_before_header' ); ?>

		<header id="yz-profile-header" class="<?php echo yz_headers()->get_class( 'user' ); ?>" <?php echo yz_widgets()->get_loading_effect( yz_option( 'yz_hdr_load_effect', 'fadeIn' ) ); ?>><?php do_action( 'youzer_profile_header' ); ?></header>

		<!-- <div class="yz-profile-content"> -->

			<!-- <div class="yz-inner-content"> -->

				<?php do_action( 'youzer_profile_navbar' ); ?>

				<main class="yz-page-main-content">

					<?php

					/**
					 * Fires before the display of member home content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_before_member_home_content' ); ?>

					<?php do_action( 'yz_profile_main_content' ); ?>

					<?php

						/**
						 * Fires after the display of member home content.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_after_member_home_content' );

					?>

				</main>

			<!-- </div> -->

		<!-- </div> -->

		<?php do_action( 'youzer_profile_sidebar' ); ?>

	</div>

	<?php do_action( 'youzer_profile_after_content' ); ?>

</div>

<?php do_action( 'youzer_profile_after_profile' ); ?>

</div>