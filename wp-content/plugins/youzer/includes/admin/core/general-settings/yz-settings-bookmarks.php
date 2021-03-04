<?php

/**
 * # Bookmarks Settings.
 */

function yz_bookmarks_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Bookmarks', 'youzer' ),
            'desc'  => __( 'Enable bookmarks', 'youzer' ),
            'id'    => 'yz_enable_bookmarks',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Bookmarks Privacy', 'youzer' ),
            'desc'  => __( 'Who can see users bookmarks?', 'youzer' ),
            'id'    => 'yz_enable_bookmarks_privacy',
            'opts'  => array(
                'public' => __( 'Public', 'youzer' ),
                'private' => __( 'Private', 'youzer' ),
                'friends' => __( 'Friends', 'youzer' ),
                'loggedin' => __( 'Logged-in Users', 'youzer' ),
            ),
            'type'  => 'select'
        )
    );

    do_action( 'yz_after_bookmarks_settings' );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}