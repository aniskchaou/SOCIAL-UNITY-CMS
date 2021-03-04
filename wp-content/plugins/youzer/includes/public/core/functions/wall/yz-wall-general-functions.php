<?php

/**
 * Wall- Status Action
 */
function yz_activity_action_wall_posts( $action, $activity ) {

	if ( $activity->component == 'gamipress' ) {
		return $action;
	}

	// Get User & Post Data.
	$post_link = yz_get_wall_post_url( $activity->id );
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Get Post Action.
	switch ( $activity->type ) {

		case 'activity_update':
		case 'activity_giphy':
		case 'activity_slideshow':
		case 'activity_status':
		case 'activity_quote':
		case 'activity_photo':
		case 'activity_video':
		case 'activity_audio':
		case 'activity_link':
		case 'activity_share':
		case 'activity_file':

			// Add Group Description.
			if ( yz_wall_is_group_post( $activity ) ) {
				$action =  sprintf( __( '%1s posted', 'youzer' ), $user_link );
			} else {
				$action = $user_link;
			}

			break;

		case 'new_cover':
			$action = sprintf(
				__( '%1s Changed their profile cover', 'youzer' ), $user_link );
			break;

	};


	$action = apply_filters( 'yz_activity_post_action_before_group_description', $action, $activity, $user_link, $post_link );

	// Add Group Description.
	$hide_group_description = array( 'joined_group', 'created_group', 'activity_update' );

	if (
		bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() &&
		! in_array( $activity->type, $hide_group_description ) ) {
		$group = groups_get_group( $activity->item_id );
		$action .= sprintf( __( ' in the group %1s', 'youzer' ), '<a href="' . bp_get_group_permalink( $group ) . '">' . esc_attr( $group->name ) . '</a>' );
	} else {
		$mood = apply_filters( 'yz_activity_post_mood', false, $activity );
		$tagged_users = apply_filters( 'yz_activity_post_tagged_users', false, $activity );

		if ( ! empty( $tagged_users ) || ! empty( $mood ) ) {
			$action .=  ' ' . __( 'is', 'youzer' ) . $mood . $tagged_users;
		}
	}

	// Return Action
	return apply_filters( 'yz_activity_new_post_action', $action, $activity );

}

add_filter( 'bp_get_activity_action_pre_meta' , 'yz_activity_action_wall_posts', 999, 2 );

/**
 * Get Wall Post Url
 */
function yz_get_wall_post_url( $activity_id ) {
	// Get Post Url.
	$post_link = bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/p/' . $activity_id . '/';
	// Return Link.
	return $post_link;
}

/**
 * Check if post belong to a group.
 */
function yz_wall_is_group_post( $activity ) {

	if ( bp_is_active( 'groups' ) && 'groups' == $activity->component && ! bp_is_groups_component() ) {
		return true;
	}

	return false;
}

/**
 * Strip Emoji from Content.
 */
function yz_remove_emoji( $content ) {

    // Clear Content .
    $content = preg_replace('/&#x[\s\S]+?;/', '', $content );

    return $content;
}

/**
 * Copy Image from Buddypress Directory to Youzer Directory.
 */
function yz_copy_image_to_youzer_directory( $bp_path ) {

	global $YZ_upload_url, $YZ_upload_dir;

	// Get File Name
	$filename = basename( $bp_path );

    // Get File New Name.
    $new_name = $filename;

	// Get Unique File Name for the file.
    while ( file_exists( $YZ_upload_dir . $new_name ) ) {
		$new_name = uniqid( 'file_' ) . '.' . $ext;
	}

	// Get Files Path.
	$old_file = $bp_path;
	$new_file = $YZ_upload_dir . $new_name;

	// Move File From Buddypress Directory to the Youzer Directory.
    if ( copy( $old_file, $new_file ) ) {
    	return  $YZ_upload_url . $filename;
    }

   return false;

}

/**
 * Get List of people who liked a post.
 */
