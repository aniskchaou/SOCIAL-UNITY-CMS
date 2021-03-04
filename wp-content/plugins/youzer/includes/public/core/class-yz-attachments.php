<?php

/**
 * Wall Attachments.
 */
class Youzer_Attachments {

	function __construct() {

		// Ajax - Upload Attachments
		add_action( 'wp_ajax_yz_upload_wall_attachments', array( $this, 'upload_attachments' ) );

		// Save Attachments.
		add_action( 'yz_after_adding_wall_post', array( $this, 'save_activity_attachments' ), 10 );
		add_action( 'yz_after_adding_wall_post', array( $this, 'save_embeds_videos' ), 10, 2 );
		add_action( 'bp_activity_comment_posted', array( $this, 'save_comments_attachments' ), 10 );

		// Delete Hashtags On Post Delete.
		add_action( 'bp_activity_after_delete', array( $this, 'delete_attachments' ) );

		// Copy Uploaded Avatar & Cover to The Youzer Upload Directory.
		add_action( 'bp_activity_after_save', array( $this, 'set_new_avatar_activity' ) );
		add_action( 'members_cover_image_uploaded', array( $this, 'set_new_cover_activity' ), 10, 3 );

		// Save Messages Attachments.
		add_action( 'messages_message_sent', array( $this, 'save_messages_attachments' ) );

		// Upload Profile Files.
		add_action( 'wp_ajax_upload_files', array( $this, 'upload_profile_files' ) );

		// Delete Activity Attachments.
		add_action( 'wp_ajax_yz_delete_wall_attachment', array( $this, 'delete_temporary_attachment' ) );

		// Delete Account Attachments.
		add_action( 'wp_ajax_yz_delete_attachment', array( $this, 'delete_attachment' ) );

		// Delete Attachment.
		add_action( 'deleted_post', array( $this, 'delete_attachments_media' ), 10, 2 );

	}

