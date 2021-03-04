<?php
/**
 * Captcha Settings
 */
function logy_captcha_settings() {

    global $Yz_Settings;

    // Get Captcha Url
    $captcha_url = 'https://www.google.com/recaptcha/intro/index.html';

    $Yz_Settings->get_field(
        array(
            'title'     => __( 'How to get your captcha keys?', 'youzer' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_msgbox_captcha',
            'msg'       => sprintf( __( 'To get your keys visit <strong><a href="%s">The reCAPTCHA Site</a></strong> and make sure to use the Recaptcha V2 or check the documentation.', 'youzer' ), $captcha_url )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Captcha', 'youzer' ),
            'desc'  => __( 'Enable using the captcha', 'youzer' ),
            'id'    => 'logy_enable_recaptcha',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Captcha Site Key', 'youzer' ),
            'desc'  => __( 'The reCaptcha site key', 'youzer' ),
            'id'    => 'logy_recaptcha_site_key',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Captcha Secret Key', 'youzer' ),
            'desc'  => __( 'The reCaptcha secret key', 'youzer' ),
            'id'    => 'logy_recaptcha_secret_key',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}