<?php

/**
 * # General Settings.
 */

function yz_general_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Background', 'youzer' ),
            'desc'  => __( 'Plugin pages background color', 'youzer' ),
            'id'    => 'yz_plugin_background',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pages Content Width', 'youzer' ),
            'desc'  => __( 'Content width by default is 1170px', 'youzer' ),
            'id'    => 'yz_plugin_content_width',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Style', 'youzer' ),
            'id'    => 'yz_buttons_border_style',
            'desc'  => __( 'Pages buttons border style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'buttons_border_styles' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tabs Icons Style', 'youzer' ),
            'id'    => 'yz_tabs_list_icons_style',
            'desc'  => __( 'Pages tabs icons style', 'youzer' ),
            'type'  => 'select',
            'opts'  => $Yz_Settings->get_field_options( 'tabs_list_icons_style' )
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Optimization Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Lazy Load', 'youzer' ),
            'desc'  => __( 'Load images only when they appear in the browser viewport for a faster page load time.', 'youzer' ),
            'id'    => 'yz_lazy_load',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Compress Images', 'youzer' ),
            'desc'  => __( 'Compress new uploaded images in profile widgets and activity posts.', 'youzer' ),
            'id'    => 'yz_compress_images',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Compression Quality Percentage', 'youzer' ),
            'desc'  => __( 'The default JPEG compression setting is 90%.', 'youzer' ),
            'id'    => 'yz_images_compression_quality',
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Wordpress Menu Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Disable Avatar Dropdown Icon', 'youzer' ),
            'desc'  => __( 'Disable youzer avatar dropdown icon in the wordpress menu', 'youzer' ),
            'id'    => 'yz_disable_wp_menu_avatar_icon',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Membership System Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Activate Membership System', 'youzer' ),
            'desc'  => __( 'Activate youzer membership system', 'youzer' ),
            'id'    => 'yz_activate_membership_system',
            'type'  => 'checkbox'
        )
    );

    // $Yz_Settings->get_field(
    //     array(
    //         'title' => __( 'Disable Buddypress Registration', 'youzer' ),
    //         'desc'  => __( 'works only if the Youzer Membership System is disabled', 'youzer' ),
    //         'id'    => 'yz_disable_bp_registration',
    //         'type'  => 'checkbox'
    //     )
    // );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'Info', 'youzer' ),
            'id'       => 'yz_msgbox_logy_login',
            'msg'      => __( "If the <strong>Youzer Membership System</strong> is active the <strong>Login Pages Settings</strong> below won't work", 'youzer' )
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Page Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Use Login Option', 'youzer' ),
            'desc'  => __( 'Choose login page option', 'youzer' ),
            'id'    => 'yz_login_page_type',
            'opts'  => array(
                'url'  => __( 'URL', 'youzer' ),
                'page' => __( 'Page', 'youzer' ),
            ),
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Page', 'youzer' ),
            'desc'  => __( 'Choose Login Page', 'youzer' ),
            'id'    => 'yz_login_page',
            'opts'  => yz_get_pages(),
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Login Page URL', 'youzer' ),
            'desc'  => __( 'Type login page URL', 'youzer' ),
            'id'    => 'yz_login_page_url',
            'std'   => wp_login_url(),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Top', 'youzer' ),
            'id'    => 'yz_plugin_margin_top',
            'desc'  => __( 'Specify plugin top margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Margin Bottom', 'youzer' ),
            'id'    => 'yz_plugin_margin_bottom',
            'desc'  => __( 'Specify plugin bottom margin', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Top', 'youzer' ),
            'id'    => 'yz_plugin_padding_top',
            'desc'  => __( 'Specify plugin top padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Bottom', 'youzer' ),
            'id'    => 'yz_plugin_padding_bottom',
            'desc'  => __( 'Specify plugin bottom padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Left', 'youzer' ),
            'id'    => 'yz_plugin_padding_left',
            'desc'  => __( 'Specify plugin left padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Padding Right', 'youzer' ),
            'id'    => 'yz_plugin_padding_right',
            'desc'  => __( 'Specify plugin right padding', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Scroll To Top Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Scroll Button', 'youzer' ),
            'id'    => 'yz_display_scrolltotop',
            'desc'  => __( 'Display scroll to top button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Hover Color', 'youzer' ),
            'desc'  => __( 'Scroll to top hover color', 'youzer' ),
            'id'    => 'yz_scroll_button_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Reset Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'button_title' => __( 'Reset All Settings', 'youzer' ),
            'title' => __( 'Reset All Settings', 'youzer' ),
            'desc'  => __( 'Reset all youzer plugin settings', 'youzer' ),
            'id'    => 'yz-reset-all-settings',
            'type'  => 'button'
        )
    );

    yz_popup_dialog( 'reset_all' );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}