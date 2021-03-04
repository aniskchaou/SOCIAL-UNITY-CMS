<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

class Olympus_Widget_Flickr extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
        'olympus-flickr-feed', esc_html__( 'Theme widget: Flickr', 'olympus' ), array( 'description' => esc_html__( 'Displays your latest Flickr photos', 'olympus' ) )
        );
    }

    function widget( $args, $instance ) {
        $username      = esc_attr( $instance[ 'username' ] );
        $title         = esc_attr( $instance[ 'title' ] );
        $number        = ( (int) ( esc_attr( $instance[ 'number' ] ) ) > 0 ) ? esc_attr( $instance[ 'number' ] ) : 5;
        $before_widget = $args[ 'before_widget' ];
        $after_widget  = $args[ 'after_widget' ];
        if ( $title ) {
            $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
        }

        if ( !empty( $username ) ) {
            wp_enqueue_script( 'olympus-flickr-widget', get_template_directory_uri() . '/inc/widgets/flickr/static/js/scripts.js', array( 'jquery' ), '1.0' );

            $view_path = get_template_directory() . '/inc/widgets/flickr/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'username', 'title', 'number', 'after_widget' ) );
        }
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'    => esc_html__( 'Flickr', 'olympus' ),
            'username' => '',
            'number'   => 9,
        ) );
        ?>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'olympus' ); ?>:
                <input type="text" name="<?php olympus_render( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" class="widefat"
                       id="<?php $this->get_field_id( 'title' ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'olympus' ); ?> :
                <input type="text" name="<?php olympus_render( $this->get_field_name( 'username' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'username' ] ); ?>" class="widefat"
                       id="<?php $this->get_field_id( 'username' ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of photos', 'olympus' ); ?>:
                <input type="text" name="<?php olympus_render( $this->get_field_name( 'number' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'number' ] ); ?>" class="widefat"
                       id="<?php olympus_render( $this->get_field_id( 'number' ) ); ?>" />
            </label>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance               = $old_instance;
        $instance[ 'title' ]    = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'username' ] = trim( strip_tags( $new_instance[ 'username' ] ) );
        $instance[ 'number' ]   = absint( $new_instance[ 'number' ] );

        return $instance;
    }

}
