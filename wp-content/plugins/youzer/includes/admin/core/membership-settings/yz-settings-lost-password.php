<?php

/**
 * Lost Password Settings
 */

function logy_lost_password_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzer' ),
            'desc'  => __( 'Lost password form title', 'youzer' ),
            'id'    => 'logy_lostpswd_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzer' ),
            'desc'  => __( 'Lost password subtitle', 'youzer' ),
            'id'    => 'logy_lostpswd_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Reset Button Title', 'youzer' ),
            'desc'  => __( 'Reset password button title', 'youzer' ),
            'id'    => 'logy_lostpswd_submit_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Form Cover', 'youzer' ),
            'desc'  => __( 'Enable form header cover?', 'youzer' ),
            'id'    => 'logy_lostpswd_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Cover', 'youzer' ),
            'desc'  => __( 'Upload login form cover', 'youzer' ),
            'id'    => 'logy_lostpswd_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}