<?php

/**
 * Add Activity Shortcode.
 **/
function yz_activitiy_shortcode( $atts ) {

	if ( is_admin() ) {
		return;
	}

	// Include Wall Files.
    youzer()->include_activity_files();

    global $yz_activity_shortcode_args;

	// Call Mentions Scripts.
    add_filter( 'bp_activity_maybe_load_mentions_scripts', '__return_true' );

    bp_activity_mentions_script();

	do_action( 'yz_before_activity_shortcode' );

	// Get Args.
	$yz_activity_shortcode_args = wp_parse_args( $atts, array( 'page' => 1, 'show_sidebar' => 'false', 'show_form' => 'true', 'load_more' => 'true', 'show_filter' => 'true' ) );

	if ( $yz_activity_shortcode_args['show_sidebar'] == 'false' ) {
	    // Remove Sidebar.
	    add_filter( 'yz_activate_activity_stream_sidebar', '__return_false' );
	}

	$class = $yz_activity_shortcode_args['show_sidebar'] == 'false' ? 'yz-no-sidebar' : 'yz-with-sidebar';

    // Add Filter.
    add_filter( 'bp_after_has_activities_parse_args', 'yz_set_activity_stream_shortcode_atts', 99 );

    if ( isset( $yz_activity_shortcode_args['form_roles'] ) ) {
    	add_filter( 'yz_is_wall_posting_form_active', 'yz_set_wall_posting_form_by_role' );
    }

    if ( $yz_activity_shortcode_args['show_form'] == 'false' ) {
    	add_filter( 'yz_is_wall_posting_form_active', '__return_false' );
    }

    if ( $yz_activity_shortcode_args['show_filter'] == 'false' ) {
    	add_filter( 'yz_enable_activity_directory_filter_bar', '__return_false' );
    }

    if ( $yz_activity_shortcode_args['load_more'] == 'false' ) {
    	add_filter( 'bp_activity_has_more_items', '__return_false' );
    }

    $activity_data = '';

    if ( ! empty( $yz_activity_shortcode_args ) ) foreach ( $yz_activity_shortcode_args as $key => $value) { $activity_data .= "data-$key='$value'"; }

	ob_start();
    echo "<div class='yz-activity-shortcode $class' style='display: none;' $activity_data>";
    include YZ_TEMPLATE . 'activity/index.php';
    echo "</div>";

	if ( $yz_activity_shortcode_args['show_sidebar'] == 'false' ) {
	    // Remove Sidebar.
	    remove_filter( 'yz_activate_activity_stream_sidebar', '__return_false' );
	}

    if ( $yz_activity_shortcode_args['show_filter'] == 'false' ) {
    	remove_filter( 'yz_enable_activity_directory_filter_bar', '__return_false' );
    }

    if ( isset( $yz_activity_shortcode_args['form_roles'] ) ) {
    	remove_filter( 'yz_is_wall_posting_form_active', 'yz_set_wall_posting_form_by_role' );
    }

    if ( $yz_activity_shortcode_args['show_form'] == 'false' ) {
    	remove_filter( 'yz_is_wall_posting_form_active', '__return_false' );
    }

    if ( $yz_activity_shortcode_args['load_more'] == 'false' ) {
    	remove_filter( 'bp_activity_has_more_items', '__return_false' );
    }

    // Add Filter.
    remove_filter( 'bp_after_has_activities_parse_args', 'yz_set_activity_stream_shortcode_atts', 99 );

	return ob_get_clean();
}

add_shortcode( 'youzer_activity', 'yz_activitiy_shortcode' );


/**
 * Activity Shortcode - Ajax Pagination
 */

add_action( 'wp_ajax_yz_activity_load_activities', 'yz_activity_load_activities' );
add_action( 'wp_ajax_nopriv_yz_activity_load_activities', 'yz_activity_load_activities' );

function yz_activity_load_activities() {

	if ( bp_has_activities( $_POST['data'] ) ) {

		ob_start();

		?>

		<?php while ( bp_activities() ) : bp_the_activity(); ?>

			<?php bp_get_template_part( 'activity/entry' ); ?>

		<?php endwhile; ?>

		<?php yz_activity_load_more(); ?>

	<?php
		$content = ob_get_clean();

		wp_send_json_success( $content );
	} else {
		wp_send_json_error( array(
			'message' => __( 'Sorry, there was no activity found.', 'bp-activity-shortcode' ),
		) );
	}

	die();
}

/**
 * Load More Button
 */
function yz_activity_load_more() { ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>"><i class="fas fa-level-down-alt"></i><?php _e( 'Load More Posts', 'youzer' ); ?></a>
		</li>

	<?php endif; ?>

	<?php

}
/**
 * Activity Shortcode.
 */
function yz_set_activity_stream_shortcode_atts( $loop ) {
    global $yz_activity_shortcode_args;
    return shortcode_atts( $loop, $yz_activity_shortcode_args, 'yz_activity_stream' );
}

