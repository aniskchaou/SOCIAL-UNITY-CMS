<?php

/**
 * # Plugin Schemes Settings.
 */

function yz_schemes_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title'     => __( 'Info', 'youzer' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'yz_msgbox_profile_schemes',
            'msg'       => __( 'If you want to use the <strong>Custom Profile Scheme Color</strong>, make sure that you <strong>enabled</strong> the custom scheme button.', 'youzer' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Custom Scheme?', 'youzer' ),
            'desc'  => __( 'Wanna use custom scheme color?', 'youzer' ),
            'id'    => 'yz_enable_profile_custom_scheme',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Custom Scheme', 'youzer' ),
            'desc'  => __( 'Profile custom scheme color', 'youzer' ),
            'id'    => 'yz_profile_custom_scheme_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Profile Schemes', 'youzer' ),
            'class' => 'uk-img-radius youzer-plugin-schemes uk-center-elements',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'        =>  'yz_profile_scheme',
            'use_class' => true,
            'type'      => 'imgSelect',
            'opts'      => array(
                'yz-blue-scheme', 'yz-orange-scheme', 'yz-red-scheme', 'yz-green-scheme',
                'yz-crimson-scheme', 'yz-aqua-scheme', 'yz-purple-scheme', 'yz-brown-scheme',
                'yz-yellow-scheme', 'yz-pink-scheme', 'yz-darkblue-scheme', 'yz-darkgreen-scheme',
                'yz-darkorange-scheme', 'yz-gray-scheme', 'yz-lightblue-scheme', 'yz-darkgray-scheme'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}