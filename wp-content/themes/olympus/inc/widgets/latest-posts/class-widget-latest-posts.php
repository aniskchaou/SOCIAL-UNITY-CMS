<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

if ( defined( 'FW' ) ) {

    class Olympus_Widget_Latest_Posts extends WP_Widget {

        private $options, $title;
        private $slug = 'olympus_latest_posts';

        /**
         * Register widget with WordPress.
         */
        function __construct() {
            $this->prepareOptionFields();
            $this->title = esc_html__( 'Theme widget: Latest Posts', 'olympus' );
            parent::__construct( $this->slug, $this->title, array( 'description' => esc_html__( 'Displays your latest posts', 'olympus' ) ) );
        }

        public function prepareOptionFields() {
            $this->options = array(
                'title'       => array(
                    'type'  => 'text',
                    'value' => esc_html__( 'Latest Posts', 'olympus' ),
                    'label' => esc_html__( 'Widget Title', 'olympus' ),
                ),
                'number'      => array(
                    'type'  => 'text',
                    'value' => 3,
                    'label' => esc_html__( 'Number of post', 'olympus' ),
                ),
                'categories'  => array(
                    'type'       => 'multi-select',
                    'label'      => esc_html__( 'Categories', 'olympus' ),
                    'population' => 'taxonomy',
                    'source'     => 'category'
                ),
                'filtering'   => array(
                    'type'         => 'switch',
                    'value'        => 'include',
                    'label'        => esc_html__( 'Category filter type', 'olympus' ),
                    'desc'         => esc_html__( 'Work when categories are selected', 'olympus' ),
                    'left-choice'  => array(
                        'value' => 'include',
                        'label' => esc_html__( 'Include', 'olympus' ),
                    ),
                    'right-choice' => array(
                        'value' => 'exclude',
                        'label' => esc_html__( 'Exclude', 'olympus' ),
                    ),
                ),
                'description' => array(
                    'type'         => 'switch',
                    'value'        => 'include',
                    'label'        => esc_html__( 'Description', 'olympus' ),
                    'left-choice'  => array(
                        'value' => 'show',
                        'label' => esc_html__( 'Show', 'olympus' ),
                    ),
                    'right-choice' => array(
                        'value' => 'hide',
                        'label' => esc_html__( 'Hide', 'olympus' ),
                    ),
                )
            );
        }

        function widget( $args, $instance ) {
            $title         = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
            $number        = empty( $instance[ 'number' ] ) ? 3 : $instance[ 'number' ];
            $categories    = empty( $instance[ 'categories' ] ) ? '' : $instance[ 'categories' ];
            $filtering     = empty( $instance[ 'filtering' ] ) ? 'include' : $instance[ 'filtering' ];
            $description   = empty( $instance[ 'description' ] ) ? 'show' : $instance[ 'description' ];
            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];
            $post_args     = array(
                'numberposts' => $number,
                'orderby'     => 'date',
                'order'       => 'DESC',
            );

            if ( $title ) {
                $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
            }

            // Prepare categories
            if ( is_array( $categories ) && count( $categories ) ) {
                $post_args[ 'category' ] = $filtering === 'exclude' ? '-' . implode( ',-', $categories ) : implode( ',', $categories );
            }

            $posts = get_posts( $post_args );

            $view_path = get_template_directory() . '/inc/widgets/latest-posts/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'title', 'posts', 'after_widget', 'description' ) );
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

        function update( $new_instance, $old_instance ) {
            return fw_get_options_values_from_input(
            $this->options, FW_Request::POST( fw_html_attr_name_to_array_multi_key( $this->get_field_name( $this->slug ) ), array() )
            );
        }

    }

}