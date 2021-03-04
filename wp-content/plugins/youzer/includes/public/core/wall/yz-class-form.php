<?php

/**
 * Wall Form
 */
class Youzer_Wall_Form {

	function __construct() {

		// Add Action Tools.
		add_action( 'yz_activity_form_post_types', array( $this, 'post_types_buttons' ) );

		// Handle Save Form Post - Normal Request.
		add_action( 'bp_actions', array( $this, 'action_post_update' ) );

		// Handle Save Form Post - Ajax Request.
		add_action( 'wp_ajax_yz_post_update', array( $this, 'legacy_theme_post_update' ) );

		// Validate Wall Form
		add_action( 'yz_before_adding_wall_post', array( $this, 'validate' ), 10, 2 );

		// Save Activity Meta.
		add_action( 'yz_after_adding_wall_post', array( $this, 'save_meta' ), 10, 2 );

		// Save Activity Liver Preview Url.
		add_action( 'yz_after_adding_wall_post', array( $this, 'save_live_preview' ), 10, 2 );

		// Moderate Comment Content.
		add_action( 'bp_activity_before_save', array( $this, 'moderate_post' ), 10 );

	}

	/**
	 * Wall Form Post Types Options.
	 */
	function post_types_buttons() {

		$checked = true;

		$post_types = apply_filters( 'yz_wall_form_post_types_buttons',
			array(
				'activity_status' => array(
					'uploader' => 'off',
					'icon' 	=> 'fas fa-comment-dots',
					'name'  => __( 'Status', 'youzer' ),
				),
				'activity_photo' => array(
					'icon' 	=> 'fas fa-camera-retro',
					'uploader' => 'on',
					'name'  => __( 'Photo', 'youzer' ),
				),
				'activity_slideshow' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-film',
					'name'  => __( 'Slideshow', 'youzer' ),
				),
				'activity_quote' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-quote-right',
					'name'  => __( 'Quote', 'youzer' ),
				),
				'activity_giphy' => array(
					'uploader' => 'off',
					'icon' 	=> 'fas fa-images',
					'name'  => __( 'GIF', 'youzer' ),
				),
				'activity_file' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-cloud-download-alt',
					'name'  => __( 'File', 'youzer' ),
				),
				'activity_video' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-video',
					'name'  => __( 'Video', 'youzer' ),
				),
				'activity_audio' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-volume-up',
					'name'  => __( 'Audio', 'youzer' ),
				),
				'activity_link' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-link',
					'name'  => __( 'Link', 'youzer' ),
				)
			)
		);

		// Get Unallowed Activities.
		$unallowed_activities = yz_option( 'yz_unallowed_activities' );

		if ( ! empty( $unallowed_activities ) ) {
			$unallowed_activities = (array) array_flip( $unallowed_activities );
		}

		$count = count( $post_types );

		foreach ( $post_types as $post_type => $data ) :

			if ( isset( $unallowed_activities[ $post_type ] ) ) {
				$count--;
				continue;
			} ?>

			<div class="yz-wall-opts-item">
				<input type="radio" value="<?php echo $post_type; ?>" name="post_type" id="yz-wall-add-<?php echo $post_type; ?>" <?php if ( $checked ) echo 'checked'; ?> data-uploader="<?php echo $data['uploader']; ?>">
				<label class="yz-wall-add-<?php echo $post_type; ?>" for="yz-wall-add-<?php echo $post_type; ?>">
					<i class="<?php echo $data['icon']; ?>"></i><span><?php echo $data['name']; ?></span>
				</label>
			</div>

			<?php $checked = false; ?>

		<?php endforeach;

		// After Printing Buttons.
		do_action( 'yz_wall_form_post_types' );

		if ( $count > 5 ) : ?>
			<div class="yz-wall-opts-item yz-wall-opts-show-all"><label class="yzw-form-show-all"><i class="fas fa-ellipsis-h"></i></label></div>
		<?php endif;

	}

	/**
	 * Post user/group activity update.
	 */
	function action_post_update() {

		// Do not proceed if user is not logged in, not viewing activity, or not posting.
		if ( ! is_user_logged_in() || ! bp_is_activity_component() || ! bp_is_current_action( 'post' ) ) {
			return false;
		}

		do_action( 'yz_before_wall_post_update' );

		// Check the nonce.
		check_admin_referer( 'yz_post_update', '_yz_wpnonce_post_update' );

		// Init Vars.
		$post_type = sanitize_text_field( $_POST['post_type'] );

		/**
		 * Filters the content provided in the activity input field.
		 */
		$content = apply_filters( 'yz_bp_activity_post_update_content', $_POST['status'] );

		if ( ! empty( $_POST['whats-new-post-object'] ) ) {

			/**
			 * Filters the item type that the activity update should be associated with.
			 *
			 * @since 1.2.0
			 *
			 * @param string $value Item type to associate with.
			 */
			$object = apply_filters( 'bp_activity_post_update_object', $_POST['whats-new-post-object'] );
		}

		if ( ! empty( $_POST['whats-new-post-in'] ) ) {

			/**
			 * Filters what component the activity is being to.
			 *
			 * @since 1.2.0
			 *
			 * @param string $value Chosen component to post activity to.
			 */
			$item_id = apply_filters( 'bp_activity_post_update_item_id', $_POST['whats-new-post-in'] );
		}

		do_action( 'yz_before_adding_wall_post', $_POST );

		// No existing item_id.
		if ( empty( $item_id ) ) {

			$activity_id = $this->activity_post_update( array(
				'content' => $content,
				'type'    => $post_type,
			) );

		// Post to groups object.
		} elseif ( 'groups' == $object && bp_is_active( 'groups' ) ) {
			if ( (int) $item_id ) {
				$activity_id = $this->groups_post_update(
					array(
						'content' => $content,
						'group_id' => $item_id,
						'type' => $post_type
						)
				);
			}
		} else {

			/**
			 * Filters activity object for BuddyPress core and plugin authors before posting activity update.
			 *
			 * @since 1.2.0
			 *
			 * @param string $object  Activity item being associated to.
			 * @param string $item_id Component ID being posted to.
			 * @param string $content Activity content being posted.
			 */
			$activity_id = apply_filters( 'bp_activity_custom_update', $object, $item_id, $content );
		}

		do_action( 'yz_after_adding_wall_post', $activity_id, $_POST );

		// Provide user feedback.
		if ( ! empty( $activity_id ) ) {
			bp_core_add_message( __( 'Update Posted!', 'youzer' ) );
		} else {
			bp_core_add_message( __( 'There was an error when posting your update. Please try again.', 'youzer' ), 'error' );
		}

		// Redirect.
		bp_core_redirect( wp_get_referer() );
	}

	/**
	 * Processes Activity updates received via a POST request.
	 *
	 */
	function legacy_theme_post_update() {

		$bp = buddypress();

		if ( ! bp_is_post_request() ) {
			return;
		}

		do_action( 'yz_before_adding_wall_post', $_POST, true );

		// Check the nonce.
		check_admin_referer( 'yz_post_update', '_yz_wpnonce_post_update' );

		/**
		 * Filters the content provided in the activity input field.
		 */
		$content = apply_filters( 'yz_bp_activity_post_update_content', $_POST['status'] );

		$activity_id = 0;
		$item_id     = 0;
		$object      = '';


		// Try to get the item id from posted variables.
		if ( ! empty( $_POST['item_id'] ) ) {
			$item_id = (int) $_POST['item_id'];
		}

		// Try to get the object from posted variables.
		if ( ! empty( $_POST['object'] ) ) {
			$object  = sanitize_key( $_POST['object'] );

		// If the object is not set and we're in a group, set the item id and the object
		} elseif ( bp_is_group() ) {
			$item_id = bp_get_current_group_id();
			$object = 'groups';
		}

		if ( ! $object && bp_is_active( 'activity' ) ) {

			$activity_id = $this->activity_post_update( array(
				'content' => $content,
				'type'    => $_POST['post_type'],
			) );

		} elseif ( 'groups' === $object ) {

			if ( $item_id && bp_is_active( 'groups' ) ) {

				$activity_id = $this->groups_post_update(
					array(
						'content' => $content,
						'group_id' => $item_id,
						'type' => $_POST['post_type']
					)
				);

			}

		} else {

			/** This filter is documented in bp-activity/bp-activity-actions.php */
			$activity_id = apply_filters( 'bp_activity_custom_update', false, $object, $item_id, $_POST['content'] );
		}

		do_action( 'yz_after_adding_wall_post', $activity_id, $_POST );

		if ( false === $activity_id ) {
			$error_msg = __( 'There was a problem posting your update. Please try again.', 'youzer' );
			yz_die( $error_msg );
		} elseif ( is_wp_error( $activity_id ) && $activity_id->get_error_code() ) {
			$error_msg = $activity_id->get_error_message();
			yz_die( $error_msg );
		}

		$last_recorded = ! empty( $_POST['since'] ) ? date( 'Y-m-d H:i:s', intval( $_POST['since'] ) ) : 0;
		if ( $last_recorded ) {
			$activity_args = array( 'since' => $last_recorded );
			$bp->activity->last_recorded = $last_recorded;
			add_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10, 1 );
		} else {
			$activity_args = array( 'include' => $activity_id );
		}

		// Remove Effect Class.
		remove_filter( 'bp_get_activity_css_class', 'yz_add_activity_css_class' );

		if ( bp_has_activities ( $activity_args ) ) {
			while ( bp_activities() ) {
				bp_the_activity();
				bp_get_template_part( 'activity/entry' );
			}
		}

		if ( ! empty( $last_recorded ) ) {
			remove_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10 );
		}

		exit;
	}

	/**
	 * Post an activity update.
	 */
	function activity_post_update( $args = '' ) {

		$r = wp_parse_args( $args, array(
			'content'    => false,
			'type'    	 => 'activity_update',
			'user_id'    => bp_loggedin_user_id(),
			'error_type' => 'bool',
		) );

		if ( bp_is_user_inactive( $r['user_id'] ) ) {
			return false;
		}

		// Record this on the user's profile.
		$activity_content = $r['content'];
		$primary_link     = bp_core_get_userlink( $r['user_id'], false, true );

		/**
		 * Filters the new activity content for current activity item.
		 */
		$add_content = apply_filters( 'bp_activity_new_update_content', $activity_content );

		/**
		 * Filters the activity primary link for current activity item.
		 */
		$add_primary_link = apply_filters( 'yz_activity_new_update_primary_link', $primary_link );

		$activity_args = array(
			'user_id'      => $r['user_id'],
			'content'      => $add_content,
			'primary_link' => $add_primary_link,
			'component'    => buddypress()->activity->id,
			'type'         => $r['type'],
			'error_type'   => $r['error_type']
		);

		if ( isset( $_POST['secondary_item_id'] ) && ! empty( $_POST['secondary_item_id'] ) ) {
			$activity_args['secondary_item_id'] = $_POST['secondary_item_id'];
		}

		// Now write the values.
		$activity_id = bp_activity_add( $activity_args );

		// Bail on failure.
		if ( false === $activity_id || is_wp_error( $activity_id ) ) {
			return $activity_id;
		}

		/**
		 * Filters the latest update content for the activity item.
		 */
		$activity_content = apply_filters( 'yz_activity_latest_update_content', $r['content'], $activity_content );

		// Add this update to the "latest update" usermeta so it can be fetched anywhere.
		bp_update_user_meta( bp_loggedin_user_id(), 'bp_latest_update', array(
			'id'      => $activity_id,
			'content' => $activity_content
		) );

		/**
		 * Fires at the end of an activity post update, before returning the updated activity item ID.
		 *
		 */
		do_action( 'yz_activity_posted_update', $r['content'], $r['user_id'], $activity_id );
		do_action( 'bp_activity_posted_update', $r['content'], $r['user_id'], $activity_id );

		return $activity_id;
	}

	/**
	 * Post an Activity status update affiliated with a group.
	 */
	function groups_post_update( $args = '' ) {

		if ( ! bp_is_active( 'activity' ) ) {
			return false;
		}

		$bp = buddypress();

		$defaults = array(
			'content'    => false,
			'type'    	 => 'activity_update',
			'user_id'    => bp_loggedin_user_id(),
			'group_id'   => 0,
			'error_type' => 'bool'
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		if ( empty( $group_id ) && !empty( $bp->groups->current_group->id ) )
			$group_id = $bp->groups->current_group->id;

		if ( empty( $user_id ) || empty( $group_id ) )
			return false;

		$bp->groups->current_group = groups_get_group( $group_id );

		// Be sure the user is a member of the group before posting.
		if ( ! bp_current_user_can( 'bp_moderate' ) && ! groups_is_user_member( $user_id, $group_id ) ) {
			return false;
		}

		// Record this in activity streams.
		$activity_action  = sprintf( __( '%1$s posted an update in the group %2$s', 'youzer' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_permalink( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );
		$activity_content = $content;

		/**
		 * Filters the action for the new group activity update.
		 */
		$action = apply_filters( 'yz_groups_activity_new_update_action',  $activity_action, $user_id, $group_id );

		/**
		 * Filters the content for the new group activity update.
		 */
		$content_filtered = apply_filters( 'yz_groups_activity_new_update_content', $activity_content );

		$activity_args = array(
			'user_id'    => $user_id,
			'action'     => $action,
			'content'    => $content_filtered,
			'type'       => $r['type'],
			'item_id'    => $group_id,
			'error_type' => $error_type
		);

		if ( isset( $_POST['secondary_item_id'] ) && ! empty( $_POST['secondary_item_id'] ) ) {
			$activity_args['secondary_item_id'] = $_POST['secondary_item_id'];
		}

		$activity_id = groups_record_activity( $activity_args );

		groups_update_groupmeta( $group_id, 'last_activity', bp_core_current_time() );

		/**
		 * Fires after posting of an Activity status update affiliated with a group.
		 */
		do_action( 'yz_groups_posted_update', $content, $user_id, $group_id, $activity_id );
		do_action( 'bp_groups_posted_update', $content, $user_id, $group_id, $activity_id );

		return $activity_id;
	}

	/**
	 * Save Activity Meta
	 */
	function save_meta( $activity_id, $data ) {

		if ( isset( $data['yz-activity-privacy'] ) && ! empty( $data['yz-activity-privacy'] ) ) {

			if ( isset( $data['object'] ) && $data['object'] == 'groups' ) {
				$data['yz-activity-privacy'] = 'public';
				$_POST['yz-activity-privacy'] = 'public';
			}

			// Save Post Privacy.
			$this->save_privacy( $activity_id, $data['yz-activity-privacy'] );

		}

		// Save Post Tagged Users.
		if ( isset( $data['tagged_users'] ) && ! empty( $data['tagged_users'] ) ) {
			bp_activity_update_meta( $activity_id, 'tagged_users', $data['tagged_users'] );
			do_action( 'yz_after_activity_tagged_users_save', $activity_id, $data['tagged_users'] );
		}
		// Save Post Feeling / Activity.
		if ( isset( $data['mood_value'] ) && ! empty( $data['mood_value'] ) ) {
			bp_activity_update_meta( $activity_id, 'mood', array( 'type' => $data['mood_type'], 'value' => $data['mood_value'] ) );
		}

		if ( ! isset( $data['post_type'] ) ) {
			return;
		}

		switch ( $data['post_type'] ) {

			case 'activity_link':

				// Init Vars.
				$link_url = esc_url( $data['link_url'] );
				$link_desc = sanitize_textarea_field( $data['link_desc'] );
				$link_title = sanitize_text_field( trim( $data['link_title'] ) );

				// Save Data
				bp_activity_update_meta( $activity_id, 'yz-link-url', $link_url );
				bp_activity_update_meta( $activity_id, 'yz-link-desc', $link_desc );
				bp_activity_update_meta( $activity_id, 'yz-link-title', $link_title );

				break;

			case 'activity_quote':

				// Init Vars.
				$quote_text = sanitize_textarea_field( $data['quote_text'] );
				$quote_owner = sanitize_text_field( $data['quote_owner'] );

				// Save Data.
				bp_activity_update_meta( $activity_id, 'yz-quote-text', $quote_text );
				bp_activity_update_meta( $activity_id, 'yz-quote-owner', $quote_owner );

				break;


			case 'activity_giphy':

				if ( ! empty( $data['giphy_image'] ) ) {
					bp_activity_update_meta( $activity_id, 'giphy_image', $data['giphy_image'] );
				}

				break;
		}

	}

	/**
	 * Save Activity Privacy.
	 */
	function save_privacy( $activity_id, $privacy ) {

		global $wpdb, $bp;

		// Prepare SQL
		$sql = $wpdb->prepare( "UPDATE {$bp->activity->table_name} SET privacy = %s WHERE id = %d", $privacy, $activity_id );

		// Update Privacy
		$wpdb->query( $sql );

	}

	/**
	 * Save Activity Meta
	 */
	function save_live_preview( $activity_id, $data ) {

		if ( ! isset( $data['url_preview_link'] ) || empty( $data['url_preview_link'] ) ) {
			return;
		}

		// Check if use thumbnail
		$use_thumbnail = isset( $data['url_preview_use_thumbnail'] ) ? $data['url_preview_use_thumbnail'] : 'off';

		$url_preview_args = array(
			'use_thumbnail' => $use_thumbnail,
			'image' 		=> $data['url_preview_img'],
			'site'  		=> $data['url_preview_site'],
			'link'  		=> esc_url( $data['url_preview_link'] ),
			'description'   => stripslashes( esc_textarea( $data['url_preview_desc'] ) ),
			'title' 		=> stripslashes( sanitize_text_field( $data['url_preview_title'] ) )
		);

		// Serialize.
		$url_preview_data = base64_encode( serialize( $url_preview_args ) );

		// Save Url Data.
		bp_activity_update_meta( $activity_id, 'url_preview', $url_preview_data );

		do_action( 'yz_after_saving_activity_live_preview', $activity_id, $url_preview_args );

	}

	/**
	 * Validate Wall Form.
	 */
	function validate( $post, $is_ajax = false ) {

		// Get Vars.
		$post_type = sanitize_text_field( $post['post_type'] );
		$post_content = sanitize_text_field( $post['status'] );

		// Check Post Type.
		if ( apply_filters( 'yz_validate_wall_form_post_type', true ) ) {

			// Get Allowed Post Types.
			$allowed_post_types = apply_filters( 'yz_allowed_form_post_types', array( 'activity_status', 'activity_photo', 'activity_video' , 'activity_audio', 'activity_link', 'activity_slideshow','activity_file', 'activity_quote', 'activity_giphy', 'activity_share' ) );

			if ( ! in_array( $post_type, $allowed_post_types ) ) {
				$this->show_error( 'invalid_post_type' );
			}

		}

		// Check Attachments.
		if ( apply_filters( 'yz_validate_wall_form_attachments', true ) ) {
			// Get Attachments Post Types.
			$attachments_post_types = array( 'activity_photo', 'activity_video', 'activity_audio', 'activity_slideshow', 'activity_file' );

			if ( in_array( $post_type, $attachments_post_types ) && empty( $post['attachments_files'] )  ) {
				$this->show_error( 'no_attachments' );
			}

		}


		// Check if status is empty.
		if ( 'activity_status' == $post_type || 'activity_comment' == $post_type ) {

			if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'off' == yz_option( 'yz_enable_wall_url_preview', 'on' ) ) {
				$this->show_error( 'empty_status' );
			}

			if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'on' == yz_option( 'yz_enable_wall_url_preview', 'on' ) && empty( $_POST['url_preview_link'] ) ) {
				$this->show_error( 'empty_status' );
			}

		}

		if ( apply_filters( 'yz_validate_wall_form_slideshow', true ) ) {
			// Check Slideshow Post.
			if ( 'activity_slideshow' == $post_type && count( $post['attachments_files'] ) < 2 ) {
				$this->show_error( 'slideshow_need_images' );
			}
		}

		// Check Quote Post.
		if ( 'activity_quote' == $post_type ) {

			// Init Vars.
			$quote_text = sanitize_textarea_field( $post['quote_text'] );
			$quote_owner = sanitize_text_field( trim( $post['quote_owner'] ) );

			// Validate Quote Owner.
			if ( empty( $quote_owner ) ) {
				$this->show_error( 'empty_quote_owner' );
			}

			// Validate Quote text.
			if ( empty( $quote_text ) ) {
				$this->show_error( 'empty_quote_text' );
			}

		}

		// Check Link Post.
		if ( 'activity_link' == $post_type ) {

			// Init Vars.
			$link_url = esc_url( $post['link_url'] );
			$link_desc = sanitize_textarea_field( $post['link_desc'] );
			$link_title = sanitize_text_field( trim( $post['link_title'] ) );

			// Validate Link Url.
			if (  empty( $link_url ) ) {
				$this->show_error( 'empty_link_url' );
			}

			// Validate Link Url.
			if ( filter_var( $link_url, FILTER_VALIDATE_URL ) === false ) {
				$this->show_error( 'invalid_link_url' );
			}

			// Validate Link title.
			if ( empty( $link_title ) ) {
				$this->show_error( 'empty_link_title' );
			}

			// Validate Link Description.
			if ( empty( $link_desc ) ) {
				$this->show_error( 'empty_link_desc' );
			}
		}

		// Check Giphy Post.
		if ( 'activity_giphy' == $post_type ) {

			// Get Image.
			$giphy_image = isset( $post['giphy_image'] ) ? $post['giphy_image'] : '';

			// Check if image is empty.
			if ( empty( $giphy_image ) ) {
				$this->show_error( 'select_image' );
			}

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $giphy_image, PATHINFO_EXTENSION ) );

			// Check if image is gif.
			if ( 'gif' != $ext ) {
				$this->show_error( 'select_gif_image' );
			}

		}

	}

	/**
	 * Check for moderation keys and too many links.
	 *
	 */
	function moderate_post( $activity ) {

		// Bail if super admin is author.
		if ( is_super_admin( bp_loggedin_user_id() ) ) {
			return false;
		}

		/**
		 * Filters whether or not to bypass checking for moderation keys and too many links.
		 */
		if ( apply_filters( 'yz_bypass_check_for_moderation', false ) ) {
			return true;
		}

		// Check if type is under moderation or not.
		if ( ! in_array( $activity->type, $this->moderation_post_types() ) ) {
			return true;
		}

		// Define local variable(s).
		$match_out = '';

		// Check for black list words.
		if ( $this->check_for_blacklist_words( $activity->content ) ){

			if ( $activity->type == 'activity_comment' ) {
				exit( '-1<div id="message" class="error bp-ajax-message"><p>' . __( 'You have used an inappropriate word.', 'youzer' ) . '</p></div>');
			} else {
				$this->show_error( 'word_inappropriate' );
			}

		}

	}

	/**
	 * Check for black list words.
	 **/
	function check_for_blacklist_words( $content ) {

		if ( empty( $content ) ) {
			return false;
		}

		// Get the moderation keys.
		$blacklist_words = yz_option( 'yz_moderation_keys' );

		// Bail if blacklist is empty.
		if ( ! empty( $blacklist_words ) ) {

			// Get words separated by new lines.
			// $words = explode( "\n", $blacklist );

			// Loop through words.
			foreach ( $blacklist_words as $word ) {

				// Trim the whitespace from the word.
				$word = trim( $word );

				// Skip empty lines.
				if ( empty( $word ) ) {
					continue;
				}

				// Do some escaping magic so that '#' chars in the
				// spam words don't break things.
				$word    = preg_quote( $word, '#' );
				$pattern = "#$word#i";

				// Check each user data for current word.
				if ( preg_match( $pattern, $content ) ) {
					return true;
				}

			}
		}

		return false;
	}

	/**
	 * Set Moderation Post Types.
	 */
	function moderation_post_types() {

	    $types = array( 'activity_link', 'activity_file', 'activity_audio', 'activity_photo', 'activity_video', 'activity_quote', 'activity_giphy', 'activity_status', 'activity_update', 'activity_comment' );

	    return apply_filters( 'yz_moderation_post_types', $types );

	}

	/**
	 * Display Wall Error.
	 */
	function show_error( $code ) {

		if ( apply_filters( 'yz_show_wall_error', true, $code ) ) {
			if ( wp_doing_ajax() ) {
				yz_die( $this->msg( $code ) );
			} else {
			    // Get Reidrect page.
			    $redirect_to = ! empty( $redirect_to ) ? $redirect_to : wp_get_referer();

			    // Add Message.
			    bp_core_add_message( $this->msg( $code ), 'error' );

				// Redirect User.
		        wp_redirect( $redirect_to );
		        exit;
			}
		}

	}

    /**
     * Get Attachments Error Message.
     */
    public function msg( $code ) {

        // Messages
        switch ( $code ) {

            case 'empty_status':
                return __( "Please type some text before posting.", 'youzer' );

            case 'invalid_post_type':
                return __( "Invalid post type.", 'youzer' );

            case 'invalid_link_url':
                return __( "Invalid link url.", 'youzer' );

            case 'empty_link_url':
                return __( "Empty link url.", 'youzer' );

            case 'empty_link_title':
                return __( "Please fill the link title field.", 'youzer' );

            case 'empty_link_desc':
                return __( "Please fill the link description field.", 'youzer' );

            case 'empty_quote_owner':
                return __( "Please fill the quote owner field.", 'youzer' );

            case 'empty_quote_text':
                return __( "Please fill the quote text field.", 'youzer' );

            case 'word_inappropriate':
                return __( "You have used an inappropriate word.", 'youzer' );

            case 'no_attachments':
                return __( "No attachment was uploaded.", 'youzer' );

            case 'slideshow_need_images':
                return __( "Slideshows need at least 2 images to work.", 'youzer' );

            case 'select_image':
                return __( 'Please select an image image before posting.', 'youzer' );

            case 'select_gif_image':
                return __( 'Please select a GIF image.', 'youzer' );

    	    return __( 'An unknown error occurred. Please try again later.', 'youzer' );
    	}

	}

}

$form = new Youzer_Wall_Form();