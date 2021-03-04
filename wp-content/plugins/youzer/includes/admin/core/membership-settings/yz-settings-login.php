<?php

/**
 * # Admin Settings.
 */
function logy_login_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Ajax Login', 'youzer' ),
            'desc'  => __( 'Enable ajax in login forms?', 'youzer' ),
            'id'    => 'yz_enable_ajax_login',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Login Popup', 'youzer' ),
            'desc'  => __( 'Enable popup login form?', 'youzer' ),
            'id'    => 'yz_enable_login_popup',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Title', 'youzer' ),
            'desc'  => __( 'Type login button title', 'youzer' ),
            'id'    => 'logy_login_signin_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Title', 'youzer' ),
            'desc'  => __( 'Type register button title', 'youzer' ),
            'id'    => 'logy_login_register_btn_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Lost Password Title', 'youzer' ),
            'desc'  => __( 'Type lost password title', 'youzer' ),
            'id'    => 'logy_login_lostpswd_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Custom Registration Link', 'youzer' ),
            'desc'  => __( 'Paste a custom registration page link', 'youzer' ),
            'id'    => 'logy_login_custom_register_link',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Redirect Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    // Get Website Pages.
    $website_pages = youzer_get_panel_pages();

    // Get User Default Redirect Options
    $default_user_redirect_options = $Yz_Settings->get_field_options( 'user_login_redirect_pages' );

    // Get All Redirect Options.
    $user_login_redirect_pages = $default_user_redirect_options + $website_pages;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'After login redirect user to?', 'youzer' ),
            'desc'  => __( 'After user login redirect to which page?', 'youzer' ),
            'id'    => 'logy_user_after_login_redirect',
           'opts'  => $user_login_redirect_pages,
            'type'  => 'select'
        )
    );

    // Get Admin Default Redirect Options
    $default_admin_redirect_options = $Yz_Settings->get_field_options( 'admin_login_redirect_pages' );

    // Get All Redirect Options.
    $admin_login_redirect_pages = $default_admin_redirect_options + $website_pages;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'After login redirect admin to?', 'youzer' ),
            'desc'  => __( 'After admin login redirect to which page?', 'youzer' ),
            'id'    => 'logy_admin_after_login_redirect',
            'opts'  => $admin_login_redirect_pages,
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'After logout redirect user to?', 'youzer' ),
            'desc'  => __( 'After user logout redirect to which page?', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'logout_redirect_pages' ),
            'id'    => 'logy_after_logout_redirect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Header Settings
    logy_login_header_settings();

    // Get Fields Settings
    logy_login_fields_settings();

    // Get Buttons Settings
    logy_login_buttons_settings();

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Widget Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Box Margin Top', 'youzer' ),
            'id'    => 'logy_login_wg_margin_top',
            'desc'  => __( 'Specify box top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Box Margin Bottom', 'youzer' ),
            'id'    => 'logy_login_wg_margin_bottom',
            'desc'  => __( 'Specify box bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Header Settings
 */
function logy_login_header_settings() {

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
            'id'    => 'logy_login_form_enable_header',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzer' ),
            'desc'  => __( 'Login form title', 'youzer' ),
            'id'    => 'logy_login_form_title',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzer' ),
            'desc'  => __( 'Login form subtitle', 'youzer' ),
            'id'    => 'logy_login_form_subtitle',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Cover', 'youzer' ),
            'desc'  => __( 'Upload login form cover', 'youzer' ),
            'id'    => 'logy_login_cover',
            'type'  => 'upload'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Fields Settings
 */
function logy_login_fields_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Fields Layouts', 'youzer' ),
            'class' => 'ukai-center-elements',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'logy_login_form_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-field-v1', 'logy-field-v2', 'logy-field-v3', 'logy-field-v4', 'logy-field-v5',
                'logy-field-v6', 'logy-field-v7', 'logy-field-v8', 'logy-field-v9', 'logy-field-v10',
                'logy-field-v11', 'logy-field-v12'
            )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fields Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fields Icons Position', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select fields icons position <br>( works only with layouts that support icons! )', 'youzer' ),
            'id'    => 'logy_login_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fields Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select fields border style', 'youzer' ),
            'id'    => 'logy_login_fields_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Buttons Settings
 */
function logy_login_buttons_settings() {

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
            'id'    => 'logy_login_actions_layout',
            'type'  => 'imgSelect',
            'opts'  =>  array(
                'logy-actions-v1', 'logy-actions-v2', 'logy-actions-v3', 'logy-actions-v4',
                'logy-actions-v5', 'logy-actions-v6', 'logy-actions-v7', 'logy-actions-v8',
                'logy-actions-v9', 'logy-actions-v10'
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
            'desc'  => __( 'Select buttons icons position <br>( works only with buttons that support icons! )', 'youzer' ),
            'id'    => 'logy_login_btn_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzer' ),
            'id'    => 'logy_login_btn_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Get Wordpress Pages
 */
function youzer_get_panel_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )','youzer' ), $page->post_title, $page->ID );
    }

    return $pages;
}