<?php

/**
 * Project Settings.
 */
function yz_project_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_project_title',
            'desc'  => __( 'Type widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Types', 'youzer' ),
            'id'    => 'yz_wg_project_types',
            'desc'  => __( 'Add project types', 'youzer' ),
            'type'  => 'taxonomy'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_project_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Visibility Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Meta', 'youzer' ),
            'desc'  => __( 'Show project meta', 'youzer' ),
            'id'    => 'yz_display_prjct_meta',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Meta Icons', 'youzer' ),
            'desc'  => __( 'Show project icons', 'youzer' ),
            'id'    => 'yz_display_prjct_meta_icons',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Tags', 'youzer' ),
            'id'    => 'yz_display_prjct_tags',
            'desc'  => __( 'Show project tags', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Type Background', 'youzer' ),
            'desc'  => __( 'Project type background color', 'youzer' ),
            'id'    => 'yz_wg_project_type_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Type Text', 'youzer' ),
            'desc'  => __( 'Type text color', 'youzer' ),
            'id'    => 'yz_wg_project_type_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Title', 'youzer' ),
            'desc'  => __( 'Project title color', 'youzer' ),
            'id'    => 'yz_wg_project_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Meta', 'youzer' ),
            'id'    => 'yz_wg_project_meta_txt_color',
            'desc'  => __( 'Project meta color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Meta Icons', 'youzer' ),
            'id'    => 'yz_wg_project_meta_icon_color',
            'desc'  => __( 'Project icons color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Description', 'youzer' ),
            'desc'  => __( 'Project description color', 'youzer' ),
            'id'    => 'yz_wg_project_desc_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Project Tags', 'youzer' ),
            'id'    => 'yz_wg_project_tags_color',
            'desc'  => __( 'Project tags color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzer' ),
            'id'    => 'yz_wg_project_tags_bg_color',
            'desc'  => __( 'Tags background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Hashtag', 'youzer' ),
            'desc'  => __( 'Project hashtags color', 'youzer' ),
            'id'    => 'yz_wg_project_tags_hashtag_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}