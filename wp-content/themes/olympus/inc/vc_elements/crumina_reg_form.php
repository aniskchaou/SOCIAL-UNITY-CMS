<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
       vc_map( array(
        "name"             => esc_html__( "Crumina Registration Form", 'olympus' ),
        "base"             => "crumina_reg_form",
        "category"         => 'Olympus',
        "params"           => array(
            array(
                'type'       => 'vc_link',
                'heading'    => esc_html__( 'Redirect To', 'olympus' ),
                'param_name' => 'redirect_to',
                'description' => esc_html__( 'After logged in, the user will be redirected to this page.', 'olympus' ),
            ),
            array(
                'type'        => 'el_id',
                'heading'     => esc_html__( 'Element ID', 'olympus' ),
                'param_name'  => 'id',
                'description' => sprintf( wp_kses( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'olympus' ), array( 'a' => array( 'href' => true, 'title' => true ) ) ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            ),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Extra class name', 'olympus' ),
                'param_name'  => 'class',
                'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'olympus' ),
            ),
        ),
    ) );
} );

class WPBakeryShortCode_Crumina_Reg_Form extends WPBakeryShortCode {
    
}