/**
 * Set Wall Posting Form By Role.
 */
function yz_set_wall_posting_form_by_role( $active ) {

	global $yz_activity_shortcode_args;

    $active = false;

    $shortcode_roles = explode( ',' , $yz_activity_shortcode_args['form_roles'] );

    if ( ! empty( $shortcode_roles ) ) {

	    // Get Current User Data.
	    $user = get_userdata( bp_loggedin_user_id() );

	    // Get Roles.
	    $user_roles = (array) $user->roles;

	    foreach ( $shortcode_roles as $role ) {
	        if ( in_array( $role, $user_roles ) ) {
	            $active = true;
	            continue;
	        }
	    }

    }

    return $active;

}

/**
 * Get Post Like Button.
 */
function yz_get_post_like_button() {

	// Get Activity ID.
	$activity_id = bp_get_activity_id();

	if ( ! bp_get_activity_is_favorite() ) {

		// Get Like Link.
		$like_link = bp_get_activity_favorite_link();

		// Get Like Button.
		$button = '<a href="'. $like_link .'" class="button fav bp-secondary-action">' . __( 'Like', 'youzer' ) . '</a>';

		// Filter.
		$button = apply_filters( 'yz_filter_post_like_button', $button, $like_link, $activity_id );

	} else {

		// Get Unlike Link.
		$unlike_link = bp_get_activity_unfavorite_link();

		// Get Like Button.
		$button = '<a href="'. $unlike_link .'" class="button unfav bp-secondary-action">' . __( 'Unlike', 'youzer' ) . '</a>';

		// Filter.
		$button = apply_filters( 'yz_filter_post_unlike_button', $button, $unlike_link, $activity_id );

	}

	return $button;

}

/**
 * Wall Post - Get Comment Button Title.
 */
function yz_wall_get_comment_button_title() {

	// Get Comments Number.
	$comments_nbr = bp_activity_get_comment_count();

	$button_title = sprintf( _n( '<span>%s</span> <span class="stats-name">Comment</span>', '<span>%s</span> <span class="stats-name">Comments</span>', $comments_nbr, 'youzer' ), $comments_nbr );

	echo apply_filters( 'yz_wall_get_comment_button_title', $button_title, $comments_nbr );

}

/**
 * Register Wall New Actions.
 */
