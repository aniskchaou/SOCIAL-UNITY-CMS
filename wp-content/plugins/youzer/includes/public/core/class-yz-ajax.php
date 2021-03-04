<?php

class Youzer_Ajax {

	function __construct() {

		// Call Edit Form Modal.
		add_action( 'wp_ajax_yz_get_share_activity_form', array( $this, 'get_share_activity_form' ) );

		// Posts - Ajax Pagination
		add_action( 'wp_ajax_nopriv_pages_pagination', array( $this, 'posts_pagination' ) );
		add_action( 'wp_ajax_pages_pagination', array( $this, 'posts_pagination' ) );

		// Comments - Ajax Pagination
		add_action( 'wp_ajax_nopriv_comments_pagination', array( $this, 'comments_pagination' ) );
		add_action( 'wp_ajax_comments_pagination', array( $this, 'comments_pagination' ) );

		// Handle Account Verification.
		add_action( 'wp_ajax_yz_handle_account_verification',  array( $this, 'handle_verification' ) );

		// Add Activity.
		add_action( 'wp_ajax_yz_get_activity_tools',  array( $this, 'get_activity_tools' ) );

    	// Handle Sticky Posts.
		add_action( 'wp_ajax_yz_handle_sticky_posts',  array( $this, 'handle_sticky_posts' ) );
		add_action( 'wp_ajax_yz_handle_posts_bookmark',  array( $this, 'handle_posts_bookmark' ) );

		// Bookmarks Ajax.
        // add_filter( 'bp_after_has_activities_parse_args', 'yz_set_user_bookmarks_query' );

		add_action( 'wp_ajax_yz_unlink_provider_account',  array( $this, 'unlink_instagram_provider_account' ) );

	}

	/**
	 * Get Share Activity Form.
	 */
	function get_share_activity_form() {

 		// Check Nonce Security
 		check_ajax_referer( 'youzer-nonce', 'yz_share_activity_nonce' );

		// Get Activity ID.
		$activity_id = isset( $_POST['activity_id'] ) ? (int) $_POST['activity_id'] : false;

		if ( ! $activity_id ) {
			die( json_encode( array( 'remove_button' => true, 'error' => __( 'Nothing found!', 'youzer' ) ) ) );
		}

		// Get activity ID.
		$activity = new BP_Activity_Activity( $activity_id );

		// Get Activity ID for a shared post.
		if ( apply_filters( 'yz_disable_sharing_shared_posts', true ) ) {
			if ( $activity->type == 'activity_share' ) {
				$activity_id = $activity->secondary_item_id;
			}
		}

	    // Args
	    $modal_args = array(
	    	'title' => __( 'Share Activity', 'youzer' ),
	    	'title_icon' => 'far fa-share-square',
	        'modal_type' => 'div',
	        'hide-action' => true,
	        'show_close' => false,
	        'id'        => 'yz-share-activity-form',
	        'button_id' => 'yz-share-activity',
	    );

	    // Get User Share Form.
		ob_start();
	    yz_modal( $modal_args, array( $this, 'form_content' ) );
	    $form = ob_get_contents();
	    ob_end_clean();

	    // Get User Post Preview.
		ob_start();

		global $wp_embed;

		if ( bp_use_embed_in_activity() ) {
			add_filter( 'bp_get_activity_content_body', array( $wp_embed, 'autoembed' ), 8 );
		}

	    yz_activity()->get_wall_shared_post( $activity_id );

	    $preview = ob_get_contents();

	    ob_end_clean();

	    // Send Result.
		wp_send_json_success( array( 'activity_id' => $activity_id, 'form' => $form, 'posts_emojis' => yz_option( 'yz_enable_posts_emoji', 'on' ), 'preview' => $preview, 'show_all' => '<div class="yz-show-all-less"><div class="yz-show-all"><i class="fas fa-arrow-down"></i>' . __( 'Show All', 'youzer' ) . '</div><div class="yz-collapse"><i class="fas fa-arrow-up"></i>' . __( 'Collapse', 'youzer' ) . '</div></div>' ) );

	}

	/**
	 * Form Content
	 */
	function form_content() {

		// Limit Form Post Type.
		// add_filter( 'yz_wall_form_post_types_buttons', array( $this, 'set_form_activity_type' ) );

		// Add Form Custom Fields.
		// add_action( 'bp_activity_post_form_options', array( $this,'add_edit_form_fields' ) );
		add_action( 'yz_wall_before_submit_form_action', array( $this, 'post_in_button' )  );

		?>

		<div id="youzer-share-activity-wrapper">
			<?php bp_get_template_part( 'activity/post-form' ); ?>
		</div>

		<?php

		// Limit Form Post Type.
		// remove_filter( 'yz_wall_form_post_types_buttons', array( $this, 'set_form_activity_type' ) );

	}

