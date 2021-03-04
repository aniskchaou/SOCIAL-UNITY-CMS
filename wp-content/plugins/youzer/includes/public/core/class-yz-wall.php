<?php

class Youzer_Wall {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 *
	 * @since 3.0.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

    public function __construct() {

    	// Enqueue Scripts.
    	if ( bp_is_activity_component() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
    	} else if ( ! is_admin() ) {
	    	$this->scripts();
    	}

    	// Include Wall Functions.
    	$this->include_files();

    	// Add Wall Sidebar.
		add_action( 'yz_global_wall_sidebar', array( $this, 'get_wall_sidebar' ) );

		// Wall Post Attachments
		// add_action( 'bp_activity_entry_content', array( $this, 'get_post' ) );

		// Fomat Post
		add_filter( 'bp_insert_activity_meta', array( $this, 'hide_activity_time_stamp' ),10, 2 );
		add_filter( 'bp_get_activity_content_body', array( $this, 'get_activity_content_body' ), 10, 2 );

		// Open Activity Post and Comment link on new tabs.
		// add_filter( 'bp_get_activity_content_body', array( $this, 'open_links_in_new_tabs' ) );
		add_filter( 'bp_activity_comment_content', array( $this, 'open_links_in_new_tabs' ) );
		add_filter( 'bp_activity_comment_content', array( $this, 'add_comment_attachments' ) );
		add_filter( 'bp_get_the_thread_message_content', array( $this, 'open_links_in_new_tabs' ) );

	    // Remove Activity Action Filter
	    remove_filter( 'bp_get_activity_action', 'bp_activity_filter_kses', 1 );

		// Add Embed Urls in a new line so they can be converted to iframes.
		add_filter( 'yz_groups_activity_new_update_content', array( $this, 'activate_autoembed' ) );
		add_filter( 'bp_activity_new_update_content', array( $this, 'activate_autoembed' ) );

    }

    /**
     * Include Wall Files.
     */
    function include_files() {

    	// Include Files.
    	require YZ_PUBLIC_CORE . 'wall/yz-class-form.php';
    	require YZ_PUBLIC_CORE . 'wall/yz-class-hashtags.php';
    	require YZ_PUBLIC_CORE . 'wall/yz-class-functions.php';

    	if ( yz_enable_activity_privacy() ) {
	    	require YZ_PUBLIC_CORE . 'wall/yz-class-privacy.php';
    	}

    	if ( yz_is_share_posts_active() ) {
			require YZ_PUBLIC_CORE . 'wall/yz-class-share.php';
    	}

    	if ( yz_enable_activity_mood() ) {
	    	require YZ_PUBLIC_CORE . 'wall/yz-class-mood.php';
    	}

    	if ( yz_enable_activity_tag_friends() ) {
			require YZ_PUBLIC_CORE . 'wall/yz-class-tag-users.php';
    	}

    	if ( yz_is_sticky_posts_active() ) {
			require YZ_PUBLIC_CORE . 'wall/yz-class-sticky-posts.php';
    	}

	    if ( $this->is_bookmark_active() ) {
			require YZ_PUBLIC_CORE . 'wall/yz-class-bookmarks.php';
    	}

    }

    /**
	 * Check if Bookmarking Posts Option is Enabled.
	 */
	function is_bookmark_active() {
	    $activate = 'on' == yz_option( 'yz_enable_bookmarks', 'on' ) ? true : false;
	    return apply_filters( 'yz_is_bookmarks_active', $activate );
	}

	/**
	 * Get Wall Post Content.
	 */
	function get_activity_content_body( $content = null, $activity = null ) {

	    // Check if activity content is not empty.
	    if ( ! empty( $content ) ) {
	    	$content = '<div class="activity-inner">' . $this->open_links_in_new_tabs( $content ) . '</div>';
	    }

	    // Add Embeds.
	    $content .= $this->embeds( $activity );

	    // Add Attachments.
	    $content .= $this->post_attachments( $activity );

	    return apply_filters( 'yz_get_activity_content_body', $content, $activity );

	}

