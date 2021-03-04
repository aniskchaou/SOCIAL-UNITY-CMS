<?php

/**
 * # Add BBpress Settings Tab
 */
function yz_bbpress_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'BBpress Integration', 'youzer' ),
            'desc'  => __( 'Enable BBpress integration', 'youzer' ),
            'id'    => 'yz_enable_bbpress',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}