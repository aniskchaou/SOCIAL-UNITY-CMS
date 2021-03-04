<?php
/**
 * Hashtags Class
 */
class Youzer_Wall_Hashtags {

	public $regex;

	function __construct() {

		// Hashtah Search.
		add_filter( 'bp_ajax_querystring', array( $this, 'hashtags_querystring' ), 100 );

		// Save Activity Hashtags.
		add_action( 'yz_after_adding_wall_post', array( $this, 'save_activity_hashtags' ) );
		add_action( 'bp_activity_comment_posted', array( $this, 'save_activity_hashtags' ) );

		// Activate Hashtags On New Edited Posts.
		add_action( 'yzea_activity_content', array( $this, 'generate_hashtags' ) );

		// Update_hashtags
		add_action( 'yz_after_editing_activity', array( $this, 'update_hashtags' ) );

		// Delete Hashtags On Post Delete.
		add_action( 'bp_before_activity_delete', array( $this, 'delete_activity_hashtags' ) );

		// Regex
		$this->regex = '/[#]([\p{L}_0-9a-zA-Z-]{1,100})\b(?!;)/iu';
	}

	/**
	 * Display Posts By Hashtag On the Activity Stream.
	 */
	function hashtags_querystring( $query ) {

		if (  ! bp_is_activity_component() || ! isset( $_GET['s'] ) || empty( $_GET['s'] ) ) {
			return $query;
		}

		if ( ! empty( $query ) ) {
	        $query .= '&';
	    }

		// Query String.
		$query .= 'display_comments=true&search_terms=' . $_GET['s'];

		return $query;
	}

	/**
	 * Save Activity Hashtags
	 */
	function save_activity_hashtags( $activity_id ) {

		// Get Activity.
		$activity = new BP_Activity_Activity( $activity_id );

		if ( empty( $activity->content ) ) {
			return;
		}

		// Get All Matches.
		preg_match_all( $this->regex, $activity->content, $matches );

		// Save Hashtags.
		if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {

			$activity->content = $this->generate_hashtags( $activity->content );

			// Save Activity.
			$activity->save();

			// Save Hashtags.
			$this->save_hashtags( $matches[1], $activity );
		}

	}

	/**
	 * Convert Activity Stream Hashtags Into Url.
	 */
	function generate_hashtags( $content ) {
		return preg_replace( $this->regex, ' <a href="' . $this->get_hashtag_link() . '$1"   class="yz-hashtag" target="_self">#$1</a>', $content );
	}


	/**
	 * Hashtags Shortcode.
	 */
	function hashtags_shortcode( $atts ) {

	    $options = shortcode_atts( array(
	        'limit' => 5,
	        'type' => 'popular',
	    ), $atts );

		do_action( 'yz_before_hashtags_widget' );

		ob_start();
	    $this->widget( $options );
		return ob_get_clean();
	}

	/**
	 * Get Hashtag Link
	 */
	function get_hashtag_link( $hashtag = null ) {
		return apply_filters( 'yz_hashtag_link', bp_get_activity_directory_permalink() . '?s=%23' . $hashtag );
	}

	/**
	 * Update Hashtags.
	 */
	function update_hashtags( $activity ) {

		// Hashtag Regex.

		preg_match_all( $this->regex, wp_strip_all_tags( $activity->old_content ), $old_hashtags );

		// Save Hashtags.
		if ( ! isset( $old_hashtags[1] ) || empty( $old_hashtags[1] ) ) {
			return;
		}

		// Get All Matches.
		preg_match_all( $this->regex, wp_strip_all_tags( $activity->content ), $new_hashtags );

		// Get List of Old Hashtags.
		$new_hashtags = isset( $new_hashtags[1] ) ? $new_hashtags[1] : array();
		$removable_hashtags = array_diff( $old_hashtags[1], $new_hashtags );
		$new_hashtags = array_diff( $new_hashtags, $old_hashtags[1] );

		// Save New Hashtags
		if ( ! empty( $new_hashtags ) ) {
			$this->save_hashtags( $new_hashtags, $activity );
		}

		// Delete Removed Hashtags.
		if ( ! empty( $removable_hashtags ) ) {
			$this->delete_hashtags( $removable_hashtags, $activity );
		}

		// Delete Cache.
		$this->delete_cache();

	}

