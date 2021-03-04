<?php

/**
 * Post Settings.
 */
function yz_post_widget_settings() {

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'post' );

    $Yz_Settings->get_field(
        array(
            'title' => yz_option( 'yz_wg_post_title', __( 'Post', 'youzer' ) ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Type', 'youzer' ),
            'id'    => 'wg_post_type',
            'desc'  => __( 'Choose post type', 'youzer' ),
            'opts'  => yz_get_select_options( 'yz_wg_post_types' ),
            'type'  => 'select'
        ), true
    );

    // Get User Posts Titles for Post Settings

    $post_titles = array( __( 'No Post', 'youzer' ) );

    $posts = get_posts( array( 'author' => bp_displayed_user_id(), 'orderby' => 'post_date', 'posts_per_page' => -1, 'order' => 'DESC' ) );

    if ( $posts ) {
        foreach ( $posts as $post ) {
            $post_titles[ $post->ID ] = $post->post_title;
        }
    }

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post', 'youzer' ),
            'id'    => 'yz_profile_wg_post_id',
            'desc'  => __( 'Choose your post', 'youzer' ),
            'opts'  => $post_titles,
            'type'  => 'select'
        ), true
    );

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}