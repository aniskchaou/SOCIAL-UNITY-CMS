<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
   'header_general_bg_color'       => array(
        'type'  => 'color-picker',
        'value' => '#ffffff',
        'label' => esc_html__( 'General Header Background color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #ffffff', 'olympus' ),
    ),
   'header_general_logo_color'       => array(
        'type'  => 'color-picker',
        'value' => '#3f4257',
        'label' => esc_html__( 'General Header Logo color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #3f4257', 'olympus' ),
    ),
   'header_general_cart_color'       => array(
        'type'  => 'color-picker',
        'value' => '#9a9fbf',
        'label' => esc_html__( 'General Header  Cart color', 'olympus' ),
        'desc'  => esc_html__( 'Default: #9a9fbf', 'olympus' ),
    ),
);