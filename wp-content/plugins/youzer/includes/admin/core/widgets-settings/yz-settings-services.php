<?php

/**
 * Services Settings.
 */
function yz_services_widget_settings() {

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
            'id'    => 'yz_wg_services_display_title',
            'desc'  => __( 'Show services title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_services_title',
            'desc'  => __( 'Type widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_services_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Services Number', 'youzer' ),
            'desc'  => __( 'Maximum allowed services number', 'youzer' ),
            'id'    => 'yz_wg_max_services',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Services Box Layouts', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_wg_services_layout',
            'desc'  => __( 'Services Widget Layouts', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'services_layout' ),
            'type'  => 'imgSelect',
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // $Yz_Settings->get_field(
    //     array(
    //         'title' => __( 'services icon background style', 'youzer' ),
    //         'type'  => 'openBox'
    //     )
    // );

    // $Yz_Settings->get_field(
    //     array(
    //         'id'    => 'yz_wg_service_icon_bg_format',
    //         'type'  => 'imgSelect',
    //         'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
    //     )
    // );

    // $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Visibility Setting', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Icon', 'youzer' ),
            'desc'  => __( 'Show services icon', 'youzer' ),
            'id'    => 'yz_display_service_icon',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Title', 'youzer' ),
            'desc'  => __( 'Show services title', 'youzer' ),
            'id'    => 'yz_display_service_title',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Description', 'youzer' ),
            'id'    => 'yz_display_service_text',
            'desc'  => __( 'Show services description', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Icon', 'youzer' ),
            'id'    => 'yz_wg_service_icon_color',
            'desc'  => __( 'Service icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Icon Background', 'youzer' ),
            'id'    => 'yz_wg_service_icon_bg_color',
            'desc'  => __( 'Service icon background', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Title', 'youzer' ),
            'id'    => 'yz_wg_service_title_color',
            'desc'  => __( 'Service title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Service Description', 'youzer' ),
            'desc'  => __( 'Service description color', 'youzer' ),
            'id'    => 'yz_wg_service_text_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}