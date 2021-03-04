<?php

register_post_type( 'crum-form', array(
    'labels'             => array(
        'name'               => __( 'Forms', 'unyson' ),
        'singular_name'      => __( 'Form', 'unyson' ),
        'menu_name'          => __( 'Forms', 'unyson' ),
        'name_admin_bar'     => __( 'Form', 'unyson' ),
        'add_new'            => __( 'Add New', 'unyson' ),
        'add_new_item'       => __( 'Add New Form', 'unyson' ),
        'new_item'           => __( 'New Form', 'unyson' ),
        'edit_item'          => __( 'Edit Form', 'unyson' ),
        'view_item'          => __( 'View Form', 'unyson' ),
        'view_items'         => __( 'View Forms', 'unyson' ),
        'all_items'          => __( 'All Forms', 'unyson' ),
        'search_items'       => __( 'Search Forms', 'unyson' ),
        'parent_item_colon'  => __( 'Parent Forms:', 'unyson' ),
        'attributes'         => __( 'Form Attributes', 'unyson' ),
        'not_found'          => __( 'No Forms found.', 'unyson' ),
        'not_found_in_trash' => __( 'No Forms found in Trash.', 'unyson' )
    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'form' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 20,
    'menu_icon'          => 'dashicons-editor-table',
    'supports'           => array( 'title', 'thumbnail', 'page-attributes' )
) );
