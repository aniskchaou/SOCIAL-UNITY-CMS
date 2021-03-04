<?php

/**
 * Social Networks Settings.
 */
function yz_social_networks_widget_settings() {

    global $Yz_Settings;

    // Get Social Networks
    $social_networks = yz_option( 'yz_social_networks' );

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Get Args
    $args = yz_get_profile_widget_args( 'social_networks' );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Social Networks', 'youzer' ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    if ( ! empty( $social_networks )  ) {

        foreach ( $social_networks as $network => $data ) {
            $Yz_Settings->get_field(
                array(
                    'title' => sanitize_text_field( $data['name'] ),
                    'id'    => $network,
                    'type'  => 'text'
                ), true
            );
        }

    }

    $Yz_Settings->get_field( array( 'type' => 'close' ) );
}