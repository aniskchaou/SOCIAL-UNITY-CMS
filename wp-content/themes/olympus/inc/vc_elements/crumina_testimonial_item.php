<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    vc_map( array(
        "name"             => esc_html__( "Crumina Testimonial Item", 'olympus' ),
        "base"             => "crumina_testimonial_item",
        "category"         => 'Olympus',
        "js_view"          => 'vcCruminaTestimonialItemView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-testimonial-item">{{params.name ? params.name : "- empty -"}}</div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_testimonial_item.js' ),
        "params"           => array(
            array(
                'type'        => 'attach_image',
                'heading'     => esc_html__( 'Avatar', 'olympus' ),
                'param_name'  => 'avatar',
                'value'       => '',
                'description' => esc_html__( 'Select photo from media library.', 'olympus' ),
                'group'       => esc_html__( 'User', 'olympus' ),
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Avatar width', 'olympus' ),
                'param_name' => 'avatar_width',
                'value'      => 98,
                'group'      => esc_html__( 'User', 'olympus' ),
                "dependency" => array(
                    'element'   => "avatar",
                    'not_empty' => true
                )
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Avatar height', 'olympus' ),
                'param_name' => 'avatar_height',
                'value'      => 98,
                'group'      => esc_html__( 'User', 'olympus' ),
                "dependency" => array(
                    'element'   => "avatar",
                    'not_empty' => true
                )
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Name', 'olympus' ),
                'param_name' => 'name',
                'group'      => esc_html__( 'User', 'olympus' ),
            ),
            array(
                'type'       => 'vc_link',
                'holder'     => 'strong',
                'heading'    => esc_html__( 'Link', 'olympus' ),
                'param_name' => 'link',
                'group'      => esc_html__( 'User', 'olympus' ),
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'User description', 'olympus' ),
                'param_name' => 'user_description',
                'group'      => esc_html__( 'User', 'olympus' ),
            ),
            array(
                'type'       => 'textfield',
                'heading'    => esc_html__( 'Title', 'olympus' ),
                'param_name' => 'title',
                'group'      => esc_html__( 'Review', 'olympus' ),
            ),
            array(
                'type'       => 'dropdown',
                'heading'    => esc_html__( 'Rating', 'olympus' ),
                'param_name' => 'rating',
                'value'      => array(
                    esc_html__( '- select -', 'olympus' ) => '',
                    1                             => 1,
                    2                             => 2,
                    3                             => 3,
                    4                             => 4,
                    5                             => 5,
                ),
                'group'      => esc_html__( 'Review', 'olympus' ),
            ),
            array(
                'type'       => 'textarea',
                'heading'    => esc_html__( 'Description', 'olympus' ),
                'param_name' => 'description',
                'group'      => esc_html__( 'Review', 'olympus' ),
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
                'type'             => 'colorpicker',
                'heading'          => esc_html__( 'Header overlay', 'olympus' ),
                'param_name'       => 'header_overlay',
                'description'      => esc_html__( 'Select custom background color for your element.', 'olympus' ),
                'std'              => '#ff5e3a',
                'group'      => esc_html__( 'Design options', 'olympus' ),
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

class WPBakeryShortCode_Crumina_Testimonial_Item extends WPBakeryShortCode {
    
}
