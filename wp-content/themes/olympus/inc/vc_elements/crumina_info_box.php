<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    vc_map( array(
        "name"   => esc_html__( "Crumina Info Box", 'olympus' ),
        "base"   => "crumina_info_box",
        "category"  => 'Olympus',
        "params" => array(
            array(
                'type'        => 'attach_image',
                'heading'     => esc_html__( 'Image', 'olympus' ),
                'param_name'  => 'image',
                'value'       => '',
                'description' => esc_html__( 'Select image from media library.', 'olympus' ),
            ),
            array(
                'type'        => 'textfield',
                'holder'      => 'strong',
                'heading'     => esc_html__( 'Title', 'olympus' ),
                'param_name'  => 'title',
            ),
            array(
                'type'        => 'textarea',
                'holder'      => 'div',
                'heading'     => esc_html__( 'Text', 'olympus' ),
                'param_name'  => 'text',
            ),
            array(
                'type'        => 'el_id',
                'heading'     => esc_html__( 'Element ID', 'olympus' ),
                'param_name'  => 'id',
                'description' => sprintf( wp_kses( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'olympus' ), array( 'a' => array( 'href' => true, 'title' => true ) )), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            ),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Extra class name', 'olympus' ),
                'param_name'  => 'class',
                'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'olympus' ),
            ),
            array(
                'type'       => 'css_editor',
                'heading'    => esc_html__( 'Css', 'olympus' ),
                'param_name' => 'css',
                'group'      => esc_html__( 'Design options', 'olympus' ),
            ),
        ),
    ) );
} );

class WPBakeryShortCode_Crumina_Info_Box extends WPBakeryShortCode {
    
}
