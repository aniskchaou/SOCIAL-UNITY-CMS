<?php

/**
 * # Admin Settings.
 */
function logy_register_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Registration', 'youzer' ),
            'desc'  => __( 'Enable users registration', 'youzer' ),
            'id'    => 'users_can_register',
            'type'  => 'checkbox'
        )
    );

    // Get Site Rules
    global $wp_roles;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'New User Default Role', 'youzer' ),
            'desc'  => __( 'Select new user default role', 'youzer' ),
            'opts'  => $wp_roles->get_names(),
            'id'    => 'default_role',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Title', 'youzer' ),
            'desc'  => __( 'Type register button title', 'youzer' ),
            'id'    => 'logy_signup_register_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Title', 'youzer' ),
            'desc'  => __( 'Type login button title', 'youzer' ),
            'id'    => 'logy_signup_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Terms & Privacy Policy Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Note', 'youzer' ),
            'desc'  => __( 'Display terms and privacy policy note', 'youzer' ),
            'id'    => 'logy_show_terms_privacy_note',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Terms URL', 'youzer' ),
            'desc'  => __( 'Enter terms and conditions link', 'youzer' ),
            'id'    => 'logy_terms_url',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Privacy Policy URL', 'youzer' ),
            'desc'  => __( 'Enter privacy policy link', 'youzer' ),
            'id'    => 'logy_privacy_url',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_register_header_settings();

    // Get Buttons Settings
    logy_register_buttons_settings();

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Widget Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Box Margin Top', 'youzer' ),
            'id'    => 'logy_register_wg_margin_top',
            'desc'  => __( 'Specify box top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Box Margin Bottom', 'youzer' ),
            'id'    => 'logy_register_wg_margin_bottom',
            'desc'  => __( 'Specify box bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_register_header_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Form Cover', 'youzer' ),
            'desc'  => __( 'Enable form header cover?', 'youzer' ),
            'id'    => 'logy_signup_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzer' ),
            'desc'  => __( 'Registration form title', 'youzer' ),
            'id'    => 'logy_signup_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'form Subtitle', 'youzer' ),
            'desc'  => __( 'Sign up form Subtitle', 'youzer' ),
            'id'    => 'logy_signup_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Upload Cover', 'youzer' ),
            'desc'  => __( 'Upload registration form cover', 'youzer' ),
            'id'    => 'logy_signup_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Buttons Settings
 */
function logy_register_buttons_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Layout', 'youzer' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'logy_signup_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-regactions-v1', 'logy-regactions-v2', 'logy-regactions-v3', 'logy-regactions-v4',
                'logy-regactions-v5', 'logy-regactions-v6'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Icons Position', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select buttons icons position <br>( works only with buttons that support icons ! )', 'youzer' ),
            'id'    => 'logy_signup_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzer' ),
            'id'    => 'logy_signup_btn_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}