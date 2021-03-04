<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * Autoload function, that include all theme classes.
 */
load_template( get_template_directory() . '/inc/classes/olympus-class-autoload.php' );

Olympus_Core::get_instance()->init();

/**
 * Initialize the Theme Updater
 *
 * @return void
 */
function olympus_appsero_init_tracker() {

	if ( ! class_exists( 'Appsero\Client' ) ) {
		require_once get_template_directory() . '/lib/appsero/src/Client.php';
	}

	$client = new Appsero\Client( '933a26aa-f086-4de9-90a8-0c39a029ed3e', 'Olympus - Social Network', __FILE__ );

	// Active insights
	$client->insights()->init();

	// Active automatic updater
	//$client->updater();

	// Active license page and checker
	$args = array(
		'type'       => 'menu',
		'menu_title' => esc_html__( 'Olympus License', 'olympus' ),
		'page_title' => esc_html__( 'Olympus - Update and License Theme Settings', 'olympus' ),
		'menu_slug'  => 'olympus_license_settings',
	);

	global $olympus_license;
	$olympus_license = $client->license();
	$olympus_license->add_settings_page( $args );

}

olympus_appsero_init_tracker();