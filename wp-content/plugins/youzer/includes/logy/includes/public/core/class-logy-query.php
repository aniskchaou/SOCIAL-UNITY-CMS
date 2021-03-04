<?php

class Logy_Query {

	protected $users_table;

	function __construct() {
    	$this->users_table = 'logy_users';
	}



	/**
	 * Get User Profile Data.
	 */
	public function get_user_profile( $user_id, $provider, $uid ) {
		
		global $wpdb;

		// Get SQL Request.
		$sql = "SELECT * FROM " . $wpdb->prefix ."logy_users WHERE user_id = %d AND provider = %s AND identifier = %s";

		// Get Result.		
		$result = $wpdb->get_results( $wpdb->prepare( $sql, $user_id, $provider, $uid ) );

		return $result;
	}

}