    /**
     * Post.
     */
    function embeds( $activity = null ) {

    	$content = '';

		switch ( $activity->type ) {

			case 'joined_group':
		        $content = bp_is_groups_component() ? $this->embed_user( $activity->user_id ) : $this->embed_group( $activity->item_id );
				break;

			case 'created_group':
		        $content = $this->embed_group( $activity->item_id );
				break;

			case 'new_member':
        		$content = $this->embed_user( $activity->user_id );
				break;

			case 'updated_profile':
        		$content = $this->embed_user( $activity->user_id );
				break;

			case 'friendship_created':
        		$user_id = ( bp_is_user() && bp_displayed_user_id() != $activity->user_id ) ? $activity->user_id : $activity->secondary_item_id;
        		$content = $this->embed_user( $user_id );
				break;

			case 'new_blog_post':
    			$content = $this->embed_post( $activity->item_id, $activity->secondary_item_id );
				break;

			case 'new_wc_purchase':
			case 'new_wc_product':
				do_action( 'yz_show_wc_embed_product' );
				break;
		}

		// Get Embed Post.
	    if ( ! empty( $content ) ) {
	    	$content = '<div class="yz-activity-embed">' . $content . '</div>';
    	}

    	return $content;

    }

    /**
     * Comment Attachments.
     */
    function add_comment_attachments( $content ) {

    	// Get Comment ID.
    	$comment_id = bp_get_activity_comment_id();

    	if ( empty( $comment_id ) ) {
    		if ( isset( $_POST['activity_id'] ) && ! empty( $_POST['activity_id'] ) ) {
    			$comment_id = $_POST['activity_id'];
    		}
    	}

    	// Get Attachments.
		$attachments = bp_activity_get_meta( $comment_id, 'yz_attachments' );

		if ( ! empty( $attachments ) ) {

			foreach ( $attachments as $attachment_id => $data ) {

			$item_url = wp_get_attachment_url( $attachment_id );

			ob_start();

			// Get File Type.
			$type = yz_get_file_type( $item_url );

			switch ( $type ) {

				case 'image':

					?>
					<a href="<?php echo $item_url; ?>" rel="nofollow" class="yz-img-with-padding" data-lightbox="yz-post-<?php echo $comment_id; ?>"><img loading="lazy" <?php echo yz_get_image_attributes( $attachment_id, 'youzify-comment', 'activity-comment' ); ?> alt=""></a>
					<?php
					break;

				case 'file':

					?>

					<a rel="nofollow" href="<?php echo $item_url; ?>" class="yz-comment-file">
						<span class="yz-file-icon"><i class="fas fa-download yz-attachment-file-icon"></i></span>
						<span class="yzw-file-details">
							<span class="yzw-file-title"><?php echo yz_get_filename_excerpt( $data['real_name'], 45 ); ?></span>
							<span class="yzw-file-size"><?php echo yz_file_format_size( $data['file_size'] ); ?></span>
						</span>
					</a>

					<?php

					break;

				case 'video':

				echo apply_filters( 'yz_get_wall_post_video', '<video width="100%" controls preload="metadata" type="video/quicktime"><source src="' . wp_get_attachment_url( $attachment_id ) . '" type="video/mp4">' . __( 'Your browser does not support the video tag.', 'youzer' ) . '</video>', $attachments, $attachment_id, $data );
					break;

				case 'audio':
					// Get audio Link.
					$audio_link = wp_get_attachment_url( $attachment_id );

					echo apply_filters( 'yz_get_wall_post_audio', '<audio controls><source src="' . $audio_link . '" type="audio/mpeg">' . __( 'Your browser does not support the audio element.', 'youzer' ) . '</audio>', $audio_link, $attachment_id );
					break;

			}

			}

			$attachments = ob_get_contents();

			ob_end_clean();

			$content =  $content . '<div class="yz-comment-attachments">' . $attachments . '</div>';

		}

    	return $content;

    }