function yz_add_new_wall_post_actions() {

	// Init Vars
	$bp = buddypress();

	bp_activity_set_action(
		$bp->activity->id,
		'activity_status',
		__( 'Posted a new status', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Status', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_quote',
		__( 'Posted a new quote', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Quotes', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_photo',
		__( 'Posted a new photo', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Photos', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_video',
		__( 'Posted a new video', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Videos', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_audio',
		__( 'Posted a new audio file', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Audios', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_slideshow',
		__( 'Posted a new slideshow', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Slideshows', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_link',
		__( 'Posted a new link', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Links', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_file',
		__( 'Uploaded a new file', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Files', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->profile->id,
		'new_cover',
		__( 'Changed their profile cover', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Cover', 'youzer' ),
		array( 'activity', 'member' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_giphy',
		__( 'Added a new GIF', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Giphy', 'youzer' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	bp_activity_set_action(
		$bp->activity->id,
		'activity_share',
		__( 'Shared a new post', 'youzer' ),
		'yz_activity_action_wall_posts',
		__( 'Shared Posts', 'youzer' ),
		array( 'activity', 'group', 'member' )
	);

}

add_action( 'bp_register_activity_actions', 'yz_add_new_wall_post_actions' );

/**
 * Activity Mood
 */
function yz_enable_activity_mood() {
	$active = 'on' == yz_option( 'yz_activity_mood', 'on' ) ? true : false;
	return apply_filters( 'yz_enable_activity_mood', $active );
}

/**
 * Activity Privacy
 */
function yz_enable_activity_privacy() {
	$active = 'on' == yz_option( 'yz_activity_privacy', 'on' ) ? true : false;
	return apply_filters( 'yz_enable_activity_privacy', $active );
}

/**
 * Activity Mood
 */
function yz_enable_activity_tag_friends() {
	$active = 'on' == yz_option( 'yz_activity_tag_friends', 'on' ) ? true : false;
	return apply_filters( 'yz_enable_activity_tag_friends', $active );
}

/**
 * Activity Hashtags
 */
function yz_enable_activity_hastags() {
	$active = 'on' == yz_option( 'yz_activity_hashtags', 'on' ) ? true : false;
	return apply_filters( 'yz_enable_activity_hastags', $active );
}

/**
 * Get Activity Attachments.
 */
function yz_get_activity_attachments( $activity_id = null, $field = 'src', $component = null ) {

	if ( empty( $activity_id ) ) {
		return;
	}

	global $wpdb, $Yz_media_table;

	$component = ! empty( $component ) ? $component : 'activity';

	// Prepare Sql
	$sql = $wpdb->prepare( "SELECT $field FROM $Yz_media_table WHERE item_id = %d AND component = '%s'", $activity_id, $component );

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
 * Support Wall Embeds Videos Attachments.
 */
function yz_attachments_embeds_videos() {
	return apply_filters( 'yz_attachments_embeds_videos', array( 'youtube' => 'youtube.com', 'vimeo' => 'vimeo.com', 'dailymotion' => 'dailymotion.com' ));
}

/**
 * Get Vimeo Video Url
 */
function yz_get_embed_video_id( $provider, $url ) {

	// Init Vars
	$id = '';
	$match = array();

	switch ( $provider ) {

		case 'youtube':

			if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
				if ( isset( $match[1] ) && ! empty( $match[1] ) ) {
					$id = $match[1];
				}
			}

			break;

		case 'vimeo':
		 	if ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $match ) ) {
		    	if ( isset( $match[3] ) && ! empty( $match[3] ) ) {
		        	$id = $match[3];
		    	}
		    }

			break;

		case 'dailymotion':

		 	if ( preg_match( '!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $match ) ) {

		        if ( isset( $match[6] ) ) {
		            return $match[6];
		        }

		        if ( isset( $match[4] ) ) {
		            return $match[4];
		        }

		        return $match[2];

		    }

			break;

	}

    return apply_filters( 'yz_get_embed_video_id', $id );

}

/**
 * Get Video Thumbnail By Provider
 **/
function yz_get_embed_video_thumbnails( $provider, $id, $size = null ) {

	$data = array( 'provider' => $provider, 'id' => $id );

	switch ( $provider ) {

		case 'youtube':

			$data = array_merge( $data, array( 'thumbnail' => "https://img.youtube.com/vi/$id/mqdefault.jpg", 'medium' => "https://img.youtube.com/vi/$id/sddefault.jpg", 'full' => "https://img.youtube.com/vi/$id/sddefault.jpg" ) );

		case 'vimeo':

			$get_thumbnail_data = yz_file_get_contents( 'http://vimeo.com/api/v2/video/' . $id . '.php' );

			if ( ! empty( $get_thumbnail_data ) ) {

				$thumbnails = maybe_unserialize( $get_thumbnail_data );

				if ( isset( $thumbnails[0]['thumbnail_small'] ) ) {
					$data['thumbnail'] = $thumbnails[0]['thumbnail_small'];
				}

				if ( isset( $thumbnails[0]['thumbnail_medium'] ) ) {
					$data['medium'] = $thumbnails[0]['thumbnail_medium'];
				}

				if ( isset( $thumbnails[0]['thumbnail_large'] ) ) {
					$data['full'] = $thumbnails[0]['thumbnail_large'];
				}

			}

			break;

        case 'dailymotion':

            $thumbnails = json_decode( yz_file_get_contents( "https://api.dailymotion.com/video/$id?fields=thumbnail_medium_url,thumbnail_small_url,thumbnail_large_url" ) );

            if ( isset( $thumbnails->thumbnail_small_url ) ) {
            	$data['thumbnail'] = $thumbnails->thumbnail_small_url;
            }

            if ( isset( $thumbnails->thumbnail_medium_url ) ) {
            	$data['medium'] = $thumbnails->thumbnail_medium_url;
            }

            if ( isset( $thumbnails->thumbnail_large_url ) ) {
            	$data['full'] = $thumbnails->thumbnail_large_url;
            }

            break;

	}

	if ( ! empty( $size ) ) {
		$data = isset( $data[ $size ] ) ? $data[ $size ] : '';
	}

	return apply_filters( 'yz_get_wall_embed_video_thumbnails', $data );

}

/**
 * Remove Blog Posts Default Content
 */
add_filter( 'bp_activity_create_summary', 'yzc_remove_blog_post_excerpt', 10, 3 );

function yzc_remove_blog_post_excerpt( $summary, $content, $activity ) {

	if ( $activity['type'] == 'new_blog_post' ) {
		return '';
	}

	return $summary;

}


/**
 * Get Wall Comments.
 */
function yz_activity_comments_count() {
	// Check if comments allowed.
	if ( ! bp_activity_can_comment() || 0 == bp_activity_get_comment_count() ) {
		return false;
	}

	?>
	<div class="yz-post-comments-count"><i class="far fa-comments"></i><?php yz_wall_get_comment_button_title(); ?></div>
	<?php
}

/**
 * Get Share Count.
 */
function yz_activity_share_count() {

	// Get Share Count
	$share_count = bp_activity_get_meta( bp_get_activity_id(), 'yz_activity_share_count' );

	if ( $share_count < 1 ) {
		return;
	}

	?><div class="yz-post-shares-count yz-trigger-who-modal" data-action="yz_get_who_shared_post"><i class="far fa-share-square"></i><?php echo apply_filters( 'yz_wall_get_share_button_title', sprintf( _n( '<span>%s</span> <span class="stats-name">Share</span>', '<span>%s</span> <span class="stats-name">Shares</span>', $share_count, 'youzer' ), $share_count ), $share_count ); ?></div><?php
}
