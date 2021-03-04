<?php

class Yz_Custom_Widgets {

    public $widget_name;

    public function __construct( $widget_name ) {
        $this->widget_name = $widget_name;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get Widgets.
        $custom_widgets = yz_option( 'yz_custom_widgets' );

        // Get Widget.
        $widget = $custom_widgets[ $this->widget_name ];

        // init Array.
        $widget_class = array( 'yz-custom-widget-box' );

        // Add Padding class.
        $widget_class[] = 'true' == $widget['display_padding'] ? 'yz-custom-widget-box-padding' : null;

        // Filter Content
        $content = yz_convert_content_tags( urldecode( stripcslashes( $widget['content'] ) ) );

        // Display Widget.
        echo "<div class='" . yz_generate_class( $widget_class ) . "'>";

        $wp_content = apply_filters( 'the_content', $content );

        if ( empty( $wp_content ) ) {
            $wp_content = $content;
        }

        echo $wp_content;

        echo "</div>";

    }

    /**
     * Get Custom Widget data.
     */
    function get_all_data( $widget_name ) {
        $widgets = yz_option( 'yz_custom_widgets' );
        return $widgets[ $widget_name ];
    }

    /**
     * Get Custom Widget data.
     */
    function args() {

        $widgets = yz_option( 'yz_custom_widgets' );

        // Get Custom Widgets
        $custom_widget_data = $widgets[ $widget_name ];

        // Get Custom Widget Args.
        $args = array(
            'id'                => 'custom_widgets',
            'function_options'  => $widget_name,
            'main_data'         => 'yz_custom_widgets',
            'widget_title'      => $custom_widget_data['name'],
            'icon'              => $custom_widget_data['icon'],
            'display_title'     => $custom_widget_data['display_title'],
            'display_padding'   => $custom_widget_data['display_padding'],
            'load_effect'       => yz_option( 'yz_custom_widgets_load_effect', 'fadeIn' )
        );

        return $args;
    }

    /**
     * Get Custom Widget data.
     */
    function get_widget_data( $widget_name, $data_type ) {
        $widgets = yz_option( 'yz_custom_widgets' );
        return $widgets[ $widget_name ][ $data_type ];
    }
}