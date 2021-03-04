<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}
if ( defined( 'FW' ) ) {

    class Olympus_Widget_About_Company extends WP_Widget {

        private $options;
        private $slug  = 'olympus_about_company';
        private $title = 'Theme widget: About Company';

        function __construct() {
            $this->prepareOptionFields();

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
            $this->options = array(
                'title'         => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Widget Title', 'olympus' )
                ),
                'link'          => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Company link', 'olympus' )
                ),
                'logo'          => array(
                    'type'        => 'upload',
                    'label'       => esc_html__( 'Logo', 'olympus' ),
                    'images_only' => true
                ),
                'logo_title'    => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Logo title', 'olympus' )
                ),
                'logo_subtitle' => array(
                    'type'  => 'text',
                    'label' => esc_html__( 'Logo subtitle', 'olympus' )
                ),
                'description'   => array(
                    'type'  => 'textarea',
                    'label' => esc_html__( 'Description', 'olympus' )
                ),
                'links'         => array(
                    'type'            => 'addable-box',
                    'label'           => esc_html__( 'Social networks', 'olympus' ),
                    'box-options'     => array(
                        'link' => array(
                            'label' => esc_html__( 'Link to social network page', 'olympus' ),
                            'type'  => 'text',
                        ),
                        'icon' => array(
                            'label'   => esc_html__( 'Icon', 'olympus' ),
                            'type'    => 'select',
                            'choices' => olympus_social_network_icons( true ),
                        ),
                    ),
                    'template'        => '{{- icon }}',
                    'limit'           => 0,
                    'add-button-text' => esc_html__( 'Add icon', 'olympus' ),
                    'desc'            => esc_html__( 'Icons of social networks with links to profile', 'olympus' ),
                    'sortable'        => true,
                ),
            );
        }

        public function widget( $args, $instance ) {
            $title         = isset( $instance[ 'title' ] ) ? apply_filters( 'widget_title', $instance[ 'title' ] ) : '';
            $link          = isset( $instance[ 'link' ] ) ? $instance[ 'link' ] : '';
            $logo          = isset( $instance[ 'logo' ][ 'url' ] ) ? $instance[ 'logo' ][ 'url' ] : '';
            $logo_title    = isset( $instance[ 'logo_title' ] ) ? $instance[ 'logo_title' ] : '';
            $logo_subtitle = isset( $instance[ 'logo_subtitle' ] ) ? $instance[ 'logo_subtitle' ] : '';
            $description   = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : '';
            $links         = isset( $instance[ 'links' ] ) ? $instance[ 'links' ] : '';

            $widget_id     = 'widget_' . $args[ 'widget_id' ];
            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];

            if ( $title ) {
                $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
            }

            $view_path = get_template_directory() . '/inc/widgets/about-company/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'title', 'logo', 'link', 'logo_title', 'logo_subtitle', 'description', 'links', 'widget_id', 'after_widget' ) );
        }

    }

}