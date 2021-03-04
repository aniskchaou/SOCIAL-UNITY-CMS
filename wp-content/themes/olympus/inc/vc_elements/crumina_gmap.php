<?php

if ( !class_exists( 'WPBakeryShortCode' ) ) {
    return false;
}

add_action( 'vc_before_init', function () {
    $map_link = 'https://www.google.com/maps/';
    $api_key_link = 'https://developers.google.com/maps/documentation/javascript/get-api-key';
    $all_styles    = olympus_google_map_custom_styles();
    $style_options = array();
    foreach ( $all_styles as $key => $value ) {
        $style_options[ $value[ 0 ] ] = $key;
    }

    vc_map( array(
        "name"             => esc_html__( "Crumina Google Map", 'olympus' ),
        "base"             => "crumina_gmap",
        "category"         => 'Olympus',
        "js_view"          => 'vcCruminaGmapView',
        'custom_markup'    => '{{title}}<div class="vc-crumina-gmap">{{params.address ? params.address : params.map_embed ? "' . esc_html__( 'Embed map', 'olympus' ) . '" : "- empty -"}}</div>',
        'admin_enqueue_js' => get_theme_file_uri( 'vc_extend/crumina_gmap.js' ),
        "params"           => array(
            array(
                'type'        => 'checkbox',
                'heading'     => esc_html__( 'Show JS Google Map', 'olympus' ),
                'param_name'  => 'google_js',
                'description' => esc_html__( 'Extended options section for show javascript google map.', 'olympus' ),
            ),
            array(
                'type'        => 'textarea_raw_html',
                'heading'     => esc_html__( 'Map Embed Code', 'olympus' ),
                'param_name'  => 'map_embed',
                'description' => sprintf( esc_html__( 'Go to %s and search your Location. Click on menu near search text => Share or embed map => Embed map. Next copy iframe to this field', 'olympus' ), "<a href=\"{$map_link}\" target=\"_blank\">Google Map</a>" ),
                'dependency'  => array(
                    'element'            => 'google_js',
                    'value_not_equal_to' => 'true',
                ),
            ),
            array(
                'type'        => 'textfield',
                'param_name'  => 'api_key',
                'heading'     => esc_html__( 'API KEY for google maps service', 'olympus' ),
                'description' => "<a href=\"{$api_key_link}\">" . esc_html__( 'Instruction to create API key', 'olympus' ) . "</a>",
                'dependency'  => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'type'       => 'textarea',
                'heading'    => esc_html__( 'Type Address', 'olympus' ),
                'param_name' => 'address',
                'dependency' => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'param_name' => 'map_zoom',
                'heading'    => esc_html__( 'Map zoom', 'olympus' ),
                'type'       => 'textfield',
                'value'      => 14,
                'dependency' => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'type'       => 'dropdown',
                'param_name' => 'map_style',
                'heading'    => esc_html__( 'Select map style', 'olympus' ),
                'value'      => $style_options,
                'dependency' => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'type'       => 'dropdown',
                'param_name' => 'map_type',
                'heading'    => esc_html__( 'Map Type', 'olympus' ),
                'value'      => array(
                    esc_html__( 'Roadmap', 'olympus' )   => 'roadmap',
                    esc_html__( 'Terrain', 'olympus' )   => 'terrain',
                    esc_html__( 'Satellite', 'olympus' ) => 'satellite',
                    esc_html__( 'Hybrid', 'olympus' )    => 'hybrid',
                ),
                'dependency' => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'param_name'  => 'disable_scrolling',
                'type'        => 'checkbox',
                'heading'     => esc_html__( 'Disable zoom on scroll', 'olympus' ),
                'description' => esc_html__( 'Prevent the map from zooming when scrolling until clicking on the map', 'olympus' ),
                'dependency'  => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'param_name'  => 'custom_marker',
                'type'        => 'checkbox',
                'heading'     => esc_html__( 'Custom map marker', 'olympus' ),
                'description' => esc_html__( 'Replace default map marker with custom image', 'olympus' ),
                'dependency'  => array(
                    'element' => 'google_js',
                    'value'   => 'true',
                )
            ),
            array(
                'param_name' => 'marker',
                'heading'    => esc_html__( 'Marker Image', 'olympus' ),
                'desc'       => esc_html__( 'Add marker image', 'olympus' ),
                'type'       => 'attach_image',
                'dependency' => array(
                    'element' => 'custom_marker',
                    'value'   => 'true',
                )
            ),
            array(
                'type'       => 'textfield',
                'param_name' => 'map_height',
                'heading'    => esc_html__( 'Map Height (px)', 'olympus' ),
                'value'      => 350,
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

class WPBakeryShortCode_Crumina_Gmap extends WPBakeryShortCode {
    
}
