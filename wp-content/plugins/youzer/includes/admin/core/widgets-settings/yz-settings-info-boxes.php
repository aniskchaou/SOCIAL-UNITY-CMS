<?php

/**
 * Info Boxes Settings.
 */
function yz_info_boxes_widget_settings() {

    global $Yz_Settings;


    $Yz_Settings->get_field(
        array(
            'title' => __( 'Email Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'E-mail Field', 'youzer' ),
            'desc'  => __( 'Select the email box field', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_email_info_box_field',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Email Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Email loading effect', 'youzer' ),
            'id'    => 'yz_email_load_effect',
            'type'  => 'select'
        )
    );
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzer' ),
            'desc'  => __( 'gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_email_bg_left',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_email_bg_right',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Address Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'Address Field', 'youzer' ),
            'desc'  => __( 'Select the address box field', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_address_info_box_field',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Address Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Address loading effect', 'youzer' ),
            'id'    => 'yz_address_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_address_bg_left',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_address_bg_right',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Website Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'Website Field', 'youzer' ),
            'desc'  => __( 'Select the website box field', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_website_info_box_field',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Website Loading Effect', 'youzer' ),
            'desc'  => __( 'Website loading effect?', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'id'    => 'yz_website_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzer' ),
            'desc'  => __( 'gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_website_bg_left',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_website_bg_right',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Phone Number Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(

            'title' => __( 'Phone Field', 'youzer' ),
            'desc'  => __( 'Select the phone box field', 'youzer' ),
            'opts'  => yz_get_panel_profile_fields(),
            'id'    => 'yz_phone_info_box_field',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Phone Loading Effect', 'youzer' ),
            'desc'  => __( 'Phone number loading effect?', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'id'    => 'yz_phone_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Left', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_phone_bg_left',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Background Right', 'youzer' ),
            'desc'  => __( 'Gradient background color', 'youzer' ),
            'id'    => 'yz_ibox_phone_bg_right',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}