<?php

/**
 * check for youzer then check if the user media tables are installed.
 */
add_action( 'yz_patches_settings', 'yz_patch_move_to_new_wp_media_library' );

function yz_patch_move_to_new_wp_media_library() {

    if ( ! yz_option( 'yz_patch_new_media_system2' ) || yz_option( 'yz_patch_new_wp_media_library' ) ) {
        return false;
    }

    global $Yz_Settings, $wpdb, $bp;

	$already_installed = yz_option( 'yz_patch_new_wp_media_library' );

	$total = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . "yz_media" );

	if ( ! $already_installed && $total == 0 ) {
	    yz_update_option( 'yz_patch_new_wp_media_library', true );
	    return;
	}

	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Upgrade to Wordpress Media Library.', 'youzer' ),
            'type'  => 'openBox'
        )
    );

	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => "This operation will move Youzer media to WordPress Media Library to optimize our media system and make it much more faster.<span style='background: #F44336; color: #fff; display: block; padding: 15px; margin: 20px 0; border-radius: 3px;'>Before running these upgrades and for 100% safety in case anything happened please make sure to finish all these steps below!</span>1. In the previous versions we store 2 images on each upload one is orginal and the other one is compressed. By default this patch will move the compressed image and crop it. But if you wanna use the original images please go <strong>to Youzer Panel > General Settings Page > General Settings Tab > Optimization Settings Section</strong> and turn off the option <strong>Compress Images.</strong> You still can turn it on at any time and the compression will be applied on the new uploaded images. <br><br>2. Make a backup of your current database & current Youzer version.<br><br>3. Many of your beta testers that are using cloud storage plugins noticed that these plugins do not upload the new cropped images we created, they only upload original images, so if you wanna benefit from this optimization we recommend disabling them till you finish running the patches.<br><br>4. For more safety increase the maximum execution time to 1000 at least or turn it off so in case there was a big video file that need to be moved you make sure the server won't cancel the upgrade, once you finish the upgrade reset it! <a href='https://thimpress.com/knowledge-base/how-to-increase-maximum-execution-time-for-wordpress-site/'>How to increase Maximum Execution Time</a><br><br>5. Don't run 2 upgrade patches at the same time.<br><br>6. Don't forget to remove the maximum execution time function you added in the step 4 after you finish running all the patches.<br><br>8. If anything gone wrong please consider restoring the backup you already saved and open a new ticket on <a href='https://kainelabs.ticksy.com'>kainelabs.ticksy.com</a> and our support team will be more than happy to help you.<br><br>9. Don't close this page while the patch is running.<br><br>10. Hahaha!! Actually that's just a little request :) If you don't mind can you please consider taking a few seconds to give us a 5-Star rating and a good review, we really dedicated the whole last month working on this upgrade day and night to make it possible, and your review will really motivate us a lot to keep up the good working and keep adding new features and improving Youzer more and more! ( <a href='https://codecanyon.net/item/youzer-new-wordpress-user-profiles-era/reviews/19716647'>Youzer Reviews</a> )",
        'button_title' => __( 'Upgrade Now', 'youzer' ),
        'button_data' => array(
        	'step' => 1,
        	'total' => $total,
        	'items' => 'Files',
        	'run-patch' => 'true',
        	'action' => 'yz_patche_move_to_new_wp_media_library',
        	'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', 1 )
        ),
        'id'    => 'yz-run-media-patch3',
        'type'  => 'button'
    );

    $Yz_Settings->get_field( $button_args );

    // Include Setup File.
    require_once dirname( YOUZER_FILE ) .  '/includes/public/core/class-yz-setup.php';

    // Init Setup Class.
    $Youzer_Setup = new Youzer_Setup();

    // Build Database.
    $Youzer_Setup->build_database_tables();

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Migrating Ajax Call.
 */
add_action( 'wp_ajax_yz_patche_move_to_new_wp_media_library', 'yz_patche_move_to_new_wp_media_library' );

function yz_patche_move_to_new_wp_media_library() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = yz_patche_move_to_new_wp_library_media_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
        yz_update_option( 'yz_patch_new_wp_media_library', true );
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

/**
 * Migration Process.
 */
