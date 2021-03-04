<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}
/**
 * Framework options
 *
 * @var array $options Fill this array with options to generate framework settings form in backend
 */
$olympus_portfolio_tab       = $olympus_events_tab          = $olympus_products_tab        = $olympus_learning_tab        = $olympus_bbpress_tab         = $olympus_buddypress_tab      = $olympus_homepage_header_tab = $olympus_llms_tab            = array();
if ( fw_ext( 'portfolio' ) ) {
    $olympus_portfolio_tab = fw()->theme->get_options( 'portfolio-tab' );
}
if ( fw_ext( 'events' ) ) {
    $olympus_events_tab = fw()->theme->get_options( 'events-tab' );
}
if ( fw_ext( 'learning' ) ) {
    $olympus_learning_tab = fw()->theme->get_options( 'learning-tab' );
}
if ( class_exists( 'WooCommerce' ) ) {
    $olympus_products_tab = fw()->theme->get_options( 'products-tab' );
}
if ( class_exists( 'bbPress' ) ) {
    $olympus_bbpress_tab = fw()->theme->get_options( 'bbpress-tab' );
}
if ( class_exists( 'BuddyPress' ) ) {
    $olympus_buddypress_tab = fw()->theme->get_options( 'buddypress-tab' );
}
if ( get_option( 'show_on_front', 'posts' ) == 'posts' ) {
    $olympus_homepage_header_tab = fw()->theme->get_options( 'homepage-tab' );
}
if ( class_exists( 'LifterLMS' ) || class_exists( 'Sensei_Main' ) ) {
    $olympus_llms_tab = fw()->theme->get_options( 'llms-tab' );
}

$olympus_admin_url          = admin_url();
$olympus_template_directory = get_template_directory_uri();

$options = array(
    // Blog settings
    // fw()->theme->get_options( 'settings-blog' ),
    //Fixed panel settings
    //fw()->theme->get_options( 'settings-panel' ),
    //Footer settings
    // fw()->theme->get_options( 'settings-footer' ),
	// General settings
	// fw()->theme->get_options( 'settings-general' ),
	// Requirements settings
	//fw()->theme->get_options( 'settings-requirements' ),
);
