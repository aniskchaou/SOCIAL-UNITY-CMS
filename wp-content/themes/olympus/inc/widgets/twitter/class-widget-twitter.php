<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

if ( defined( 'FW' ) && class_exists( 'TwitterOAuth' ) && function_exists( 'curl_exec' ) ) {

    class Olympus_Widget_Twitter extends WP_Widget {

        /**
         * @internal
         */
        function __construct() {
            $widget_ops = array( 'description' => esc_html__( 'Twitter Feed Slider', 'olympus' ) );
            parent::__construct( 'olympus-twitter-feed', esc_html__( 'Theme widget: Twitter', 'olympus' ), $widget_ops );
        }

        /**
         * @param array $args
         * @param array $instance
         */
        function widget( $args, $instance ) {
            $username      = esc_attr( $instance[ 'username' ] );
            $title         = esc_attr( $instance[ 'title' ] );
            $number        = ( (int) ( esc_attr( $instance[ 'number' ] ) ) > 0 ) ? esc_attr( $instance[ 'number' ] ) : 5;
            $cache         = ( (int) ( esc_attr( $instance[ 'cache' ] ) ) >= 600 ) ? esc_attr( $instance[ 'cache' ] ) : HOUR_IN_SECONDS;
            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];

            $consumer_key         = fw_get_db_customizer_option( 'twitter-consumer-key' );
            $consumer_secret      = fw_get_db_customizer_option( 'twitter-consumer-secret' );
            $access_tocken        = fw_get_db_customizer_option( 'twitter-access-token' );
            $access_tocken_secret = fw_get_db_customizer_option( 'twitter-access-token-secret' );

            if ( Olympus_Core::get_extension( 'social' ) ) {
                if ( empty( $consumer_key ) ) {
                    $consumer_key = fw_get_db_ext_settings_option( 'social', 'twitter-consumer-key' );
                }
                if ( empty( $consumer_secret ) ) {
                    $consumer_secret = fw_get_db_ext_settings_option( 'social', 'twitter-consumer-secret' );
                }
                if ( empty( $access_tocken ) ) {
                    $access_tocken = fw_get_db_ext_settings_option( 'social', 'twitter-access-token' );
                }
                if ( empty( $access_tocken_secret ) ) {
                    $access_tocken_secret = fw_get_db_ext_settings_option( 'social', 'twitter-access-token-secret' );
                }

                if ( $title ) {
                    $title = $args[ 'before_title' ] . $title . $args[ 'after_title' ];
                }

                $tweets = get_site_transient( 'scratch_tweets_' . $username . '_' . $number );

                if ( empty( $tweets ) || isset( $tweets->errors ) ) {
                    /* @var $connection TwitterOAuth */

                    $connection = new TwitterOAuth( $consumer_key, $consumer_secret, $access_tocken, $access_tocken_secret );
                    $tweets     = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $username . '&count=' . $number );

                    set_site_transient( 'scratch_tweets_' . $username . '_' . $number, $tweets, $cache );
                }
            }
            $view_path = dirname( __FILE__ ) . '/views/widget.php';
            echo fw_render_view( $view_path, compact( 'before_widget', 'title', 'tweets', 'number', 'cache', 'after_widget' ) );
        }

        function update( $new_instance, $old_instance ) {
            $instance               = $old_instance;
            $instance[ 'title' ]    = strip_tags( $new_instance[ 'title' ] );
            $instance[ 'username' ] = trim( strip_tags( $new_instance[ 'username' ] ) );
            $instance[ 'number' ]   = absint( $new_instance[ 'number' ] );
            $instance[ 'cache' ]    = absint( $new_instance[ 'cache' ] );

            return $instance;
        }

        function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, array(
                'title'    => esc_html__( 'Twitter', 'olympus' ),
                'username' => '',
                'number'   => 9,
                'cache'    => HOUR_IN_SECONDS
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
                <label for="<?php olympus_render( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'User', 'olympus' ); ?> :
                    <input type="text" name="<?php olympus_render( $this->get_field_name( 'username' ) ); ?>"
                           value="<?php echo esc_attr( $instance[ 'username' ] ); ?>" class="widefat"
                           id="<?php $this->get_field_id( 'username' ); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php olympus_render( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of tweets', 'olympus' ); ?>:
                    <input type="text" name="<?php olympus_render( $this->get_field_name( 'number' ) ); ?>"
                           value="<?php echo esc_attr( $instance[ 'number' ] ); ?>" class="widefat"
                           id="<?php olympus_render( $this->get_field_id( 'number' ) ); ?>" />
                </label>
            </p>
            <p>
                <?php olympus_get_cache_time_select_html( $this->get_field_id( 'cache' ), $this->get_field_name( 'cache' ), $instance[ 'cache' ] ); ?>
            </p>
            <p><?php echo sprintf( esc_attr__( 'Set Twitter credentials %s.', 'olympus' ), '<a href="' . admin_url( 'themes.php?page=fw-settings' ) . '">' . esc_html__( 'here', 'olympus' ) . '</a>' ); ?></p>
            <?php
        }

    }

}

