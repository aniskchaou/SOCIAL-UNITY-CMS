<?php

/**
 * Register Global Scripts
 */
function yz_global_scripts() {

    // Get Data.
    $jquery = array( 'jquery' );

    // Font Awesome.
    wp_register_style( 'yz-icons', YZ_AA . 'css/all.min.css', array(), YZ_Version );
    
}

add_action( 'wp_loaded', 'yz_global_scripts' );

/**
 * Icon Picker.
 */
function yz_iconpicker_scripts() {

    // Icon Picker.
    wp_enqueue_style( 'yz-iconpicker', YZ_AA . 'css/yz-icon-picker.min.css', array(), YZ_Version );

    // IconPicker Script
    wp_enqueue_script( 'yz-iconpicker', YZ_AA .'js/ukai-icon-picker.min.js', array( 'jquery' ), YZ_Version, true );

}