function yz_patche_move_to_new_wp_library_media_process_step( $step, $per_step, $total ) {

	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb, $yz_upload_component;

	    // Get Global Request
		$files = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "yz_media LIMIT $per_step OFFSET $offset", ARRAY_A );

		if ( empty( $files ) ) {
			return false;
		}

		foreach ( $files as $file ) {

			// bp_activity_delete_meta( $file['item_id'], 'yz_attachments' );
			// bp_messages_delete_meta( $file['item_id'], 'yz_attachments' );
			// bp_activity_delete_meta( $file['item_id'], 'yz_attachments_data' );
			// bp_messages_delete_meta( $file['item_id'], 'yz_attachments_data' );
			// $wpdb->update( $wpdb->prefix . 'yz_media', array( 'media_id' => '0' ), array( 'id' => $file['id'] ) );

			// continue;

			if ( ! empty( $file['media_id'] ) ) {
				continue;
			}

			$update = array();

			switch ( $file['component'] ) {

				case 'activity':

					$activity = new BP_Activity_Activity( $file['item_id'] );
					$update['source'] = $activity->type;
					$update['user_id'] = $activity->user_id;
					$update['component'] = $activity->component;

					if ( $activity->component == 'groups' ) {
						$yz_upload_component = array( 'component' => 'groups', 'group_id' => $activity->item_id );
					} else {
						$yz_upload_component = array( 'component' => 'activity' );
					}

					$result = yzc_move_attachments( $activity, $file, 'activity' );

					if ( ! empty( $result['media_id'] ) ) {
						$update['media_id'] = $result['media_id'];
					}

					$update['data'] = $result['data'];

					break;

				case 'comment':

					$activity = new BP_Activity_Activity( $file['item_id'] );
					$update['user_id'] = $activity->user_id;
					$update['source'] = 'activity_comment';
					$parent = new BP_Activity_Activity( $activity->item_id );

					if ( $parent->component == 'groups' ) {
						$yz_upload_component = array( 'component' => 'groups', 'group_id' => $parent->item_id );
					} else {
						$yz_upload_component = array( 'component' => 'comment' );
					}

					$result = yzc_move_attachments( $activity, $file, 'comment' );

					if ( ! empty( $result['media_id'] ) ) {
						$update['media_id'] = $result['media_id'];
					}

					$update['data'] = $result['data'];

					break;

				case 'message':

					$yz_upload_component = array( 'component' => 'message' );
					$message = new BP_Messages_Message( $file['item_id'] );
					$update['user_id'] = $message->sender_id;
					$update['source'] = $message->thread_id;

					// Get File Data.
					$image = maybe_unserialize( stripcslashes( $file['src'] ), true );

					// Get Attachment ID.
					$attachment_id = yzc_wml_upload( $image );

				    if ( $attachment_id ) {

						$update['media_id'] = $attachment_id;

						$attachments = array( $attachment_id => 1 );

						if ( $file['type'] == 'file' ) {
							if ( ! empty( $file['data'] ) ) {
								$update['data'] = $file['data'];
								$attachments[ $attachment_id ] = maybe_unserialize( $file['data'] );
							}
						}

						bp_messages_update_meta( $file['item_id'], 'yz_attachments', $attachments );

					}

					break;

			}

			if ( ! empty( $update ) ) {
				$wpdb->update( $wpdb->prefix . 'yz_media', $update, array( 'id' => $file['id'] ) );
			}

		}

	}

	return $more;

}

/**
 * Move Temporary Files To The Main Attachments Directory.
 */
