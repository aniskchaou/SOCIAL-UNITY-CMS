<?php

/**
 * Instagram Settings.
 */
function yz_instagram_widget_settings() {

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'instagram' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_instagram_title', __( 'Instagram', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'icon'  => 'instagram',
            'provider' => 'Instagram',
            'id'    => 'wg_instagram_account_token',
            'title' => __( 'Instagram Username', 'youzer' ),
            'button'=> __( 'Connect With Instagram', 'youzer' ),
            'desc'  => __( 'Connect to your instagram account so we can get the permission to display your photos', 'youzer' ),
            'type'  => 'connect'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}
