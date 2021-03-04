<?php

/**
 * Notifications Settings.
 */
function yz_notifications_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Live Notifications Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_enable_live_notifications',
            'title' => __( 'Live Notifications', 'youzer' ),
            'desc'  => __( 'Enable live notifications', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_live_notifications_interval',
            'title' => __( 'New Notifications Interval', 'youzer' ),
            'desc'  => __( 'Check for new notifications interval by seconds', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}