function yzc_move_attachments( $activity, $attachment, $component ) {

	global $YZ_upload_dir, $yz_upload_source;

	$attachment_data = 0;

	// Get File Data.
	$file = maybe_unserialize( stripcslashes( $attachment['src'] ), true );

	// Get Source.
	$yz_upload_source = $activity->type;

	// Get Attachment ID.
	if ( $activity->type != 'activity_video' ) {

		$attachment_id = yzc_wml_upload( $file );

	    if ( ! $attachment_id ) {
	    	return;
		}

	} else {

		if ( $attachment['type'] == 'file' ) {

			$attachment_id = 0;
			$video_data = array();

			if ( isset( $file['provider'] ) && $file['provider'] != 'local' ) {

				if ( isset( $file['provider'] ) ) {
					$video_data['provider'] = $file['provider'];
				}

				if ( isset( $file['original'] ) ) {
					$video_data['id'] = $file['original'];
				}

				if ( isset( $file['thumbnail']['small'] ) ) {
					$video_data['thumbnail'] = $file['thumbnail']['small'];
				}

				if ( isset( $file['thumbnail']['medium'] ) ) {
					$video_data['medium'] = $file['thumbnail']['medium'];
				}

				if ( isset( $file['thumbnail']['large'] ) ) {
					$video_data['full'] = $file['thumbnail']['large'];
				}

			}


			if ( ! empty( $video_data ) ) {
				$attachment_data = serialize( $video_data );
				bp_activity_update_meta( $attachment['item_id'], 'yz_attachments', array( $video_data ) );
			}

		} else {
			$attachment_id = yzc_wml_upload( $file );
		}

	}

	// Add Post Type.
	// if ( $data['type'] == 'file' ) {
	// 	$data['data'] = $file;
	// }

	if ( $component == 'activity' || $component == 'groups' ) {

		// Get Attachment ID.
		$attachments = bp_activity_get_meta( $attachment['item_id'], 'yz_attachments' );

		switch ( $attachment['type'] ) {

			case 'image':

				if ( ! empty( $attachments ) ) {
					$attachments[ $attachment_id ] = 1;
				} else {
					$attachments = array( $attachment_id => 1 );
				}

				break;

			case 'video':

				if ( $activity->type != 'activity_file' ) {

					$attachments = array();

					if ( ! empty( $attachment_id ) ) {

						$provider = isset( $file['provider'] ) ? $file['provider'] : 'local';

						$attachment_data = array( 'provider' => $provider );

						// Set Video As Uploaded Localy.
						$attachments[ $attachment_id ] = array( 'provider' => $provider );

						if ( isset( $file['thumbnail'] ) && ! empty( $file['thumbnail'] ) ) {

							// Set Original Image.
							$file['original'] = $file['thumbnail'];

							// Get Video Thumbnail.
							$video_thumbnail_id = yzc_wml_upload( $file );

							if ( ! empty( $video_thumbnail_id ) ) {
								$attachment_data['thumbnail'] = $video_thumbnail_id;
								$attachments[ $attachment_id ]['thumbnail'] = $video_thumbnail_id;
							}

						}

						$attachment_data = serialize( $attachment_data );
					}

				} else {
					$attachments = array( $attachment_id => 1 );
				}

				break;

			case 'audio':
				$attachments = array( $attachment_id => 1 );
				break;

			case 'file':
				$attachments = $activity->type != 'activity_video' ? array( $attachment_id => 1 ) : '';
				break;
		}

		if ( $activity->type == 'activity_file' && ! empty( $attachment['data'] ) ) {
			$attachment_data = $attachment['data'];
			$attachments[ $attachment_id ] = maybe_unserialize( $attachment['data'] );
		}

		if ( ! empty( $attachments ) ) {
			bp_activity_update_meta( $attachment['item_id'], 'yz_attachments', $attachments );
		}

	} elseif ( $component == 'comment' ) {

		if ( ! empty( $attachment_id ) ) {

			$attachments = array( $attachment_id => 1 );

			if ( $attachment['type'] == 'file' ) {
				if ( ! empty( $attachment['data'] ) ) {
					$attachment_data = $attachment['data'];
					$attachments[ $attachment_id ] = maybe_unserialize( $attachment['data'] );
				}
			}

			bp_activity_update_meta( $attachment['item_id'], 'yz_attachments', $attachments );

		}

	}

	return array( 'media_id' => $attachment_id, 'data' => $attachment_data );

}

function yzc_upgrade_get_images_sizes( $sizes ) {

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
function yzc_wml_upload( $file ) {

	$original_file = $file['original'];

	// Get Uploaded File extension
	$ext = strtolower( pathinfo( $original_file, PATHINFO_EXTENSION ) );

	if ( yz_option( 'yz_compress_images', 'on' ) == 'on' && in_array( $ext, array( 'jpg', 'jpeg', 'png' ) ) ) {
		$original_file = isset( $file['thumbnail'] ) && ! empty( $file['thumbnail'] ) ? $file['thumbnail'] : $file['original'];
		$new_name = str_replace( array( '_thumb', '-thumb' ), '', $original_file );
	} else {
		$new_name = $file['original'];
	}

    // Get Uploads Directory Path.
    $upload_dir = wp_upload_dir();

	// Get File Path.
	$upload_path = $upload_dir['baseurl'] . '/youzer/' ;

	$file_url = $upload_path . $original_file;

	// Disable Wordpress Media Default Sizes.
	add_filter( 'intermediate_image_sizes_advanced', 'yzc_upgrade_get_images_sizes' );

	// Set Upload Directory to Youzify Directory.
	add_filter( 'upload_dir', 'youzify_upgrade_upload_directory' );

	$attachment_id = '';

	// Upload File to WordPress Media Library.
	$upload_file = wp_upload_bits( $new_name, null, file_get_contents( $file_url ) );

	if ( ! $upload_file['error'] ) {

		// Get File Type.
		$wp_filetype = wp_check_filetype( $original_file, null );

		// Get Attachment Args.
		$args = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace( '/\.[^.]+$/', '', $original_file ),
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
	remove_filter( 'upload_dir', 'youzify_upgrade_upload_directory' );

	return $attachment_id;
}

/***
 * check for youzer then check if the user media tables are installed.
 */
add_action( 'yz_patches_settings', 'yz_patch_move_profile_to_new_wp_media_library' );

function yz_patch_move_profile_to_new_wp_media_library() {

    if ( ! yz_option( 'yz_patch_new_media_system2' ) || yz_option( 'yz_patch_new_profile_wp_media_library' ) ) {
        return false;
    }

    global $Yz_Settings, $wpdb, $bp;

	$already_installed = yz_option( 'yz_patch_new_wp_media_library' );

	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Move Profile Widgets Images to Wordpress Media Library.', 'youzer' ),
            'type'  => 'openBox'
        )
    );

	$total = count_users();

	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => 'This operation will add move Youzer Profiles Media to WordPress Media Library to optimize our media system and make it much more faster.',
        'button_title' => __( 'Upgrade Now', 'youzer' ),
        'button_data' => array(
        	'step' => get_option( 'yz_wpml_patch_last_user', 1 ),
        	'items' => 'Users',
        	'run-patch' => 'true',
        	'action' => 'yz_patch_move_profile_to_wpml_process_step',
        	'total' => $total['total_users'],
        	'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', 1 ),
        ),
        'id'    => 'yz-run-media-patch4',
        'type'  => 'button'
    );

    $Yz_Settings->get_field( $button_args );

}

