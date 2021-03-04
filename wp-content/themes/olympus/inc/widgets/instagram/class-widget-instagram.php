<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

class Olympus_Widget_Instagram extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
        'olympus-instagram-feed', esc_html__( 'Theme widget: Instagram', 'olympus' ), array( 'description' => esc_html__( 'Displays your latest Instagram photos', 'olympus' ) )
        );
    }

    function widget( $args, $instance ) {
        $title         = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
        $username      = empty( $instance[ 'username' ] ) ? '' : $instance[ 'username' ];
        $number        = empty( $instance[ 'number' ] ) ? 9 : $instance[ 'number' ];
        $cache         = ( (int) ( esc_attr( $instance[ 'cache' ] ) ) >= 600 ) ? esc_attr( $instance[ 'cache' ] ) : HOUR_IN_SECONDS;
        $before_widget = $args[ 'before_widget' ];
        $after_widget  = $args[ 'after_widget' ];

        if ( $title ) {
            $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
        }

        if ( !empty( $username ) ) {
            $insta_data = olympus_scrape_instagram( $username, $number );

            $view_path = get_template_directory() . '/inc/widgets/instagram/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'title', 'insta_data', 'after_widget' ) );
        }
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'    => esc_html__( 'Instagram', 'olympus' ),
            'username' => '',
            'cache'    => HOUR_IN_SECONDS,
            'number'   => 9 ) );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'olympus' ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'olympus' ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'username' ] ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of photos', 'olympus' ); ?>:
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'number' ] ); ?>" />
            </label>
        </p>
        <p>
            <?php olympus_get_cache_time_select_html( $this->get_field_id( 'cache' ), $this->get_field_name( 'cache' ), $instance[ 'cache' ] ); ?>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance               = $old_instance;
        $instance[ 'title' ]    = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'username' ] = trim( strip_tags( $new_instance[ 'username' ] ) );
        $instance[ 'number' ]   = absint( $new_instance[ 'number' ] );
        $instance[ 'cache' ]    = absint( $new_instance[ 'cache' ] );

        return $instance;
    }

    function images_only( $media_item ) {
        if ( 'image' === $media_item[ 'type' ] ) {
            return true;
        }

        return false;
    }

}