    /**
     * Get Wall Attachments.
     */
	function post_attachments( $activity = null ) {

		// Get Attachments
		$attachments = bp_activity_get_meta( $activity->id, 'yz_attachments' );

		ob_start();

		echo '<div class="yz-post-attachments">';

		switch ( $activity->type ) {
			case 'activity_photo':
				$this->get_wall_post_images( $attachments, $activity->id );
				break;
			case 'activity_video':
				$this->get_wall_post_video( $attachments );
				break;
			case 'activity_link':
				$this->get_wall_post_link( $attachments, $activity->id );
				break;
			case 'activity_slideshow':
				$this->get_wall_post_slideshow( $attachments );
				break;
			case 'activity_file':
				$this->get_wall_post_file( $attachments );
				break;
			case 'activity_quote':
				$this->get_wall_post_quote( $attachments, $activity->id );
				break;
			case 'activity_audio':
				$this->get_wall_post_audio( $attachments );
				break;
			case 'new_cover':
				$this->get_wall_post_cover( $attachments );
				break;
			case 'new_avatar':
				$this->get_wall_post_avatar( $attachments, $activity->id  );
				break;
			case 'activity_giphy':
				$this->get_wall_post_giphy( $activity->id );
				break;
			case 'activity_share':
				$this->get_wall_shared_post( $activity->secondary_item_id );
				break;
		}

		// Get Url Preview
		$this->get_activity_url_preview( $activity->id, $activity->content );

		echo '</div>';

		$content = ob_get_contents();

		ob_end_clean();

		return apply_filters( 'yz_activity_attachment_content', $content, $activity );

	}