function yz_get_who_liked_activities( $activity_id ) {

	$users = get_transient( 'yz_get_who_liked_activities_' . $activity_id );

	if ( false === $users ) :

	global $wpdb;

	// Prepare Sql
	$sql = $wpdb->prepare( "SELECT user_id FROM " . $wpdb->base_prefix . "usermeta WHERE meta_key = 'bp_favorite_activities' AND meta_value LIKE %s", '%' . $activity_id . '%' );

	// Get Result
	$result = $wpdb->get_results( $sql , ARRAY_A );

	// Get List of user id's & Remove Duplicated Users.
	$users = array_unique( wp_list_pluck( $result, 'user_id' ) );

	// Hide Deleted Users.
	// foreach ( $users as $key => $user_id ) {
 //        if ( ! yz_is_user_exist( $user_id ) ) {
 //            unset( $users[ $key ] );
 //        }
	// }

    set_transient( 'yz_get_who_liked_activities_' . $activity_id, $users, 12 * HOUR_IN_SECONDS );

	endif;

	return $users;
}


/**
 * Display Who Liked a Post.
 */
function yz_show_who_liked_activities() {

	// Check if likes allowed.
	if ( ! bp_activity_can_favorite() ) {
		return false;
	}

	// Get list of people who liked a post.
	$liked_users = yz_get_who_liked_activities( bp_get_activity_id() );

    if ( empty( $liked_users ) ) {
    	return false;
    }

    $output = '';

    // Max User Number.
    $max_users_number = 3;

	$liked_count = (int) bp_activity_get_meta( bp_get_activity_id(), 'favorite_count' ) - $max_users_number;

    foreach ( $liked_users as $key => $user_id ) {

    	if ( $key > $max_users_number - 1 ) {
    		break;
    	}

    	// Get User Image Code.
        $output .= "<a data-yztooltip='" . bp_core_get_user_displayname( $user_id ) . "' href='" . bp_core_get_user_domain ( $user_id ) . "'>" . bp_core_fetch_avatar( array( 'html' => true, 'type' => 'thumb', 'item_id' => $user_id ) ) ."</a>";
    }

    if ( $output ) { ?>
		<div class="yz-post-liked-by">
        	<?php echo $output; if ( $liked_count > 0 ) : ?>
			<a class="yz-trigger-who-modal yz-view-all" data-action="yz_get_who_liked_post" data-yztooltip="<?php _e( 'View All', 'youzer' ); ?>">+<?php echo $liked_count; ?></a>
			<?php endif ;?>
			<span class="yz-liked-this"><?php _e( 'liked this', 'youzer' ); ?></span>
        </div>
    <?php }

}

/**
 * Get Wall Model.
 */
function yz_wall_modal( $args = false ) {

	// item ID.
	$content_function = $args['function'];

	?>
	<div id="yz-modal">
		<div class="yz-wall-modal">
			<div class="yz-wall-modal-title"><?php if ( isset( $args['icon'] ) ) echo '<i class="' . $args['icon'] . '"></i>'; echo $args['title']; ?><i class="fas fa-times yz-wall-modal-close yz-modal-close-icon"></i></div>
			<div class="yz-wall-modal-content">
				<?php $content_function( $args['item_id'] ); ?>
			</div>
		</div>
	</div>

	<?php
}

/**
 * Get who liked a post Modal.
 */
function yz_get_who_liked_post_modal() {

	// Get Modal Args
	$args = array(
		'item_id'  => $_POST['post_id'],
		'function' => 'yz_get_who_liked_post_list',
		'title'    => __( 'People Who Liked This', 'youzer' )
	);

	// Get Modal Content
	yz_wall_modal( $args );

	die();
}

add_action( 'wp_ajax_yz_get_who_liked_post', 'yz_get_who_liked_post_modal' );
add_action( 'wp_ajax_nopriv_yz_get_who_liked_post', 'yz_get_who_liked_post_modal' );

/**
 * Get who liked a post List.
 */
function yz_get_who_liked_post_list( $post_id ) {

	// Get Liked Users.
	$users = yz_get_who_liked_activities( $post_id );

	// Get Users List.
	yz_get_popup_user_list( $users );

}

/**
 * Get who liked a post List.
 */
