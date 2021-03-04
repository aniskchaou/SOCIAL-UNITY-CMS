<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}
if ( defined( 'FW' ) ) {

    class Olympus_Widget_Location extends WP_Widget {

        private $options;
        private $gApiKey = null;
        private $slug    = 'olympus_location';
        private $title   = 'Theme widget: Location';

        function __construct() {
            $this->prepareOptionFields();
            $this->gApiKey = fw_get_db_customizer_option( 'gmap-key' );

            parent::__construct( $this->slug, $this->title );
        }

        function update( $new_instance, $old_instance ) {
            return fw_get_options_values_from_input(
            $this->options, FW_Request::POST( fw_html_attr_name_to_array_multi_key( $this->get_field_name( $this->slug ) ), array() )
            );
        }

        function form( $values ) {

            $prefix = $this->get_field_id( $this->slug );
            $id     = 'fw-widget-options-' . $prefix;

            echo '<div class="fw-force-xs fw-theme-admin-widget-wrap" id="' . esc_attr( $id ) . '">';
            echo fw()->backend->render_options( $this->options, $values, array(
                'id_prefix'   => $prefix . '-',
                'name_prefix' => $this->get_field_name( $this->slug ),
            ) );
            echo '</div>';

            return $values;
        }

        public function prepareOptionFields() {
            $map_link = 'https://www.google.com/maps/';
            $this->options = array(
                'title'   => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Widget Title', 'olympus' ),
                ),
                'content' => array(
                    'type'             => 'wp-editor',
                    'label'            => esc_html__( 'Content', 'olympus' ),
                    'size'             => 'small',
                    'media_buttons'    => false,
                    'drag_drop_upload' => false,
                ),
                'map'     => array(
                    'type'  => 'textarea',
                    'label' => esc_html__( 'Map embed code', 'olympus' ),
                    'desc'  => sprintf( wp_kses( __( 'Go to <a href="%s" target="_blank">Google Map</a> and search your Location. Click on menu near search text => Share or embed map => Embed map. Next copy iframe to this field', 'olympus' ), array( 'a' => array( 'href' => true, 'title' => true ) ) ), $map_link ),
                ),
                'height'  => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Map height', 'olympus' ),
                ),
            );
        }

        public function widget( $args, $instance ) {
            $title   = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
            $content = isset( $instance[ 'content' ] ) ? $instance[ 'content' ] : '';
            $map     = isset( $instance[ 'map' ] ) ? $instance[ 'map' ] : '';
            $height  = (int) isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : 200;
            $key     = $this->gApiKey;

            $widget_id     = 'widget_' . $args[ 'widget_id' ];
            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];

            if ( $title ) {
                $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
            }

            if ( $key ) {
                $language = substr( get_locale(), 0, 2 );
                wp_enqueue_script( 'google-maps-api-v3', "https://maps.googleapis.com/maps/api/js?key={$key}&language={$language}", array( 'jquery' ), false, true );
            }

            $view_path = get_template_directory() . '/inc/widgets/location/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'key', 'title', 'content', 'map', 'height', 'widget_id', 'after_widget' ) );
        }

    }

}