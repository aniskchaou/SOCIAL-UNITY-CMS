<?php

/**
 * # Account Verification Settings.
 */
function yz_account_verification_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Verified Badges', 'youzer' ),
            'desc'  => __( 'Enable accounts verification', 'youzer' ),
            'id'    => 'yz_enable_account_verification',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Badge Background', 'youzer' ),
            'desc'  => __( 'Badge background color', 'youzer' ),
            'id'    => 'yz_verified_badge_background_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Badge Icon', 'youzer' ),
            'desc'  => __( 'Badge icon color', 'youzer' ),
            'id'    => 'yz_verified_badge_icon_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}