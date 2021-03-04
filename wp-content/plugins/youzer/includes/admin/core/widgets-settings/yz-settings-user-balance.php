<?php

/**
 * User Balance Settings.
 */
function yz_user_balance_widget_settings() {

    global $Yz_Settings;

    if ( ! defined( 'myCRED_VERSION' ) ) {


        $Yz_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'yz_msgbox_user_balance_widget_notice',
                'title'     => __( 'How to activate user balance widget?', 'youzer' ),
                'msg'       => sprintf( __( 'Please install the <a href="%1s"> MyCRED Plugin</a> to activate the user balance widget.'), 'https://wordpress.org/plugins/mycred/' )
            )
        );

	} else {

        $Yz_Settings->get_field(
            array(
                'title' => __( 'General Settings', 'youzer' ),
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Display Title', 'youzer' ),
                'id'    => 'yz_wg_user_balance_display_title',
                'desc'  => __( 'Show widget title', 'youzer' ),
                'type'  => 'checkbox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Widget Title', 'youzer' ),
                'id'    => 'yz_wg_user_balance_title',
                'desc'  => __( 'Add widget title', 'youzer' ),
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Loading Effect', 'youzer' ),
                'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'How you want the widget to be loaded?', 'youzer' ),
                'id'    => 'yz_user_balance_load_effect',
                'type'  => 'select'
            )
        );


        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Box Gradient Settings', 'youzer' ),
                'class' => 'ukai-box-2cols',
                'type'  => 'openBox'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Left Color', 'youzer' ),
                'id'    => 'yz_user_balance_gradient_left_color',
                'desc'  => __( 'Gradient left color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Right Color', 'youzer' ),
                'id'    => 'yz_user_balance_gradient_right_color',
                'desc'  => __( 'Gradient right color', 'youzer' ),
                'type'  => 'color'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

	do_action( 'yz_user_balance_widget_settings' );

}