<?php

/**
 * Project Settings.
 */
function yz_project_widget_settings() {

    // Call Scripts.
    wp_enqueue_script( 'yz-ukaitags', YZ_PA .'js/ukaitag.min.js', array( 'jquery' ), YZ_Version, true );

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'project' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_project_title', __( 'Project', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Type', 'youzer' ),
            'id'    => 'wg_project_type',
            'desc'  => __( 'Choose project type', 'youzer' ),
            'opts'  => yz_get_select_options( 'yz_wg_project_types' ),
            'type'  => 'select'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzer' ),
            'id'    => 'wg_project_title',
            'desc'  => __( 'Type project title', 'youzer' ),
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'source' => 'profile_project_widget',
            'title' => __( 'Project Thumbnail', 'youzer' ),
            'id'    => 'wg_project_thumbnail',
            'desc'  => __( 'Upload Project Thumbnail', 'youzer' ),
            'type'  => 'image'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Link', 'youzer' ),
            'id'    => 'wg_project_link',
            'desc'  => __( 'Add project link', 'youzer' ),
            'type'  => 'text'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Description', 'youzer' ),
            'id'    => 'wg_project_desc',
            'desc'  => __( 'Add project description', 'youzer' ),
            'type'  => 'wp_editor'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Categories', 'youzer' ),
            'desc'  => __( 'Write category name and hit "Enter" to add it.', 'youzer' ),
            'id'    => 'wg_project_categories',
            'type'  => 'taxonomy'
        ), true
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project tags', 'youzer' ),
            'id'    => 'wg_project_tags',
            'desc'  => __( 'Write tag name and hit "Enter" to add it.', 'youzer' ),
            'type'  => 'taxonomy'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}