	/**
	 * Get Wall Shared Post
	 */
	function get_wall_shared_post( $activity_id ) {

		// Get Activity.
		global $activities_template;

        // Back up the global.
        $old_activities_template = $activities_template;

        $show_preview = true;

		if ( bp_has_activities ( 'include=' . $activity_id ) ) {
			while ( bp_activities() ) {
				bp_the_activity();
				if ( $activities_template->activity->privacy != 'public' ) {
					$show_preview = false;
				}
			}
		} else {
			$show_preview = false;
		}

		?>

		<div class="yz-shared-wrapper">

			<div class="yz-shared-wrapper-container">

				<?php if ( $show_preview ) : ?>

				<div class="yz-shared-attachments"><?php

					$activity = $activities_template->activity;

					do_action( 'yz_share_post_attachments', $activity );

				?></div>
				<div class="yz-shared-description">
					<div class="yz-shared-description-content">
						<?php $profile_url = bp_core_get_user_domain( $activity->user_id ); ?>
						<div class="activity-avatar"><a href="<?php bp_activity_user_link(); ?>"><?php bp_activity_avatar() ?></a></div>
						<div class="yz-shared-head">
							<a class="yz-post-author" href="<?php bp_activity_user_link(); ?>"><?php echo bp_core_get_user_displayname( bp_get_activity_user_id() ); ?><?php yz_the_user_verification_icon( bp_get_activity_user_id() ); ?></a>
							<div class="yz-timestamp-area"><?php echo yz_get_activity_time_stamp_meta(); ?></div>
						</div>
						<div class="yz-shared-content"><?php bp_activity_content_body(); ?></div>
					</div>
				</div>
				<?php else: ?>
					<div class="yz-shared-post-unavailable">
						<div class="title"><?php _e( "This content isn't available right now", 'youzer' ); ?></div>
						<p><?php _e( "When this happens, it's usually because the owner only shared it with a small group of people, change who can see it or it's been deleted.", 'youzer' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php

        $activities_template = $old_activities_template;
	}

	/**
	 * Get Activity Privacy.
	 */
	function get_privacy( $activity_id ) {

		global $wpdb, $bp;

		// Prepare SQL
		$sql = $wpdb->prepare( "SELECT privacy from {$bp->activity->table_name} WHERE id = %d", $activity_id );

		// Update Privacy
		return $wpdb->get_var( $sql );

	}
	/**
	 * Open Wall Post & Comment Content On New Tab.
	 */
	function open_links_in_new_tabs( $content ) {

		if ( ! empty( $content ) ) {

		  	$pattern = '/<a(.*?)?href=[\'"]?[\'"]?(.*?)?>/i';

		    $content = preg_replace_callback( $pattern, function( $m ) {

		        $tpl = array_shift( $m );
		        $hrf = isset( $m[1] ) ? $m[1] : null;

		        if ( preg_match( '/target=[\'"]?(.*?)[\'"]?/i', $tpl ) ) {
		            return $tpl;
		        }

		        if ( trim( $hrf ) && 0 === strpos( $hrf, '#' ) ) {
		            return $tpl;
		        }

		        return preg_replace_callback( '/href=/i', function( $m2 ) {
		            return sprintf( 'target="_blank" %s', array_shift( $m2 ) );
		        }, $tpl );

	    	}, $content );

		}

		return $content;
	}

	/**
	 * Cover Post.
	 */
	function get_wall_post_cover( $attachments ) {

		if ( ! empty( $attachments ) ) {

			foreach ( $attachments as $media_id => $data ) {
				echo '<img loading="lazy" ' . yz_get_image_attributes( $media_id, 'youzify-wide', 'activity-cover-image' ) . ' alt="">';

			}
		}

	}

	/**
	 * Avatar Post.
	 */
	function get_wall_post_avatar( $attachments, $activity_id ) {

		if ( ! empty( $attachments ) ) {

			foreach ( $attachments as $media_id => $data ) {
				echo '<a href="' . wp_get_attachment_image_url( $media_id ) . '" data-lightbox="yz-post-' . $activity_id . '" class="yz-img-with-padding"><img loading="lazy" ' . yz_get_image_attributes( $media_id, 'full', 'activity-avatar-image' ) . ' alt=""></a>';
			}

		}

	}

	/**
	 * Cover Post.
	 */
	function get_wall_post_giphy( $activity_id ) {

		// Get Image Url.
		$img_url = bp_activity_get_meta( $activity_id, 'giphy_image' );

		?>
		<a href="<?php echo $img_url; ?>" rel="nofollow" class="yz-img-with-padding" data-lightbox="yz-post-<?php echo $activity_id; ?>"><img loading="lazy" <?php echo yz_get_image_attributes_by_link( $img_url ); ?> alt=""></a>
		<?php

	}

	/**
	 * Quote Post.
	 */
	function get_wall_post_quote( $attachments, $activity_id ) { ?>

	    <div class="yzw-quote-post">
		    <div class="yzw-quote-content quote-with-img">
		        <?php if ( ! empty( $attachments ) ) : foreach ( $attachments as $media_id => $data ) { ?><img loading="lazy" <?php echo yz_get_image_attributes( $media_id, 'youzify-wide', 'activity-quote' ); ?> alt=""><?php } endif; ?>
		        <div class="yzw-quote-main-content">
		            <div class="yzw-quote-icon"><i class="fas fa-quote-right"></i></div>
		            <blockquote><?php echo bp_activity_get_meta( $activity_id, 'yz-quote-text' ); ?></blockquote>
		            <h3 class="yzw-quote-owner"><?php echo bp_activity_get_meta( $activity_id, 'yz-quote-owner' ); ?></h3>
		        </div>
		    </div>
	    </div>

		<?php
	}

	/**
	 * File Post.
	 */
	function get_wall_post_file( $attachments ) {

		foreach ( $attachments as $media_id => $data ) {

		?>

		<div class="yzw-file-post">
			<i class="fas fa-cloud-download-alt yzw-file-icon"></i>
			<div class="yzw-file-details">
				<div class="yzw-file-title"><?php echo yz_get_filename_excerpt( $data['real_name'], 45 ); ?></div>
				<div class="yzw-file-size"><?php echo yz_file_format_size( $data['file_size'] ); ?></div>
			</div>
			<a rel="nofollow" href="<?php echo wp_get_attachment_url( $media_id ); ?>" class="yzw-file-download"><i class="fas fa-download"></i><?php _e( 'Download', 'youzer' ); ?></a>
		</div>

		<?php

		}

	}

	/**
	 * Link Post.
	 */
	function get_wall_post_link( $attachments, $activity_id ) {

		// Get Link Data
		$link_url = bp_activity_get_meta( $activity_id, 'yz-link-url' );

		?>

		<a class="yz-wall-link-content" rel="nofollow" href="<?php echo $link_url; ?>" target="_blank">
			<?php if ( ! empty( $attachments ) ) : foreach ( $attachments as $media_id => $data ) { ?><img loading="lazy" <?php echo yz_get_image_attributes( $media_id, 'youzify-activity-wide', 'activity-link' ); ?> alt=""><?php } endif; ?>
			<div class="yz-wall-link-data">
				<div class="yz-wall-link-title"><?php echo bp_activity_get_meta( $activity_id, 'yz-link-title' ); ?></div>
				<div class="yz-wall-link-desc"><?php echo bp_activity_get_meta( $activity_id, 'yz-link-desc' ); ?></div>
				<div class="yz-wall-link-url"><?php echo $link_url; ?></div>
			</div>
		</a>

		<?php
	}

	/**
	 * Get Url Preview
	 */
	function get_activity_url_preview( $activity_id, $activity_content = null ) {

		// Get Url Data.
		$url = bp_activity_get_meta( $activity_id, 'url_preview' );

		if ( empty( $url ) ) {
			return;
		}

		// Unserialize data.
		$url = is_serialized( $url ) ? unserialize( $url ) : maybe_unserialize( base64_decode( $url ) );

		if ( ! $this->show_url_preview( $url, $activity_content ) ) {
			return;
		}

		?>

		<a class="yz-wall-link-content" rel="nofollow" href="<?php echo $url['link']; ?>" target="_blank">
			<?php if ( ! empty( $url['image'] ) && ( empty( $url['use_thumbnail'] ) || $url['use_thumbnail'] == 'off' ) ) : ?><img loading="lazy" <?php echo yz_get_image_attributes_by_link( $url['image'] ); ?> alt=""><?php endif; ?>
			<div class="yz-wall-link-data">
				<?php if ( ! empty( $url['title'] ) ) : ?><div class="yz-wall-link-title"><?php echo $url['title']; ?></div><?php endif; ?>
				<?php if ( ! empty( $url['description'] ) ) : ?><div class="yz-wall-link-desc"><?php echo $url['description']; ?></div><?php endif; ?>
				<?php if ( ! empty( $url['site'] ) ) : ?><div class="yz-wall-link-url"><?php echo $url['site']; ?></div><?php endif; ?>
			</div>
		</a>
		<?php

	}

	/**
	 * Check if we should show Live Url Preview.
	 **/
	function show_url_preview( $data, $activity_content = null ) {

		$show = true;

		// Get Preview Link.
		$preview_link = ! empty( $data['link'] ) ? $data['link'] : null;

		if ( empty( $data ) || empty( $preview_link ) ) {
			$show = false;
		}

		if ( $show == true ) {

			// Get Post Urls.
			preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $activity_content, $match );

			if ( isset( $match[0] ) && ! empty( $match[0] ) ) {

				foreach ( array_unique( $match[0] ) as $link ) {

					if ( strpos( $link, '?s=%23' ) !== false ) {
						continue;
					}

					if ( wp_oembed_get( $link ) ) {
						$show = false;
						break;
					}

				}

			}
		}

		return apply_filters( 'yz_display_activity_live_url_preview', $show );
	}

