<?php

$ext = fw_ext( 'sign-form' );

if ( ! is_admin() ) {

	wp_enqueue_style( 'sign-form', $ext->locate_URI( '/static/css/styles.css' ), array(), $ext->manifest->get_version() );
	wp_enqueue_script( 'sign-form', $ext->locate_URI( '/static/js/scripts.js' ), array( 'jquery' ), $ext->manifest->get_version(), true );
	wp_enqueue_script( 'password-strength-meter' );
	wp_enqueue_script( 'sign-form-password-verify', $ext->locate_URI( '/static/js/password-verify.js' ), array( 'jquery', 'password-strength-meter' ), $ext->manifest->get_version(), true );
	wp_enqueue_script( 'bootstrap-select', $ext->locate_URI( '/static/js/bootstrap-select.min.js' ), array( 'jquery' ), $ext->manifest->get_version(), true );
	wp_enqueue_script( 'lottie-player', $ext->locate_URI( '/static/js/lottie-player.js' ), array(), $ext->manifest->get_version(), true );

	$config            = $ext->get_config();
	$config['ajaxUrl'] = admin_url( 'admin-ajax.php' );

	$enable_captcha = fw_get_db_customizer_option('sign-in-enable-captcha', false);
	$captcha_site_key = fw_get_db_customizer_option('sign-in-captcha-site-key');

	wp_localize_script( 'sign-form', 'signFormConfig', $config );
	wp_localize_script( 'sign-form', 'signFormConfigCaptcha', array('enable_captcha' => $enable_captcha, 'captcha_site_key' => $captcha_site_key) );
	wp_localize_script( 'sign-form-password-verify', 'signFormConfig', $config );

} else {

	wp_enqueue_style( 'sign-form-admin', $ext->locate_URI( '/static/css/admin.css' ), array(), $ext->manifest->get_version() );

}