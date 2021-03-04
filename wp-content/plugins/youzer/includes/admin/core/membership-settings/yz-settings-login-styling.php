<?php

/**
 * Styling Settings
 */
function logy_login_styling_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Header Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Title', 'youzer' ),
            'desc'  => __( 'Form title color', 'youzer' ),
            'id'    => 'logy_login_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzer' ),
            'desc'  => __( 'Form subtitle color', 'youzer' ),
            'id'    => 'logy_login_subtitle_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Title Background', 'youzer' ),
            'desc'  => __( 'Cover title background color', 'youzer' ),
            'id'    => 'logy_login_cover_title_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fields Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Labels', 'youzer' ),
            'desc'  => __( 'Form labels color', 'youzer' ),
            'id'    => 'logy_login_label_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Placeholder', 'youzer' ),
            'desc'  => __( 'Form labels color', 'youzer' ),
            'id'    => 'logy_login_placeholder_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Text', 'youzer' ),
            'desc'  => __( 'Inputs text color', 'youzer' ),
            'id'    => 'logy_login_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Background', 'youzer' ),
            'desc'  => __( 'Inputs background color', 'youzer' ),
            'id'    => 'logy_login_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Border', 'youzer' ),
            'desc'  => __( 'Inputs border color', 'youzer' ),
            'id'    => 'logy_login_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icons', 'youzer' ),
            'desc'  => __( 'Fields icons color', 'youzer' ),
            'id'    => 'logy_login_fields_icons_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icons Background', 'youzer' ),
            'desc'  => __( 'Fields icons background color', 'youzer' ),
            'id'    => 'logy_login_fields_icons_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Remember Me Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( '"Remember Me" Color', 'youzer' ),
            'desc'  => __( 'Form "remember me" color', 'youzer' ),
            'id'    => 'logy_login_remember_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Checkbox Border', 'youzer' ),
            'desc'  => __( 'Form checkbox border color', 'youzer' ),
            'id'    => 'logy_login_checkbox_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Checkbox Icon', 'youzer' ),
            'desc'  => __( 'Form checkbox icon color', 'youzer' ),
            'id'    => 'logy_login_checkbox_icon_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( '"Lost Password" Color', 'youzer' ),
            'desc'  => __( 'Form "lost password" color', 'youzer' ),
            'id'    => 'logy_login_lostpswd_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Color', 'youzer' ),
            'desc'  => __( 'Login button background color', 'youzer' ),
            'id'    => 'logy_login_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Text', 'youzer' ),
            'desc'  => __( 'Login button text color', 'youzer' ),
            'id'    => 'logy_login_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Color', 'youzer' ),
            'desc'  => __( 'Register button background color', 'youzer' ),
            'id'    => 'logy_login_regbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Text', 'youzer' ),
            'desc'  => __( 'Register button text color', 'youzer' ),
            'id'    => 'logy_login_regbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}