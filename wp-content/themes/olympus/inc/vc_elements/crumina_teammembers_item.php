<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    vc_map( array(
        "name"             => esc_html__( "Crumina Teammembers Item", 'olympus' ),
        "base"             => "crumina_teammembers_item",
        "category"         => 'Olympus',
        "js_view"          => 'vcCruminaTeammembersItemView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-teammembers-item">{{params.name ? params.name : "- empty -"}}</div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_teammembers_item.js' ),
        "params"           => array(
            array(
                'type'        => 'attach_image',
                'heading'     => esc_html__( 'Photo', 'olympus' ),
                'param_name'  => 'photo',
                'value'       => '',
                'description' => esc_html__( 'Select photo from media library.', 'olympus' ),
            ),
            array(
                'type'       => 'checkbox',
                'heading'    => esc_html__( 'Enable animation', 'olympus' ),
                'param_name' => 'animation',
                'value' => array( esc_html__( 'Yes', 'olympus' ) => 'yes' ),
                'dependency' => array(
                    'element'   => "photo",
                    'not_empty' => true
                )
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Photo width', 'olympus' ),
                'param_name' => 'photo_width',
                'dependency' => array(
                    'element'   => "photo",
                    'not_empty' => true
                )
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Photo height', 'olympus' ),
                'param_name' => 'photo_height',
                'dependency' => array(
                    'element'   => "photo",
                    'not_empty' => true
                )
            ),
            array(
                'type'       => 'vc_link',
                'holder'     => 'strong',
                'heading'    => esc_html__( 'Link', 'olympus' ),
                'param_name' => 'link',
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Name', 'olympus' ),
                'param_name' => 'name',
                'group'       => esc_html__( 'Social icons', 'olympus' ),
            ),
            array(
                'type'       => 'textfield',
                'holder'     => 'div',
                'heading'    => esc_html__( 'Description', 'olympus' ),
                'param_name' => 'description',
                'group'       => esc_html__( 'Social icons', 'olympus' ),
            ),
            array(
                'type'        => 'param_group',
                'heading'     => esc_html__( 'Social icons', 'olympus' ),
                'param_name'  => 'socials',
                'description' => esc_html__( 'Add social icons', 'olympus' ),
                'params'      => array(
                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Network', 'olympus' ),
                        'param_name'  => 'network',
                        'value'       => olympus_social_network_icons(),
                        'description' => esc_html__( 'Select social network.', 'olympus' ),
                    ),
                    array(
                        'type'        => 'vc_link',
                        'heading'     => esc_html__( 'Link', 'olympus' ),
                        'param_name'  => 'link',
                        'description' => esc_html__( 'Enter link to page.', 'olympus' ),
                    )
                ),
                'group'       => esc_html__( 'Social icons', 'olympus' ),
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
            array(
                'type'       => 'css_editor',
                'heading'    => esc_html__( 'Css', 'olympus' ),
                'param_name' => 'css',
                'group'      => esc_html__( 'Design options', 'olympus' ),
            ),
        ),
    ) );
} );

class WPBakeryShortCode_Crumina_Teammembers_Item extends WPBakeryShortCode {
    
}
