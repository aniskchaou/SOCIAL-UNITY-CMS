<?php

/**
 * Styling Settings
 */
function logy_register_styling_settings() {

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
            'id'    => 'logy_signup_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Form Subtitle', 'youzer' ),
            'desc'  => __( 'Form subtitle color', 'youzer' ),
            'id'    => 'logy_signup_subtitle_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Cover Title Background', 'youzer' ),
            'desc'  => __( 'Cover title background color', 'youzer' ),
            'id'    => 'logy_signup_cover_title_bg_color',
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
            'id'    => 'logy_signup_label_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Placeholder', 'youzer' ),
            'desc'  => __( 'Form labels color', 'youzer' ),
            'id'    => 'logy_signup_placeholder_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Text', 'youzer' ),
            'desc'  => __( 'Inputs text color', 'youzer' ),
            'id'    => 'logy_signup_inputs_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Background', 'youzer' ),
            'desc'  => __( 'Inputs background color', 'youzer' ),
            'id'    => 'logy_signup_inputs_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Inputs Border', 'youzer' ),
            'desc'  => __( 'Inputs border color', 'youzer' ),
            'id'    => 'logy_signup_inputs_border_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Color', 'youzer' ),
            'desc' => __( 'Submit button background', 'youzer' ),
            'id'    => 'logy_signup_submit_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Register Button Text', 'youzer' ),
            'desc'  => __( 'Register button text color', 'youzer' ),
            'id'    => 'logy_signup_submit_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Color', 'youzer' ),
            'desc'  => __( 'Register button background color', 'youzer' ),
            'id'    => 'logy_signup_loginbutton_bg_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Button Text', 'youzer' ),
            'desc'  => __( 'Register button text color', 'youzer' ),
            'id'    => 'logy_signup_loginbutton_txt_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}