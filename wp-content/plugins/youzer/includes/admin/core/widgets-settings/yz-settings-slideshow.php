<?php

/**
 * Slideshow Settings.
 */
function yz_slideshow_widget_settings() {

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
            'id'    => 'yz_wg_slideshow_display_title',
            'desc'  => __( 'Show slideshow title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_slideshow_title',
            'desc'  => __( 'Slideshow widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the slideshow to be loaded?', 'youzer' ),
            'id'    => 'yz_slideshow_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Slides Number', 'youzer' ),
            'id'    => 'yz_wg_max_slideshow_items',
            'desc'  => __( 'Maximum allowed slides', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_slideshow_height_type',
            'title' => __( 'Slides Height Type', 'youzer' ),
            'desc'  => __( 'Set slides height type', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'height_types' ),
            'type'  => 'select',
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Slideshow Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pagination Color', 'youzer' ),
            'desc'  => __( 'Slider pagination color', 'youzer' ),
            'id'    => 'yz_wg_slideshow_pagination_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Slideshow Buttons', 'youzer' ),
            'desc'  => __( '"Next" & "Prev" color', 'youzer' ),
            'id'    => 'yz_wg_slideshow_np_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}