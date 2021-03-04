<?php
/**
 * Sticky Posts Class
 */
class Youzer_Wall_Bookmarks {

	function __construct( ) {
		
		// Actions.
		add_filter( 'yz_activity_tools', array( $this, 'add_bookmark_tool' ), 10, 2 );
		add_filter( 'bp_has_activities', array( $this, 'loop_has_content' ), 999, 3 );
        add_filter( 'bp_after_has_activities_parse_args', array( $this, 'set_user_bookmarks_query' ) );		
	}
	
	/**
	 * Add New Activity Tool.
	 */
	function add_bookmark_tool( $tools, $post_id ) {
		
		if ( ! $this->is_user_can_bookmark() ) {
			return $tools;
		}

		if ( yz_get_bookmark_id( bp_loggedin_user_id(), $post_id, 'activity' ) ) {
			// Get Unpin Button Data.
			$action = 'unsave';
			$class = 'yz-unsave-post';
			$title = __( 'Remove Bookmark', 'youzer' );
			$icon  = 'fas fa-times';
		} else {
			// Get Pin Button Data.
			$action = 'save';
			$icon  = 'fas fa-bookmark';
			$class = 'yz-save-post';
			$title = __( 'Bookmark', 'youzer' );
		}

		// Get Tool Data.
		$tools[] = array(
			'icon' => $icon,
			'title' => $title,
			'action' => $action,
			'class' => array( 'yz-bookmark-tool', $class ),
			'attributes' => array( 'item-type' => 'activity' )
		);

		return $tools;
	}

	/**
	 * Check if User Have Bookmarks
	 */
	function loop_has_content( $has_activities , $activities_template, $r ) {

		if ( ! bp_is_current_component( 'bookmarks' ) ) {
			return $has_activities;
		}

		// Check if user has bookmarks.
		if ( isset( $r['include'] ) && empty( $r['include'] ) ) {
	    	return false;
	    }

	    return $has_activities;
	}

	/**
	 * Check is User Can Bookmark Activities.
	 */
	function is_user_can_bookmark() {
		return apply_filters( 'yz_is_user_can_bookmark', true );
	}

    /**
     * Set User Bookmarks Query.
     */
    function set_user_bookmarks_query( $retval ) {

        if ( ! bp_is_current_component( 'bookmarks' ) || $retval['display_comments'] == 'stream' ) {
            return $retval;
        }

        // Get List of bookmarked items.
        $items_ids = $this->get_user_bookmarks( bp_displayed_user_id(), 'activity', 'list' );

        // Check if private users have no activities.
        if ( empty( $items_ids ) ) {
            return $retval;
        }

        // Set Activities
        $retval['include'] = implode( ',', $items_ids );

        // Show Hidden Posts to admins and profile owners.
        if ( bp_core_can_edit_settings() ) {
            $retval['show_hidden'] = 1;
        }
        
        $retval['per_page'] = 2;

        return $retval;

    }

    /**
     * Get User Bookmarks.
     */
    function get_user_bookmarks( $user_id, $item_type, $result_type = null ) {
       
        // Get Transient Option.
        $transient_id = 'yz_user_bookmarks_' . $user_id;

        $user_bookmarks = get_transient( $transient_id );

        if ( false === $user_bookmarks ) {

            global $wpdb, $Yz_bookmark_table;
            
            // Get SQL Query.
            $sql = $wpdb->prepare(
                "SELECT item_id FROM $Yz_bookmark_table WHERE user_id = %d AND item_type = %s",
                $user_id, $item_type
            );

            // Get Result
            $result = $wpdb->get_col( $sql );

            // Clean up array.
            $user_bookmarks = wp_parse_id_list( $result );

            set_transient( $transient_id, $user_bookmarks, 12 * HOUR_IN_SECONDS );
            
        }

        // Return.
        return $user_bookmarks;

    }
}

$bookmarks = new Youzer_Wall_Bookmarks();

/**
 * Bookmark Scripts
 */
// function yz_bookmarks_scripts() {

//     // Pin Activities Script
//     if ( yz_is_user_can_bookmark() ) {

//         // Call Bookmark Posts Script.
//         wp_enqueue_script( 'yz-bookmark-posts', YZ_PA . 'js/yz-bookmark-posts.min.js', array(), YZ_Version );

//         // Get Data
//         $script_data = array(
//         );

//         // Localize Script.
//         wp_localize_script( 'yz-bookmark-posts', 'Yz_Bookmark_Posts', $script_data );

//     }

// }

// add_action( 'yz_activity_scripts', 'yz_bookmarks_scripts' );