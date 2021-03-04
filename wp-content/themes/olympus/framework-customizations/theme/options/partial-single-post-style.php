<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$olympus = Olympus_Options::get_instance();

$options = array(
    'label'   => esc_html__( 'Post style', 'olympus' ),
    'desc'    => esc_html__( 'Select Style for display single post.', 'olympus' ),
    'type'    => 'image-picker',
    'value'   => 'default',
    'blank'   => false,
    'choices' => array(
        'modern'   => array(
            'small' => array(
                'height' => 90,
                'src'    => get_template_directory_uri() . '/images/admin/single-post/style-1.jpg',
            ),
        ),
        'centered' => array(
            'small' => array(
                'height' => 90,
                'src'    => get_template_directory_uri() . '/images/admin/single-post/style-2.jpg',
            ),
        ),
        'classic'  => array(
            'small' => array(
                'height' => 90,
                'src'    => get_template_directory_uri() . '/images/admin/single-post/style-3.jpg',
            ),
        ),
    ),
);