/**
 * Migration Process.
 */
add_action( 'wp_ajax_yz_patch_move_profile_to_wpml_process_step', 'yz_patch_move_profile_to_wpml_process_step' );

function yz_patch_move_profile_to_wpml_process_step() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = yz_patche_move_profile_to_new_wp_library_media_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
        yz_update_option( 'yz_patch_new_profile_wp_media_library', true );
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}
function yz_patche_move_profile_to_new_wp_library_media_process_step( $step, $per_step, $total ) {

	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		global $wpdb, $yz_upload_source;

		$more = true;

		// Get the results
		$users = get_users( array( 'fields' => 'id', 'number' => $per_step, 'offset' => $offset ) );

		$fields = array( 'wg_about_me_photo' => 'profile_about_me_widget', 'wg_project_thumbnail' => 'profile_project_widget', 'wg_quote_img' => 'profile_quote_widget', 'wg_link_img' => 'profile_link_widget', 'youzer_portfolio' => 'profile_portfolio_widget', 'youzer_slideshow' => 'youzer_slideshow_widget' );

		foreach ( $users as $user_id ) {

			// Set Current User ID.
			update_option( 'yz_wpml_patch_last_user', $user_id );

			foreach ( $fields as $field_id => $source ) {

				// Get Data.
				$data = get_the_author_meta( $field_id, $user_id );

				if ( ! empty( $data ) ) {

					// Get Current Time.
					$time = bp_core_current_time();

					$yz_upload_source = $source;

					if ( in_array( $field_id, array( 'youzer_slideshow', 'youzer_portfolio' ) ) ) {

						foreach ( $data as $key => $item ) {

							if ( ! is_array( $item ) ) {
								continue;
							}

							$attachment_id = yzc_wml_upload( $item );

							if ( ! empty( $attachment_id ) ) {

								$data[ $key ]['image'] = $attachment_id;

								$wpdb->insert( $wpdb->prefix . 'yz_media', array( 'item_id' => 0, 'privacy' => 'public', 'component' => 'profile', 'type' => 'image', 'user_id' => $user_id, 'source' => $source, 'media_id' => $attachment_id, 'time' => $time ) );

								if ( isset( $data[ $key ]['original'] ) ) {
									unset($data[ $key ]['original']);
								}
								if ( isset( $data[ $key ]['thumbnail'] ) ) {
									unset($data[ $key ]['thumbnail']);

								}
							}
						}

						if ( ! empty( $data ) ) {
							update_user_meta( $user_id, $field_id, $data );
						}

					} else {
						if ( is_array( $data ) ) {

							$attachment_id = yzc_wml_upload( $data );

							if ( ! empty( $attachment_id ) ) {
								update_user_meta( $user_id, $field_id, $attachment_id );
								$wpdb->insert( $wpdb->prefix . 'yz_media', array( 'item_id' => 0, 'privacy' => 'public', 'component' => 'profile', 'type' => 'image', 'user_id' => $user_id, 'source' => $source, 'media_id' => $attachment_id, 'time' => $time ) );
							}

						}
					}
				}
			}


		}

	}

	return $more;
}


/**
 * Change Default Upload Directory to the Youzer Directory.
 */
function youzify_upgrade_upload_directory( $upload_dir ) {

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