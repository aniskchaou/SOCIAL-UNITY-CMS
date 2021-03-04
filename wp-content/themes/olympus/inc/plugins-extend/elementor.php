<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

function olympus_default_elementor_options() {

	//if exists, assign to $cpt_support var
	$cpt_support = get_option( 'elementor_cpt_support' );

	//check if option DOESN'T exist in db
	if( ! $cpt_support ) {
		$cpt_support = [ 'page', 'post', 'fw-portfolio' ]; //create array of our default supported post types
		update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
	}

	//if it DOES exist, but portfolio is NOT defined
	else if( ! in_array( 'fw-portfolio', $cpt_support ) ) {
		$cpt_support[] = 'fw-portfolio'; //append to array
		update_option( 'elementor_cpt_support', $cpt_support ); //update database
	}

	//otherwise do nothing, portfolio already exists in elementor_cpt_support option

    // Add support for FontAwesome4
	update_option( 'elementor_load_fa4_shim', 'yes' ); //write it to the database

	// Update Default options
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_disable_color_schemes', 'yes' );

}
add_action( 'after_switch_theme', 'olympus_default_elementor_options' );
add_action( 'upgrader_process_complete', 'olympus_default_elementor_options', 10, 2);

function olympus_elementor_add_ref_links( $settings ){

	$settings = array_replace_recursive( $settings, [
		'icons'                => [
			'goProURL' => 'https://elementor.com/pro/?ref=3814',
		],
		'elementor_site'       => 'https://go.elementor.com/about-elementor/?ref=3814',
		'docs_elementor_site'  => 'https://go.elementor.com/docs/?ref=3814',
		'help_the_content_url' => 'https://go.elementor.com/the-content-missing/?ref=3814',
		'help_right_click_url' => 'https://go.elementor.com/meet-right-click/?ref=3814',
		'help_flexbox_bc_url'  => 'https://go.elementor.com/flexbox-layout-bc/?ref=3814',
		'elementPromotionURL'  => 'https://go.elementor.com/go-pro-%s?ref=3814',
		'dynamicPromotionURL'  => 'https://go.elementor.com/go-pro-dynamic-tag?ref=3814',
	] );

	return $settings;
};

add_filter( 'elementor/editor/localize_settings', 'olympus_elementor_add_ref_links' );
