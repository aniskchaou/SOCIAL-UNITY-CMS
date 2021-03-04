<?php

class Youzer_Reviews_Query {

    public function __construct() {
	}

	/**
	 * Get Review ID.
	 */
	function get_review_id( $reviewed, $reviewer ) {

		global $wpdb, $Yz_reviews_table;

		// Prepare Sql
		$sql = $wpdb->prepare(
			"SELECT id FROM $Yz_reviews_table WHERE reviewer = %d AND reviewed = %d",
			$reviewer, $reviewed
		);

		// Get Result
		$result = $wpdb->get_var( $sql );

		return $result;

	}

	/**
	 * Get Review Data.
	 */
	function get_review_data( $review_id ) {

		global $wpdb, $Yz_reviews_table;

		// Prepare Sql
		$sql = $wpdb->prepare(
			"SELECT * FROM $Yz_reviews_table WHERE id = %d",
			$review_id
		);

		// Get Result
		$result = $wpdb->get_results( $sql , ARRAY_A );
		$result = ( isset( $result[0]['id'] ) && ! empty( $result[0]['id'] ) ) ? $result[0] : false;

		return $result;

	}

	/**
	 * Get User Reviews.
	 */
	function get_user_reviews( $args = null ) {

		global $wpdb, $Yz_reviews_table;

		$request = "SELECT * FROM $Yz_reviews_table";

		if ( isset( $args['user_id'] ) && ! empty( $args['user_id'] ) ) {
			$request .= " WHERE reviewed = '{$args['user_id']}'";
		}

		if ( isset( $args['order_by'] ) ) {
			$request .= " ORDER BY id {$args['order_by']}";
		}

		if ( isset( $args['per_page'] ) ) {
			$request .= " LIMIT {$args['per_page']}";
		}

		if ( isset( $args['offset'] ) ) {
			$request .= " OFFSET {$args['offset']}";
		}

		// Get Result
		$result = $wpdb->get_results( $request , ARRAY_A );

		return $result;

	}

	/**
	 * Get User Reviews Count.
	 */
	function get_user_reviews_count( $user_id = null ) {
		
	    // Get User ID.
	    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	    // Get Transient Option.
	    $reviews_count = get_the_author_meta( 'yz_user_reviews_count', $user_id );

	    if ( empty( $reviews_count ) && $reviews_count !== '0' ) {
	    	$reviews_count = $this->update_user_reviews_count( $user_id );
	    }

		return apply_filters( 'yz_user_reviews_count', $reviews_count );

	}

	function update_user_reviews_count( $user_id ) {

		global $wpdb, $Yz_reviews_table;

		$request = "SELECT COUNT(*) FROM $Yz_reviews_table";

		if ( ! empty( $user_id ) ) {
			$request .= "  WHERE reviewed = $user_id";
		}

		// Get count(var)
		$reviews_count = $wpdb->get_var( $request );

		// Update User Ratings Count.
		update_user_meta( $user_id, 'yz_user_reviews_count', $reviews_count );
		
		return $reviews_count;
	
	}

	/**
	 * Get Ratings By Stars Number.
	 */
	function get_user_ratings_by_stars( $user_id, $stars ) {

		global $wpdb, $Yz_reviews_table;

		// Get Count
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $Yz_reviews_table WHERE reviewed = $user_id AND rating = '4'" );
		
		return $count;

	}

	/**
	 * Get User Reviews Average.
	 */
	function get_user_ratings_rate( $user_id ) {

	    // Get User ID.
	    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	    // Get Transient Option.
	    $ratings_rate = get_the_author_meta( 'yz_user_ratings_rate', $user_id );

	    if ( empty( $ratings_rate ) && $ratings_rate !== '0' ) {
	    	$ratings_rate = $this->update_user_ratings_rate( $user_id );
	    }

		return $ratings_rate;

	}

	/**
	 * Update User Reviews Average.
	 */
	function update_user_ratings_rate( $user_id ) {

		global $wpdb, $Yz_reviews_table;

		// Get Count
		$count = $wpdb->get_var( "SELECT AVG(rating) FROM $Yz_reviews_table WHERE reviewed = $user_id" );

		$ratings_rate = apply_filters( 'yz_get_user_ratings_rate', $count, $user_id );

		// Update User Ratings Rate.
		update_user_meta( $user_id, 'yz_user_ratings_rate', $ratings_rate );
	
		return $ratings_rate;	
	}

	/**
	 * Add Review.
	 */
	function add_review( $data = array() ) {

		global $wpdb, $Yz_reviews_table;

		// Get Current Time.
		$data['time'] = bp_core_current_time();
		
		// Insert Review.
		$result = $wpdb->insert( $Yz_reviews_table, $data );

		if ( $result ) {
			// Return ID.
			return $wpdb->insert_id;
		}

		return false;

	}

	/**
	 * Save Review.
	 */
	function update_review( $review_id, $data = array() ) {

		global $wpdb, $Yz_reviews_table;

		// Get Current Time.
		$data['time'] = bp_core_current_time();
		
		unset( $data['reviewed'], $data['reviewer'], $data['review_id'] );

		// Get Values Format
		$values_format = apply_filters( 'yz_update_review_values_format', array( '%d', '%s', '%s' ) );

		// Update Review.
		$result = $wpdb->update( $Yz_reviews_table, $data, array( 'id' => $review_id ), $values_format, array( '%d') );

		return $result;

	}

	/**
	 * Delete Reaction.
	 */
	function delete_review( $review_id ) {

		global $wpdb, $Yz_reviews_table;

		// Delete Review.
		$delete = $wpdb->delete( $Yz_reviews_table, array( 'id' => $review_id ), array( '%d' ) );

		// Get Result.
		if ( $delete ) {
			return true;
		}

		return false;

	}

}