	/**
	 * Save Activity Comment Attachments
	 */
	function save_comments_attachments( $comment_id ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Save Attachments.
			$attachments = $this->save_attachments( $comment_id, array( $_POST['attachments_files'] ), 'comment' );

			// Save Comment Attachments.
			if ( ! empty( $attachments ) ) {
				bp_activity_add_meta( $comment_id, 'yz_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Message Attachment.
	 */
	function save_messages_attachments( $message ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Handle Compose Multiple Messages.
			if ( is_array( $_POST['attachments_files'] ) && isset( $_POST['attachments_files'][0] ) ) {
				$_POST['attachments_files'] = $_POST['attachments_files'][0];
			}

			// Save Attachments.
			$attachments = $this->save_attachments( $message->id, array( $_POST['attachments_files'] ), 'message' );

			// Save Message Attachments.
			if ( ! empty( $attachments ) ) {
				bp_messages_add_meta( $message->id, 'yz_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Activity Attachments
	 */
	function save_activity_attachments( $activity_id ) {

		if ( isset( $_POST['attachments_files'] ) && ! empty( $_POST['attachments_files'] ) ) {

			// Get Activity.
			$activity = new BP_Activity_Activity( $activity_id );

			// Save Attachments.
			$attachments = $this->save_attachments( $activity_id, $_POST['attachments_files'], $activity->component );

			// Save Post Attachments.
			if ( ! empty( $attachments ) ) {
				bp_activity_add_meta( $activity_id, 'yz_attachments', $attachments );
			}

		}

	}

	/**
	 * Save Attachments.
	 */
	function save_attachments( $item_id, $attachments, $component ) {

		// Get Attachment.
		$attachments = $this->move_attachments( $attachments, $component );

		return $this->save_media_attachments( $item_id, $attachments, $component );

	}

	/**
	 * Save Posts Embeds Videos.
	 **/
	function save_embeds_videos( $activity_id, $data ) {

		if ( $data['post_type'] != 'activity_status' || empty( $data['content'] ) ) {
			return;
		}

		$embed_exists = false;

		// Init Array.
		$atts = array();

		$supported_videos = yz_attachments_embeds_videos();

		// Get Post Urls.
		if ( preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $data['content'], $match ) ) {

			foreach ( array_unique( $match[0] ) as $link ) {

				foreach ( $supported_videos as $provider => $domain ) {

					$video_id = yz_get_embed_video_id( $provider, $link );

					if ( ! empty( $video_id ) ) {

						$embed_exists = true;

						$video_data = array();

						$embed_data = yz_get_embed_video_thumbnails( $provider, $video_id );

						if ( ! empty( $embed_data ) ) {
							$video_data['id'] = 0;
							$video_data['type'] = 'video';
							$video_data['data'] = $embed_data;
							$video_data['provider'] = $provider;
							$video_data['source'] = 'activity_video';
							$video_data['user_id'] = bp_loggedin_user_id();
						}

						$atts[] = $video_data;

					}

				}

			}

		}

		// Change Activity Type from status to video.
		if ( $embed_exists ) {
			$activity = new BP_Activity_Activity( $activity_id );
			$activity->type = 'activity_video';
			$activity->save();
		}

		// Save Attachment.
		$medias = $this->save_media_attachments( $activity_id, $atts, 'activity' );

		if ( ! empty( $medias ) ) {

			// Add Meta
			bp_activity_add_meta( $activity_id, 'yz_attachments', $medias );

		}
	}

	/**
	 * Get Privacy
	 */
	function get_privacy( $activity_id ) {

		global $wpdb, $bp;

		$privacy = $wpdb->get_var( "SELECT privacy from {$bp->activity->table_name} WHERE id = $activity_id" );

		return $privacy;
	}

	/**
	 * Save Attachments.
	 */
	function save_media_attachments( $item_id, $attachments, $component ) {

		// Serialize Attachments Data.
		$attachments = maybe_unserialize( $attachments );

		if ( empty( $attachments ) ) {
			return;
		}

		global $wpdb, $Yz_media_table, $YZ_upload_dir;

		// Init Vars
		$medias = array();

		// Get Current Time.
		$time = bp_core_current_time();

		switch ( $component ) {

			case 'activity':
			case 'groups':
				$privacy = $this->get_privacy( $item_id );
				break;

			case 'comment':
				global $bp;
				$comment_activity_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = $item_id" );
				$privacy = $this->get_privacy( $comment_activity_id );
				break;

			case 'message':
				$privacy = 'onlyme';
				break;

			default:
				$privacy = 'public';
				break;

		}

		foreach ( $attachments as $attachment ) {

			$data = 1;

			if ( $component == 'activity' || $component == 'groups' ) {

				switch ( $attachment['source'] ) {

					case 'activity_video':

						$data = $attachment['provider'] == 'local' ? array( 'thumbnail' => $attachment['thumbnail'], 'provider' => $attachment['provider'] ) : $attachment['data'];

						if ( ! empty( $attachment['id'] ) ) {
							$medias[ $attachment['id'] ] = $data;
						} else {
							$medias[] = $data;
						}

						break;

					case 'activity_audio':
						$medias = array( $attachment['id'] => 1 );
						break;

					case 'activity_file':
						$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
						$medias[ $attachment['id'] ] = $data;
						break;

					default:
						$medias[ $attachment['id'] ] = 1;
						break;
				}

			} elseif ( $component == 'comment' ) {

				switch ( $attachment['type'] ) {

					case 'video':
						$data = isset( $attachment['thumbnail'] ) && ! empty( $attachment['thumbnail'] ) ? array( 'thumbnail' => $attachment['thumbnail'] ) : 1;
						$medias[ $attachment['id'] ] = $data;
						break;

					default:
						$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
						$medias[ $attachment['id'] ] = $data;
						break;
				}

			} elseif ( $component == 'message' ) {
				$data = isset( $attachment['data'] ) ? $attachment['data'] : 1;
				$medias[ $attachment['id'] ] = $data;

			}

			$args = array(
				'media_id' => $attachment['id'],
				'item_id' => $item_id,
				'component' => $component,
				'user_id' => $attachment['user_id'],
				'privacy' => $privacy,
				'type' => $attachment['type'],
				'time' => $time,
				'data' => $data != 1 ? serialize( $data ) : 0,
				'source' => $attachment['source']
			);

			// Insert Attachment.
			$result = $wpdb->insert( $Yz_media_table, $args );

		}

		return $medias;
	}

	/**
	 * Move Temporary Files To The Main Attachments Directory.
	 */
    function move_attachments( $attachments, $component ) {

    	// Get Maximum Files Number.
	    $max_files = yz_option( 'yz_attachments_max_nbr', 200 );

		// Check attachments files number.
	    if ( count( $attachments ) > $max_files ) {
			$response['error'] = $this->msg( 'max_files' );
			die( json_encode( $response ) );
	    }

    	global $YZ_upload_dir, $yz_upload_source, $yz_upload_component;

    	if ( isset( $_POST['object'] ) && $_POST['object'] == 'groups' ) {
    		$yz_upload_component = array( 'component' => 'groups', 'group_id' => $_POST['item_id'] );
    	}

    	// Get Source.
    	$yz_upload_source = isset( $_POST['post_type'] ) ? $_POST['post_type'] : $_POST['thread_id'];

    	if ( $yz_upload_source == 'activity_comment' ) {

			// Get Comment Parent.
			$parent = new BP_Activity_Activity( $_POST['form_id'] );

			if ( $parent->component == 'groups' ) {
				$yz_upload_component = array( 'component' => 'groups', 'group_id' => $parent->item_id );
			}

    	}

    	// New Attachments List.
    	$new_attachments = array();

		// Get File Path.
		$temp_path = $YZ_upload_dir . 'temp/' ;

 		foreach ( $attachments as $attachment ) {

 			// Get File Data.
	    	$attachment = json_decode( stripcslashes( $attachment ), true );

			// Get Attachment ID.
			$attachment_id = $this->wml_upload( $temp_path . $attachment['original'], $attachment['real_name'] );

	        if ( $attachment_id ) {

	        	// Get Attachment Data.
	        	$data = array( 'id' => $attachment_id, 'type' => yz_get_file_type( $attachment['real_name'] ), 'user_id' => bp_loggedin_user_id(), 'source' => $yz_upload_source );

	        	// Add Post Type.
	        	if ( $yz_upload_source == 'activity_file' || ( $yz_upload_source == 'activity_comment' && $data['type'] == 'file' ) || ( $component == 'message' && $data['type'] == 'file' ) ) {
	        		$data['data'] = $attachment;
	        	}

	        	// Get Post video Data
	        	if ( in_array( $yz_upload_source, array( 'activity_video', 'activity_comment' ) ) && $data['type'] == 'video' ) {

					// Set Video As Uploaded Localy.
					$data['provider'] = 'local';

					if ( isset( $attachment['video_thumbnail'] ) ) {

						// Get Video Thumbnail.
						$video_thumbnail_id = $this->upload_video_thumbnail( $attachment['video_thumbnail'], $attachment['real_name'] );

 						if ( ! empty( $video_thumbnail_id ) ) {
 							$data['thumbnail'] = $video_thumbnail_id;
 						}

 					}

	        	}

	        	$new_attachments[] = $data;

	        }

 		}

 		return ! empty( $new_attachments ) ? serialize( $new_attachments ) : false;

    }

	/**
	 * Upload Video Thumbnail.
	 **/
	function upload_video_thumbnail( $image = null, $video_name ) {

		if ( empty( $image ) ) {
			return;
		}

		global $YZ_upload_dir;

		// Decode Image.
		$decoded_image = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $image ) );

		// Get Unique File Name.
		$filename = uniqid( 'file_' ) . '.jpg';

		// Get File Link.
		$file_link = $YZ_upload_dir . 'temp/' . $filename;

		// Get Unique File Name for the file.
        while ( file_exists( $file_link ) ) {
			$filename = uniqid( 'file_' ) . '.jpg';
		}

		// Upload Image.
		$image_upload = file_put_contents( $file_link, $decoded_image );

		if ( $image_upload ) {

			// Set Same Video Name
			$video_name = pathinfo( $video_name, PATHINFO_FILENAME ) . '.jpg';

			return $this->wml_upload( $file_link, $video_name );

		}

		return false;

	}

	/**
	 * Upload Attachment.
	 */
    function upload_attachments( $manual_files = null ) {

		/**
		 * These functions are for future debuging purpose :
		 *  echo json_encode( $uploaded_files ); // die( json_encode( array('error' => 'ok' ) ) );
		*/

    	global $YZ_upload_dir, $YZ_upload_url;

		// Before Upload User Files Action.
		do_action( 'yz_before_upload_wall_files' );

		// Check Nonce Security
		check_ajax_referer( 'youzer-nonce', 'security' );

		// Get Files.
		$files = ! empty( $manual_files ) ? $manual_files : $_FILES;

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

		$file = $files['file'];

		// Get Uploaded File extension
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

	    // Get Max File Size in Mega.
	    switch ( $_POST['target'] ) {

	    	case 'activity':

	    		if ( ! in_array( $_POST['post_type'], array( 'activity_photo', 'activity_slideshow' ) ) ) {
		    		if ( $_POST['attachments_number'] > 1 ) {
		    			yz_die( __( "You can't upload more than one file.", 'youzer' ) );
		    		}
	    		}

	    		switch ( $_POST['post_type'] ) {

	    			case 'activity_photo':
	    			case 'activity_slideshow':

			    		// Get Max Files Number.
			    		$max_files_number = yz_option( 'yz_attachments_max_nbr', 200 );

			    		if ( $_POST['attachments_number'] > $max_files_number ) {
			    			yz_die( sprintf( __( "You can't upload more than %d files.", 'youzer' ), $max_files_number ) );
			    		}

            			// Get Image Allowed Extentions.
	    				$image_extensions = yz_get_allowed_extensions( 'image' );

		    			if ( ! in_array( $ext, $image_extensions ) ) {
		    				yz_die( sprintf( __( 'Invalid image extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $image_extensions ) ) );
		    			}

	    				break;

	    			case 'activity_video':

		            	// Get Video Allowed Extentions.
	    				$video_extensions = yz_get_allowed_extensions( 'video' );

		    			if ( ! in_array( $ext, $video_extensions ) ) {
		    				yz_die( sprintf( __( 'Invalid video extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $video_extensions ) ) );
		    			}

	    				break;

	    			case 'activity_audio':

		            	// Get Audio Allowed Extentions.
	    				$audio_extensions = yz_get_allowed_extensions( 'audio' );

		    			if ( ! in_array( $ext, $audio_extensions ) ) {
		    				yz_die( sprintf( __( 'Invalid audio extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $audio_extensions ) ) );
		    			}

	    				break;

	    			case 'activity_file':

		            	// Get File Allowed Extentions.
	    				$file_extensions = yz_get_allowed_extensions( 'file' );

		    			if ( ! in_array( $ext, $file_extensions ) ) {
		    				yz_die( sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $file_extensions ) ) );
		    			}

	    				break;

	    			default:
	    				break;
	    		}

	    		$max_size = yz_option( 'yz_attachments_max_size', 10 );

	    		break;

	    	case 'comment':
	    		$max_size = yz_option( 'yz_wall_comments_attachments_max_size', 10 );
	    		$comments_extensions = yz_option( 'yz_wall_comments_attachments_extensions', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi' ) );
    			if ( ! in_array( $ext, $comments_extensions ) ) {
    				yz_die( sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $comments_extensions ) ) );
    			}

	    		break;

	    	case 'message':
	    		$max_size = yz_option( 'yz_messages_attachments_max_size', 10 );
	    		$message_extensions = yz_option( 'yz_messages_attachments_extensions', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi' ) );
    			if ( ! in_array( $ext, $message_extensions ) ) {
    				yz_die( sprintf( __( 'Invalid file extension.<br> Only %1s are allowed.', 'youzer' ), implode( ', ', $message_extensions ) ) );
    			}

	    		break;

	    	default:
	    		break;

	    }

		// Set max file size in bytes.
		$max_file_size = apply_filters( 'yz_wall_attachments_max_size', $max_size * 1048576 );

		// Check that the file is not too big.
	    if ( $file['size'] > $max_file_size ) {
	    	yz_die( sprintf( __( 'File too large. File must be less than %g megabytes.', 'youzer' ), $max_size ) );
	    }

		// Check File has the Right Extension.
		if ( ! $this->validate_file_extension( $ext ) ) {
	    	yz_die( __( 'Sorry, this file type is not permitted for security reasons.', 'youzer' ) );
		}

		if ( $file['name'] ) {

			// Get File Name
			$file_name = apply_filters( 'yz_wall_attachment_filename', $file['name'], $ext );

		    // Change Default Upload Directory to the Plugin Directory.
			add_filter( 'upload_dir', array( $this, 'temporary_upload_directory' ) );

			$enable_compression = yz_option( 'yz_compress_images', 'on' ) == 'on' ? true : false;

			// Check if images compression is enabled.
	        if ( apply_filters( 'yz_enable_attachments_compression', $enable_compression ) && in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {

	        	// Get Compressed Image Name.
	        	$movefile = $this->get_compressed_image( $file['tmp_name'], $file_name );

	        	// Change PNG extension to JPG.
	        	if ( $movefile && $ext == 'png' ) {
	        		$file_name = str_replace( '.png', '.jpg', $file_name );
	        	}

		        // Upload File.
	        	if ( ! $movefile ) {

	        		$upload_args = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );

		        	$movefile = wp_handle_upload( $upload_args, array( 'test_form' => false ) );
	        	}

	        } else {

	        	$upload_args = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );
		        // Upload File.
		        $movefile = wp_handle_upload( $upload_args, array( 'test_form' => false ) );
	        }

	        // Get Files Data.
	        if ( $movefile && ! isset( $movefile['error'] ) ) {
	        	$file_data = array( 'real_name' => $file_name, 'type' => $file['type'], 'file_size' => $file['size'], 'original' => basename( $movefile['url'] ), 'base_url' => $YZ_upload_url );
	        }

    	}

    	// After Upload Hook
    	do_action( 'yz_after_attachments_upload', $file_data, $movefile );

	    // Change Upload Directory to the Default Directory .
		remove_filter( 'upload_dir', array( $this, 'temporary_upload_directory' ) );

		if ( empty( $manual_files ) ) {
			echo json_encode( $file_data );
			die();
		} else {
			return $file_data;
		}

    }

    function get_images_sizes( $sizes ) {

    	global $yz_upload_source;

    	if ( is_numeric( $yz_upload_source ) ) {
    		$yz_upload_source = 'message';
    	}

    	$sizes = array(
    		'youzify-thumbnail' => array( 'width' => 150, 'height' => 150, 'crop' => 1 ),
    		'youzify-medium' => array('width' => 300, 'height' => 300, 'crop' => 1 )
    	);

    	switch ( $yz_upload_source ) {

    		case 'message':
    			$sizes = array( 'youzify-message' => array( 'width' => 500, 'crop' => 0 ) );
    			break;

    		case 'activity_comment':
    			$sizes = array( 'youzify-comment' => array( 'width' => 300, 'crop' => 0 ) );
    			break;

    		case 'activity_file':
    		case 'activity_avatar':
    			$sizes = array();
    			break;

    		case 'profile_project_widget':
    			$sizes = array( 'youzify-medium' => array( 'width' => 600, 'height' => 600, 'crop' => 1 ) );
    			break;

    		case 'profile_about_me_widget':
    			$sizes['youzify-thumbnail'] = array( 'width' => 180, 'height' => 180, 'crop' => 1 );
    			break;

    		case 'activity_link':
    		case 'activity_photo':
    			$sizes['youzify-activity-wide'] = array( 'width' => 825, 'height' => 0, 'crop' => 0 );
    			break;

    		case 'profile_link_widget':
    		case 'profile_quote_widget':
    		case 'profile_slideshow_widget':
    			$sizes = array( 'youzify-wide' => array( 'width' => 825, 'height' => 300, 'crop' => 1 ) );
    			break;

    		case 'activity_quote':
    		case 'activity_cover':
    		case 'activity_slideshow':
    			$sizes['youzify-wide'] = array( 'width' => 825, 'height' => 300, 'crop' => 1 );
    			break;

    	}

    	return apply_filters( 'yz_images_sizes', $sizes, $yz_upload_source );

    }

    /**
     * Upload to Wordpress Library
     **/
    function wml_upload( $file_url, $original_image, $new_name = false ) {

    	// Disable Wordpress Media Default Sizes.
    	add_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

    	// Set Upload Directory to Youzify Directory.
		add_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

    	$attachment_id = '';

    	// Get New Name.
    	$new_name = empty( $new_name ) ? $original_image : $new_name;

		// Upload File to WordPress Media Library.
		$upload_file = wp_upload_bits( $new_name, null, file_get_contents( $file_url ) );

		if ( ! $upload_file['error'] ) {

			// Get File Type.
			$wp_filetype = wp_check_filetype( $original_image, null );

			// Get Attachment Args.
			$args = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', $original_image ),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			// Insert Attachment.
			$attachment_id = wp_insert_attachment( $args, $upload_file['file'] );

			wp_set_object_terms( $attachment_id, 'youzify_media', 'category', true );

			if ( ! is_wp_error( $attachment_id ) ) {

				// Include Image Clas.
				require_once ABSPATH . 'wp-admin/includes/image.php';

				// Generate Metadata
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );

				if ( ! empty( $attachment_data ) ) {
					wp_update_attachment_metadata( $attachment_id, $attachment_data );
				}

			}

		}

    	// Set Upload Directory to Default Again.
		remove_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

		return $attachment_id;
    }

    /**
     * Delete Activity Attachments.
     */
    function delete_attachments( $activities ) {

    	global $wpdb, $Yz_media_table;

    	$force_delete = apply_filters( 'yz_force_attachments_delete', true );

    	foreach ( $activities as $activity ) {

			// Get Activity Attachments.
			$attachments = bp_activity_get_meta( $activity->id, 'yz_attachments' );

	    	// Check if the activity contains Attachments.
			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment_id => $data ) {

					// Delete Attachment
					wp_delete_attachment( $attachment_id, $force_delete );

					// Delete Thumbnail if found.
					if ( isset( $data['thumbnail'] ) && ! isset( $data['id']) ) {
						wp_delete_attachment( $data['thumbnail'], $force_delete );
					}

				}
			}

			if ( $activity->type == 'activity_video' ) {

				global $Yz_media_table, $wpdb;

				// Get Activity Attachments.
				$attachments = yz_get_activity_attachments( $activity->id );

		    	// Check if the activity contains Attachments.
				if ( empty( $attachments ) ) {
					continue;
				}

				// Get Component.
				$component = $activity->type == 'activity_comment' ? 'comment' : 'activity';

				// Delete All Activity Attachments.
				$wpdb->delete( $Yz_media_table, array( 'item_id' => $activity->id, 'component' => $component ), array( '%d', '%s' ) );

			}

			// Delete Activity Attachments Data.
			bp_activity_delete_meta( $activity->id, 'yz_attachments' );

    	}

    }

    /**
     * Delete Attachments Media.
     */
    function delete_attachments_media( $post_id, $post ) {

    	global $wpdb, $Yz_media_table;

    	$wpdb->delete( $Yz_media_table, array( 'media_id' => $post_id ), array( '%d' ) );

    }

    /**
     * Delete Attachments By Media ID.
     */
    function delete_attachments_by_media_id( $media_id = null, $item_id = null ) {

    	if ( empty( $media_id ) || empty( $item_id ) ) {
    		return;
    	}

    	// Get Medias
    	$medias = is_array( $media_id ) ? $media_id : array( $media_id );

    	// Force Delete.
    	$force_delete = apply_filters( 'yz_force_attachments_delete', true );

    	// Get Activity Attachments
    	$activity_attachments = bp_activity_get_meta( $item_id, 'yz_attachments' );

    	foreach ( $medias as $attachment_id ) {

    		// Delete Wordpress Media Library Attachment.
    		wp_delete_attachment( $attachment_id, $force_delete );

    		if ( isset( $activity_attachments[ $attachment_id ] ) ) {

    			// Delete Thumbnail If Found.
    			if ( isset( $activity_attachments[ $attachment_id ]['thumbnail'] ) ) {
    				wp_delete_attachment( $activity_attachments[ $attachment_id ]['thumbnail'], $force_delete );
    			}

    			// Delete Activity Attachment.
    			unset( $activity_attachments[ $attachment_id ] );

    		}

    	}

		if ( ! empty( $activity_attachments ) ) {
    		bp_activity_update_meta( $item_id, 'yz_attachments', $activity_attachments );
    	} else {
    		bp_activity_delete_meta( $item_id, 'yz_attachments' );
    	}

    }

    /**
     * Validate file extension.
     */
    function validate_file_extension( $file_ext ) {

	   // Get a list of allowed mime types.
	   $mimes = get_allowed_mime_types();

	    // Loop through and find the file extension icon.
	    foreach ( $mimes as $type => $mime ) {
	      if ( false !== strpos( $type, $file_ext ) ) {
	          return true;
	        }
	    }

	    return false;
	}

	/**
	 * Add 'user uploaded new avatar' Post.
	 */
	function set_new_avatar_activity( $activity ) {

		if ( 'new_avatar' != $activity->type ) {
			return false;
		}

		// Get User Avatar.
		$avatar_url = bp_core_fetch_avatar(
			array(
				'item_id' => $activity->user_id,
				'type'	  => 'full',
				'html' 	  => false,
			)
		);

	    // Get Attachment ID.
	    $attachment_id = $this->wml_upload( $avatar_url, basename( $avatar_url ) );

		if ( $attachment_id ) {

			// Save Attachment.
			$this->save_media_attachments( $activity->id, array( array( 'id' => $attachment_id, 'user_id' => bp_displayed_user_id(), 'source' => 'activity_avatar', 'type' => 'image' ) ), 'activity' );

			// Add Meta
			bp_activity_add_meta( $activity->id, 'yz_attachments', array( $attachment_id => 1 ) );

		}

	}

	/**
	 * Add 'User Uploaded New Cover' Post.
	 */
	function set_new_cover_activity( $item_id, $name, $cover_url ) {

		if ( ! bp_is_active( 'activity' ) ) {
			return;
		}

		// Get Activitiy ID.
		$activity_id = bp_activity_add(
			array(
				'type'      => 'new_cover',
				'user_id'   => bp_displayed_user_id(),
				'component' => buddypress()->activity->id,
			)
		);

	    // Get Attachment ID.
	    $attachment_id = $this->wml_upload( $cover_url, basename( $cover_url ) );

		// Save Cover Url.
		if ( $attachment_id ) {

			// Save Attachment.
			$this->save_media_attachments( $activity_id, array( array( 'id' => $attachment_id, 'user_id' => bp_displayed_user_id(), 'source' => 'activity_cover', 'type' => 'image' ) ), 'activity' );

			// Add Meta
			bp_activity_add_meta( $activity_id, 'yz_attachments', array( $attachment_id => 1 ) );

		}

	}

	/**
	 * # Save Uploaded Files.
	 */
	function upload_profile_files() {

		// Before Upload User Files Action.
		do_action( 'youzer_before_upload_user_files' );

		// Check Nonce Security
		check_ajax_referer( 'yz_nonce_security', 'nonce' );

		// Get Files.
		$files = $_FILES;

	    if ( ! function_exists( 'wp_handle_upload' ) ) {
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    }

	    $upload_overrides = array( 'test_form' => false );

	    // Get Max File Size in Mega.
	    $max_size = yz_option( 'yz_files_max_size', 3 );

		// Set max file size in bytes.
		$max_file_size = $max_size * 1048576;

		// Valid Extensions
		$valid_extensions = apply_filters( 'yz_profile_attachements_valid_extensions', array( 'jpeg', 'jpg', 'png', 'gif' ) );

		// Valid Types
		$valid_types = array( 'image/jpeg', 'image/jpg', 'image/png','image/gif' );

		// Minimum Image Resolutions.
		$min = apply_filters( 'yz_attachments_image_min_resolution', array( 'width' => '100', 'height' => '100' ) );

	    // Change Default Upload Directory to the Youzer Directory.
		add_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

    	// Disable Wordpress Media Default Sizes.
    	add_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

		// Create New Array
		$uploaded_files = array();

	    foreach ( $files as $key => $file ) :

		    // Get Image Size.
		    $get_image_size = getimagesize( $file['tmp_name'] );

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

			// Check File has the Right Extension.
			if ( ! in_array( $ext, $valid_extensions ) ) {
				$data['error'] = __( 'Invalid file extension.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check That The File is of The Right Type.
			if ( ! in_array( $file['type'], $valid_types ) ) {
				$data['error'] = __( 'Invalid file type.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check that the file is not too big.
		    if ( $file['size'] > $max_file_size ) {
				$data['error'] = sprintf( esc_html__( 'File too large. File must be less than %d megabytes.', 'youzer' ), $max_size );
				die( json_encode( $data ) );
		    }

			// Check Image Existence.
			if ( ! $get_image_size ) {
				$data['error'] = __( 'Uploaded file is not a valid image.', 'youzer' );
				die( json_encode( $data ) );
			}

			// Check Image Minimum Width.
			if ( $get_image_size[0] < $min[ 'width' ] ) {
				$data['error'] = sprintf( esc_html__( 'Image minimum width is %d pixel.', 'youzer' ), $min['width'] );
				die( json_encode( $data ) );
			}
			// Check Image Minimum Height.
			if ( $get_image_size[1] < $min[ 'height' ] ) {
				$data['error'] = sprintf( esc_html__( 'Image minimum height is %d pixel.', 'youzer' ), $min['height'] );
				die( json_encode( $data ) );
			}

			if ( $file['name'] ) {

				// Get File Name
				$file_name = apply_filters( 'yz_wall_attachment_filename', $file['name'], $ext );

				// Check if images compression is enabled.
				$enable_compression = yz_option( 'yz_compress_images', 'on' ) == 'on' ? true : false;

		        if ( apply_filters( 'yz_enable_attachments_compression', $enable_compression ) && in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {

		        	// Get Compressed Image Name.
		        	$movefile = $this->get_compressed_image( $file['tmp_name'], $file_name );

		        	// Change PNG extension to JPG.
		        	if ( $movefile && $ext == 'png' ) {
		        		$file_name = str_replace( '.png', '.jpg', $file_name );
		        	}

			        // Upload File.
		        	if ( ! $movefile ) {
		        		$uploadedfile = array( 'name' => $file_name, 'size' => $file['size'], 'type' => $file['type'], 'error' => $file['error'], 'tmp_name' => $file['tmp_name'] );
			        	$movefile = wp_handle_upload( $uploadedfile, array( 'test_form' => false ) );
		        	}

		        } else {

					$uploadedfile = array(
					    'name'     => $file_name,
					    'size'     => $file['size'],
					    'type'     => $file['type'],
					    'error'    => $file['error'],
					    'tmp_name' => $file['tmp_name']
					);

			        // Upload File.
			        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		        }

		        if ( $movefile && ! isset( $movefile['error'] ) ) {

					// Get File Type.
					$wp_filetype = wp_check_filetype( $file_name, null );

					// Get Attachment Args.
					$args = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', '', $file_name ),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					// Insert Attachment.
					$attachment_id = wp_insert_attachment( $args, $movefile['file'] );

					// Set Media Category.
					wp_set_object_terms( $attachment_id, 'youzify_media', 'category', true );

					if ( ! is_wp_error( $attachment_id ) ) {

						// Include Image Clas.
						require_once ABSPATH . 'wp-admin/includes/image.php';

						// Generate Metadata
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $movefile['file'] );

						// Update Attachment.
						wp_update_attachment_metadata( $attachment_id, $attachment_data );

					}

					// Save Attachment.
					$this->save_media_attachments( 0, array( array( 'id' => $attachment_id, 'user_id' => $_POST['user_id'], 'source' => $_POST['source'], 'type' => 'image' ) ), 'profile' );

 		        	echo json_encode( array( 'attachment_id' => $attachment_id, 'url' => $movefile['url'] ) );

				}

	    	}

	    endforeach;

    	// Enable Wordpress Media Default Sizes.
    	remove_filter( 'intermediate_image_sizes_advanced', array( $this, 'get_images_sizes' ) );

	    // Change Youzer Upload Directory to the Default Directory .
		remove_filter( 'upload_dir', array( $this, 'youzify_upload_directory' ) );

		die();
	}

	/**
	 * Delete Attachment.
	 */
    function delete_attachment() {

    	if ( ! isset( $_POST['attachment_id'] ) || empty( $_POST['attachment_id'] ) ) {
    		return;
    	}

	    wp_delete_attachment( $_POST['attachment_id'], apply_filters( 'yz_force_attachments_delete', true ) );

    }

	/**
	 * Delete Temporary Attachment.
	 */
    function delete_temporary_attachment() {

    	global $YZ_upload_dir;

		// Before Delete Attachment Action.
		do_action( 'yz_before_delete_attachment' );

		// Check Nonce Security
		check_ajax_referer( 'youzer-nonce', 'security' );

		// Get File Path.
		$file_path = $YZ_upload_dir . 'temp/' . wp_basename( $_POST['attachment'] );

		// Delete File.
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		die();
    }


	/**
	 * Save New Thumbnail
	 */
	function get_compressed_image( $source, $filename ) {

	    // Get image from file
	    $img = false;

	    // Get File Type.
	    $file_type = wp_check_filetype( $filename );

	    // Get File Name.
	    $file_name = pathinfo( $filename, PATHINFO_FILENAME );

	    switch ( $file_type['type'] ) {

	        case 'image/jpeg': {
	            $img = imagecreatefromjpeg( $source );
	            break;
	        }

	        case 'image/png': {
	            $image = imagecreatefrompng( $source );
				$img = imagecreatetruecolor( imagesx( $image ), imagesy( $image ) );
				imagefill( $img, 0, 0, imagecolorallocate( $img, 255, 255, 255 ) );
				imagealphablending( $img, TRUE );
				imagecopy( $img, $image, 0, 0, 0, 0, imagesx( $image ), imagesy( $image ) );
	            break;
	        }

	    }

	    if ( empty( $img ) ) {
	        return false;
	    }

	    // Get File Path.
	    $folder = wp_get_upload_dir();

	    // Get Compression Quality.
	    $quality = apply_filters( 'yz_attachments_compression_quality', yz_option( 'yz_images_compression_quality', 90 ) );

	    // Get New Image Path.
	    $compressed_image = wp_unique_filename( $folder['path'], $file_name . '.jpg' );

	    // Get New File Path.
	    $new_file = $folder['path'] . '/' . $compressed_image;

	    if ( imagejpeg( $img, $new_file, $quality ) ) {

	        imagedestroy( $img );

	        return array( 'url' => $folder['url'] . '/' . $compressed_image, 'file' => $new_file );

	    }

	    return false;

	}

	/**
	 * Change Default Upload Directory to the Temporary Youzer Directory.
	 */
	function temporary_upload_directory( $dir ) {

		global $YZ_upload_folder, $YZ_upload_url, $YZ_upload_dir;

	    return array(
	        'path'   => $YZ_upload_dir . 'temp',
	        'url'    => $YZ_upload_url . 'temp',
	        'subdir' => '/' . $YZ_upload_folder . 'temp',
	    ) + $dir;

	}

	/**
	 * Change Default Upload Directory to the Youzer Directory.
	 */
	function youzify_upload_directory( $upload_dir ) {

		global $yz_upload_component;

		if ( ! isset( $yz_upload_component ) || 'groups' != $yz_upload_component['component'] ) {
			$folder = 'members/';
			$id = get_current_user_id();
		} else {
			$folder = 'groups/';
			$id = $yz_upload_component['group_id'];
		}

		// Youzify Upload Folder
		$upload_folder = apply_filters( 'youzify_upload_folder_name', 'youzify' );

		if ( strpos( $upload_dir['path'], $upload_folder . '/' . $folder ) === false ) {
			$upload_dir['path'] = trailingslashit( str_replace( $upload_dir['subdir'], '', $upload_dir['path'] ) ) . $upload_folder . '/' . $folder . $id . $upload_dir['subdir'];
			$upload_dir['url']  = trailingslashit( str_replace( $upload_dir['subdir'], '', $upload_dir['url'] ) ) . $upload_folder . '/' . $folder . $id . $upload_dir['subdir'];
		}

		return apply_filters( 'youzify_filter_upload_dir', $upload_dir );
	}

}

$attachments = new Youzer_Attachments();