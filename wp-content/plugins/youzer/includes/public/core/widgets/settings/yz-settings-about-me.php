<?php

/**
 * About Me Settings.
 */
function yz_about_me_widget_settings() {

    global $Yz_Settings;

    $args = yz_get_profile_widget_args( 'about_me' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_aboutme_title', __( 'About Me', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'wg_about_me_photo',
            'title' => __( 'Upload Photo', 'youzer' ),
            'desc'  => __( 'Upload about me photo', 'youzer' ),
            'source' => 'profile_about_me_widget',
            'type'  => 'image'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzer' ),
            'id'    => 'wg_about_me_title',
            'desc'  => __( 'Type your full name', 'youzer' ),
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Description', 'youzer' ),
            'desc'  => __( 'Type your position', 'youzer' ),
            'id'    => 'wg_about_me_desc',
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Biography', 'youzer' ),
            'id'    => 'wg_about_me_bio',
            'desc'  => __( 'Add your biography', 'youzer' ),
            'type'  => 'wp_editor'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}