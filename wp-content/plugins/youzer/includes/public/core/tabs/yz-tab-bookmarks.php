<?php

class YZ_Bookmarks_Tab {
    
    function __construct() {

        // add_action( 'bp_setup_nav', array( $this, 'add_bookmark_subtabs' ) );
        $this->add_bookmark_subtabs();
        add_filter( 'yz_is_current_tab_has_children', '__return_false' );

    }

    /**
     * # Tab.
     */
    function tab() {
        
        // Include Wall Files.
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-general-functions.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-wall.php';
        
        do_action( 'bp_bookmarks_screen' );
        
        bp_get_template_part( 'members/single/bookmarks' );

    }

    /**
     * Check if User Have Bookmarks
     */
    // function yz_set_bookmarks_as_has_nochildren( $has_children ) {

    //     if ( ! bp_is_current_component( 'bookmarks' ) ) {
    //         return $has_children;
    //     }

    //     return false;
    // }

    /**
     * Set User Bookmarks Query.
     */
    function set_user_bookmarks_query( $retval ) {

        if ( ! bp_is_current_component( 'bookmarks' ) || $retval['display_comments'] == 'stream'  ) {
            return $retval;
        }

        // Get List of bookmarked items.
        $sql = $wpdb->prepare( "SELECT item_id FROM $Yz_bookmark_table WHERE user_id = %d AND item_type = %s", bp_displayed_user_id(), 'activity' );

        // Clean up array.
        $items_ids = wp_parse_id_list( $wpdb->get_col( $sql ) );

        // Check if private users have no activities.
        if ( empty( $items_ids ) ) {
            return $retval;
        }
        
        // Covert List of Activities ids to string.
        $items_ids = implode( ',', $items_ids );
        
        // Set Activities
        $retval['include'] = $items_ids;

        // Show Hidden Posts to admins and profile owners.
        if ( bp_core_can_edit_settings() ) {
            $retval['show_hidden'] = 1;
        }
        
        $retval['per_page'] = 2;

        return $retval;

    }

    /**
     * # Setup Tabs.
     */
    function add_bookmark_subtabs() {
        
        // if ( ! yz_is_user_can_see_bookmarks() ) {
        //     return false;
        // }

        // $bp = buddypress();

        // Add Activities Sub Tab.
        bp_core_new_subnav_item( array(
                'slug' => 'activities',
                'name' => __( 'Activities', 'youzer' ),
                'parent_slug' => 'bookmarks',
                'parent_url' => bp_displayed_user_domain() . "bookmarks/",
                'screen_function' => 'yz_bookmarks_screen',
            )
        );
    }

}