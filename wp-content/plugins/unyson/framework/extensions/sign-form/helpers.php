<?php

if ( !defined( 'FW' ) ) {
    return;
}

/**
 * Generate html markup for registration form.
 *
 * @param $redirect_to string Redirect URL
 */
function crumina_get_reg_form_html( $redirect_to_custom = '', $option_data = array() ) {
    global $wp;
    $ext = fw_ext( 'sign-form' );

    $forms              = fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-forms', 'both' );
    $redirect           = fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-redirect-to/sign-form-redirect', 'current' );
    $register_redirect  = fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-redirect-register-to/sign-form-register-redirect', 'current' );
    $redirect_to        = filter_var( fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-redirect-to/custom/redirect_to', '' ), FILTER_VALIDATE_URL );
    $register_redirect_to = filter_var( fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-redirect-register-to/custom/redirect_register_to', '' ), FILTER_VALIDATE_URL );
    $login_descr        = fw_get_db_customizer_option( 'sign-form-popup/native/sign-form-login-descr', '' );
    $redirect_to_custom = filter_var( $redirect_to_custom, FILTER_VALIDATE_URL );

    $redirect_to = ($redirect_to && $redirect === 'custom') ? $redirect_to : home_url( $wp->request );
    if ( $redirect_to_custom ) {
        $redirect_to = $redirect_to_custom;
    }

    $register_redirect_to = ($register_redirect_to && $register_redirect === 'custom') ? $register_redirect_to : home_url( $wp->request );
    if ( $redirect_to_custom ) {
        $register_redirect_to = $redirect_to_custom;
    }

    $attr = array();

    if(!empty($option_data)){
        foreach ($option_data as $option_data_key => $option_data_value){
            $attr[$option_data_key] = $option_data_value;
        }
    }else{
        $attr = array(
            'register_redirect_to' => $register_redirect_to,
            'redirect_to' => $redirect_to,
            'forms'       => $forms,
            'redirect'    => $redirect,
            'register_redirect' => $register_redirect,
            'login_descr' => $login_descr,
        );
    }

    // Captcha
    $enable_captcha = fw_get_db_customizer_option('sign-in-enable-captcha', false);
    $captcha_site_key = fw_get_db_customizer_option('sign-in-captcha-site-key');

    $attr['enable_captcha'] = $enable_captcha;
    $attr['captcha_site_key'] = $captcha_site_key;
    // Captcha

    $register_fields_type = fw_get_db_customizer_option('sign-in-register-fields-type', 'simple');
	$attr['register_fields_type'] = $register_fields_type;

    return $ext->get_view( 'form', $attr);
}