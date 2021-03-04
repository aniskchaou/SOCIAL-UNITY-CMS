<?php

/**
 * Add Woocommerce Settings Tab
 */
function yz_woocommerce_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Woocommerce Integration', 'youzer' ),
            'desc'  => __( 'Enable woocommerce integration', 'youzer' ),
            'id'    => 'yz_enable_woocommerce',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}