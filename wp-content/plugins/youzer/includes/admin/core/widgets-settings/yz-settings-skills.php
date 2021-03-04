<?php

/**
 * # Skills Settings.
 */
function yz_skills_widget_settings() {

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
            'desc'  => __( 'Show skills title', 'youzer' ),
            'id'    => 'yz_wg_skills_display_title',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_skills_title',
            'desc'  => __( 'Type widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_skills_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Skills Number', 'youzer' ),
            'desc'  => __( 'Maximum llowed skills number.', 'youzer' ),
            'id'    => 'yz_wg_max_skills',
            'std'   => 3,
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}