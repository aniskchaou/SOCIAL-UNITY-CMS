<?php

/**
 * # Porfile 404 Settings.
 */

function yz_profile_404_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Error Message', 'youzer' ),
            'desc'  => __( 'Page error message', 'youzer' ),
            'id'    => 'yz_profile_404_desc',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Title', 'youzer' ),
            'desc'  => __( 'Page button title', 'youzer' ),
            'id'    => 'yz_profile_404_button',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Photo', 'youzer' ),
            'desc'  => __( 'Upload 404 profile photo', 'youzer' ),
            'id'    => 'yz_profile_404_photo',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover', 'youzer' ),
            'desc'  => __( 'Upload 404 profile cover', 'youzer' ),
            'id'    => 'yz_profile_404_cover',
            'type'  => 'upload'
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
            'title' => __( 'Title', 'youzer' ),
            'desc'  => __( 'Title color', 'youzer' ),
            'id'    => 'yz_profile_404_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Description', 'youzer' ),
            'desc'  => __( 'Description color', 'youzer' ),
            'id'    => 'yz_profile_404_desc_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Text', 'youzer' ),
            'desc'  => __( 'Button text color', 'youzer' ),
            'id'    => 'yz_profile_404_button_txt_color',
            'type'  => 'color'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Background', 'youzer' ),
            'desc'  => __( 'Button background color', 'youzer' ),
            'id'    => 'yz_profile_404_button_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}