function yz_get_activity_tagged_users( $activity_id ) {

	// Get Tagged Users.
	$users = bp_activity_get_meta( $activity_id, 'tagged_users' );

	// Remove First User as it's already shown.
	if ( isset($users[0] ) ) {
		unset( $users[0] );
	}

	// Get Users List.
	yz_get_popup_user_list( $users );

}

/**
 * Users Pop Up List.
 **/
function yz_get_popup_user_list( $users ) {

	if ( empty( $users ) ) {
		return;
	}

	echo '<div class="yz-users-who-list">';

	foreach ( $users as $user_id ) {

		?>

		<div class="yz-list-item">
			<a href="<?php echo bp_core_get_user_domain( $user_id ); ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'type'=> 'thumb', 'item_id'=> $user_id ) ); ?></a>
			<div class="yz-item-data">
				<div class="yz-item-name"><?php echo bp_core_get_userlink( $user_id ); ?></div>
				<div class="yz-item-meta">@<?php echo bp_core_get_username( $user_id ); ?></div>
			</div>
		</div>

	<?php }

	echo '</div>';
}

/**
 * Edit 'User wrote a new post' Post Action.
 */
function yz_edit_new_blog_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Get Action
	$action = sprintf( __( '%1s wrote a new post', 'youzer' ), $user_link );

	return $action;
}

add_filter( 'bp_blogs_format_activity_action_new_blog_post', 'yz_edit_new_blog_action', 10, 2 );

/**
 * Edit 'posted an update' Post Action.
 */
function yz_edit_activity_post_action( $action , $activity ) {

	// Get User Link
	$user_link = bp_core_get_userlink( $activity->user_id );

	// Add Group Description.
	if ( yz_wall_is_group_post( $activity ) ) {
		$action =  sprintf( __( '%1s posted', 'youzer' ), $user_link );
	} else {
		$action = $user_link;
	}

	return $action;
}

add_filter( 'bp_activity_new_update_action', 'yz_edit_activity_post_action', 10, 2 );

/**
 * Get Wall Single Post Content.
 */
function yz_get_single_wall_post() {

    ?>

    <?php do_action( 'bp_before_single_activity_post' ); ?>

    <div class="activity no-ajax">
        <?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

            <ul id="activity-stream" class="activity-list item-list">

            <?php while ( bp_activities() ) : bp_the_activity(); ?>
                <?php bp_get_template_part( 'activity/entry' ); ?>
            <?php endwhile; ?>

            </ul>

        <?php endif; ?>
    </div>

    <?php do_action( 'bp_after_single_activity_post' ); ?>

    <?php
}

/**
 * Get Activity By ID.
 */
function yz_get_activity_by_id( $activity_id ) {

	$activity = BP_Activity_Activity::get( array( 'in' => $activity_id ) );
	$activity = isset( $activity['activities'][0] ) ? $activity['activities'][0] : null;

	return $activity;
}

/**
 * Delete Wall Favs Transient.
 */
function yz_delete_activity_likes_transient( $activity_id = null ) {
	// Delete Transient.
	delete_transient( 'yz_get_who_liked_activities_' . $activity_id );
}

add_action( 'bp_activity_remove_user_favorite', 'yz_delete_activity_likes_transient', 1 );
add_action( 'bp_activity_add_user_favorite', 'yz_delete_activity_likes_transient', 1 );

/**
 * Set Shortcode page as buddypress component.
 */
// function yz_enable_activity_shortcode_mentions( $activate ) {
// 	return true;
// }

/**
 * Limit Wall Posts Images Height.
 */
function yz_limit_wall_posts_image_height() {
	return apply_filters( 'yz_limit_wall_posts_image_height', false );
}

/**
 * Enable Posting Form.
 */
function yz_is_wall_posting_form_active() {
	return apply_filters( 'yz_is_wall_posting_form_active', true );
}

/**
 * Enable Posts Effect.
 */
function yz_enable_wall_posts_effect() {
	return apply_filters( 'yz_enable_wall_posts_effect', 'on' == yz_option( 'yz_enable_wall_activity_effects', 'on' ) ? true : false );
}

/**
 * Add Effect
 */
