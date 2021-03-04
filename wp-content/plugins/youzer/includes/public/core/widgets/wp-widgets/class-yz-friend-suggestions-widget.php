<?php

/**
 * Friend Suggestions Widget
 */

class YZ_Friend_Suggestions_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_friend_suggestions_widget',
			__( 'Youzer - Friend Suggestions', 'youzer' ),
			array( 'description' => __( 'Friend suggestions widget', 'youzer' ) )
		);

		// Save Removed Suggestions.
		add_action( 'wp_ajax_yz_friends_refused_suggestion', array( $this, 'hide_suggestion' ) );

	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance,
	    	array(
		    	'title' => __( 'People You May Know', 'youzer' ),
		        'show_buttons' => 'on',
		        'limit' => '5',
	    	)
	    );

	    // Get Input's Data.
		$limit = absint( $instance['limit'] );
		$title = strip_tags( $instance['title'] );

		?>

		<!-- Title. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'bp-group-suggest' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<!-- Suggestions Number. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Suggestions Number:', 'bp-group-suggest' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" style="width: 30%">
			</label>
		</p>

		<!-- Display Buttons -->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['show_buttons'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_buttons' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_buttons' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'show_buttons' ); ?>"><?php _e( 'Show Buttons', 'youzer' ); ?></label>
    	</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance = $old_instance;
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_buttons'] = $new_instance['show_buttons'];

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		if ( ! apply_filters( 'yz_display_friends_suggestions_widget', true ) || ! is_user_logged_in() || ! bp_is_active( 'friends' ) ) {
			return false;
		}

		// Get Friend Suggestions
		$friend_suggestions = $this->get_friend_suggestions( bp_loggedin_user_id() );

		// Hide Widget IF There's No suggestions.
		if ( empty( $friend_suggestions ) ) {
			return false;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		$this->get_suggestions_list( $instance );

		echo $args['after_widget'];

	}

	/**
	 * Get Friend Suggestions.
	 */
	function get_friend_suggestions( $user_id ) {

		// Get List Of excluded Id's.
		$excluded_ids = (array) $this->get_excluded_friends_ids( $user_id );

		// Get Friends of Friends.
		$friends_of_friends = (array) $this->get_user_friends_of_friends( $user_id );

		// Get Friend Suggestion.
		$friend_suggestions = array_diff( $friends_of_friends, $excluded_ids );

		// Remove Current User ID
		$current_user_id = array_search( bp_loggedin_user_id() , $friend_suggestions );

	    if ( false !== $current_user_id ) {
	        unset( $friend_suggestions[ $current_user_id ] );
	    }

		// Randomize Order.
		shuffle( $friend_suggestions );

		// Return Friends ID's.
		return $friend_suggestions;
	}

	/**
	 * Get Suggestions List.
	 */
	function get_suggestions_list( $args ) {

		// Get User ID.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_loggedin_user_id();

		// Get Friend Suggestions.
		$friend_suggestions = $this->get_friend_suggestions( $user_id );

		// Limit Groups Number
		$friend_suggestions = array_slice( $friend_suggestions, 0, $args['limit'] );

		// Get 'Show Button' Option Value
		$show_buttons = $args['show_buttons'] ? 'on' : 'off';

		?>

		<div class="yz-items-list-widget yz-suggested-friends-widget yz-list-avatar-circle">

			<?php foreach ( $friend_suggestions as $friend_id ) : ?>

			<?php $profile_url = bp_core_get_user_domain( $friend_id ); ?>

			<div class="yz-list-item">
				<a href="<?php echo $profile_url; ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $friend_id, 'type' => 'thumb' ) ); ?></a>
				<div class="yz-item-data">
					<a href="<?php echo $profile_url; ?>" class="yz-item-name"><?php echo bp_core_get_user_displayname( $friend_id ); ?><?php yz_the_user_verification_icon( $friend_id ); ?></a>
					<div class="yz-item-meta">
						<div class="yz-meta-item">@<?php echo bp_core_get_username( $friend_id ); ?></div>
					</div>
				</div>
				<?php if ( 'on' == $show_buttons ) : ?>
				<div class="yz-item-action">

					<?php

						// Get Add Friend Url.
						$add_url = wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id . '/', 'friends_add_friend' );
						// Get Remove Friend Suggestion Url.
						$refused_url = bp_get_root_domain() . "/refuse-friend-suggestion/?suggestion_id=" . $friend_id . "&_wpnonce=" . wp_create_nonce( 'friend-suggestion-refused-' . $friend_id );

					?>

					<a href="<?php echo $add_url; ?>" class="yz-list-button yz-icon-button yz-add-button">
						<i class="fas fa-user-plus"></i>
					</a>
					<a href="<?php echo $refused_url;?>" class="yz-list-button yz-icon-button yz-close-button">
						<i class="fas fa-trash-alt"></i>
					</a>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>

		<script type="text/javascript">

			/**
			 * Save New Removed Friend Suggestions.
			 */
			jQuery( document ).on( 'click', '.yz-suggested-friends-widget .yz-close-button', function ( e ) {

				e.preventDefault();

				//hide the suggestion
				var item = jQuery( this ).closest( '.yz-list-item' );

				jQuery( item ).fadeOut( 400, function() {
					jQuery( this ).remove();
				});

				var url = jQuery( this ).attr( 'href' );

				jQuery.post( Youzer.ajax_url, {
					action: 'yz_friends_refused_suggestion',
					suggestion_id: jQuery.yz_get_var_in_url( url, 'suggestion_id' ),
					_wpnonce: jQuery.yz_get_var_in_url( url, '_wpnonce' )
				});

				return false;

			});

		</script>

		<?php

	}
	/**
	 * Get User Friends Groups
	 */
	function get_user_friends_of_friends( $user_id = null ) {

		// Init Vars.
		$friends_of_friends = array();

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();

		// Get All User Friends List.
		$user_friends = (array) friends_get_friend_user_ids( $user_id );

		// Check If User have friends.
		if ( empty( $user_friends ) ) {
			return;
		}

		foreach ( $user_friends as $friend_id ) {

			$friends = friends_get_friend_user_ids( $friend_id );

			if ( ! empty( $friends ) ) {

				foreach ( $friends as $id ) {
					$friends_of_friends[] = $id;
				}

			}

		}

		// Remove Repeated ID's.
		$friends_of_friends = array_unique( $friends_of_friends );

		return apply_filters( 'yz_friends_suggestions_friends_of_friends', $friends_of_friends );

	}

	/**
	 * Get User Excluded Groups
	 */
	function get_excluded_friends_ids( $user_id = null ) {

		// Get User Friends
		$user_friends = (array) friends_get_friend_user_ids( $user_id );

		// Get User Friendship requests List.
		$friendship_requests = $this->get_user_friendship_requests( $user_id );

		// List of Refused Suggestions
		$refused_friends = (array) self::get_refused_friend_suggestions( $user_id );

		// make an array of users group+groups hidden by user
		$excluded_ids = array_merge( $user_friends, $friendship_requests, $refused_friends );

		// Remove Repeated ID's.
		$excluded_ids = array_unique( $excluded_ids );

		return $excluded_ids;
	}

	/**
	 * User Friendship requests
	 */
	function get_user_friendship_requests( $user_id ) {

		global $wpdb;

		// Init Vars.
		$bp = buddypress();

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();

		// SQL
		$sql = "SELECT friend_user_id FROM {$bp->friends->table_name} WHERE initiator_user_id = %d AND is_confirmed = 0";

		// Get List of Membership Requests.
		$friendship_requests = $wpdb->get_col( $wpdb->prepare( $sql, $user_id ) );

		return $friendship_requests;
	}

	/**
	 * Save New Refused Suggestions.
	 */
	public function hide_suggestion() {

		// Get Suggested Group ID.
		$suggestion_id = isset( $_POST['suggestion_id'] ) ? $_POST['suggestion_id'] : 0;

		check_ajax_referer( 'friend-suggestion-refused-' . $suggestion_id );

		if ( empty( $suggestion_id ) || ! is_user_logged_in() ) {
			return;
		}

		// Get Current User ID.
		$user_id = bp_loggedin_user_id();

		// Get Old Refused Suggestions.
		$refused_suggestions = (array) get_user_meta( $user_id, 'yz_refused_friend_suggestions', true );

		// Add The new Refused Suggestion to the old refused suggetions list.
		if ( ! in_array( $suggestion_id, $refused_suggestions ) ) {
			$refused_suggestions[] = $suggestion_id;
		}

		// Save New Refused Suggestion
		update_user_meta( $user_id, 'yz_refused_friend_suggestions', $refused_suggestions );

		die();

	}

	/**
	 * Get Refused Suggestions.
	 */
	public static function get_refused_friend_suggestions( $user_id = null ) {

		// Get User ID.
		$user_id = ( $user_id ) ? $user_id : bp_loggedin_user_id();

		// Get Refused Groups.
		return get_user_meta( $user_id, 'yz_refused_friend_suggestions', true );

	}

}