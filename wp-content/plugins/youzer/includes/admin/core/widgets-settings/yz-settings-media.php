<?php

/**
 * # Media Settings.
 */
function yz_media_widget_settings() {

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
            'id'    => 'yz_wg_media_display_title',
            'desc'  => __( 'Show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_media_title',
            'desc'  => __( 'Add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_media_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Media Filters', 'youzer' ),
            'id'    => 'yz_wg_media_filters',
            'desc'  => __( 'You can change the order of filters or remove some. The allowed filters names are photos, videos, audios, files', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Media Photos Number', 'youzer' ),
            'id'    => 'yz_wg_max_media_photos',
            'desc'  => __( 'Maximum shown items', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Media Videos Number', 'youzer' ),
            'id'    => 'yz_wg_max_media_videos',
            'desc'  => __( 'Maximum shown items', 'youzer' ),
            'type'  => 'number'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Media Audios Number', 'youzer' ),
            'id'    => 'yz_wg_max_media_audios',
            'desc'  => __( 'Maximum shown items', 'youzer' ),
            'type'  => 'number'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Media Files Number', 'youzer' ),
            'id'    => 'yz_wg_max_media_files',
            'desc'  => __( 'Maximum shown items', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}