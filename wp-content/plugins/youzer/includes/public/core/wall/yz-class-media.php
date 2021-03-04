<?php
/**
 * Wall Media
 */
class Youzer_Media {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Return the instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

	function __construct( ) {

		// Media - Ajax Pagination
		add_action( 'wp_ajax_yz_media_pagination', array( $this, 'ajax_pagination' ) );
		add_action( 'wp_ajax_nopriv_yz_media_pagination', array( $this, 'ajax_pagination' ) );

	}

	/***
	 * Get Medi Photos
	 */
	function get_media( $args ) {

		global $bp, $wpdb, $Yz_media_table;

		// Get Shortcode Args.
		$args = wp_parse_args( $args );

		$query = ! isset( $args['query'] ) || $args['query'] == 'data' ? 'm.id, m.media_id, m.item_id, m.data' : 'COUNT(*)';

		$page = isset( $args['page'] ) ? $args['page'] : 1;

        $limit = isset( $args['limit'] ) ? $args['limit'] : 9;

        // Get Offset.
		$offset = isset( $args['limit'] ) ? ( $page - 1 ) * $args['limit'] : 0;

		// Get Activity Types.
		$type = isset( $args['type'] ) ? $this->get_activity_types( $args['type'] ) : $this->get_activity_types( 'photos' );

		// Prepare Sql
		$sql ="SELECT $query FROM $Yz_media_table AS m JOIN {$bp->activity->table_name} AS a ON m.item_id = a.id WHERE ";

        // Set Activity Component.
		if ( ! isset( $args['group_id'] ) ) {

            $sql .= " m.component = 'activity'";

            // If is not admin hide hidden posts.
            if ( ! current_user_can( 'administrator' ) ) {
                $sql .= " AND a.hide_sitewide = 0";
            }

		} else {
			$sql .= " m.component = 'groups' AND a.item_id = {$args['group_id']}";
        }

        // Get User ID.
        $user_id = isset( $args['user_id'] ) ? $args['user_id'] : '';

		if ( ! empty( $user_id ) ) {
			$sql .= " AND a.user_id = $user_id";
		}

        // Set Privacy.
        $privacy = $this->get_activities_privacy( $user_id );

        // Set Privacy.
        if ( ! empty( $privacy ) ) {
            $sql .= " AND a.privacy IN ( $privacy )";
        }

        // Hide Spam Posts AND Set posts type.
        $sql .= " AND a.is_spam = 0 AND a.type IN ($type)";

        // Filter SQL
        $sql = apply_filters( 'yz_activity_media_sql', $sql );

		if ( $query == 'COUNT(*)' ) {
			// Get Result
			$result = $wpdb->get_var( $sql );
		} else {

    		if ( ! empty( $limit ) ) {
    			$sql .= " GROUP BY m.id ORDER BY m.id DESC LIMIT $limit";
    		}

    		if ( ! empty( $offset ) ) {
    			$sql .= " OFFSET $offset ";
    		}

			// Get Result
			$result = $wpdb->get_results( $sql , ARRAY_A );
		}

		return $result;

	}

    /**
     * Check Sticky Post Visibilyty.
     */
    function get_activities_privacy( $user_id = null ) {

        $privacy = "'public'";

        if ( is_user_logged_in() ) {

            // Add Members Privacy.
            $privacy .= ", 'members'";

            if ( ! empty( $user_id ) ) {

                if ( $user_id == bp_loggedin_user_id() ) {
                    return '';
                }

                if ( bp_is_active( 'friends' ) && friends_check_friendship( bp_loggedin_user_id(), $user_id ) ) {
                    $privacy .=", 'friends'";
                }
            }

        }

        return $privacy;

    }

	/**
	 * Get User Media Count.
	 */
	function get_media_count( $args = null ) {

		global $bp, $wpdb, $Yz_media_table;

		// Prepare Sql
		$sql ="SELECT count(*) FROM $Yz_media_table WHERE user_id = {$args['user_id']} ";

		return $wpdb->get_var( $sql );
	}

	/**
	 * Get Media Activity Types.
	 */
	function get_activity_types( $types ) {

		switch ( $types ) {
			case 'photos':
				$types = "'activity_photo','activity_slideshow','activity_quote','activity_link','new_avatar','new_cover'";
				break;
			case 'videos':
				$types = "'activity_video'";
				break;
			case 'audios':
				$types = "'activity_audio'";
				break;
			case 'files':
				$types = "'activity_file'";
				break;
		}

		return apply_filters( 'yz_get_media_activity_types', $types );
	}

    /**
     * Get Photos Items.
     **/
    function get_photos_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'photos';

        $photos = $this->get_media( $args );

        if ( empty( $photos ) ) {
            echo '<div class="yz-media-no-items">' . __( 'Sorry, no items found.', 'youzer' ) . '</div>';
            return;
        }

