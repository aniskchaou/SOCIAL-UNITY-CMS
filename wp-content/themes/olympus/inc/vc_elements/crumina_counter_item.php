<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    vc_map( array(
        "name"   => esc_html__( "Crumina Counter Item", 'olympus' ),
        "base"   => "crumina_counter_item",
        "category"  => 'Olympus',
        "js_view"          => 'vcCruminaCounterItemView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-counter-item"><strong>{{params.speed ? params.speed : "- empty -"}}</strong><div>{{params.text ? params.text : "- empty -"}}</div></div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_counter_item.js' ),
        "params" => array(
            array(
                'type'        => 'textfield',
                'holder'      => 'strong',
                'heading'     => esc_html__( 'To', 'olympus' ),
                'param_name'  => 'to',
                'description' => esc_html__( '3 is minimum', 'olympus' ),
            ),
            array(
                'type'        => 'textfield',
                'holder'      => 'div',
                'heading'     => esc_html__( 'Speed (seconds)', 'olympus' ),
                'param_name'  => 'speed',
                'value'       => '2',
                'description' => esc_html__( 'Default 2 (seconds)', 'olympus' ),
            ),
            array(
                'type'        => 'textfield',
                'holder'      => 'div',
                'heading'     => esc_html__( 'Units', 'olympus' ),
                'param_name'  => 'units',
                'description' => esc_html__( 'You can wrap some characters in {} for highlighting. (Ex.: K{+})', 'olympus' ),
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

class WPBakeryShortCode_Crumina_Counter_Item extends WPBakeryShortCode {
    
}
