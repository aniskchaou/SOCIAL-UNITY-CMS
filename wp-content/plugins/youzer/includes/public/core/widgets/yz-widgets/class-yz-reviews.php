<?php

class YZ_Reviews {

    /**
     * Constructor
     */
    function __construct() {
        // Filter.
        add_filter( 'yz_profile_widget_visibility', array( $this, 'is_widget_visible' ), 10, 2 );
    }

    /**
     * # Display Widget.
     */
    function is_widget_visible( $visibility, $widget_name ) {

        if ( 'reviews' != $widget_name ) {
            return $visibility;
        }


        return true;

    }

    /**
     * # Content.
     */
    function widget() {

        global $Youzer;

        if ( ! isset( $Youzer->reviews ) ) {
            return;
        }

        echo yz_get_user_reviews( array(
            'user_id' => bp_displayed_user_id(),
            'per_page' => yz_option( 'yz_wg_max_reviews_items', 3 ),
            'show_more' => true,
            'return' => true
        ) );

    }

}