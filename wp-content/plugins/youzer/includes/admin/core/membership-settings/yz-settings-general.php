<?php

/**
 * # General Settings.
 */

function logy_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Page', 'youzer' ),
            'desc'  => __( 'Choose login page', 'youzer' ),
            'std'   => logy_page_id( 'login' ),
            'id'    => 'login',
            'opts'  => yz_get_pages(),
            'type'  => 'select'
        ),
        false,
        'logy_pages'
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Lost Password Page', 'youzer' ),
            'desc'  => __( 'Choose lost password page', 'youzer' ),
            'std'   => logy_page_id( 'lost-password' ),
            'opts'  => yz_get_pages(),
            'id'    => 'lost-password',
            'type'  => 'select'
        ),
        false,
        'logy_pages'
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Dashboard & Toolbar Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Hide Dashboard For Subscribers', 'youzer' ),
            'desc'  => __( 'Hide admin toolbar and dashborad for subscribers', 'youzer' ),
            'id'    => 'logy_hide_subscribers_dash',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Top', 'youzer' ),
            'id'    => 'logy_plugin_margin_top',
            'desc'  => __( 'Specify membership system page top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Bottom', 'youzer' ),
            'id'    => 'logy_plugin_margin_bottom',
            'desc'  => __( 'Specify membership system page bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}