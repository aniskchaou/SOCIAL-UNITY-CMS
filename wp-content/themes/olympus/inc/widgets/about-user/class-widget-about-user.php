<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}
if ( defined( 'FW' ) ) {

    class Olympus_Widget_About_User extends WP_Widget {

        private $options;
        private $slug  = 'olympus_about_user';
        private $title = 'Theme widget: About User';

        function __construct() {
            $this->prepareOptionFields();

            parent::__construct( $this->slug, $this->title );
        }

        public function prepareOptionFields() {
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
                'links'   => array(
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

        public function widget( $args, $instance ) {
            $title   = isset( $instance[ 'title' ] ) ? apply_filters( 'widget_title', $instance[ 'title' ] ) : '';
            $content = isset( $instance[ 'content' ] ) ? $instance[ 'content' ] : '';
            $links   = isset( $instance[ 'links' ] ) ? $instance[ 'links' ] : array();

            $widget_id     = 'widget_' . $args[ 'widget_id' ];
            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];

            if ( $title ) {
                $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
            }

            $view_path = get_template_directory() . '/inc/widgets/about-user/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'title', 'content', 'links', 'widget_id', 'after_widget' ) );
        }

    }

}