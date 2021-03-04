<?php

/**
 * Friends Settings.
 */
function yz_friends_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzer' ),
            'id'    => 'yz_wg_friends_display_title',
            'desc'  => __( 'Show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_friends_title',
            'desc'  => __( 'Add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Layout', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'friends_layout' ),
            'desc'  => __( 'Select widget layout', 'youzer' ),
            'id'    => 'yz_wg_friends_layout',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_friends_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Friends Number', 'youzer' ),
            'id'    => 'yz_wg_max_friends_items',
            'desc'  => __( 'Maximum number of friends to display', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // $Yz_Settings->get_field(
    //     array(
    //         'title' => __( 'Friends Avatar border style', 'youzer' ),
    //         'type'  => 'openBox'
    //     )
    // );

    // $Yz_Settings->get_field(
    //     array(
    //         'id'    => 'yz_wg_friends_avatar_img_format',
    //         'type'  => 'imgSelect',
    //         'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
    //     )
    // );

    // $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}