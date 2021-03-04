<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$olympus = Olympus_Options::get_instance();
$description_logo = $olympus->get_option( 'site_description/yes/footer-logo/yes/logo-options', false, $olympus::SOURCE_SETTINGS );

$options = array(
    'type'          => 'popup',
    'label'         => esc_html__( 'Logotype options', 'olympus' ),
    'desc'          => esc_html__( 'Set your website image or text based logotype', 'olympus' ),
    'popup-title'   => esc_html__( 'Logotype options', 'olympus' ),
    'button'        => esc_html__( 'Define website logotype', 'olympus' ),
    'size'          => 'medium',
    'value' => $description_logo,
    'popup-options' => array(
        'logo_image'    => array(
            'label'       => esc_html__( 'Logotype Image', 'olympus' ),
            'type'        => 'upload',
            'images_only' => true,
        ),
        'logo_retina'   => array(
            'type'  => 'switch',
            'label' => esc_html__( 'Logo in Retina?', 'olympus' ),
            'desc'  => esc_html__( 'This image wil be displayed twice smaller than uploaded image size.', 'olympus' ),
            'left-choice' => array(
	            'value' => 'no',
	            'label' => esc_html__( 'No', 'olympus' )
            ),
            'right-choice'  => array(
	            'value' => 'yes',
	            'label' => esc_html__( 'Yes', 'olympus' )
            ),
        ),
        'logo_title'    => array(
            'type'  => 'text',
            'label' => esc_html__( 'Logotype text', 'olympus' ),
            'desc'  => esc_html__( 'Write your logo title', 'olympus' ),
            'value' => get_bloginfo( 'name' ),
        ),
        'logo_subtitle' => array(
            'type'  => 'text',
            'label' => esc_html__( 'Logotype description', 'olympus' ),
            'desc'  => esc_html__( 'Write your logo description', 'olympus' ),
            'value' => get_bloginfo( 'description' ),
        ),
    ),
);