	/**
	 * Delete Hashtags.
	 */
	function delete_activity_hashtags( $args ) {

		// Get Activity.
		$activity = new BP_Activity_Activity( $args['id'] );

		if ( empty( $activity->content ) ) {
			return;
		}

		// Get All Matches.
		preg_match_all( $this->regex, wp_strip_all_tags( $activity->content ), $hashtags );

		// Save Hashtags.
		if ( isset( $hashtags[1] ) && ! empty( $hashtags[1] ) ) {
			$this->delete_hashtags( $hashtags[1], $activity );
			// Delete Cache.
			$this->delete_cache();
		}

	}

	/**
	 * Save Hashtags.
	 */
	function save_hashtags( $hashtags = null, $activity, $object = 'bp' ) {

		if ( empty( $hashtags ) ) {
			return;
		}

		global $wpdb;

		// Get Current Time.
		$time = bp_core_current_time();

		// Get Hashtags Table.
		$hashtag_table = $wpdb->prefix . 'yz_hashtags';

		// Remove Duplicates.
		$hashtags = array_unique( $hashtags );

		foreach ( $hashtags as $hashtag ) {

			// Insert Hastag.
			$result = $wpdb->insert(
				$wpdb->prefix . 'yz_hashtags_items',
				array( 'hashtag' => $hashtag, 'user_id' => $activity->user_id, 'item_id' => $activity->id, 'object' => $object, 'time' => $time )
			);

			// Get Hashtag Data.
			$data = $wpdb->get_results( "SELECT * FROM " . $hashtag_table . " WHERE hashtag = '$hashtag' AND object = 'bp'" );

			if ( isset( $data[0] ) ) {
				// Update Hashtag Count.
				$wpdb->update( $hashtag_table, array( 'count' => $data[0]->count + 1 ), array( 'id' => $data[0]->id ), array( '%d' ), array( '%d') );
			} else {
				// Set New Hashtag Count.
				$result = $wpdb->insert( $hashtag_table, array( 'hashtag' => $hashtag, 'object' => $object, 'count' => 1 ) );
			}
		}

		// Delete Cache.
		$this->delete_cache();

		return;

	}
	/**
	 * Delete Hashtag.
	 */
	function delete_hashtags( $hashtags, $activity ) {

		global $wpdb;

		// Get Hashtags Table.
		$hashtag_table = $wpdb->prefix . 'yz_hashtags';

		foreach ( $hashtags as $hashtag ) {

			// Delete Hastag.
			$result = $wpdb->delete(
				$wpdb->prefix . 'yz_hashtags_items',
				array( 'hashtag' => $hashtag, 'user_id' => $activity->user_id, 'item_id' => $activity->id, 'object' => 'bp' ),
				array( '%s', '%d', '%d', '%s' )
			);

			// Get Hashtag Data.
			$data = $wpdb->get_results( "SELECT * FROM " . $hashtag_table . " WHERE hashtag = '$hashtag' AND object = 'bp'" );

			// Reset Counts.
			if ( isset( $data[0] ) ) {

				// Get New Count.
				$new_count = $data[0]->count - 1;

				if ( $new_count > 0 ) {
					// Update Hashtag Count.
					$wpdb->update( $hashtag_table, array( 'count' => $new_count ), array( 'id' => $data[0]->id ), array( '%d' ), array( '%d') );
				} else {
					$result = $wpdb->delete( $hashtag_table, array( 'id' => $data[0]->id, 'object' => 'bp' ), array( '%d' ) );
				}

			}

		}

	}
	/**
	 * Delete Transient.
	 */
	function delete_cache() {
		delete_transient( 'yz_get_community_hashtags_widget' );
		delete_transient( 'yz_get_trending_hashtags' );
		delete_transient( 'yz_get_hashtags_widget' );
	}

}

$hastags = new Youzer_Wall_Hashtags();