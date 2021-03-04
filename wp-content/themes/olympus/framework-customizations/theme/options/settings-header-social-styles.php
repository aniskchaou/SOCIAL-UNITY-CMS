<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'header_social_bg_color'        => array(
        'type'  => 'color-picker',
        'value' => '#3f4257',
        'label' => esc_html__( 'Social Header Background color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #3f4257', 'olympus' ),
    ),
    'header_social_title_color'     => array(
        'type'  => 'color-picker',
        'value' => '#ffffff',
        'label' => esc_html__( 'Social Header Title and icon color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #fff', 'olympus' ),
    ),
    'header_social_form_bg_color'   => array(
        'type'  => 'color-picker',
        'value' => '#494c62',
        'label' => esc_html__( 'Social Header Search form background color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #494c62', 'olympus' ),
    ),
    'header_social_form_text_color' => array(
        'type'  => 'color-picker',
        'value' => '#9a9fbf',
        'label' => esc_html__( 'Social Header Search form placeholder', 'olympus' ),
        'desc'  => esc_html__( 'Default: #9a9fbf', 'olympus' ),
    ),
    'header_social_form_default_icon' => array(
        'type'  => 'upload',
        'label' => esc_html__( 'Social Header Search form default icon', 'olympus' ),
        'images_only' => true,
    ),
);
