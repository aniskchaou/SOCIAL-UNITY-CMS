<?php

/**
 * # Reviews Settings.
 */

function yz_reviews_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Reviews', 'youzer' ),
            'desc'  => __( 'Enable reviews', 'youzer' ),
            'id'    => 'yz_enable_reviews',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allow Reviews Edition', 'youzer' ),
            'desc'  => __( 'Allow users to edit their reviews?', 'youzer' ),
            'id'    => 'yz_allow_users_reviews_edition',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Reviews Privacy', 'youzer' ),
            'desc'  => __( 'Who can see users reviews?', 'youzer' ),
            'id'    => 'yz_user_reviews_privacy',
            'opts'  => array(
                'public' => __( 'Public', 'youzer' ),
                'private' => __( 'Private', 'youzer' ),
                'friends' => __( 'Friends', 'youzer' ),
                'loggedin' => __( 'Logged-in Users', 'youzer' ),
            ),
            'type'  => 'select'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Reviews Per Page', 'youzer' ),
            'desc'  => __( 'Number of reviews per page?', 'youzer' ),
            'id'    => 'yz_profile_reviews_per_page',
            'type'  => 'number'
        )
    );

    do_action( 'yz_after_reviews_settings' );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}