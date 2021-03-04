<?php

/**
 * Link Settings.
 */
function yz_link_widget_settings() {

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'link' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_link_title', __( 'Link', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Link Background Image', 'youzer' ),
            'id'    => 'wg_link_img',
            'source' => 'profile_link_widget',
            'desc'  => __( 'Upload link cover', 'youzer' ),
            'type'  => 'image'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Link Description', 'youzer' ),
            'id'    => 'wg_link_txt',
            'desc'  => __( 'Add link description', 'youzer' ),
            'type'  => 'textarea'
            ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Link URL', 'youzer' ),
            'desc'  => __( 'Add your link', 'youzer' ),
            'id'    => 'wg_link_url',
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}