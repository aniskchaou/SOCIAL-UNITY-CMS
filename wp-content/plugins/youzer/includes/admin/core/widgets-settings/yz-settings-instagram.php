<?php

/**
 * # Instagram Settings.
 */
function yz_instagram_widget_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzer' ),
            'id'    => 'yz_wg_instagram_display_title',
            'desc'  => __( 'Show widget title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_instagram_title',
            'desc'  => __( 'Add widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
            'id'    => 'yz_instagram_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Allowed Photos Number', 'youzer' ),
            'id'    => 'yz_wg_max_instagram_items',
            'desc'  => __( 'Maximum allowed photos', 'youzer' ),
            'std'   => 6,
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon Background', 'youzer' ),
            'id'    => 'yz_wg_instagram_img_icon_bg_color',
            'desc'  => __( 'Icon background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Color', 'youzer' ),
            'id'    => 'yz_wg_instagram_img_icon_color',
            'desc'  => __( 'Icon text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Background', 'youzer' ),
            'id'    => 'yz_wg_instagram_img_icon_bg_color_hover',
            'desc'  => __( 'Icon hover background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Icon Hover Color', 'youzer' ),
            'id'    => 'yz_wg_instagram_img_icon_color_hover',
            'desc'  => __( 'Icon text hover color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'yz_msgbox_instagram_wg_app_setup_steps',
            'title'     => __( 'How to get Instagram keys?', 'youzer' ),
            'msg'       => implode( '<br>', yz_get_instagram_app_register_steps() )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Instagram App Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Application ID', 'youzer' ),
            'desc'  => __( 'Enter application ID', 'youzer' ),
            'id'    => 'yz_wg_instagram_app_id',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Application Secret', 'youzer' ),
            'desc'  => __( 'Enter application secret key', 'youzer' ),
            'id'    => 'yz_wg_instagram_app_secret',
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * How to register an instagram application
 */
function yz_get_instagram_app_register_steps() {

    // Init Vars.
    $apps_url = 'https://kainelabs.ticksy.com/article/15737/';
    $auth_url = home_url( '/yz-auth/feed/Instagram' );

    // Get Note
    $steps[] = __( '<strong><a>Note:</a> You should submit your application for review and it should be approved in order to make your website users able to use the instagram widget.</strong>', 'youzer' ) . '<br>';

    // Get Steps.
    $steps[] = sprintf( __( '1. Check this topic on <a href="%1s">How to setup Instagram widget</a> for a detailed steps.', 'youzer' ), $apps_url );
    $steps[] = __( '2. Put the below url as OAuth redirect_uri Authorized Redirect URLs:', 'youzer' );
    $steps[] = sprintf( __( '3. Redirect URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
    return $steps;
}