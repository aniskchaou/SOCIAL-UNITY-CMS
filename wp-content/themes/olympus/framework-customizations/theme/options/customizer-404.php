<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'not_found_page_image' => array(
        'label' => esc_html__( 'Image', 'olympus' ),
        'type'  => 'upload',
        'value' => '',
        'images_only' => true,
    ),
    'not_found_page_title'  => array(
        'label' => esc_html__( 'Title', 'olympus' ),
        'type'  => 'textarea',
        'value' => esc_html__('A wild ghost appears! Sadly, not what you were looking for...', 'olympus' )
    ),
    'not_found_page_text'  => array(
        'label' => esc_html__( 'Text', 'olympus' ),
        'type'  => 'textarea',
        'value' => esc_html__('Sorry! The page you were looking for has been moved or doesn\'t exist. If you like, you can return to our homepage, or try a search?', 'olympus' )
    ),
    'not_found_page_button_link'  => array(
        'label' => esc_html__( 'Button link', 'olympus' ),
        'type'  => 'text',
        'value' => esc_url(home_url())
    ),
    'not_found_page_button_text'  => array(
        'label' => esc_html__( 'Button text', 'olympus' ),
        'type'  => 'text',
        'value' => esc_html__( 'Go to Homepage', 'olympus' )
    ),
);