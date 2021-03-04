<?php

/**
 * Post Settings.
 */
function yz_post_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Types', 'youzer' ),
            'id'    => 'yz_wg_post_types',
            'desc'  => __( 'Add post types', 'youzer' ),
            'type'  => 'taxonomy'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_post_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Visibility Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_post_title',
            'desc'  => __( 'Type widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Meta', 'youzer' ),
            'id'    => 'yz_display_wg_post_meta',
            'desc'  => __( 'Show post meta', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Meta Icons', 'youzer' ),
            'desc'  => __( 'Show meta icons', 'youzer' ),
            'id'    => 'yz_display_wg_post_meta_icons',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Date', 'youzer' ),
            'id'    => 'yz_display_wg_post_date',
            'desc'  => __( 'Show post date', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Comments', 'youzer' ),
            'id'    => 'yz_display_wg_post_comments',
            'desc'  => __( 'Show post comments', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Excerpt', 'youzer' ),
            'id'    => 'yz_display_wg_post_excerpt',
            'desc'  => __( 'Show post excerpt', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Tags', 'youzer' ),
            'id'    => 'yz_display_wg_post_tags',
            'desc'  => __( 'Show post tags', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzer' ),
            'id'    => 'yz_display_wg_post_readmore',
            'desc'  => __( 'Show read more button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Styling Widget', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Type Background', 'youzer' ),
            'id'    => 'yz_wg_post_type_bg_color',
            'desc'  => __( 'Post type background', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Post Type Text', 'youzer' ),
            'id'    => 'yz_wg_post_type_txt_color',
            'desc'  => __( 'Type text color ', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzer' ),
            'id'    => 'yz_wg_post_title_color',
            'desc'  => __( 'Post title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta', 'youzer' ),
            'id'    => 'yz_wg_post_meta_txt_color',
            'desc'  => __( 'Post meta color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta Icons', 'youzer' ),
            'id'    => 'yz_wg_post_meta_icon_color',
            'desc'  => __( 'Meta icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Excerpt', 'youzer' ),
            'id'    => 'yz_wg_post_text_color',
            'desc'  => __( 'Post excerpt color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags', 'youzer' ),
            'id'    => 'yz_wg_post_tags_color',
            'desc'  => __( 'Post tags color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzer' ),
            'id'    => 'yz_wg_post_tags_bg_color',
            'desc'  => __( 'Tags background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Hashtag', 'youzer' ),
            'id'    => 'yz_wg_post_tags_hashtag_color',
            'desc'  => __( 'Post hashtags color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More', 'youzer' ),
            'id'    => 'yz_wg_post_rm_color',
            'desc'  => __( 'Read more text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More Background', 'youzer' ),
            'id'    => 'yz_wg_post_rm_bg_color',
            'desc'  => __( 'Read more background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Read More Icon', 'youzer' ),
            'id'    => 'yz_wg_post_rm_icon_color',
            'desc'  => __( 'Read more icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}