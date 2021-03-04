<?php

/**
 * # Groups Settings.
 */

function yz_groups_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Scroll To Top', 'youzer' ),
            'desc'  => __( 'Show group scroll to top button', 'youzer' ),
            'id'    => 'yz_display_group_scrolltotop',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Group Avatar Format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_group_header_avatar_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Visibility Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Avatar Border', 'youzer' ),
            'id'    => 'yz_enable_group_header_avatar_border',
            'desc'  => __( 'Display photo transparent border', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Privacy', 'youzer' ),
            'id'    => 'yz_display_group_header_privacy',
            'desc'  => __( 'Display group privacy', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Activity', 'youzer' ),
            'id'    => 'yz_display_group_header_activity',
            'desc'  => __( 'Display group activity', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Members', 'youzer' ),
            'id'    => 'yz_display_group_header_members',
            'desc'  => __( 'Display members number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts', 'youzer' ),
            'id'    => 'yz_display_group_header_posts',
            'desc'  => __( 'Display posts number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Overlay Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Overlay', 'youzer' ),
            'id'    => 'yz_enable_group_header_overlay',
            'desc'  => __( 'Enable cover dark background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_group_header_overlay_opacity',
            'desc'  => __( 'Choose a value between 0.1 - 1', 'youzer' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Pattern Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Dotted Pattern', 'youzer' ),
            'id'    => 'yz_enable_group_header_pattern',
            'desc'  => __( 'Enable cover dotted pattern', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_group_header_pattern_opacity',
            'desc'  => __( 'Choose a value between 0.1 - 1', 'youzer' ),
            'type'  => 'number',
            'step'  => 0.01
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Background', 'youzer' ),
            'id'    => 'yz_group_header_bg_color',
            'desc'  => __( 'Header background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Group Name', 'youzer' ),
            'id'    => 'yz_group_header_username_color',
            'desc'  => __( 'Name text color', 'youzer' ),
            'type'  => 'color'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta Color', 'youzer' ),
            'id'    => 'yz_group_header_text_color',
            'desc'  => __( 'Group name text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icons Color', 'youzer' ),
            'id'    => 'yz_group_header_icons_color',
            'desc'  => __( 'Header icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Title', 'youzer' ),
            'id'    => 'yz_group_header_statistics_title_color',
            'desc'  => __( 'Statistics title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Number', 'youzer' ),
            'id'    => 'yz_group_header_statistics_nbr_color',
            'desc'  => __( 'Statistics numbers color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Layouts', 'youzer' ),
            'class' => 'uk-center-layouts',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_group_header_layout',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'group_header_layouts' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Groups Photos Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Group Avatar', 'youzer' ),
            'desc'  => __( 'Upload default groups avatar', 'youzer' ),
            'id'    => 'yz_default_groups_avatar',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Group Cover', 'youzer' ),
            'desc'  => __( 'Upload default groups cover', 'youzer' ),
            'id'    => 'yz_default_groups_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}