	/**
	 * Audio Post.
	 */
	function get_wall_post_audio( $attachments ) {

		// Get Audio Link.
		foreach ( $attachments as $media_id => $data ) {

			$audio_link = wp_get_attachment_url( $media_id );

			echo apply_filters( 'yz_get_wall_post_audio', '<audio controls><source src="' . $audio_link . '" type="audio/mpeg">' . __( 'Your browser does not support the audio element.', 'youzer' ) . '</audio>', $audio_link );
		}

	}

	/**
	 * Video Post.
	 */
	function get_wall_post_video( $attachments ) {

		if ( isset( $attachments[0] ) ) {
			return;
		}

		foreach ( $attachments as $media_id => $data ) {

			// Get Video Poster.
			$poster = isset( $data['thumbnail'] ) ? wp_get_attachment_url( $data['thumbnail'], 'full' ) : '';

			echo apply_filters( 'yz_get_wall_post_video', '<video width="100%" controls preload="metadata" type="video/quicktime" poster="' . $poster . '"><source src="' . wp_get_attachment_url( $media_id ) . '" type="video/mp4">' . __( 'Your browser does not support the video tag.', 'youzer' ) . '</video>', $media_id, $data );
		}

	}

	/**
	 * Slideshow Post.
	 */
	function get_wall_post_slideshow( $slides ) { ?>

	    <div class="yzw-slider yz-slides-fixed-height">
	    <?php foreach ( $slides as $media_id => $data ) : ?><div class="yzw-slideshow-item"><img loading="lazy" <?php echo yz_get_image_attributes( $media_id, 'youzify-wide', 'activity-slideshow' ); ?> alt=""></div><?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Photo Post.
	 */
	function get_wall_post_images( $attachments, $activity_id ) {

		if ( empty( $attachments ) ) {
			return;
		}

		// Get Attachments number.
		$count_atts = count( $attachments );

		if ( 1 == $count_atts ) {

			foreach( $attachments as $media_id => $data ) : ?>

			<a href="<?php echo wp_get_attachment_image_url( $media_id, 'full' ); ?>" rel="nofollow" class="yz-img-with-padding" data-lightbox="yz-post-<?php echo $activity_id; ?>"><img loading="lazy" <?php echo yz_get_image_attributes( $media_id, 'youzify-wide', 'activity-photo' ); ?> alt=""></a>

			<?php endforeach; } elseif ( 2 == $count_atts || 3 == $count_atts ) { ?>

			<div class="yz-post-<?php echo $count_atts; ?>imgs">

				<?php $n = 0; foreach( $attachments as $media_id => $data ) : $n++;?>
					<a class="yz-post-img<?php echo $n;?>" rel="nofollow" href="<?php echo wp_get_attachment_image_url( $media_id, 'full' ); ?>" data-lightbox="yz-post-<?php echo $activity_id; ?>">
						<div class="yz-post-img"><img loading="lazy" <?php echo yz_get_image_attributes( $media_id, 'youzify-medium', 'activity-photo' ); ?> alt=""></div>
					</a>

				<?php endforeach; ?>

			</div>

		<?php } elseif ( 4 <= $count_atts ) { ?>

			<div class="yz-post-4imgs">
				<?php $i = 0; foreach( $attachments as $media_id => $data ) : ?>
				<a class="yz-post-img<?php echo $i + 1; if ( 3 == $i && $count_atts > 4  ) { echo ' yz-post-plus4imgs'; }?>" href="<?php echo wp_get_attachment_image_url( $media_id, 'full' ); ?>" rel="nofollow" data-lightbox="yz-post-<?php echo $activity_id; ?>">
					<div class="yz-post-img">
						<?php $size = $i == 0 ? 'youzify-wide' : 'youzify-medium'; ?>
						<img loading="lazy" <?php echo yz_get_image_attributes( $media_id, $size, 'activity-photo' ); ?> alt="">
						<?php
							if ( 3 == $i && $count_atts > 4 ) {
								$images_nbr = $count_atts - 4;
								echo '<span class="yz-post-imgs-nbr">+' . $images_nbr . '</span>';
							}
						?>
					</div>
				</a>

				<?php $i++; endforeach; ?>

			</div>
			<?php
		}
	}

	/**
	 * 	Wall Embed Group
	 */
	function embed_group( $group_id = false ) {

		if ( ! $group_id ) {
			return false;
		}

	    $group = groups_get_group( array( 'group_id' => $group_id ) );

	    // Get Profile Link.
	    $group_url = bp_get_group_permalink( $group );

	    // Get Group Members Number
	    $members_count = bp_get_group_total_members( $group );

		ob_start();

		?>

	 	<div class="yz-wall-embed yz-wall-embed-group">
	 		<div class="yz-cover"><?php echo yz_get_group_cover( $group_id ); ?></div>
	 		<a href="<?php echo $group_url; ?>" class="yz-embed-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $group_id, 'type' => 'full', 'html' => true, 'object' => 'group' ) ); ?></a>
	 		<div class="yz-embed-data">
	 			<div class="yz-embed-head">
		 			<a href="<?php echo $group_url; ?>" class="yz-embed-name"><?php echo $group->name; ?></a>
		 			<div class="yz-embed-meta">
		 				<div class="yz-embed-meta-item"><?php echo yz_get_group_status( $group->status ); ?></div>
		 				<div class="yz-embed-meta-item">
		 					<i class="fas fa-users"></i><span><?php echo sprintf( _n( '%s Member', '%s Members', $members_count, 'youzer' ), bp_core_number_format( $members_count ) ); ?></span>
		 				</div>
		 			</div>
	 			</div>
	 			<div class="yz-embed-action">
	 				<?php do_action( 'yz_wall_embed_group_actions' );?>
	 				<?php bp_group_join_button( $group ); ?>
	 			</div>
	 		</div>
	 	</div>

		<?php

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

	/**
	 * Wall Embed User
	 */
	function embed_user( $user_id = false ) {

		if ( ! $user_id ) {
			return false;
		}

		ob_start();

	    // Get Profile Link.
	    $profile_url = bp_core_get_user_domain( $user_id );

		?>

	 	<div class="yz-wall-embed yz-wall-embed-user">
	 		<div class="yz-cover"><?php echo yz_get_user_cover( $user_id );  ?></div>
	 		<a href="<?php echo $profile_url; ?>" class="yz-embed-avatar"><?php echo bp_core_fetch_avatar(
			array( 'item_id' => $user_id, 'type' => 'full', 'html' => true ) ); ?></a>
	 		<div class="yz-embed-data">
	 			<div class="yz-embed-head">
		 			<a href="<?php echo $profile_url; ?>" class="yz-embed-name"><?php echo bp_core_get_user_displayname( $user_id ); ?></a>
		 			<div class="yz-embed-meta">@<?php echo bp_core_get_username( $user_id ); ?></div>
	 			</div>
	 			<div class="yz-embed-action">
	 				<?php do_action( 'yz_wall_embed_user_actions' ); ?>
	 				<?php if ( bp_is_active( 'friends' ) ) { bp_add_friend_button( $user_id ); } ?>
	 				<?php yz_send_private_message_button( $user_id ); ?>
	 			</div>
	 		</div>
	 	</div>

		<?php

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

	/**
	 * 	Wall New Post Thumbnail
	 */
	function embed_post_thumbnail( $post_id = false ) {

		// Get Image ID.
		$img_id = get_post_thumbnail_id( $post_id );

		// Get Image Url.
	    $img_url = wp_get_attachment_image_src( $img_id , 'large' );

	    if ( ! empty( $img_url[0] ) ) {
	        $thumbnail = '<img loading="lazy" '. yz_get_image_attributes_by_link( $img_url[0] ) . ' alt="">';
	    } else {

	    	// Get Post Format
	    	$post_format = get_post_format();

	        // Set Post Format
	        $format = ! empty( $post_format ) ? $post_format : 'standard';

	        // Get Thumbnail.
	        $thumbnail = '<div class="yz-wall-nothumb" style="background-image:url( ' . YZ_PA . 'images/geopattern.png);"><div class="yz-thumbnail-icon"><i class="' . yz_get_format_icon( $format ) . '"></i></div></div>';

	    }

	    return $thumbnail;
	}

	/**
	 * 	Wall Embed Post
	 */
	function embed_post( $blog_id = false, $post_id = false ) {

		if ( ! $post_id ) {
			return false;
		}

	    switch_to_blog( $blog_id );

	    // Get Post Data.
	    $post = get_post( $post_id );

	    // Get Categories
	    $post_link = get_the_permalink( $post_id );
	    $post_tumbnail = $this->embed_post_thumbnail( $post_id );
	    $categories = get_the_category_list( ', ', ' ', $post_id );

	    restore_current_blog();


		ob_start();

		?>

	 	<div class="yz-wall-new-post">
	 		<div class="yz-post-img"><a href="<?php echo $post_link; ?>"><?php echo $post_tumbnail; ?></a></div>

	 		<?php do_action( 'yz_after_wall_new_post_thumbnail', $post_id ); ?>

	 		<div class="yz-post-inner">

		 		<div class="yz-post-head">
		 			<div class="yz-post-title"><a href="<?php echo $post_link; ?>"><?php echo $post->post_title; ?></a></div>
		 			<div class="yz-post-meta">
		 				<?php if ( ! empty( $categories ) ) : ?>
		 				<div class="yz-meta-item"><i class="fas fa-tags"></i><?php echo $categories; ?></div>
		 				<?php endif; ?>
		 				<div class="yz-meta-item"><i class="far fa-calendar-alt"></i><?php echo get_the_date( 'F j, Y', $post_id ); ?></div>
		 				<div class="yz-meta-item"><i class="far fa-comments"></i><?php echo $post->comment_count; ?></div>
		 			</div>
		 		</div>
		 		<div class="yz-post-excerpt">
			        <p><?php echo yz_get_excerpt( $post->post_content, 40 ); ?></p>
		 		</div>
		 		<?php do_action( 'yz_activity_new_blog_post_before_read_more', $post ); ?>
		 		<a href="<?php echo $post_link; ?>" class="yz-post-more-button"><span class="yz-btn-icon"><i class="fas fa-angle-double-right"></i></span><span class="yz-btn-title"><?php echo apply_filters( 'yz_wall_embed_blog_post_read_more_button', __( 'Read More', 'youzer' ) ); ?></span></a>
	 		</div>
	 	</div>

		<?php

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

    /**
     * Activate Embeds.
     */
	function activate_autoembed( $content ) {

		// Get Post Urls.
		preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $content, $match );

		if ( ! isset( $match[0] ) && empty( $match[0] ) ) {
			return $content;
		}

		foreach ( array_unique( $match[0] ) as $link ) {

			if ( ! wp_oembed_get( $link ) ) {
				continue;
			}

			$content = str_replace( $link, "\n$link\n", $content );
		}

		return $content;

	}

	/**
	 * Get Wall Comments.
	 */
	function show_wall_post_comments_number() {

		// Check if comments allowed.
		if ( ! bp_activity_can_comment() ) {
			return false;
		}

		if ( is_user_logged_in() || 0 == bp_activity_get_comment_count() ) {
			return false;
		}

		?>
		<div class="yz-post-comments-count"><i class="far fa-comments"></i><?php yz_wall_get_comment_button_title(); ?></div>
		<?php

	}

	/**
	 * Call Wall Sidebar
	 */
	function get_wall_sidebar() {

		if ( apply_filters( 'yz_activate_activity_stream_sidebar', true ) ) {

		  	// Display Widgets.
			if ( is_active_sidebar( 'yz-wall-sidebar' ) ) {
				dynamic_sidebar( 'yz-wall-sidebar' );
			}

		}

	}

    /**
     * Activity Scripts.
     */
    function scripts() {

	    // Wall JS.
	    wp_enqueue_script( 'yz-wall', YZ_PA . 'js/yz-wall.min.js', array( 'jquery' ), YZ_Version );

	    // Wall Css
	    wp_enqueue_style( 'yz-wall', YZ_PA . 'css/yz-wall.min.css', array(), YZ_Version );

	    // Load Profile Style
	    wp_enqueue_style( 'yz-profile', YZ_PA . 'css/yz-profile-style.min.css', array(), YZ_Version );;

	    // Load Carousel CSS and JS.
	    wp_enqueue_style( 'yz-carousel-css', YZ_PA . 'css/owl.carousel.min.css', array(), YZ_Version );
	    wp_enqueue_script( 'yz-carousel-js', YZ_PA . 'js/owl.carousel.min.js', array(), YZ_Version );
	    wp_enqueue_script( 'yz-slider', YZ_PA . 'js/yz-slider.min.js', array(), YZ_Version );

	    if ( is_user_logged_in() ) {

	        global $YZ_upload_url;

	        // Wall Uploader
	        wp_enqueue_script( 'yz-wall-form', YZ_PA . 'js/yz-wall-form.min.js', array( 'jquery' ), YZ_Version, true );

	        $wall_args = apply_filters( 'yz_wall_js_args', array(
	                'max_one_file'      => __( "You can't upload more than one file.", 'youzer' ),
	                'base_url'          => $YZ_upload_url,
	                'giphy_limit'       => 12,
	            ) );

	        // Localize Script.
	        wp_localize_script( 'yz-wall-form', 'Yz_Wall', $wall_args );

	        // if ( 'on' == yz_option( 'yz_enable_wall_giphy', 'on' ) ) {
	            // Giphy Script.
	        wp_enqueue_script( 'yz-giphy', YZ_PA . 'js/yz-giphy.min.js', array( 'jquery' ), YZ_Version, true );
	        // }

	    }

	    if ( yz_enable_wall_posts_effect() ) {
	        // Load View Port Checker Script
	        wp_enqueue_script( 'yz-viewchecker', YZ_PA . 'js/yz-viewportChecker.min.js', array( 'jquery' ), YZ_Version, true  );
	    }

	    // yz_common_scripts();

	    // if its not the activity directory exit.
	    if ( bp_is_activity_directory() && 'on' == yz_option( 'yz_enable_activity_custom_styling', 'off' ) ) {
	        // Get CSS Code.
	        $custom_css = yz_option( 'yz_activity_custom_styling' );
	        if ( ! empty( $custom_css ) ) {
	            // Custom Styling File.
	            wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );
	            wp_add_inline_style( 'youzer-customStyle', $custom_css );
	        }
	    }

	    do_action( 'yz_activity_scripts' );
    }

	/**
	 * Hide Activity Content Tipstamp.
	 */
    function hide_activity_time_stamp( $new_content, $old_content ) {
    	return $old_content;
    }

}

/**
 * Get a unique instance of Youzer Activity.
 */
function yz_activity() {
	return Youzer_Wall::get_instance();
}

/**
 * Launch Youzer Activity!
 */
yz_activity();