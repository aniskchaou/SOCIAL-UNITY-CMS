<?php

/**
 * Social Networks Settings.
 */
function yz_social_networks_widget_settings() {

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
            'id'    => 'yz_wg_sn_display_title',
            'desc'  => __( 'Show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_sn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_sn_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Icons Size', 'youzer' ),
            'desc'  => __( 'Select icons size', 'youzer' ),
            'id'    => 'yz_wg_sn_icons_size',
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'icons_sizes' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Background', 'youzer' ),
            'desc'  => __( 'Select background type', 'youzer' ),
            'id'    => 'yz_wg_sn_bg_type',
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'wg_icons_colors' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Border Style', 'youzer' ),
            'desc'  => __( 'Select networks border style', 'youzer' ),
            'id'    => 'yz_wg_sn_bg_style',
            'type'  => 'select',
            'opts'  =>  $Yz_Settings->get_field_options( 'border_styles' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}