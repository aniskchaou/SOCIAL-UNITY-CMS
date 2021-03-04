<?php
/**
 * Wall Activity Share.
 */
class Youzer_Activit_Share {

	function __construct( ) {

		// Update Share Count.
		add_action( 'bp_activity_posted_update', array( $this, 'set_activity_share_count' ), 10, 3 );
		add_action( 'bp_groups_posted_update', array( $this, 'set_group_activity_share_count' ), 10, 4 );
		add_action( 'bp_activity_after_delete', array( $this, 'update_activity_share_count_on_delete' ) );

		// Get who shared an activity.
		add_action( 'wp_ajax_yz_get_who_shared_post', array( $this, 'who_shared_activity_modal' ) );
		add_action( 'wp_ajax_nopriv_yz_get_who_shared_post', array( $this, 'who_shared_activity_modal' ) );

		// Notification
		add_filter( 'yz_format_notifications', array( $this, 'notification' ), 10, 5 );
		add_action( 'yz_after_activity_share', array( $this, 'add_notification' ), 10, 2 );
		add_action( 'bp_actions', array( $this, 'mark_notifications_as_read' ) );
	}

	/**
	 * Mark Likes notifications as read when reading a topic
	 */
	function mark_notifications_as_read( $action = '' ) {

		if ( ! bp_is_active( 'activity' ) || ! bp_is_single_activity()  ) {
			return;
		}

		// Bail if no activity ID is passed
		if ( empty( $_GET['activity_id'] ) || ! isset( $_GET['action'] ) || $_GET['action'] != 'yz_new_share_mark_read' ) {
			return;
		}

		// Get required data
		$user_id  = bp_loggedin_user_id();
		$activity_id = intval( $_GET['activity_id'] );

		// Check nonce
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'yz_new_share_mark_read_' . $activity_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
		    bp_core_add_message( "<strong>ERROR</strong>: Sorry you don't have permission to do that !", 'error' );
			return;
		}

		// Attempt to clear notifications for the current user from this topic
		$success = bp_notifications_mark_notifications_by_item_id( $user_id, $activity_id, 'youzer', 'yz_new_share' );

		// Do additional subscriptions actions
		do_action( 'yz_notifications_mark_share_notifications_as_read', $success, $user_id, $activity_id, $action );

		// Redirect to the topic
		$redirect = bp_activity_get_permalink( $activity_id );

		// Redirect
		wp_safe_redirect( $redirect );

		// For good measure
		exit();
	}

	/**
	 * Add User Tag Notification.
	 */
	function add_notification( $new_activity_id, $shared_activity_id ) {

		// Get Activity.
		$activity = new BP_Activity_Activity( $shared_activity_id );

		// Bail if the post owner shared their own post.
		if ( bp_loggedin_user_id() == $activity->user_id ) {
			return;
		}

	    bp_notifications_add_notification(
	    	array(
		        'user_id'           => $activity->user_id,
		        'item_id'           => $new_activity_id,
		        'secondary_item_id' => bp_loggedin_user_id(),
		        'component_name'    => 'youzer',
		        'component_action'  => 'yz_new_share',
		        'date_notified'     => bp_core_current_time(),
		        'is_new'            => 1
	    	)
	    );

	}

	/**
	 * Set Notification.
	 */
	function notification( $action, $item_id, $secondary_item_id, $total_items, $format ) {

	    if ( 'yz_new_share' == $action ) {

	        // Init Vars.
	        $link = wp_nonce_url( add_query_arg( array( 'action' => 'yz_new_share_mark_read', 'activity_id' => $item_id ), bp_activity_get_permalink( $item_id ) ), 'yz_new_share_mark_read_' . $item_id );

	        $title  = sprintf( __( '@%s Shares', 'youzer' ), bp_get_loggedin_user_username() );

	        $amount = 'single';

	        if ( (int) $total_items > 1 ) {
	            $text   = sprintf( __( 'You have %1$d new post shares', 'youzer' ), (int) $total_items );
	            $amount = 'multiple';
	        } else {
	            $text = sprintf( __( '%1$s shared your post', 'youzer' ), bp_core_get_user_displayname( $secondary_item_id ) );
	        }

	        if ( $format == 'string' ) {
	            return apply_filters( 'yz_format_single_new_share_notifications', '<a href="' . esc_url( $link ) . '">' . esc_html( $text ) . '</a>', $text, $link );
	        } else {
	            return apply_filters( 'yz_format_multiple_new_share_notifications', array( 'text' => $text, 'link' => $link ), $text, $link );
	        }

	    }

	    return $action;

	}

	/**
	 * Update share count after post share.
	 */
	function set_activity_share_count( $content, $user_id, $activity_id ) {
		$this->update_activity_share_count( $activity_id );
	}

	/**
	 * Update group share count after post share.
	 */
	function set_group_activity_share_count( $content, $user_id, $group_id, $activity_id ) {
		$this->update_activity_share_count( $activity_id );
	}

	/**
	  * Update Activity share count.
	  */
	function update_activity_share_count( $activity_id ) {

		// Get Activity
		$activity = new BP_Activity_Activity( $activity_id );

		// Shared Activity ID.
		$shared_activity_id = $activity->secondary_item_id;

		if ( $activity->type != 'activity_share' ) {
			return;
		}

		// Get share count.
		$share_count = bp_activity_get_meta( $shared_activity_id, 'yz_activity_share_count' );

		// Calc share count.
		$share_count = ! empty( $share_count ) ? (int) $share_count + 1 : 1;

		// Update count.
		bp_activity_update_meta( $shared_activity_id, 'yz_activity_share_count', $share_count );

		// Action.
		do_action( 'yz_after_activity_share', $activity_id, $shared_activity_id );
	}

	/**
	 * Delete share count after post share.
	 */
	function update_activity_share_count_on_delete( $activities ) {

		foreach ( $activities as $activity ) {

			if ( $activity->type != 'activity_share' ) {
				continue;
			}

			// Get share count
			$share_count = bp_activity_get_meta( $activity->secondary_item_id, 'yz_activity_share_count' );

			// Get share count
			$share_count = ! empty( $share_count ) ? (int) $share_count - 1 : 0;

			// Update count
			bp_activity_update_meta( $activity->secondary_item_id, 'yz_activity_share_count', $share_count );

		}

	}

	/**
	 * Get who liked a post Modal.
	 */
	function who_shared_activity_modal() {

		// Get Modal Args
		$args = array(
			'item_id'  => $_POST['post_id'],
			'function' => array( $this, 'get_users_list' ),
			'title'    => __( 'People Who Shared This', 'youzer' )
		);

		// Get Modal Content
		yz_wall_modal( $args );

		die();
	}

	/**
	 * Get who shared a post list.
	 */
	function get_users_list( $post_id ) {

		global $wpdb, $bp;

		// Prepare Sql
		$sql = $wpdb->prepare( "SELECT user_id FROM {$bp->activity->table_name} WHERE type = 'activity_share' AND secondary_item_id = %d", $post_id );

		// Get Result
		$result = $wpdb->get_results( $sql , ARRAY_A );

		// Get List of user id's & Remove Duplicated Users.
		$users = wp_list_pluck( $result, 'user_id' );

		// Get Users List.
		yz_get_popup_user_list( $users );

	}

}

new Youzer_Activit_Share();