function yz_add_activity_css_class( $classes ) {

	// Add Activity Class.
	// $classes .= ' yz-activity-item';

	if ( ! yz_enable_wall_posts_effect() ) {
		return $classes;
	}
	return $classes . ' yz_effect';
}

add_filter( 'bp_get_activity_css_class', 'yz_add_activity_css_class' );

/**
 * Get Activity Live Url Preview Meta.
 */
function yz_get_activity_url_preview_meta( $activity_id ) {

	// Get Url Data.
	$url_data = bp_activity_get_meta( $activity_id, 'url_preview' );

	if ( ! empty( $url_data ) ) {

		// Unserialize data.
		$url_data = is_serialized( $url_data ) ? unserialize( $url_data ) : maybe_unserialize( base64_decode( $url_data ) );

	}

	return apply_filters( 'yz_get_activity_url_preview_meta', $url_data, $activity_id );
}


/**
 * Add profile activity page filter bar.
 */
function yz_profile_activity_tab_filter_bar() {

    if ( ! bp_is_user_activity() ) {
        return;
    }

    if ( 'on' == yz_option( 'yz_enable_wall_filter_bar', 'on' ) ) :

?>

<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'youzer' ); ?>" role="navigation">
    <ul>

        <?php bp_get_options_nav(); ?>

        <li id="activity-filter-select" class="last">
            <label for="activity-filter-by"><?php _e( 'Show:', 'youzer' ); ?></label>
            <select id="activity-filter-by">
                <option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youzer' ); ?></option>

                <?php bp_activity_show_filters(); ?>

                <?php

                /**
                 * Fires inside the select input for member activity filter options.
                 *
                 * @since 1.2.0
                 */
                do_action( 'bp_member_activity_filter_options' ); ?>

            </select>
        </li>
    </ul>
</div><!-- .item-list-tabs -->

<?php endif;

}

add_action( 'yz_profile_main_content', 'yz_profile_activity_tab_filter_bar' );

/**
 * Fix Buddypress Time Since
 */
function yz_get_activity_time_stamp_meta( $activity = false ) {

    global $activities_template;

    $activity = empty( $activity ) ? $activities_template->activity : $activity;

    // Strip any legacy time since placeholders from BP 1.0-1.1.
    $new_content = $content = '';
    // $new_content = str_replace( '<span class="time-since">%s</span>', '', $content );

    // Get the time since this activity was recorded.
    $date_recorded = bp_core_time_since( $activity->date_recorded );

    // Set up 'time-since' <span>.
    $time_since = sprintf(
        '<span class="time-since" data-livestamp="%1$s">%2$s</span>',
        bp_core_get_iso8601_date( $activity->date_recorded ),
        $date_recorded
    );

    /**
     * Filters the activity item time since markup.
     *
     * @since 1.2.0
     *
     * @param array $value Array containing the time since markup and the current activity component.
     */
    $time_since = apply_filters_ref_array( 'bp_activity_time_since', array(
        $time_since,
        &$activity
    ) );

    // Insert the permalink.
    if ( ! bp_is_single_activity() ) {

        // Setup variables for activity meta.
        $activity_permalink = bp_activity_get_permalink( $activity->id, $activity );
        $activity_meta      = sprintf( '%1$s <a href="%2$s" class="view activity-time-since bp-tooltip" data-bp-tooltip="%3$s">%4$s</a>',
            $new_content,
            $activity_permalink,
            esc_attr__( 'View Discussion', 'buddypress' ),
            $time_since
        );

        /**
         * Filters the activity permalink to be added to the activity content.
         *
         * @since 1.2.0
         *
         * @param array $value Array containing the html markup for the activity permalink, after being parsed by
         *                     sprintf and current activity component.
         */
        $new_content = apply_filters_ref_array( 'bp_activity_permalink', array(
            $activity_meta,
            &$activity
        ) );
    } else {
        $new_content .= str_pad( $time_since, strlen( $time_since ) + 2, ' ', STR_PAD_BOTH );
    }

    /**
     * Filters the activity content after activity metadata has been attached.
     *
     * @since 1.2.0
     *
     * @param string $content Activity content with the activity metadata added.
     */
    return apply_filters( 'yz_insert_activity_meta', $new_content, $content, $activity->id );
}

