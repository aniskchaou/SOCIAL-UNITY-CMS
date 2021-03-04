<?php

/**
 * Portfolio Settings.
 */
function yz_portfolio_widget_settings() {

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
            'id'    => 'yz_wg_portfolio_display_title',
            'desc'  => __( 'Show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_portfolio_title',
            'desc'  => __( 'Add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_portfolio_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Services Number', 'youzer' ),
            'id'    => 'yz_wg_max_portfolio_items',
            'desc'  => __( 'Maximum allowed services', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Styling Widget', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Color', 'youzer' ),
            'id'    => 'yz_wg_portfolio_button_color',
            'desc'  => __( 'Photo buttons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Icon', 'youzer' ),
            'id'    => 'yz_wg_portfolio_button_txt_color',
            'desc'  => __( 'Button icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Hover Color', 'youzer' ),
            'id'    => 'yz_wg_portfolio_button_hov_color',
            'desc'  => __( 'Buttons hover color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icons Hover', 'youzer' ),
            'desc'  => __( 'Buttons icons hover color', 'youzer' ),
            'id'    => 'yz_wg_portfolio_button_txt_hov_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}