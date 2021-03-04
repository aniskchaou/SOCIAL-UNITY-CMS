<?php
/**
 * Captcha Settings
 */
function logy_limit_login_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Limit Login', 'youzer' ),
            'desc'  => __( 'Enable limit login attempts', 'youzer' ),
            'id'    => 'logy_enable_limit_login',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Login Retries', 'youzer' ),
            'desc'  => __( 'Lock out after this many tries', 'youzer' ),
            'id'    => 'logy_allowed_retries',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Short Lockouts Number', 'youzer' ),
            'desc'  => __( 'Apply long lockout after this many lockouts', 'youzer' ),
            'id'    => 'logy_allowed_lockouts',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Retries Duration', 'youzer' ),
            'desc'  => __( 'Reset retries after this many seconds', 'youzer' ),
            'id'    => 'logy_retries_duration',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Short Lockouts Duration', 'youzer' ),
            'desc'  => __( 'Short lockout for this many seconds', 'youzer' ),
            'id'    => 'logy_short_lockout_duration',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Long Lockouts Duration', 'youzer' ),
            'desc'  => __( 'Long lockout for this many seconds', 'youzer' ),
            'id'    => 'logy_long_lockout_duration',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}