<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

class Olympus_Widget_Banner extends WP_Widget {

    /**
     * Construct.
     *
     * @internal
     */
    public function __construct() {
        $widget_ops = array( 'description' => esc_html__( 'Create own custom banner', 'olympus' ) );
        parent::__construct( false, esc_html__( 'Theme widget: Banner', 'olympus' ), $widget_ops );
    }

    /**
     * Options.
     *
     * @param array $args
     * @param array $instance
     */
    function widget( $args, $instance ) {
        if ( defined( 'FW' ) ) {

            $title              = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
            $button_link        = isset( $instance[ 'button_link' ] ) ? $instance[ 'button_link' ] : '';
            $button_text        = isset( $instance[ 'button_text' ] ) ? $instance[ 'button_text' ] : '';
            $button_color       = isset( $instance[ 'button_color' ] ) ? $instance[ 'button_color' ] : '';
            $background         = isset( $instance[ 'background' ] ) ? $instance[ 'background' ] : '';
            $background_overlay = isset( $instance[ 'background_overlay' ] ) ? $instance[ 'background_overlay' ] : '';
            $description        = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : '';
            $description_color  = isset( $instance[ 'description_color' ] ) ? $instance[ 'description_color' ] : '';
            $icon               = isset( $instance[ 'icon' ] ) ? $instance[ 'icon' ] : '';

            $before_widget = $args[ 'before_widget' ];
            $after_widget  = $args[ 'after_widget' ];

            // Widget frontend. Can be modified via child theme.
            $view_path = fw_locate_theme_path( '/inc/widgets/banner/views/widget.php' );
            echo fw_render_view( $view_path, compact( 'title', 'button_link', 'button_text', 'button_color', 'background', 'background_overlay', 'description', 'description_color', 'icon', 'before_widget', 'after_widget' ) );
        }
    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance[ 'title' ]              = esc_html( $new_instance[ 'title' ] );
        $instance[ 'button_link' ]        = esc_url( $new_instance[ 'button_link' ] );
        $instance[ 'button_text' ]        = esc_html( $new_instance[ 'button_text' ] );
        $instance[ 'button_color' ]       = esc_html( $new_instance[ 'button_color' ] );
        $instance[ 'background' ]         = esc_url( $new_instance[ 'background' ] );
        $instance[ 'background_overlay' ] = esc_html( $new_instance[ 'background_overlay' ] );
        $instance[ 'description' ]        = esc_textarea( $new_instance[ 'description' ] );
        $instance[ 'description_color' ]  = esc_html( $new_instance[ 'description_color' ] );
        $instance[ 'icon' ]               = esc_url( $new_instance[ 'icon' ] );

        return $instance;
    }

    function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array(
            'title'              => '',
            'button_link'        => '',
            'button_text'        => '',
            'button_color'       => '',
            'background'         => '',
            'background_overlay' => '',
            'description'        => '',
            'description_color'  => '',
            'icon'               => '',
        ) );
        ?>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'olympus' ); ?>:
                <input type="text" name="<?php olympus_render( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" class="widefat"
                       id="<?php olympus_render( $this->get_field_id( 'title' ) ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'button_link' ) ); ?>"><?php esc_html_e( 'Button link', 'olympus' ); ?>:
                <input type="url" name="<?php olympus_render( $this->get_field_name( 'button_link' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'button_link' ] ); ?>" class="widefat"
                       id="<?php olympus_render( $this->get_field_id( 'button_link' ) ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button text', 'olympus' ); ?>:
                <input type="text" name="<?php olympus_render( $this->get_field_name( 'button_text' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'button_text' ] ); ?>" class="widefat"
                       id="<?php olympus_render( $this->get_field_id( 'button_text' ) ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'button_color' ) ); ?>"><?php esc_html_e( 'Button color', 'olympus' ); ?>:</label>
            <input type="text" name="<?php olympus_render( $this->get_field_name( 'button_color' ) ); ?>"
                   value="<?php echo esc_attr( $instance[ 'button_color' ] ); ?>"
                   class="widefat widget-color-picker"
                   id="<?php olympus_render( $this->get_field_id( 'button_color' ) ); ?>" />
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'background' ) ); ?>"><?php esc_html_e( 'Background', 'olympus' ); ?>:
                <input type="url" name="<?php olympus_render( $this->get_field_name( 'background' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'background' ] ); ?>" class="widefat field-image-add"
                       id="<?php olympus_render( $this->get_field_id( 'background' ) ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'background_overlay' ) ); ?>"><?php esc_html_e( 'Background overlay', 'olympus' ); ?>:</label>
            <input type="text" name="<?php olympus_render( $this->get_field_name( 'background_overlay' ) ); ?>"
                   value="<?php echo esc_attr( $instance[ 'background_overlay' ] ); ?>"
                   class="widefat widget-color-picker" data-alpha="true"
                   id="<?php olympus_render( $this->get_field_id( 'background_overlay' ) ); ?>" />
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'olympus' ); ?>:
                <textarea name="<?php olympus_render( $this->get_field_name( 'description' ) ); ?>"
                          id="<?php olympus_render( $this->get_field_id( 'description' ) ); ?>"
                          rows="3" class="widefat"><?php echo esc_textarea( $instance[ 'description' ] ); ?></textarea>
            </label>
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'description_color' ) ); ?>"><?php esc_html_e( 'Description color', 'olympus' ); ?>:</label>
            <input type="text" name="<?php olympus_render( $this->get_field_name( 'description_color' ) ); ?>"
                   value="<?php echo esc_attr( $instance[ 'description_color' ] ); ?>"
                   class="widefat widget-color-picker"
                   id="<?php olympus_render( $this->get_field_id( 'description_color' ) ); ?>" />
        </p>
        <p>
            <label for="<?php olympus_render( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icon', 'olympus' ); ?>:
                <input type="url" name="<?php olympus_render( $this->get_field_name( 'icon' ) ); ?>"
                       value="<?php echo esc_attr( $instance[ 'icon' ] ); ?>" class="widefat field-image-add"
                       id="<?php olympus_render( $this->get_field_id( 'icon' ) ); ?>" />
            </label>
        </p>
        <?php
    }

}
