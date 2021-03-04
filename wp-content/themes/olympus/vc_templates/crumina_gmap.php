<?php
$wrapper_attributes = array();

extract( shortcode_atts( array(
    'map_zoom'          => 14,
    'map_height'        => 350,
    'google_js'         => '',
    'map_embed'         => '',
    'api_key'           => '',
    'address'           => '',
    'map_style'         => '',
    'map_type'          => '',
    'disable_scrolling' => '',
    'custom_marker'     => '',
    'marker'            => '',
    'id'                => '',
    'css'               => '',
    'class'             => ''
), $atts ) );

$olympus        = Olympus_Options::get_instance();
$global_api_key = $olympus->get_option( 'gmap-key', '', $olympus::SOURCE_CUSTOMIZER );

if ( $global_api_key && !$api_key ) {
    $api_key = $global_api_key;
}

$map_width  = '100%';
$map_height = (int) $map_height ? (int) $map_height : 350;
$map_id     = "map-" . rand( 1000, 9999 );
$language   = substr( get_locale(), 0, 2 );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'wpb_content_element' );

$wrapper_attributes[] = 'class=" ' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}

wp_enqueue_script( 'google-maps-api-v3', "https://maps.googleapis.com/maps/api/js?key={$api_key}&language={$language}", array( 'jquery' ), false, true );
?>

<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <?php if ( $google_js ) { ?>
        <?php
        $all_styles                                = olympus_google_map_custom_styles();
        $map_data_attr                             = array();
        $map_data_attr[ 'data-map-style' ]         = isset( $all_styles[ $map_style ][ 1 ] ) ? $all_styles[ $map_style ][ 1 ] : '';
        $map_data_attr[ 'data-locations' ]         = str_replace( '\\', '', $address );
        $map_data_attr[ 'data-zoom' ]              = $map_zoom;
        $map_data_attr[ 'data-map-type' ]          = $map_type;
        $map_data_attr[ 'data-custom-marker' ]     = $custom_marker;
        $map_data_attr[ 'data-disable-scrolling' ] = $disable_scrolling;
        $map_data_attr[ 'data-address' ]           = $address;
        $map_data_attr[ 'data-custom-marker' ]     = get_template_directory_uri() . "/images/marker-google.png";

        if ( $custom_marker && (int) $marker ) {
            $img_link = wp_get_attachment_image_src( $marker, 'thumb' );

            if ( isset( $img_link[ 0 ] ) ) {
                $map_data_attr[ 'data-custom-marker' ] = $img_link[ 0 ];
            }
        }
        ?>
        <div class="crumina-google-map" id="<?php echo esc_attr( $map_id ); ?>" style="width: <?php echo esc_attr( $map_width ); ?>; height: <?php echo esc_attr( $map_height . 'px' ); ?>;" <?php echo olympus_attr_to_html( $map_data_attr ); ?>></div>
    <?php } else if ( $map_embed ) { ?>
        <?php wp_enqueue_script( 'wpb_php_js', vc_asset_url( 'lib/php.default/php.default.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true ); ?>
        <div class="crumina-google-map-embed" data-map="<?php echo esc_attr( $map_embed ); ?>" data-map-width="<?php echo esc_attr( $map_width ); ?>" data-map-height="<?php echo esc_attr( $map_height ); ?>"><?php esc_html_e( 'Loading...', 'olympus' ); ?></div>
    <?php } ?>
</div>