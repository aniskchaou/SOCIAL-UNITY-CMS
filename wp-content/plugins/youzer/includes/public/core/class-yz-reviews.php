<?php

class Youzer_Reviews {

	function __construct() {

		$this->query = new Youzer_Reviews_Query();

		// Actions.
		add_action( 'wp_ajax_yz_handle_user_reviews', array( $this, 'handle_user_reviews' ) );
		add_action( 'wp_ajax_yz_delete_user_review', array( $this, 'delete_user_review' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'bp_setup_nav', array( $this, 'setup_tabs' ) );
		add_action( 'yz_user_tools', array( $this, 'get_user_review_tool' ), 10, 2 );
		add_action( 'yz_before_review_head', array( $this, 'get_review_tools' ) );
		add_action( 'yz_author_box_ratings_content', array( $this, 'author_box_ratings' ) );
		add_action( 'bp_directory_members_item', array( $this, 'add_members_directory_cards_ratings' ), 100 );

		// Filters.
		add_filter( 'yz_review_tools', array( $this, 'add_review_tools' ), 10, 2 );

		// Reviews - Ajax Pagination.
		add_action( 'wp_ajax_nopriv_yz_reviews_pagination', array( $this, 'pagination' ) );
		add_action( 'wp_ajax_yz_reviews_pagination', array( $this, 'pagination' ) );

		// Statistics
		// add_filter( 'yz_get_user_statistics_details', array( $this, 'add_ratings_statitics' ) );
		add_filter( 'yz_get_user_statistic_number', array( $this, 'get_ratings_statistics_values' ), 10, 3 );

	}

	/**
	 * # Setup Tabs.
	 */
	function setup_tabs() {

		if ( ! yz_is_user_can_see_reviews() || ! yz_is_user_can_receive_reviews() ) {
			return false;
		}

		$bp = buddypress();

		$reviews_slug = yz_reviews_tab_slug();

		// Add Follows Tab.
		bp_core_new_nav_item(
		    array(
		        'position' => 250,
		        'slug' => $reviews_slug,
		        'name' => __( 'Reviews' , 'youzer' ),
		        'default_subnav_slug' => 'reviews',
		        'parent_slug' => $bp->profile->slug,
		        'screen_function' => 'yz_reviews_screen',
		        'parent_url' => bp_displayed_user_domain() . "$reviews_slug/"
		    )
		);

	}

	/**
	 * Handle Posts Bookmark
	 */
	function handle_user_reviews() {

		// Hook.
		do_action( 'yz_before_adding_user_review' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzer-nonce', 'security' );

		if ( ! yz_is_user_can_add_reviews() ) {
			$response['error'] = __( 'The action you have requested is not allowed.', 'youzer' );
			die( json_encode( $response ) );
		}

		$action = isset( $_POST['operation'] ) ? $_POST['operation'] : null;

		// Get Table Data.
		$data = array(
			'reviewer' => bp_loggedin_user_id(),
			'rating' => isset( $_POST['rating'] ) ? $_POST['rating'] : null,
			'review' => isset( $_POST['review'] ) ? $_POST['review'] : null,
			'reviewed' => isset( $_POST['reviewed'] ) ? $_POST['reviewed'] : null,
		);

		// Filter Data.
		$data = apply_filters( 'yz_user_review_form_data', $data );

		// Allowed Actions
		$allowed_actions = array( 'add', 'edit' );

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$response['error'] = __( 'The action you have requested does not exist.', 'youzer' );
			die( json_encode( $response ) );
		}

		// Check if The Post ID & The Component are Exist.
		if ( empty( $data['reviewer'] ) || empty( $data['reviewed'] ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzer' );
			die( json_encode( $response ) );
		}

		if ( empty( $data['rating'] ) ) {
			$response['error'] = __( 'Please make sure to rate the user.', 'youzer' );
			die( json_encode( $response ) );
		}

		if ( yz_is_user_review_description_required() && empty( $data['review'] ) ) {
			$response['error'] = __( 'The review field is empty.', 'youzer' );
			die( json_encode( $response ) );
		}

		global $Youzer;

		$youzer_query = $Youzer->reviews->query;

		// Check if user Already Reviewed Post.
		$review_id = $youzer_query->get_review_id( $data['reviewed'], $data['reviewer'] );

		if ( $action == 'add' ) {

			if ( $data['reviewed'] == $data['reviewer'] ) {
				$response['error'] = __( "Sorry you can't post a review on yourself.", 'youzer' );
				die( json_encode( $response ) );
			}

			if ( $review_id ) {
				$response['error'] = __( 'You already reviewed this user.', 'youzer' );
				die( json_encode( $response ) );
			}

			// Get Review Ad.
			$review_id = $youzer_query->add_review( $data );

			if ( $review_id ) {
				// Update User Ratings Count & Rate.
				$youzer_query->update_user_reviews_count( $data['reviewed'] );
				$youzer_query->update_user_ratings_rate( $data['reviewed'] );
				do_action( 'yz_after_adding_user_review', $review_id, $data );
			}

			$response['review_id'] = $review_id;
			$response['button_icon'] = 'fas fa-edit';
			$response['button_title'] = __( 'Edit Review', 'youzer' );
			$response['action'] = yz_is_user_can_edit_reviews() ? 'edit' : 'delete_button';
			$response['msg'] = __( 'Your review has been successfully submitted.', 'youzer' );

		}	elseif ( 'edit' == $action ) {

			if ( ! yz_is_user_can_edit_reviews( $data ) ) {
				$response['error'] = __( 'You are not allowed to edit reviews.', 'youzer' );
				die( json_encode( $response ) );
			}

			// Update Review.
			$review_id = $youzer_query->update_review( $_POST['review_id'], $data );

			if ( $review_id ) {
				$youzer_query->update_user_ratings_rate( $data['reviewed'] );
				// Hook.
				do_action( 'yz_after_updating_user_review', $review_id, $data );
			}

			// $response['action'] = 'delete_button';
			$response['msg'] = __( 'The review is successfully updated.', 'youzer' );

		}

		// Response Words
        $response['add_review'] = __( 'Add Review', 'youzer' );
        $response['edit_review'] = __( 'Edit Review', 'youzer' );
        $response['delete_review'] = __( 'Delete Review', 'youzer' );

		die( json_encode( $response ) );

	}

	/**
	 * Handle Delete User Review
	 */
	function delete_user_review() {

		// Check Ajax Referer.
		check_ajax_referer( 'youzer-nonce', 'security' );

		do_action( 'yz_before_delete_user_review' );

		// Get Review ID.
		$review_id = isset( $_POST['review_id'] ) ? $_POST['review_id'] : null;

		if ( empty( $review_id ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzer' );
			die( json_encode( $response ) );
		}

		global $Youzer;

		// Get User Query.
		$youzer_query = $Youzer->reviews->query;

		// Get Review Data.
		$review_data = $youzer_query->get_review_data( $review_id );

		if ( ! $review_data ) {
			$response['error'] = __( 'The review is already deleted or does not exist.', 'youzer' );
			die( json_encode( $response ) );
		}

		do_action( 'yz_before_deleting_user_review', $review_id, $review_data );

		// Delete Review.
		if ( $youzer_query->delete_review( $review_id ) ) {
			// Update User Ratings Count & Rate.
			$youzer_query->update_user_reviews_count( $review_data['reviewed'] );
			$youzer_query->update_user_ratings_rate( $review_data['reviewed'] );
			$response['msg'] = __( 'The review is successfully deleted.', 'youzer' );
		}

		die( json_encode( $response ) );
	}

	/**
	 * Reviews Scripts
	 */
	function scripts() {

	    // Call Review Script.
	    wp_enqueue_style( 'yz-reviews', YZ_PA . 'css/yz-reviews.min.css', array(), YZ_Version );

	    // Call Bookmark Posts Script.
	    // wp_enqueue_script( 'yz-reviews', YZ_PA . 'js/yz-reviews.min.js', array( 'jquery' ), YZ_Version, true );

	    // Get Data
	    // $script_data = array(
	    // );

	    // // Localize Script.
	    // wp_localize_script( 'yz-reviews', 'Yz_Reviews', $script_data );

	    $reviews_slug = yz_reviews_tab_slug();

	    if ( bp_is_current_component( $reviews_slug ) ) {
	        wp_enqueue_script( 'yz-reviews-pagination', YZ_PA . 'js/yz-reviews-pagination.min.js', array(), YZ_Version );
	    }

	}


	/**
	 * Add Reviews User Var.
	 */
	function add_query_vars( $vars ) {
		$vars['displayed_user_id'] = bp_displayed_user_id();
		return $vars;
	}

	/**
	 * Get User Review Tool.
	 */
	function get_user_review_tool( $user_id = null, $icons = null ) {

		if ( ! apply_filters( 'yz_display_user_review_tool', true ) ) {
			return;
		}

		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		if ( ! yz_is_user_can_receive_reviews( $user_id ) ) {
			return;
		}

		if ( bp_loggedin_user_id() == $user_id ) {
			return;
		}

		if ( ! yz_is_user_can_add_reviews( $user_id ) && ! yz_is_user_can_edit_reviews( $user_id ) ) {
			return false;
		}

		// Get User Value.
		$is_user_reviewed = yz_is_user_already_reviewed( $user_id );

		// Get Action.

		if ( $is_user_reviewed ) {
			$action = 'edit';
			$button_icon = 'far fa-edit';
			$review_id = $is_user_reviewed;
			$button_title = __( 'Edit Review', 'youzer' );
		} else {
			$action = 'add';
			$review_id = 0;
			$button_icon = 'far fa-star';
			$button_title = __( 'Add Review', 'youzer' );
		}

		?>

		<div class="yz-tool-btn yz-review-btn" <?php if ( 'only-icons' == $icons ) { ?> data-yztooltip="<?php echo $button_title; ?>"<?php } ?> data-action="<?php echo $action; ?>" data-review-id="<?php echo $review_id; ?>">
				<div class="yz-tool-icon"><i class="<?php echo $button_icon; ?>"></i></div><?php if ( 'full-btns' == $icons ) : ?><div class="yz-tool-name"><?php echo $button_title; ?></div><?php endif; ?>
		</div>

		<?php
	}

	/**
	 * # Reviews Tab Pagination.
	 */
	function pagination() {

		// Get Page Offset.
		$offset = ( $_POST['page'] - 1 ) *  $_POST['per_page'];

		// Pagination Args
		$args = array(
			'pagination' => true,
			'offset'  	=> $offset,
			'page'   	=> $_POST['page'],
			'base' 	  	=> $_POST['base'],
			'per_page'  => $_POST['per_page'],
			'user_id' 	=> $_POST['user_id'],
		);

		// Get Content
		yz_get_user_reviews( $args );

	    die();

	}

	/**
	 * Author Box - Display Ratings
	 */
	function author_box_ratings( $args = null ) {

	    // Get ratings visibility
	    $show_ratings = yz_option( 'yz_enable_author_box_ratings', 'on' );

	    if ( 'off' == $show_ratings ) {
	        return;
	    }

	    ?>

	    <div class="yzb-user-ratings">
	    	<?php yz_get_ratings_details( $args ); ?>
	    </div>

	    <?php
	}

	/**
	 * Posts Tools Function
	 */
	function get_review_tools( $review = null ) {

		// Get Activity ID.
		$review_id = $review['id'];

		// Get Tools Data.
		$tools = array();

		// Filter.
		$tools = apply_filters( 'yz_review_tools', $tools, $review );

		if ( empty( $tools ) ) {
			return false;
		}

		?>

		<div class="yz-show-item-tools"><i class="fas fa-ellipsis-v"></i></div>
		<div class="yz-item-tools" data-review-id="<?php echo $review_id; ?>" data-user-id="<?php echo $review['reviewer']; ?>">
			<?php foreach ( $tools as $tool ) : ?>
				<?php $attributes = isset( $tool['attributes'] ) ? $tool['attributes'] : null; ?>
				<div class="yz-item-tool <?php echo yz_generate_class( $tool['class'] ); ?>" <?php yz_get_item_attributes( $attributes ); ?> <?php if ( isset( $tool['action'] ) ) { echo 'data-action="' . $tool['action'] .'"'; } ?>>
					<div class="yz-tool-icon"><i class="<?php echo $tool['icon'] ?>"></i></div>
					<div class="yz-tool-name"><?php echo $tool['title']; ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Add New Review Tool.
	 */
	function add_review_tools( $tools, $review ) {

		if ( yz_is_user_can_edit_reviews( $review ) ) {

			// Get Tool Data.
			$tools[] = array(
				'icon' => 'fas fa-edit',
				'title' => __( 'Edit', 'youzer' ),
				'action' => 'edit',
				'class' => array( 'yz-review-tool', 'yz-edit-tool' )
			);

		}

		if ( yz_is_user_can_delete_reviews() ) {

			// Get Tool Data.
			$tools[] = array(
				'icon'   => 'fas fa-trash',
				'title'  => __( 'Delete', 'youzer' ),
				'action' => 'delete',
				'class' => array( 'yz-review-tool', 'yz-delete-tool' )
			);

		}

		return $tools;
	}

	/**
	 * Add Members Directory Cards Ratings.
	 */
	function add_members_directory_cards_ratings() {

		if ( ! bp_is_members_directory() ) {
			return false;
		}

	    // Get User id.
	    $user_id = bp_get_member_user_id();

		// Get User ID.
		yz_get_ratings_details( array( 'user_id' => $user_id ) );

	}

	/**
	 * Get Statistics Value
	 */
	function get_ratings_statistics_values( $value, $user_id, $type ) {

		switch ( $type ) {
			case 'ratings':
				return youzer()->reviews->query->get_user_reviews_count( $user_id );
				break;

			default:
				return $value;
				break;
		}

	}

}