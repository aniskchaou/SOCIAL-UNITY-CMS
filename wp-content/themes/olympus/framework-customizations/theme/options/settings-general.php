<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'general' => array(
        'title'   => esc_html__( 'General', 'olympus' ),
        'type'    => 'tab',
        'options' => array(
            'tracking-scripts' => array(
                'title'   => esc_html__( 'Tracking Scripts', 'olympus' ),
                'type'    => 'tab',
                'options' => array(
                    'tracking-box' => array(
                        'title'   => esc_html__( 'Tracking Scripts', 'olympus' ),
                        'type'    => 'box',
                        'options' => array(
                            'tracking_scripts' => array(
                                'type'          => 'addable-popup',
                                'size'          => 'medium',
                                'label'         => esc_html__( 'Tracking Scripts', 'olympus' ),
                                'desc'          => esc_html__( 'Add your tracking scripts (Hotjar, Google Analytics, etc)', 'olympus' ),
                                'template'      => '{{=name}}',
                                'popup-options' => array(
                                    'name'   => array(
                                        'label' => esc_html__( 'Name', 'olympus' ),
                                        'desc'  => esc_html__( 'Enter a name (it is for internal use and will not appear on the front end)', 'olympus' ),
                                        'type'  => 'text',
                                    ),
                                    'script' => array(
                                        'label' => esc_html__( 'Script', 'olympus' ),
                                        'desc'  => esc_html__( 'Copy/Paste the tracking script here', 'olympus' ),
                                        'type'  => 'textarea',
                                    )
                                ),
                            ),
                        )
                    ),
                )
            ),
            'api-keys'         => array(
                'title'   => esc_html__( 'API Keys', 'olympus' ),
                'type'    => 'tab',
                'options' => array(
                    'google-api-keys-box'  => array(
                        'title'   => esc_html__( 'Google Maps', 'olympus' ),
                        'type'    => 'box',
                        'options' => array(
                            'gmap-key' => array(
                                'label' => esc_html__( 'Google Maps', 'olympus' ),
                                'type'  => 'gmap-key',
                                'desc'  => sprintf( esc_html__( 'Create an application in %sGoogle Console%s and add the API Key here.', 'olympus' ), '<a target="_blank" href="https://console.developers.google.com/flows/enableapi?apiid=places_backend,maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true">', '</a>' )
                            ),
                        )
                    ),
                    'twitter-api-keys-box' => array(
                        'title'   => esc_html__( 'Twitter', 'olympus' ),
                        'type'    => 'box',
                        'options' => array(
                            'twitter-consumer-key'        => array(
                                'label' => esc_html__( 'Consumer Key (API Key)', 'olympus' ),
                                'type'  => 'text',
                                'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Consumer Key here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
                            ),
                            'twitter-consumer-secret'     => array(
                                'label' => esc_html__( 'Consumer Secret (API Secret)', 'olympus' ),
                                'type'  => 'text',
                                'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Consumer Secret here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
                            ),
                            'twitter-access-token'        => array(
                                'label' => esc_html__( 'Access Token', 'olympus' ),
                                'type'  => 'text',
                                'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Access Token here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
                            ),
                            'twitter-access-token-secret' => array(
                                'label' => esc_html__( 'Access Token Secret', 'olympus' ),
                                'type'  => 'text',
                                'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Access Token Secret here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
                            ),
                        )
                    ),
                )
            ),
        ),
    )
);
