<?php

/**
 * Quote Settings.
 */
function yz_quote_widget_settings() {

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'quote' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_quote_title', __( 'Quote', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'  => __( 'Quote Background Image', 'youzer' ),
            'id'     => 'wg_quote_img',
            'desc'   => __( 'Upload quote cover', 'youzer' ),
            'source' => 'profile_quote_widget',
            'type'   => 'image'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Quote Text', 'youzer' ),
            'id'    => 'wg_quote_txt',
            'desc'  => __( 'Type quote text', 'youzer' ),
            'type'  => 'textarea'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Quote Owner', 'youzer' ),
            'desc'  => __( 'Type quote owner', 'youzer' ),
            'id'    => 'wg_quote_owner',
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}