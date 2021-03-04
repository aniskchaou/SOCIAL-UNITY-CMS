<?php

defined( 'FW' ) or die();

$extensions = array(
    'extended-search'         => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Extended search', 'olympus' ),
        'description' => esc_html__( 'Extended search.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/extended-search-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
    'ajax-blog'       => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Ajax blog', 'olympus' ),
        'description' => esc_html__( 'Ajax blog.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/ajax-blog-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
    'post-reaction'   => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Post reaction', 'olympus' ),
        'description' => esc_html__( 'Post reaction.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/post-reaction-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
    'contact-form'    => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Contact form', 'olympus' ),
        'description' => esc_html__( 'Contact form.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/contact-form-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
    'sign-form'       => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Sign in Form', 'olympus' ),
        'description' => esc_html__( 'Sign in Form.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/sign-form-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
    'post-share'      => array(
        'display'     => true,
        'parent'      => null,
        'name'        => esc_html__( 'Post Share', 'olympus' ),
        'description' => esc_html__( 'Post Share.', 'olympus' ),
        'thumbnail'   => get_template_directory_uri() . '/images/post-share-extension-thumb.png',
        'download'    => array(
            'source' => 'custom',
            'opts'   => array(
                'remote' => 'https://up.crumina.net/extensions/versions/',
            ),
        ),
    ),
);
