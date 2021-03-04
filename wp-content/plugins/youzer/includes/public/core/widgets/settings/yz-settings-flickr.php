<?php

/**
 * Flickr Settings.
 */
function yz_flickr_widget_settings() {


    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'flickr' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_flickr_title', __( 'Flickr', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Flickr ID', 'youzer' ),
            'id'    => 'wg_flickr_account_id',
            'desc'  => __( 'Flickr ID format example : 12345678@N07', 'youzer' ),
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}