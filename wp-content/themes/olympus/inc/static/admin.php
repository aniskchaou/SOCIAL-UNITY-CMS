<?php

/**
 * @package olympus-wp
 */
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}
$screen = get_current_screen();

$theme_version = olympus_get_theme_version();

wp_enqueue_style( 'olympus-admin', get_template_directory_uri() . '/css/theme-admin.css', array(), $theme_version );

if ( is_rtl() ) {
    wp_enqueue_style( 'olympus-admin-rtl', get_template_directory_uri() . '/css/theme-admin-rtl.css', array(), $theme_version );
}

wp_enqueue_script( 'olympus-admin', get_template_directory_uri() . '/js/theme-admin.js', array(), $theme_version, true );

if ( 'widgets' === $screen->base ) {
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'olympus-widgets', get_template_directory_uri() . '/js/theme-widgets.js', array( 'wp-color-picker' ), $theme_version, true );
}



$js_vars = array(
    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    'nonce'   => wp_create_nonce( '_the_core_nonce_admin' )
);

wp_localize_script( 'olympus-admin', 'TheCore', $js_vars );

if ( 'nav-menus' === $screen->base ) {
    wp_enqueue_script( 'olympus-fontawesome', get_template_directory_uri() . '/js/font-awesome-all.js', array(), $theme_version, true );
    wp_enqueue_script( 'olympus-fontawesome-shims', get_template_directory_uri() . '/js/font-awesome-shims.js', array(), $theme_version, true );
}