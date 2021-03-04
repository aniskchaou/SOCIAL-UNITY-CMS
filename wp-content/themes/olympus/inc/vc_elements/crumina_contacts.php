<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    vc_map( array(
        "name"             => esc_html__( "Crumina Contacts", 'olympus' ),
        "base"             => "crumina_contacts",
        "category"         => 'Olympus',
        "js_view"          => 'vcCruminaContactsView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-contacts"><div>{{params.title ? params.title : "- empty -"}}</div></div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_contacts.js' ),
        "params"           => array(
            array(
                'type'       => 'textfield',
                'holder'     => 'strong',
                'heading'    => esc_html__( 'Title', 'olympus' ),
                'param_name' => 'title',
            ),
            array(
                'type'        => 'param_group',
                'heading'     => esc_html__( 'Social icons', 'olympus' ),
                'param_name'  => 'details',
                'description' => esc_html__( 'Add contacts elements', 'olympus' ),
                'params'      => array(
                    array(
                        'type'       => 'textfield',
                        'holder'     => 'strong',
                        'heading'    => esc_html__( 'Name', 'olympus' ),
                        'param_name' => 'name',
                    ),
                    array(
                        'type'       => 'vc_link',
                        'heading'    => esc_html__( 'Link', 'olympus' ),
                        'param_name' => 'link',
                    )
                ),
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

class WPBakeryShortCode_Crumina_Contacts extends WPBakeryShortCode {
    
}
