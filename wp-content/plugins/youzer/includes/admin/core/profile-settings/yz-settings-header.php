<?php

/**
 * # Header Settings.
 */

function yz_header_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Status', 'youzer' ),
            'desc'  => __( 'Show if user is online or offline!', 'youzer' ),
            'id'    => 'yz_header_enable_user_status',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Meta Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'The First Meta Icon', 'youzer' ),
            'desc'  => __( 'Choose the first user meta icon.', 'youzer' ),
            'id'    => 'yz_hheader_meta_icon_1',
            'type'  => 'icon'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'The First Meta', 'youzer' ),
            'desc'  => __( 'Choose the first header user meta.', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_hheader_meta_type_1',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'The Second Meta Icon', 'youzer' ),
            'desc'  => __( 'Choose the second header user meta icon.', 'youzer' ),
            'id'    => 'yz_hheader_meta_icon_2',
            'type'  => 'icon'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'The Second Meta', 'youzer' ),
            'desc'  => __( 'Choose the second header user meta.', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_hheader_meta_type_2',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Header Meta', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'Header Meta Icon', 'youzer' ),
            'desc'  => __( 'Vertical header user meta icon?', 'youzer' ),
            'id'    => 'yz_header_meta_icon',
            'type'  => 'icon'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'Header Meta', 'youzer' ),
            'desc'  => __( 'Vertical header user meta type?', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_header_meta_type',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzer' ),
            'id'       => 'yz_msgbox_profile_schemes',
            'msg'      => __( '<strong>"Vertical Header Settings"</strong> section options works only with the <strong>Vertical Header Layouts</strong>. if you use it with horizontal layouts it will have <strong>no effect</strong>!', 'youzer' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Header settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    // $Yz_Settings->get_field(
    //     array(
    //         'title' => __( 'Use photo as cover?', 'youzer' ),
    //         'desc'  => __( 'If cover not exist use profile photo as cover?', 'youzer' ),
    //         'id'    => 'yz_header_use_photo_as_cover',
    //         'type'  => 'checkbox'
    //     )
    // );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Borders', 'youzer' ),
            'desc'  => __( 'Use statistics borders?', 'youzer' ),
            'id'    => 'yz_header_use_statistics_borders',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Background', 'youzer' ),
            'desc'  => __( 'Use statistics silver background?', 'youzer' ),
            'id'    => 'yz_header_use_statistics_bg',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Image Format', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    =>  'yz_header_photo_border_style',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'image_formats' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Effects Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Photo Effect', 'youzer' ),
            'desc'  => __( 'Works only with circle photos !', 'youzer' ),
            'id'    => 'yz_profile_photo_effect',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'select header loading effect', 'youzer' ),
            'id'    => 'yz_hdr_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Networks Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Social Networks', 'youzer' ),
            'desc'  => __( 'show header social networks', 'youzer' ),
            'id'    => 'yz_display_header_networks',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Type', 'youzer' ),
            'id'    => 'yz_header_sn_bg_type',
            'desc'  => __( 'Networks background type', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Networks Style', 'youzer' ),
            'id'    => 'yz_header_sn_bg_style',
            'desc'  => __( 'Networks background style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'border_styles' )
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
            'title' => __( 'First Statistic', 'youzer' ),
            'id'    => 'yz_display_header_first_statistic',
            'desc'  => __( 'Display first statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzer' ),
            'id'    => 'yz_display_header_second_statistic',
            'desc'  => __( 'Display second statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzer' ),
            'id'    => 'yz_display_header_third_statistic',
            'desc'  => __( 'Display third statistic number', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Statistics Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'First Statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'Select header first statistic', 'youzer' ),
            'id'    => 'yz_header_first_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Second Statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'Select header second statistic', 'youzer' ),
            'id'    => 'yz_header_second_statistic',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Third Statistic', 'youzer' ),
            'opts'  => yz_get_user_statistics_details(),
            'desc'  => __( 'Select header third statistic', 'youzer' ),
            'id'    => 'yz_header_third_statistic',
            'type'  => 'select'
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
            'id'    => 'yz_enable_header_overlay',
            'desc'  => __( 'Enable cover dark background', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_profile_header_overlay_opacity',
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
            'id'    => 'yz_enable_header_pattern',
            'desc'  => __( 'Enable cover dotted pattern', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Overlay Opacity', 'youzer' ),
            'id'    => 'yz_profile_header_pattern_opacity',
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
            'id'    => 'yz_profile_header_bg_color',
            'desc'  => __( 'Header background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Username', 'youzer' ),
            'id'    => 'yz_profile_header_username_color',
            'desc'  => __( 'Username text color', 'youzer' ),
            'type'  => 'color'
        )
    );


    $Yz_Settings->get_field(
        array(
            'title' => __( 'Meta Color', 'youzer' ),
            'id'    => 'yz_profile_header_text_color',
            'desc'  => __( 'Header text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icons Color', 'youzer' ),
            'id'    => 'yz_profile_header_icons_color',
            'desc'  => __( 'Header icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Title', 'youzer' ),
            'id'    => 'yz_profile_header_statistics_title_color',
            'desc'  => __( 'Statistics title color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Statistics Number', 'youzer' ),
            'id'    => 'yz_profile_header_statistics_nbr_color',
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
            'id'    => 'yz_header_layout',
            'type'  => 'imgSelect',
            'opts'  => $Yz_Settings->get_field_options( 'header_layouts' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}