function yz_get_activity_page_class() {

    // New Array
    $activity_class = array( 'yz-horizontal-layout yz-global-wall' );

    // Get Tabs List Icons Style
    $activity_class[] = yz_option( 'yz_tabs_list_icons_style', 'yz-tabs-list-gradient' );

    // Get Profile Scheme
    $activity_class[] = yz_option( 'yz_profile_scheme', 'yz-blue-scheme' );

    // Get Page Buttons Style
    $activity_class[] = 'yz-page-btns-border-' . yz_option( 'yz_buttons_border_style', 'oval' );

    $activity_class = apply_filters( 'yz_activity_page_class', $activity_class );

    return yz_generate_class( $activity_class );
}


/**
 * Get Activity Attachments.
 */
function yz_get_activity_attachments_by_media_id( $media_id = null, $field = 'src' ) {

	if ( empty( $media_id ) ) {
		return;
	}

	global $wpdb, $Yz_media_table;

	$sql = "SELECT $field FROM $Yz_media_table WHERE ";

	if ( is_array( $media_id ) ) {
		$sql .= "id = %d";
	} else {
		$sql .= "id IN ( %s )";
	}

	// Prepare Sql
	$sql = $wpdb->prepare( $sql, $media_id );

	// Get Result
	$result = $wpdb->get_results( $sql , ARRAY_A );

	if ( empty( $result ) ) {
		return false;
	}

	if ( $field != '*' ) {

		$result = wp_list_pluck( $result, $field );

		$atts = array();

		foreach ( $result as $src ) {
			$atts[] = maybe_unserialize( $src );
		}

	} else {
		$atts = $result;
	}

	return $atts;

}

/**
 * Get Wall Allowed Images Format
 */
function yz_wall_compressed_images_format() {
	return apply_filters( 'yz_wall_allowed_images_format', array( 'jpeg', 'jpg', 'png' ) );
}

/***
 * Feeling / Activity
 */
function yz_wall_mood_categories() {

	$items = array(
		'feeling' => array(
			'title' => __( 'feeling', 'youzer' ),
			'question' => __( 'How are you feeling?', 'youzer' ),
			'icon' => 'fas fa-smile',
			'color' => '#ffc107'
		),
		'celebrating' => array(
			'title' => __( 'celebrating', 'youzer' ),
			'question' => __( 'What are you celebrating?', 'youzer' ),
			'icon' => 'fas fa-birthday-cake',
			'color' => '#2196F3'
		),
		'watching' => array(
			'title' => __( 'watching', 'youzer' ),
			'question' => __( 'What are you watching?', 'youzer' ),
			'icon' => 'fas fa-glasses',
			'color' => '#F44336'
		),
		'eating' => array(
			'title' => __( 'eating', 'youzer' ),
			'question' => __( 'What are you eating?', 'youzer' ),
			'icon' => 'fas fa-utensils',
			'color' => '#707dc3'
		),
		'drinking' => array(
			'title' => __( 'drinking', 'youzer' ),
			'question' => __( 'What are you drinking?', 'youzer' ),
			'icon' => 'fas fa-glass-cheers',
			'color' => '#0dc5b4'
		),
		'travelling' => array(
			'title' => __( 'travelling', 'youzer' ),
			'question' => __( 'Where are you going?', 'youzer' ),
			'icon' => 'fas fa-map-marked-alt',
			'color' => '#f7407e'
		),
		'listening' => array(
			'title' => __( 'listening', 'youzer' ),
			'question' => __( 'What are you listening to?', 'youzer' ),
			'icon' => 'fas fa-headphones-alt',
			'color' => '#5365ca'
		),
		'looking' => array(
			'title' => __( 'looking for', 'youzer' ),
			'question' => __( 'What are you looking for?', 'youzer' ),
			'icon' => 'fas fa-search',
			'color' => '#ff5722'
		),
		'thinking' => array(
			'title' => __( 'thinking', 'youzer' ),
			'question' => __( 'What are you thinking about?', 'youzer' ),
			'icon' => 'fas fa-brain',
			'color' => '#16a1e6'
		),
		'reading' => array(
			'title' => __( 'reading', 'youzer' ),
			'question' => __( 'What are you reading?', 'youzer' ),
			'icon' => 'fas fa-book-reader',
			'color' => '#ff5b93'
		),
		'playing' => array(
			'title' => __( 'playing', 'youzer' ),
			'question' => __( 'What are you playing?', 'youzer' ),
			'icon' => 'fas fa-gamepad',
			'color' => '#ff5548'
		),
		'supporting' => array(
			'title' => __( 'supporting', 'youzer' ),
			'question' => __( 'What are you supporting?', 'youzer' ),
			'icon' => 'fas fa-thumbs-up',
			'color' => '#ff9800'
		)
	);

	return apply_filters( 'yz_wall_mood_categories', $items );
}

