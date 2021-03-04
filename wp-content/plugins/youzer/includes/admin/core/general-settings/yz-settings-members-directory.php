<?php

/**
 * # Memebrs Directory Settings.
 */

function yz_members_directory_settings() {

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
            'desc'  => __( 'Show users cards cover', 'youzer' ),
            'id'    => 'yz_enable_md_cards_cover',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Status', 'youzer' ),
            'desc'  => __( 'Show if user is online or not', 'youzer' ),
            'id'    => 'yz_enable_md_cards_status',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display User Online Status Only', 'youzer' ),
            'desc'  => __( "Don't show offline circle.", 'youzer' ),
            'id'    => 'yz_show_md_cards_online_only',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Action Buttons', 'youzer' ),
            'desc'  => __( 'Show user card buttons', 'youzer' ),
            'id'    => 'yz_enable_md_cards_actions_buttons',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Avatar Border', 'youzer' ),
            'desc'  => __( 'Show user card avatar border', 'youzer' ),
            'id'    => 'yz_enable_md_cards_avatar_border',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Members Per Page', 'youzer' ),
            'desc'  => __( 'Max members cards per page', 'youzer' ),
            'id'    => 'yz_md_users_per_page',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Card Meta Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Cards Custom Meta', 'youzer' ),
            'desc'  => __( 'Use cards custom meta', 'youzer' ),
            'id'    => 'yz_enable_md_custom_card_meta',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'icon',
            'std'   => 'fas fa-globe',
            'id'    => 'yz_md_card_meta_icon',
            'title' => __( 'Meta Icon', 'youzer' ),
            'desc'  => __( 'Select meta icon', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta Field', 'youzer' ),
            'desc'  => __( 'Choose meta field', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_md_card_meta_field',
            'type'  => 'select'
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
            'id'    =>  'yz_md_cards_avatar_border_style',
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
            'id'    => 'yz_enable_md_users_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Posts', 'youzer' ),
            'desc'  => __( 'Enable card posts statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_posts_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Comments', 'youzer' ),
            'desc'  => __( 'Enable card comments statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_comments_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Friends', 'youzer' ),
            'desc'  => __( 'Enable card friends statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_friends_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Views', 'youzer' ),
            'desc'  => __( 'Enable card views statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_views_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Followers', 'youzer' ),
            'desc'  => __( 'Enable card followers statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_followers_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Following', 'youzer' ),
            'desc'  => __( 'Enable card following statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_following_statistics',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Points', 'youzer' ),
            'desc'  => __( 'Enable card points statistics', 'youzer' ),
            'id'    => 'yz_enable_md_user_points_statistics',
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
            'id'    => 'yz_md_cards_buttons_layout',
            'title' => __( 'Buttons Layout', 'youzer' ),
            'desc'  => __( 'Card action buttons layout', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'card_buttons_layout' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'select',
            'id'    => 'yz_md_cards_buttons_border_style',
            'title' => __( 'Buttons Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'card_border_styles' ),
            'desc'  => __( 'Card action buttons border style', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}