        foreach ( $photos as $photo ) : ?>
            <div data-item-id="<?php echo $photo['id']; ?>" class="yz-media-item"><div class="yz-media-item-img" ><img loading="lazy" <?php echo yz_get_image_attributes( $photo['media_id'], 'youzify-thumbnail', 'media-photos' ); ?> alt=""><div class="yz-media-item-tools"><a href="<?php echo bp_activity_get_permalink( $photo['item_id'] );?>"><i class="fas fa-link yz-media-post-link"></i></a><a data-lightbox="yz-media-lightbox" href="<?php echo wp_get_attachment_url( $photo['media_id'] ); ?>"><i class="fas fa-search yz-media-zoom-photo"></i></a></div></div></div>
        <?php endforeach;

        $this->pagination( $args );

    }

    /**
     * Get Videos Items.
     **/
    function get_videos_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'videos';

        $videos = $this->get_media( $args );

        if ( empty( $videos ) ) {
            echo '<div class="yz-media-no-items">' . __( 'Sorry, no items found.', 'youzer' ) . '</div>';
            return;
        }

        $thumbnail_size = isset( $args['layout'] ) && $args['layout'] == '3columns' ? 'full' : 'medium';

        foreach ( $videos as $video ) :

            $src = maybe_unserialize( $video['data'] );
            $thumbnail = '';

            $thumbnail_type = 'image';

        	if ( $src['provider'] == 'local' ) {

                if ( isset( $src['thumbnail'] ) ) {
                    $thumbnail = yz_get_image_attributes( $src['thumbnail'], 'youzify-thumbnail', 'media-video' );
                    $src['id'] = wp_get_attachment_url( $video['media_id'] );
                } else {
                    $thumbnail = wp_get_attachment_url( $video['media_id'] );
                    $thumbnail_type = 'video';
                }

        	} else {

                if ( isset( $src[ $thumbnail_size ] ) ) {
                    $thumbnail = yz_get_image_attributes_by_link( $src[ $thumbnail_size ] );
                }

                if ( empty( $thumbnail ) ) {
                    $video_thumbnail = yz_get_embed_video_thumbnails( $src['provider'], $src['id'], $thumbnail_size );
                    if ( ! empty( $video_thumbnail ) ) {
                        $thumbnail = yz_get_image_attributes_by_link( $video_thumbnail );
                    }
                }

        	}

        	?>

        	<div class="yz-media-item">
        	<?php if ( $thumbnail_type == 'image' ) : ?>
        		<div data-activity-id="<?php echo $video['item_id']; ?>" class="yz-media-item-content">
                    <div class="yz-media-item-img yz-<?php echo $src['provider']; ?>-item"><img loading="lazy" <?php echo $thumbnail; ?> alt=""><?php if ( $src['provider'] == 'local' ) echo '<i class="fas fa-play-circle yz-media-local-video"></i>'; ?><div class="yz-media-item-tools"><a href="<?php echo bp_activity_get_permalink( $video['item_id'] );?>"><i class="fas fa-link yz-media-post-link"></i></a><a class="yz-video-lightbox" data-<?php echo $this->get_video_data( $src['provider'] ); ?>="<?php echo $src['id']; ?>"><i class="fas fa-play-circle yz-media-video-play"></i></a></div></div></div>
        	<?php else : ?>
            	<div data-activity-id="<?php echo $video['item_id']; ?>" class="yz-media-item-content"><div class="yz-media-item-video"><video width="100%" controls preload="metadata"><source class="lazyload" src="<?php echo wp_get_attachment_url( $video['media_id'] ); ?>" type="video/mp4"><?php _e( 'Your browser does not support the video tag.', 'youzer' ); ?></video></div></div>
        	<?php endif; ?>
        	</div>

        <?php endforeach;

        $this->pagination( $args );

    }

    /**
     * Get Video Thumbnail By Provider
     **/
    function get_video_data( $provider ) {

    	$data = '';

    	switch ( $provider ) {

    		case 'youtube':
    			$data = 'yzyt';
    			break;

    		case 'vimeo':
    			$data = 'yzvimeo';
    			break;

            case 'dailymotion':
                $data = 'yzdailymotion';
                break;

            case 'local':
                $data = 'yzvideo';
                break;

    	}

    	return apply_filters( 'yz_get_wall_embed_video_data', $data );

    }

 	/**
     * Get Audio Items.
     **/
    function get_audios_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'audios';

        $audios = $this->get_media( $args );

        if ( empty( $audios ) ) {
            echo '<div class="yz-media-no-items">' . __( 'Sorry, no items found.', 'youzer' ) . '</div>';
            return;
        }

        foreach ( $audios as $audio ) :

        	?>

        	<div class="yz-media-item">

                <a class="yz-media-item-content" data-activity-id="<?php echo $audio['item_id']; ?>" href="<?php echo bp_activity_get_permalink( $audio['item_id'] );?>"><div class="yz-media-item-img"><i class="fas fa-headphones-alt yz-media-audio-play"></i></div></a>
                <audio controls>
                    <source src="<?php echo wp_get_attachment_url( $audio['media_id'] ); ?>" type="audio/mpeg">
                    <?php _e( 'Your browser does not support the audio element.', 'youzer' ); ?>
                </audio>

            </div>

        <?php endforeach;

        $this->pagination( $args );

    }

 	/**
     * Get Files Items.
     **/
    function get_files_items( $args = null ) {

    	// Add Query Type.
    	$args['type'] = 'files';

        $files = $this->get_media( $args );

        if ( empty( $files ) ) {
            echo '<div class="yz-media-no-items">' . __( 'Sorry, no items found.', 'youzer' ) . '</div>';
            return;
        }

        foreach ( $files as $file ) :

			// Get Attachment Data
			$attachments = bp_activity_get_meta( $file['item_id'], 'yz_attachments' );

            // Get File Data.
            $data = $attachments[ $file['media_id'] ];

        	?>

        	<div class="yz-media-item">

				<a class="yz-media-content" data-activity-id="<?php echo $file['item_id']; ?>" href="<?php echo bp_activity_get_permalink( $file['item_id'] );?>">
					<div class="yz-media-content-inner">
						<div class="yz-media-icon"><i class="fas fa-cloud-download-alt yz-file-icon"></i></div>
                        <div class="yz-media-head-area">
    						<div class="yz-media-title" title="<?php echo $data['real_name']; ?>"><?php echo $data['real_name']; ?></div>
    						<div class="yz-media-size"><?php echo yz_file_format_size( $data['file_size'] ); ?></div>
                        </div>
					</div>
				</a>

				<div class="yz-media-download">
					<a rel="nofollow" href="<?php echo wp_get_attachment_url( $file['media_id'] ); ?>"><i class="fas fa-download"></i><span><?php _e( 'Download', 'youzer' ); ?><span></a>
				</div>

            </div>

        <?php endforeach;

        $this->pagination( $args );
    }

    /**
     * Get Media Pagination Items.
     **/
    function pagination( $args ) {

        if ( ! isset( $args['pagination'] ) ) {
            return;
        }

        // Pagination Args.
        $p_args = array( 'type' => $args['type'], 'query' =>'count' );

        if ( isset( $args['user_id'] ) ) {
            $p_args['user_id'] = $args['user_id'];
        } elseif( isset( $args['group_id'] ) ) {
            $p_args['group_id'] = $args['group_id'];
        }

        // Get Total.
        $args['total'] = $this->get_media( $p_args ) ;

        // Add Pagination.
        yz_pagination( $args );

    }

    /**
     * # Media Pagination.
     */
    function ajax_pagination() {

        // Get Data.
        $data = $_POST['data'];

        // Add Current Page.
        $data['page'] = isset( $_POST['page'] ) ? $_POST['page'] : 1;

        if ( $data['type'] == 'photos' ) {
            $this->get_photos_items( $data );
        } elseif ( $data['type'] == 'videos' ) {
            $this->get_videos_items( $data );
        } elseif( $data['type'] == 'audios' ) {
            $this->get_audios_items( $data );
        } elseif( $data['type'] == 'files' ) {
            $this->get_files_items( $data );
        }

        if ( ( isset( $data['user_id'] ) || isset( $data['group_id'] ) ) && isset( $data['view_all'] ) ) {
            $total = $this->get_media( array_merge( $data, array( 'query' => 'count' ) ) );
            if ( $total > $data['limit'] ) { ?>
            <a class="yz-media-view-all" href="<?php echo $this->get_media_by_type_slug( $data ); ?>"><?php echo sprintf( __( 'View All %1s ( %2d )', 'youzer' ), $data['title'], $total ); ?></a>
            <?php }
        }

        die();

    }

    /**
     * Get Slug
     **/
    function get_media_by_type_slug( $args ) {

        $slug = '';

        if ( isset( $args['user_id'] ) ) {
            $slug = bp_core_get_user_domain( $args['user_id'] ) . yz_profile_media_slug();
        } elseif ( isset( $args['group_id'] ) ) {
            $group = groups_get_group( $args['group_id'] );
            $slug = bp_get_group_permalink( $group ) . yz_group_media_slug();
        }

        if ( isset( $args['type'] ) ) {
            $slug .= '/' . $args['type'];
        }

        return $slug;

    }

    /**
     * # Scripts.
     */
    function scripts() {
        wp_enqueue_style( 'yz-media', YZ_PA . 'css/yz-media.min.css', array(), YZ_Version );
        wp_enqueue_script( 'yz-media', YZ_PA . 'js/yz-media.min.js', array( 'jquery' ), YZ_Version, true );
    }

}


/**
 * Get a unique instance of Youzer Media.
 */
function yz_media() {
    return Youzer_Media::get_instance();
}

/**
 * Launch Youzer Media!
 */
yz_media();