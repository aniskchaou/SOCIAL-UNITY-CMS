<?php

/**
 * # Posts Settings.
 */

function yz_posts_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Per Page', 'youzer' ),
            'id'    => 'yz_profile_posts_per_page',
            'desc'  => __( 'How many posts per page?', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Visibility Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzer' ),
            'id'    => 'yz_display_post_meta',
            'desc'  => __( 'Show post meta', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta Icons', 'youzer' ),
            'id'    => 'yz_display_post_meta_icons',
            'desc'  => __( 'Show post meta icons', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzer' ),
            'id'    => 'yz_display_post_excerpt',
            'desc'  => __( 'Show post excerpt', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Date', 'youzer' ),
            'id'    => 'yz_display_post_date',
            'desc'  => __( 'Show post date', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Categories', 'youzer' ),
            'id'    => 'yz_display_post_cats',
            'desc'  => __( 'Show post categories', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Comments', 'youzer' ),
            'id'    => 'yz_display_post_comments',
            'desc'  => __( 'Show comments number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzer' ),
            'id'    => 'yz_display_post_readmore',
            'desc'  => __( 'Show read more button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Title', 'youzer' ),
            'id'    => 'yz_post_title_color',
            'desc'  => __( 'Post title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzer' ),
            'id'    => 'yz_post_meta_color',
            'desc'  => __( 'post meta color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Meta Icons', 'youzer' ),
            'id'    => 'yz_post_meta_icons_color',
            'desc'  => __( 'Meta icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzer' ),
            'id'    => 'yz_post_text_color',
            'desc'  => __( 'Post excerpt color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More Background', 'youzer' ),
            'id'    => 'yz_post_button_color',
            'desc'  => __( 'Read more button color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More Text', 'youzer' ),
            'id'    => 'yz_post_button_text_color',
            'desc'  => __( 'Read more text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More Icon', 'youzer' ),
            'id'    => 'yz_post_button_icon_color',
            'desc'  => __( 'Read more icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}