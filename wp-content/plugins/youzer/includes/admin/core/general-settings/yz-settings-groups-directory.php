<?php

/**
 * # Memebrs Directory Settings.
 */

function yz_groups_directory_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Cover', 'youzer' ),
            'desc'  => __( 'Show groups cards cover', 'youzer' ),
            'id'    => 'yz_enable_gd_cards_cover',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Action Buttons', 'youzer' ),
            'desc'  => __( 'Show groups card buttons', 'youzer' ),
            'id'    => 'yz_enable_gd_cards_actions_buttons',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Avatar Border', 'youzer' ),
            'desc'  => __( 'Show user card avatar border', 'youzer' ),
            'id'    => 'yz_enable_gd_cards_avatar_border',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Groups Per Page', 'youzer' ),
            'desc'  => __( 'Max groups cards per page', 'youzer' ),
            'id'    => 'yz_gd_groups_per_page',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Card Avatar Format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_gd_cards_avatar_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Card Statistics Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Statistics', 'youzer' ),
            'desc'  => __( 'Enable card statistics data', 'youzer' ),
            'id'    => 'yz_enable_gd_groups_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Group Posts', 'youzer' ),
            'desc'  => __( 'Enable card posts statistics', 'youzer' ),
            'id'    => 'yz_enable_gd_group_posts_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Group Activity', 'youzer' ),
            'desc'  => __( 'Enable card activity statistics', 'youzer' ),
            'id'    => 'yz_enable_gd_group_activity_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Group Members', 'youzer' ),
            'desc'  => __( 'Enable card members statistics', 'youzer' ),
            'id'    => 'yz_enable_gd_group_members_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Action Buttons Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'select',
            'id'    => 'yz_gd_cards_buttons_border_style',
            'title' => __( 'Buttons Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'card_border_styles' ),
            'desc'  => __( 'Card action buttons border style', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}