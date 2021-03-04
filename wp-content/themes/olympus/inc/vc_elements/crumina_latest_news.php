<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {

    $cats_prepared = array(
        '---' => ''
    );

    $categories = get_categories( array(
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => 1,
    ) );

    foreach ( $categories as $cat ) {
        $cats_prepared[ $cat->name ] = $cat->term_id;
    }

    vc_map( array(
        "name"             => esc_html__( "Crumina Latest News", 'olympus' ),
        "base"             => "crumina_latest_news",
        "category"         => 'Olympus',
        "js_view"          => 'vcCruminaLatestNewsView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-latest-news">Count: {{params.count ? params.count : "- empty -"}}</div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_latest_news.js' ),
        "params"           => array(
            array(
                'type'       => 'dropdown',
                'holder'     => 'div',
                'heading'    => esc_html__( 'Category', 'olympus' ),
                'param_name' => 'category',
                'value'      => $cats_prepared
            ),
            array(
                'type'       => 'textfield',
                'holder'     => 'strong',
                'heading'    => esc_html__( 'Count', 'olympus' ),
                'param_name' => 'count',
                'value'      => 3
            ),
            //======================
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

class WPBakeryShortCode_Crumina_Latest_News extends WPBakeryShortCode {
    
}
