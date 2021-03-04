<?php

/**
 * # Profile General Settings.
 */

function yz_profile_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_allow_private_profiles',
            'title' => __( 'Allow Private Profiles', 'youzer' ),
            'desc'  => __( 'Allow users to make their profiles private', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Use Profile Effects?', 'youzer' ),
            'id'    => 'yz_use_effects',
            'desc'  => __( 'Load profile elements with effects', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Login Button?', 'youzer' ),
            'id'    => 'yz_profile_login_button',
            'desc'  => __( 'Show profile sidebar login button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Account Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'bp-disable-account-deletion',
            'title' => __( 'Allow Delete Accounts', 'youzer' ),
            'desc'  => __( 'Allow registered members to delete their own accounts', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Account Scroll Button', 'youzer' ),
            'id'    => 'yz_enable_account_scroll_button',
            'desc'  => __( 'Display scroll to top button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profiles Photos Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profile Avatar', 'youzer' ),
            'desc'  => __( 'Upload default profiles avatar', 'youzer' ),
            'id'    => 'yz_default_profiles_avatar',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Default Profile Cover', 'youzer' ),
            'desc'  => __( 'Upload default profiles cover', 'youzer' ),
            'id'    => 'yz_default_profiles_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}