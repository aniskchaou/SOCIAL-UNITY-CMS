<?php

/**
 * # Widgets Settings.
 */

function yz_general_widgets_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widgets Border Style', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_wgs_border_style',
            'desc'  => __( 'Widgets border style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'widgets_formats' ),
            'type'  => 'imgSelect',
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widgets Title Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Title Icon', 'youzer' ),
            'id'    => 'yz_display_wg_title_icon',
            'desc'  => __( 'Show widget title icon', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Use Title Icon Background', 'youzer' ),
            'id'    => 'yz_use_wg_title_icon_bg',
            'desc'  => __( 'Use widget icon background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzer' ),
            'id'    => 'yz_wgs_title_color',
            'desc'  => __( 'Widget title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title Background', 'youzer' ),
            'id'    => 'yz_wgs_title_bg',
            'desc'  => __( 'Widget title background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title Border', 'youzer' ),
            'id'    => 'yz_wgs_title_border_color',
            'desc'  => __( 'Title bottom border color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon', 'youzer' ),
            'id'    => 'yz_wgs_title_icon_color',
            'desc'  => __( 'Title icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon Background', 'youzer' ),
            'id'    => 'yz_wgs_title_icon_bg',
            'desc'  => __( 'Title icon background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}