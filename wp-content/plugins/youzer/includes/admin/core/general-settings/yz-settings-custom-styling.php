<?php

/**
 * # Custom Styling Settings.
 */

function yz_custom_styling_settings() {

	global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Global Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Global CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_global_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Global Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work on all your website pages', 'youzer' ),
            'id'    => 'yz_global_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Profile Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Profile CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_profile_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Profile Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the user profile page.', 'youzer' ),
            'id'    => 'yz_profile_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Account Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Account CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_account_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Account Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the user account settings pages.', 'youzer' ),
            'id'    => 'yz_account_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Groups Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Groups CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_groups_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Groups Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the groups pages.', 'youzer' ),
            'id'    => 'yz_groups_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Members Directory Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Members Directory CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_members_directory_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Members Directory Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the members directory page.', 'youzer' ),
            'id'    => 'yz_members_directory_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Groups Directory Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Groups Directory CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_groups_directory_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Groups Directory Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the groups directory page.', 'youzer' ),
            'id'    => 'yz_groups_directory_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Activity Styling Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Activity CSS', 'youzer' ),
            'desc'  => __( 'Enable styling code below', 'youzer' ),
            'id'    => 'yz_enable_activity_custom_styling',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Activity Custom CSS', 'youzer' ),
            'desc'  => __( 'This code will work only in the Global Activity page.', 'youzer' ),
            'id'    => 'yz_activity_custom_styling',
            'type'  => 'textarea'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}