/**
 * Get Feeling Emojis.
 */
function yz_get_mood_feeling_emojis() {
	$emojis = array(
		'happy' 	=> __( 'Happy', 'youzer' ),
		'blessed' 	=> __( 'Blessed', 'youzer' ),
		'excited' 	=> __( 'Excited', 'youzer' ),
		'lovely'  	=> __( 'Lovely', 'youzer' ),
		'sad' 	  	=> __( 'Sad', 'youzer' ),
		'sleepy'  	=> __( 'Sleepy', 'youzer' ),
		'angry' 	=> __( 'Angry', 'youzer' ),
		'crazy'   	=> __( 'Crazy', 'youzer' ),
		'evil' 		=> __( 'Evil', 'youzer' ),
		'furious'	=> __( 'Furious', 'youzer' ),
		'inlove'	=> __( 'In love', 'youzer' ),
		'confused' 	=> __( 'Confused', 'youzer' ),
		'silly' 	=> __( 'Silly', 'youzer' ),
		'annoyed' 	=> __( 'Annoyed', 'youzer' ),
		'mad' 		=> __( 'Mad', 'youzer' ),
		'sick'		=> __( 'Sick', 'youzer' ),
		'shy' 		=> __( 'Shy', 'youzer' ),
		'surprised' => __( 'Surprised', 'youzer' ),
		'cool' 		=> __( 'Cool', 'youzer' ),
		'determined'=> __( 'Determined', 'youzer' ),
		'tired' 	=> __( 'Tired', 'youzer' ),
		'shocked' 	=> __( 'Shocked', 'youzer' ),
		'relaxed' 	=> __( 'Relaxed', 'youzer' ),
		'rich' 		=> __( 'Rich', 'youzer' ),
		'weird' 	=> __( 'Weird', 'youzer' ),
	);

	return $emojis;
}

/**
 * Get Feeling Emoji By type
 */
function yz_get_mood_emojis_image( $emoji ) {
	return apply_filters( 'yz_get_mood_emojis_image', YZ_PA . 'images/emojis/' . $emoji . '.png', $emoji );
}

/**
 * Check if Sticky Posts are Enabled.
 */
function yz_is_sticky_posts_active() {

	$active = false;

	if ( 'on' == yz_option( 'yz_enable_activity_sticky_posts', 'on' ) || 'on' == yz_option( 'yz_enable_groups_sticky_posts', 'on' ) ) {
		$active = true;
	}

	return apply_filters( 'yz_is_sticky_posts_active', $active );
}

/**
 * Check if Sharing Posts are enabled.
 */
function yz_is_share_posts_active() {

	// Get Sharing value.
	$active = 'on' == yz_option( 'yz_share_activity_posts', 'on' ) ? true : false;

	return apply_filters( 'yz_is_sticky_posts_active', $active );

}

/**
 * Check if activity can be shared.
 */
function yz_activity_can_share() {

	global $activities_template;

	// Init.
	$can_share = true;

	// Only public posts can be shared.
	if ( $activities_template->activity->privacy != 'public' ) {
		$can_share = false;
	}

	return apply_filters( 'yz_activity_can_share', $can_share, $activities_template->activity->privacy );

}