	/**
	 * Add Post in Button
	 */
	function post_in_button() {

		if ( ! bp_is_active( 'groups' ) || ( ! bp_is_my_profile() && ! bp_is_group() ) ) {
			return;
		}

		$show_all_options = true;

		if ( bp_is_group() ) {
			$group = groups_get_group( array( 'group_id' => bp_get_current_group_id() ) );
			if ( $group->status != 'public' ) {
				$show_all_options = false;
			}
		}

		if ( ! $show_all_options ) {
			echo '<style type="text/css">#whats-new-post-in-box select, #whats-new-post-in-box .nice-select {pointer-events: none; } #whats-new-post-in-box .nice-select:after {display:none;}</style>';
		}

		?>

		<div id="whats-new-post-in-box">

			<label for="whats-new-post-in" ><?php _e( 'Post in:', 'youzer' ); ?></label>
			<select id="whats-new-post-in" name="whats-new-post-in">
				<?php if ( $show_all_options ) : ?>
				<option selected="selected" value="0"><?php _e( 'My Profile', 'youzer' ); ?></option>

				<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
					while ( bp_groups() ) : bp_the_group(); ?>

						<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

					<?php endwhile;
				endif; ?>
				<?php else: ?>
					<option value="<?php bp_current_group_id(); ?>"><?php bp_current_group_name(); ?></option>
				<?php endif; ?>
			</select>
		</div>
		<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">

		<?php
	}

	/**
	 * Unlink Provider Account.
	 */
	function unlink_instagram_provider_account() {

	    // Hook.
	    do_action( 'yz_before_account_unlink_provider' );

	    // Check Ajax Referer.
	    check_ajax_referer( 'yz-unlink-provider-account', 'security' );

	    // Get Data.
	    $data = $_POST;

	    // Get User ID.
	    $user_id = bp_displayed_user_id();

	    // Get Data.
	    $provider = isset( $_POST['provider'] ) ? $_POST['provider'] : null;

	    // Get Access Token ID.
	    $option_id = 'wg_' . $provider . '_account_token';

	    // Delete Token.
	    $delete_token = delete_user_meta( $user_id, $option_id );

	    if ( $delete_token ) {

	        // Delete Account infos.
	        delete_user_meta( $user_id, 'wg_' . $provider . '_account_user_data' );

	        $data['action'] = 'done';
	        $data['msg'] = __( 'User account is unlinked successfully', 'youzer' );

	        do_action( 'yz_after_unlinking_provider_account', $user_id, $provider );

	    } else {

	        $data['error'] = __( "We couldn't unlink the account, please try again!", 'youzer' );

	    }

	    die( json_encode( $data ) );

	}

	/**
	 * Posts Tools Function
	 */
	function get_activity_tools() {

		do_action( 'yz_before_get_activity_tools' );

		// Get Activity ID.
		$activity_id = $_POST['activity_id'];

		// Filter.
		$tools = apply_filters( 'yz_activity_tools', array(), $activity_id );

		if ( empty( $tools ) ) {
			wp_send_json_error();
		}

		ob_start();

		?>

		<div class="yz-item-tools yz-activity-tools" data-activity-id="<?php echo $activity_id; ?>">
			<?php foreach ( $tools as $tool ) : ?>
				<?php $attributes = isset( $tool['attributes'] ) ? $tool['attributes'] : null; ?>
				<div class="yz-item-tool <?php echo yz_generate_class( $tool['class'] ); ?>" <?php yz_get_item_attributes( $attributes ); ?> <?php if ( isset( $tool['action'] ) ) { echo 'data-action="' . $tool['action'] .'"'; } ?>>
					<div class="yz-tool-icon"><i class="<?php echo $tool['icon'] ?>"></i></div>
					<div class="yz-tool-name"><?php echo $tool['title']; ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php

		$content = ob_get_clean();

		wp_send_json_success( $content );

		die();

	}

	/**
	 * # Posts Tab Pagination.
	 */
	function posts_pagination() {

        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-posts.php';

		// Get Profile User ID
	    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

	    // Pagination Args
		$args = array(
			'order' 		 => 'DESC',
			'post_status'	 => 'publish',
			'base' 		 	 => $_POST['base'],
			'paged' 		 => $_POST['page'],
			'author' 		 => $query_vars['yz_user'],
			'posts_per_page' => yz_option( 'yz_profile_posts_per_page', 5 )
		);

		$posts_tab = new YZ_Posts_Tab();

		// Get Posts Core
		$posts_tab->posts_core( $args );

	    die();

	}

	/**
	 * # Comments Tab Pagination.
	 */
	function comments_pagination() {

        require_once YZ_PUBLIC_CORE . 'tabs/yz-tab-comments.php';

		// Get Page Number.
		$cpage = $_POST['page'];

		// Get Profile User ID
	    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );

		// Get Data.
		$commentsNbr = yz_option( 'yz_profile_comments_nbr', 5 );
		$offset 	 = ( $cpage - 1 ) * $commentsNbr;

		// Pagination Args
		$args = array(
			'paged'   => $cpage,
			'offset'  => $offset,
			'number'  => $commentsNbr,
			'base' 	  => $_POST['base'],
			'user_id' => $query_vars['yz_user'],
		);

		$comments = new YZ_Comments_Tab();

		// Get Comments Core
		$comments->comments_core( $args );

	    die();
	}

	/**
	 * Change Default Upload Directory to the Youzer Directory.
	 */
	// function youzer_upload_directory( $dir ) {

	// 	global $YZ_upload_folder, $YZ_upload_url, $YZ_upload_dir;

	//     return array(
	//         'path'   => $YZ_upload_dir,
	//         'url'    => $YZ_upload_url,
	//         'subdir' => '/' . $YZ_upload_folder,
	//     ) + $dir;

	// }


	/**
	 * Handle Account Verification.
	 */
	function handle_verification( $user_id ) {

		// Hook.
		do_action( 'yz_before_handle_account_verification' );

		if ( ! yz_is_user_can_verify_accounts() || ! is_user_logged_in() ) {
			$data['error'] = $this->msg( 'invalid_role' );
			die( json_encode( $data ) );
		}

		// Get Data.
		$data = $_POST;

		// Allowed Actions
		$allowed_actions = array( 'verify', 'unverify' );

		// Get User ID.
		$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : null;

		if ( empty( $user_id ) ) {
			$data['error'] = $this->msg( 'invalid_user_id' );
			die( json_encode( $data ) );
		}

		check_ajax_referer( 'yz-account-verification-' . $user_id, 'security' );

		// Get Action
		$action = isset( $_POST['verification_action'] ) ? $_POST['verification_action'] : null;

		if ( ! in_array( $action, $allowed_actions ) ) {
			$data['error'] = $this->msg( 'invalid_action' );
			die( json_encode( $data ) );
		}

		if ( 'verify' == $action ) {
			// Mark Account As Verified.
			update_user_meta( $user_id, 'yz_account_verified', 'on' );
			$data['action'] = 'unverify';
			$data['msg'] = __( 'Account marked as verified successfully', 'youzer' );
			do_action('yz_after_verifying_account', $user_id );
		} elseif ( 'unverify' == $action ) {
			// Mark Account As Unverified.
			update_user_meta( $user_id, 'yz_account_verified', 'off' );
			$data['action'] = 'verify';
			$data['msg'] = __( 'Account marked as unverified successfully', 'youzer' );
			do_action('yz_after_unverifying_account', $user_id );
		}

		$data['verify_account'] = __( 'Verify Account', 'youzer' );
        $data['unverify_account'] = __( 'Unverify Account', 'youzer' );

		die( json_encode( $data ) );

	}

	/**
	 * Handle Sticky Posts
	 */
	function handle_sticky_posts() {

		// Hook.
		do_action( 'yz_before_handle_sticky_posts' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzer-nonce', 'security' );

		if ( ! is_user_logged_in() ) {
			$data['error'] = __( 'The action you have requested is not allowed.', 'youzer' );
			die( json_encode( $data ) );
		}

		// Get Data.
		$data = $_POST;

		// Allowed Actions
		$allowed_actions = array( 'pin', 'unpin' );

		// Get Data.
		$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		$action = isset( $_POST['operation'] ) ? $_POST['operation'] : null;
		$component = isset( $_POST['component'] ) ? $_POST['component'] : null;
		// $group_id = isset( $_POST['group_id'] ) ? $_POST['group_id'] : null;

		// Check if The Post ID & The Component are Exist.
		if ( empty( $post_id ) || empty( $component ) ) {
			$data['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzer' );
			die( json_encode( $data ) );
		}

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$data['error'] = __( 'The action you have requested does not exist.', 'youzer' );
			die( json_encode( $data ) );
		}

		// Get All Sticky Posts.
		$sticky_posts = yz_option( 'yz_' . $component . '_sticky_posts' );

		// Add the new pinned post.

		if ( 'pin' == $action ) {

			// Pin Activity.
			// yz_add_sticky_post( $component, $post_id, $group_id );

			if ( 'groups' == $component ) {

				// Get Activity.
				$activity = new BP_Activity_Activity( $post_id );

				$sticky_posts[ $activity->item_id ][] = $post_id;

			} elseif ( 'activity' == $component ) {
				$sticky_posts[] = $post_id;
			}

			$data['action'] = 'unpin';
			$data['msg'] = __( 'The activity was pinned successfully', 'youzer' );

		} elseif ( 'unpin' == $action ) {

			// Unpin Activity.
			// yz_delete_sticky_post( $component, $post_id, $group_id );

			if ( 'groups' == $component ) {

				// Get Activity.
				$activity = new BP_Activity_Activity( $post_id );

				// Get Removed Post Key.
				$post_key = array_search( $post_id, $sticky_posts[ $activity->item_id ] );

				// Remove Post.
				if ( isset( $sticky_posts[ $activity->item_id ][ $post_key ] ) ) {
					unset( $sticky_posts[ $activity->item_id ][ $post_key ] );
				}

			} elseif ( 'activity' == $component ) {

				// Get Removed Post Key.
				$post_key = array_search( $post_id, $sticky_posts );

				// Remove Post.
				if ( isset( $sticky_posts[ $post_key ] ) ) {
					unset( $sticky_posts[ $post_key ] );
				}

			}

			$data['action'] = 'pin';
			$data['msg'] = __( 'The activity is unpinned successfully', 'youzer' );
		}

		// Update Sticky Posts.
		update_option( 'yz_' . $component . '_sticky_posts', $sticky_posts, 'no' );

		// Add Pin/Unpin Strings.
		$data['pin'] = __( 'Pin', 'youzer' );
		$data['unpin'] = __( 'Unpin', 'youzer' );

		die( json_encode( $data ) );

	}

	/**
	 * Handle Posts Bookmark
	 */
	function handle_posts_bookmark() {

		// Hook.
		do_action( 'yz_before_handle_bookmark_posts' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzer-nonce', 'security' );

		if ( ! is_user_logged_in() ) {
			$response['error'] = __( 'The action you have requested is not allowed.', 'youzer' );
			die( json_encode( $response ) );
		}

		// Allowed Actions
		$allowed_actions = array( 'save', 'unsave' );

		// Get Table Data.
		$data = array(
			'user_id' => bp_loggedin_user_id(),
			'item_id' => isset( $_POST['item_id'] ) ? $_POST['item_id'] : null,
			'item_type' => isset( $_POST['item_type'] ) ? $_POST['item_type'] : null,
			'collection_id' => isset( $_POST['collection_id'] ) ? $_POST['collection_id'] : '0'
		);

		// Get Data.
		$action = isset( $_POST['operation'] ) ? $_POST['operation'] : null;

		// Check if The Post ID & The Component are Exist.
		if ( empty( $data['item_id'] ) || empty( $data['item_type'] ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzer' );
			die( json_encode( $response ) );
		}

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$response['error'] = __( 'The action you have requested does not exist.', 'youzer' );
			die( json_encode( $response ) );
		}

		// Check if user Already Bookmarked Post ( Returns ID ).
		$bookmark_id = yz_get_bookmark_id( $data['user_id'], $data['item_id'], $data['item_type'] );

		global $wpdb, $Yz_bookmark_table;

		if ( 'save' == $action ) {

			// Check is post already bookmarked !
			if ( $bookmark_id ) {
				$response['error'] = __( 'This item is already bookmarked.', 'youzer' );
				die( json_encode( $response ) );
			}

			// Get Current Time.
			$data['time'] = bp_core_current_time();

			// Insert Post.
			$result = $wpdb->insert( $Yz_bookmark_table, $data );

			if ( $result ) {
				do_action( 'yz_after_bookmark_save', $wpdb->insert_id, $data['user_id'] );
			}

			$response['action'] = 'unsave';
			$response['msg'] = __( 'The item was bookmarked successfully', 'youzer' );

		} elseif ( 'unsave' == $action ) {

			// Hook.
			do_action( 'yz_before_bookmark_delete', $bookmark_id, $data['user_id'] );

			$delete_bookmark = $wpdb->delete( $Yz_bookmark_table, array( 'id' => $bookmark_id ), array( '%d' ) );

			$response['action'] = 'save';
			$response['msg'] = __( 'The bookmark was removed successfully', 'youzer' );
		}

		// Delete Transient.
	    delete_transient( 'yz_user_bookmarks_' . $data['user_id'] );

		$response['unsave_post'] = __( 'Remove Bookmark', 'youzer' );
		$response['save_post'] = __( 'Bookmark', 'youzer' );

		die( json_encode( $response ) );

	}


    /**
     * Get Error Message.
     */
    function msg( $code ) {

        // Messages
        switch ( $code ) {

            case 'invalid_role':
                return __( 'The action you have requested is not allowed.', 'youzer' );

            case 'invalid_action':
                return __( 'The action you have requested is not exit.', 'youzer' );

            case 'invalid_user_id':
                return __( 'User id was not found, please try again later.', 'youzer' );
        }

        return __( 'An unknown error occurred. Please try again later.', 'youzer' );
    }

}

// Init Class.
$ajax = new Youzer_Ajax();
