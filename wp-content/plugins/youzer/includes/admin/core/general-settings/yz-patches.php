<?php

/**
 * # Add Patches Settings Tab
 */
function yz_patches_settings_menu( $tabs ) {

	$tabs['patches'] = array(
   	    'id'    => 'patches',
   	    'icon'  => 'fas fa-magic',
   	    'function' => 'yz_patches_settings',
   	    'title' => __( 'Patches Settings', 'youzer' ),
    );

    return $tabs;

}

add_filter( 'yz_panel_general_settings_menus', 'yz_patches_settings_menu', 9999 );

/**
 * Move WP Fields To Buddypress Xprofile Fields
 */
function yz_patche_move_wp_fields_to_bp_settings() {

    if ( ! yz_option( 'install_youzer_2.1.5_options' ) ) {
        return false;
    }

    if ( yz_option( 'yz_patch_move_wptobp' ) ) {
    	return;
    }

    global $Yz_Settings;

	$already_installed = yz_option( 'yz_patch_move_wptobp' );

	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => sprintf( __( 'Move Wordpress fields to Buddypress xprofile fields. %s', 'youzer' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

    // Get User Total Count.
	$user_count_query = count_users();
	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => __( 'This is a required step to move all the previous profile & contact fields values to the new generated xprofile fields. please note that this operation might take a long time to finish because it will go through all the users in database one by one and update their fields.', 'youzer' ),
        'button_title' => __( 'Update Fields', 'youzer' ),
        'button_data' => array(
        	'step' => 1,
        	'total' => $user_count_query['total_users'],
        	'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', 5 ),
        ),
        'id'    => 'yz-run-wptobp-patch',
        'type'  => 'button'
    );

	if ( $already_installed ) {
		unset( $button_args['button_title'] );
	}

    $Yz_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $xprofile_fields_installed = yz_option( 'yz_install_xprofile_groups' );

    if ( ! $xprofile_fields_installed ) {

	    // Include Setup File.
	    require_once dirname( YOUZER_FILE ) .  '/includes/public/core/class-yz-setup.php';

	    // Init Setup Class.
	    $Youzer_Setup = new Youzer_Setup();

	    // Install Xprofile Fields.
	    $Youzer_Setup->create_xprofile_groups();

    }

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

	?>

	<script type="text/javascript">
( function( $ ) {

        jQuery( document ).ready( function(){

		/**
		 * Process Updating Fields.
		 */
		$.process_step  = function( step, perstep, total, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'yz_patche_move_wp_fields_to_bp',
					step: step,
					total: total,
					perstep: perstep,
				},
				dataType: "json",
				success: function( response ) {

					var export_form = $( '#yz-run-wptobp-patch' );

					if ( 'done' == response.step ) {

						export_form.addClass( 'yz-is-updated' );

						// window.location = response.url;
						export_form.html( '<i class="fas fa-check"></i>Done !' );

					} else {

						$('.yz-button-progress').animate({
							width: response.percentage + '%',
						}, 50, function() {
							// Animation complete.
						});

						var total_users = ( response.step * response.perstep ) - response.perstep,
							users = total_users < response.total ? total_users : response.total;

						export_form.find( '.yz-items-count' ).html(users);

						$.process_step( parseInt( response.step ), parseInt( response.perstep ), parseInt( response.total ), self );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}

		$( 'body' ).on( 'click', '#yz-run-wptobp-patch', function(e) {

			if ( $( this ).hasClass( 'yz-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			var per_step = $( this ).data( 'perstep' );
			var total = $( this ).data( 'total' );

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating <div class="yz-button-progress"></div><span class="yz-items-count">' + 1 + '</span>' + ' / ' + total + ' Users' );

			// Start The process.
			$.process_step( 1, per_step, total, self );

		});

	});

})( jQuery );
	</script>

	<?php
}

add_action( 'yz_patches_settings', 'yz_patche_move_wp_fields_to_bp_settings', 99 );


/**
 * Process batch exports via ajax
 */
function yz_patche_move_wp_fields_to_bp() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	// $ret = $export->process_step( $step );
	$ret = yz_patch_move_wptobp_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_yz_patche_move_wp_fields_to_bp', 'yz_patche_move_wp_fields_to_bp' );


function yz_patch_move_wptobp_process_step( $step, $per_step, $total  ) {

	// Init Vars
	$more = false;
	// $done = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		// main user query
		$args = array(
		    'fields'    => 'id',
		    'number'    => $per_step,
		    'offset'    => $offset
		);

		// Get the results
		$authors = get_users( $args );

	    // Get Profile Fields.
		$profile_fields = yz_option( 'yz_xprofile_contact_info_group_ids' );
		$contact_fields = yz_option( 'yz_xprofile_profile_info_group_ids' );

		$all_fields = (array) $contact_fields + (array) $profile_fields;

		// Remove Group ID Field.
		unset( $all_fields['group_id'] );

		// Update Fields.
		foreach ( $authors as $user_id ) {

			foreach ( $all_fields as $user_meta => $field_id ) {

				// Get Old Value.
				$old_value = get_the_author_meta( $user_meta, $user_id );

				if ( empty( $old_value ) ) {
					continue;
				}

				// Set New Value.
		        xprofile_set_field_data( $field_id, $user_id, $old_value );

		        // Delete Old Value.
				// delete_user_meta( $user_id, $user_meta );

			}

		}

	} else {
	    yz_update_option( 'yz_patch_move_wptobp', true );
	}

	return $more;
}

/**
 * Move to the new media system
 **/

/***
 * check for youzer then check if the user media tables are installed.
 */
add_action( 'yz_patches_settings', 'yz_patch_move_to_new_media_system' );

function yz_patch_move_to_new_media_system() {

    if ( yz_option( 'yz_patch_new_media_system' ) ) {
        return false;
    }

    global $Yz_Settings, $wpdb, $bp;

	$already_installed = yz_option( 'yz_patch_new_media_system' );

	$total = $wpdb->get_var( "SELECT count(*) FROM {$bp->activity->table_name} WHERE type IN ( 'activity_status', 'activity_photo', 'activity_link', 'activity_slideshow', 'activity_quote', 'activity_video', 'activity_audio', 'activity_file', 'new_cover', 'new_avatar')" );


	if ( ! $already_installed && $total == 0 ) {

	    yz_update_option( 'yz_patch_new_media_system', true );

		// Install New Widget.
		$overview_widgets = yz_options( 'yz_profile_main_widgets' );
		$sidebar_widgets  = yz_options( 'yz_profile_sidebar_widgets' );
		$all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

		$install_widget = true;

		foreach ( $all_widgets as $widget ) {
			if ( key( $widget ) == 'wall_media' )  {
				$install_widget = false;
			}
		}

		if ( $install_widget == true ) {
			$sidebar_widgets[] = array( 'wall_media' => 'visible' );
			update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets );
		}

	}

	$already_installed = yz_option( 'yz_patch_new_media_system' );


	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => sprintf( __( 'Migrate to the new media system. %s', 'youzer' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => __( 'Please note that this operation might take a long time to finish because it will move all the old activity posts media ( images, videos, audios, files ) to a new database more organized and structured.<br><span style="color: red;text-transform: initial;">Make sure to enable the following functions on your server before running this patch : <b>CURL</b> and <b>getimagesize</b></span>', 'youzer' ),
        'button_title' => __( 'Migrate Now', 'youzer' ),
        'button_data' => array(
        	'run-patch' => 'true',
        	'step' => 1,
        	'items' => 'Posts',
        	'action' => 'yz_patche_move_to_new_media',
        	'total' => $total,
        	'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', 10 ),
        ),
        'id'    => 'yz-run-media-patch',
        'type'  => 'button'
    );

	if ( $already_installed ) {
		unset( $button_args['button_title'] );
	}

    $Yz_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $xprofile_fields_installed = yz_option( 'yz_is_media_table_installed' );

    if ( ! $xprofile_fields_installed ) {

	    // Include Setup File.
	    require_once dirname( YOUZER_FILE ) .  '/includes/public/core/class-yz-setup.php';

	    // Init Setup Class.
	    $Youzer_Setup = new Youzer_Setup();

	    // Build Database.
	    $Youzer_Setup->build_database_tables();

    }

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/***
 * check for youzer then check if the user media tables are installed.
 */
add_action( 'yz_patches_settings', 'yz_patch_move_to_new_media_system2' );

function yz_patch_move_to_new_media_system2() {

    if ( yz_option( 'yz_patch_new_media_system2' ) ) {
        return false;
    }

    global $Yz_Settings, $wpdb, $bp;

	$already_installed = yz_option( 'yz_patch_new_media_system2' );

	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Upgrade media system database.', 'youzer' ),
            'type'  => 'openBox'
        )
    );

	$total = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . "yz_media" );


	if ( ! $already_installed && $total == 0 ) {
	    yz_update_option( 'yz_patch_new_media_system2', true );
	}

	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => __( 'This operation will add new media database table columns to optimize our media system and make it faster.', 'youzer' ),
        'button_title' => __( 'Upgrade Now', 'youzer' ),
        'button_data' => array(
        	'run-patch' => 'true',
        	'step' => 1,
        	'items' => 'Posts',
        	'action' => 'yz_patche_move_to_new_media2',
        	'total' => $total,
        	'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', 10 ),
        ),
        'id'    => 'yz-run-media-patch2',
        'type'  => 'button'
    );

    $Yz_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $installed = yz_option( 'yz_install_yz_media_new_tables' );

    if ( ! $installed ) {

	    // Include Setup File.
	    require_once dirname( YOUZER_FILE ) .  '/includes/public/core/class-yz-setup.php';

	    // Init Setup Class.
	    $Youzer_Setup = new Youzer_Setup();

	    // Build Database.
	    $Youzer_Setup->build_database_tables();

    }

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}


/**
 * Migrating Ajax Call.
 */
function yz_patche_move_to_new_media_ajax() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = yz_patche_move_to_new_media_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_yz_patche_move_to_new_media', 'yz_patche_move_to_new_media_ajax' );

/**
 * Migrating Ajax Call.
 */
function yz_patche_move_to_new_media_ajax2() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = yz_patche_move_to_new_media_process_step2( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_yz_patche_move_to_new_media2', 'yz_patche_move_to_new_media_ajax2' );

/**
 * Migration Process.
 */
function yz_patche_move_to_new_media_process_step2( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$files = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "yz_media LIMIT $per_step OFFSET $offset", ARRAY_A );

		if ( empty( $files ) ) {
			return false;
		}

		foreach ( $files as $file ) {

			$update = array();

			if ( $file['type'] == 'none' ) {
				// Get File Source.
				$src = maybe_unserialize( $file['src'] );
				$update['type'] = yz_get_file_type( $src['original'] );
			}

			if ( $file['component'] == 'activity' ) {
				$update['privacy'] = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = {$file['item_id']}" );
			} elseif ( $file['component'] == 'message' ) {
				$update['privacy'] = 'onlyme';
			} elseif( $file['component'] == 'comment' ) {
				$activity_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = {$file['item_id']}" );
				$update['privacy'] = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = $activity_id" );
			}

			if ( empty( $update['privacy'] ) ) {
				$update['privacy'] = 'public';
			}

			if ( ! empty( $update ) ) {
				$wpdb->update( $wpdb->prefix . 'yz_media', $update, array( 'id' => $file['id'] ) );
			}

		}

	} else {
        yz_update_option( 'yz_patch_new_media_system2', true );
	}

	return $more;
}


/**
 * Migration Process.
 */
function yz_patche_move_to_new_media_process_step( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$posts = $wpdb->get_results( "SELECT id, content, type, date_recorded FROM {$bp->activity->table_name} WHERE type IN ( 'activity_status', 'activity_photo', 'activity_link', 'activity_slideshow', 'activity_quote', 'activity_video', 'activity_audio', 'new_avatar', 'new_cover', 'activity_file' ) LIMIT $per_step OFFSET $offset", ARRAY_A );

		if ( empty( $posts ) ) {
			return false;
		}


		global $YZ_upload_dir;

		// Image Extensions
		$images_ext = array( 'jpeg', 'jpg', 'png', 'gif' );

		foreach ( $posts as $post ) {

			$atts = array();

			// Get Attachments
			if ( $post['type'] == 'new_avatar' ) {

				$avatar = bp_activity_get_meta( $post['id'], 'yz-avatar' );

				if ( empty( $avatar ) ) {
					continue;
				}

				$atts[0] = yz_patch_move_media_get_image_args( $avatar );


			} elseif( $post['type'] == 'new_cover' ) {

				$cover = bp_activity_get_meta( $post['id'], 'yz-cover-image' );

				if ( empty( $cover ) ) {
					continue;
				}

				$atts[0] = yz_patch_move_media_get_image_args( $cover );

			} elseif( $post['type'] == 'activity_status' ) {

				if ( empty( $post['content'] ) ) {
					continue;
				}

				$embed_exists = false;

				$supported_videos = yz_attachments_embeds_videos();

				// Get Post Urls.
				if ( preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $post['content'], $match ) ) {

					foreach ( array_unique( $match[0] ) as $link ) {

						foreach ( $supported_videos as $provider => $domain ) {

							$video_id = yz_get_embed_video_id( $provider, $link );

							if ( ! empty( $video_id ) ) {

								$embed_exists = true;

								$video_data = array( 'provider' => $provider, 'original' => $video_id );

								$thumbnails = yz_get_embed_video_thumbnails( $provider, $video_id );

								if ( ! empty( $thumbnails ) ) {
									$video_data['thumbnail'] = $thumbnails;
								}

								$atts[] = $video_data;
							}

						}

					}

				}

				// Change Activity Type from status to video.
				if ( $embed_exists ) {
					$activity = new BP_Activity_Activity( $post['id'] );
					$activity->type = 'activity_video';
					$activity->save();
				}

			} else {
				$atts = bp_activity_get_meta( $post['id'], 'attachments' );
			}

			if ( empty( $atts ) ) {
				continue;
			}

			$atts = maybe_unserialize( $atts );

			foreach ( $atts as $attachment ) {

				// Get Data.
				// $data = array( 'file_size' => $attachment['file_size'] );

				$original_image = isset( $attachment['original'] ) ? $attachment['original'] : ( isset( $attachment['file_name'] ) ? $attachment['file_name'] :  '' );

				if ( empty( $original_image ) ) {
					continue;
				}

				$src = array( 'original' => $original_image );

				$thumbnail_image = isset( $attachment['thumbnail'] ) && file_exists( $YZ_upload_dir . $attachment['thumbnail'] ) ? $attachment['thumbnail'] : '';

				if ( ! empty( $thumbnail_image ) ) {
					$src['thumbnail'] = $thumbnail_image;
				}

				// Add Video Provider if Found.
				if ( isset( $attachment['provider'] ) ) {
					$src['provider'] = $attachment['provider'];
				}

				if ( $post['type'] == 'activity_video' && ! isset( $src['provider'] ) ) {
					$src['provider'] = 'local';
				}

				$ext = strtolower( pathinfo( $original_image, PATHINFO_EXTENSION ) );

				// Add Image Resolutions.
				if ( ! in_array( $post['type'], array( 'activity_audio', 'activity_video', 'activity_file', 'activity_status' ) ) && in_array( $ext, $images_ext ) ) {
					$attachment['size'] = yz_get_image_size( $original_image );
				}

				if ( isset( $attachment['original'] ) ) {
					unset( $attachment['original'] );
				}

				if ( isset( $attachment['thumbnail'] ) ) {
					unset( $attachment['thumbnail'] );
				}

				// Unset Thumbnail Data.
				if ( isset( $attachment['provider'] ) ) {
					unset( $attachment['provider'] );
				}

				if ( $post['type'] != 'activity_comment' ) {
					$privacy = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = {$post['id']}" );
				} else {
					$ac_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = {$post['id']}" );
					$privacy = $wpdb->get_var( "SELECT privacy from {$bp->activity->table_name} WHERE id = $ac_id" );
				}

				if ( empty( $privacy ) ) {
					$privacy = 'public';
				}

				$args = array(
					'src' => serialize( $src ),
					'data' => ! empty( $attachment ) ? serialize( $attachment ) : '',
					'item_id' => $post['id'],
					'privacy' => $privacy,
					'type' => yz_get_file_type( $attachment['original'] ),
					'time' => $post['date_recorded'],
				);

				// Set New Hashtag Count.
				$result = $wpdb->insert( $wpdb->prefix . 'yz_media', $args );

			}

		}

		// Delete Old Value Here.

	} else {

        yz_update_option( 'yz_patch_new_media_system', true, 'no' );
        yz_update_option( 'yz_patch_new_media_system2', true, 'no' );

		// Install New Widget.
		$overview_widgets = yz_options( 'yz_profile_main_widgets' );
		$sidebar_widgets  = yz_options( 'yz_profile_sidebar_widgets' );
		$all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

		$install_widget = true;

		if ( isset( $all_widgets['wall_media'] ) ) {
			$install_widget = false;
		}

		if ( $install_widget == true ) {
			$sidebar_widgets['wall_media'] = 'visible';
			update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets );
		}

	}

	return $more;
}


function yz_patch_move_media_get_image_args( $image_url ) {

	global $YZ_upload_dir;

	$image_name = basename( $image_url );

	$image_path = $YZ_upload_dir . $image_name;

	// Get Avatar Args.
	$args = array( 'original' => $image_name, 'file_size' => filesize( $image_path ), 'real_name' => $image_name );

	// Get File Size
	$file_size = getimagesize( $image_path );

	if ( ! empty( $file_size ) ) {
		$args['size'] = array( 'width' => $file_size[0], 'height' => $file_size[1] );
	}

	return $args;

}



/***
 * Optimize Data Baze.
 */
add_action( 'yz_patches_settings', 'yz_patch_optimize_database' );

function yz_patch_optimize_database() {

	// delete_option( 'yz_patch_optimize_database' );

    if ( yz_option( 'yz_patch_optimize_database' ) ) {
        return false;
    }

    global $Yz_Settings, $wpdb;

	$already_installed = yz_option( 'yz_patch_optimize_database' );

	$patche_status = $already_installed ? '<span class="yz-title-status">' . __( 'Installed', 'youzer' ) . '</span>' : '';

    $Yz_Settings->get_field(
        array(
            'title' => sprintf( __( 'Optimize Youzer Database. %s', 'youzer' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

	// $total = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "options WHERE autoload = 'yes' AND option_name Like 'yz_%'" );

	$button_args = array(
    	'class' => 'yz-wild-field',
        'desc'  => __( 'Before many youzer options were called on all the pages by running this patch they will be called only when needed. This operation will increase your website pages speed.', 'youzer' ),
        'button_title' => __( 'Optimize Now', 'youzer' ),
        'button_data' => array(
        	'run-single-patch' => 'true',
        	// 'step' => 1,
        	// 'items' => 'Options',
        	'action' => 'yz_patch_optimize_database',
        	// 'total' => $total,
        	// 'perstep' => apply_filters( 'yz_patch_move_wptobp_per_step', $total ),
        ),
        'id'    => 'yz-run-optimize-database-patch',
        'type'  => 'button'
    );

	if ( $already_installed ) {
		unset( $button_args['button_title'] );
	}

    $Yz_Settings->get_field( $button_args );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Migrating Ajax Call.
 */
function yz_patche_optimize_database_ajax() {

	global $wpdb;

	// Remove Autoload Functions.
	$wpdb->query( "UPDATE " . $wpdb->prefix . "options SET autoload = 'no' WHERE option_name LIKE 'yz_%' " );

    /**
     * Save Unallowed Post Types.
     */
	$post_types = array(
        'activity_link'         => yz_option( 'yz_enable_wall_link', 'on' ),
        'activity_file'         => yz_option( 'yz_enable_wall_file', 'on' ),
        'activity_audio'        => yz_option( 'yz_enable_wall_audio', 'on' ),
        'activity_photo'        => yz_option( 'yz_enable_wall_photo', 'on' ),
        'activity_video'        => yz_option( 'yz_enable_wall_video', 'on' ),
        'activity_quote'        => yz_option( 'yz_enable_wall_quote', 'on' ),
        'activity_giphy'        => yz_option( 'yz_enable_wall_giphy', 'on' ),
        'activity_status'       => yz_option( 'yz_enable_wall_status', 'on' ),
        'activity_update'       => yz_option( 'yz_enable_wall_status', 'on' ),
        'new_cover'             => yz_option( 'yz_enable_wall_new_cover', 'on' ),
        'activity_slideshow'    => yz_option( 'yz_enable_wall_slideshow', 'on' ),
        'new_avatar'            => yz_option( 'yz_enable_wall_new_avatar', 'on' ),
        'new_member'            => yz_option( 'yz_enable_wall_new_member', 'on' ),
        'joined_group'          => yz_option( 'yz_enable_wall_joined_group', 'on' ),
        'new_blog_post'         => yz_option( 'yz_enable_wall_new_blog_post', 'on' ),
        'created_group'         => yz_option( 'yz_enable_wall_created_group', 'on' ),
        'updated_profile'       => yz_option( 'yz_enable_wall_updated_profile', 'off' ),
        'new_blog_comment'      => yz_option( 'yz_enable_wall_new_blog_comment', 'off' ),
        'friendship_created'    => yz_option( 'yz_enable_wall_friendship_created', 'on' ),
        'friendship_accepted'   => yz_option( 'yz_enable_wall_friendship_accepted', 'on' ),
    );


    if ( class_exists( 'Woocommerce' ) ) {
        $post_types['new_wc_product'] = __( 'New Product', 'youzer' );
        $post_types['new_wc_purchase'] = __( 'New Purchase', 'youzer' );
    }

    if ( class_exists( 'bbPress' ) ) {
        $post_types['bbp_topic_create'] = __( 'Forum Topic', 'youzer' );
        $post_types['bbp_reply_create'] = __( 'Forum Reply', 'youzer' );
    }

	$unallowed_activities = array();

	foreach ( $post_types as $activity_type => $activity_visibilty ) {

		if ( $activity_visibilty != 'on' ) {

			$unallowed_activities[] = $activity_type;

			if ( $activity_type == 'activity_status' ) {
				$unallowed_activities[] = 'activity_update';
			}

		}

	}

	if ( in_array( 'friendship_accepted', $unallowed_activities ) && in_array( 'friendship_created', $unallowed_activities ) ) {
		$unallowed_activities[] = 'friendship_accepted,friendship_created';
		foreach ( array( 'friendship_accepted', 'friendship_created' ) as $type ) {
			if ( ( $key = array_search( $type, $unallowed_activities ) ) !== false) {
				unset( $unallowed_activities[ $key ] );
			}
		}
	}

	if ( empty( $unallowed_activities ) ) {
		delete_option( 'yz_unallowed_activities' );
	} else {
		update_option( 'yz_unallowed_activities', $unallowed_activities, 'no' );
	}

	// Save Tabs.
	$tabs = array();
	$old_tabs = yz_get_profile_primary_nav();
	$default_tabs = yz_profile_tabs_default_value();

	foreach ( $old_tabs as $old_tab ) {

		if ( $slug == 'activity' ) {
			continue;
		}

		$slug = $old_tab['slug'];


		$position = yz_option( 'yz_' . $slug . '_tab_order' );
		if ( ! empty( $position ) && $position != $old_tab['position'] ) {
			$tabs[ $slug ]['position'] = $position;
		}

		$title = yz_option( 'yz_' . $slug  . '_tab_title' );
		$old_title = _bp_strip_spans_from_title( $old_tab['name'] );
		if ( ! empty( $title ) && $title != $old_title ) {
			$count = strstr( $old_title, '<span' );
			$tabs[ $slug ]['name'] = ! empty( $count ) ? $title . $count : $title;
		}

		$visibility = yz_options( 'yz_display_' . $slug . '_tab' );
		if ( empty( $visibility ) || $visibility != 'on' ) {
			$tabs[ $slug ]['visibility'] = 'off';
		}

		$icon = yz_options( 'yz_' . $slug . '_tab_icon' );
		if ( ! empty( $icon ) && $icon != 'fas fa-globe-asia' && $icon != 'globe' && $icon != 'fas fa-globe' ) {
			if ( isset( $default_tabs[ $slug ]['icon'] ) ) {
				if ( $icon != $default_tabs[ $slug ]['icon'] ) {
					$tabs[ $slug ]['icon'] = $icon;
				}
			} else {
				$tabs[ $slug ]['icon'] = $icon;
			}
		}

		$deleted = yz_option(  'yz_delete_' . $slug . '_tab' );
		if ( ! empty( $deleted ) && $deleted == 'on' ) {
			$tabs[ $slug ]['deleted'] = 'on';
		}

	}

	if ( empty( $tabs ) ) {
		delete_option( 'yz_profile_tabs' );
	} else {
		update_option( 'yz_profile_tabs', $tabs, 'no' );
	}

    $hidden = array();
    $o_widgets = array();
    $s_widgets = array();

    $overview_widgets = yz_options( 'yz_profile_main_widgets' );
    $sidebar_widgets = yz_options( 'yz_profile_sidebar_widgets' );

    if ( ! empty( $overview_widgets ) ) {
		foreach ( $overview_widgets as $o_widget ) {
			if ( ! empty( $o_widget ) ) {
			    foreach ( $o_widget as $o_widget_name => $o_visibility ) {
			        $o_widgets[ $o_widget_name] = $o_visibility;
			    }
			}
		}
    }

    if ( ! empty( $sidebar_widgets ) ) {
	    foreach ( $sidebar_widgets as $s_widget ) {
	    	if ( ! empty( $s_widget ) ) {
		    	foreach ( $s_widget as $s_widget_name => $s_visibility ) {
		            $s_widgets[ $s_widget_name ] = $s_visibility;
		        }
	    	}
	    }
    }

    update_option( 'yz_profile_main_widgets', $o_widgets, 'no' );
    update_option( 'yz_profile_sidebar_widgets', $s_widgets, 'no' );

    $all_widgets = array_merge( $overview_widgets, $sidebar_widgets );

    if ( ! empty( $all_widgets ) ) {
	    foreach ( $all_widgets as $widget ) {
	    	if ( ! empty( $widget ) ) {
		        foreach ( $widget as $widget_name => $visibility ) {
		            if ( $visibility == 'invisible' ) {
		                $hidden[] = $widget_name;
		            }
		        }
		    }

	    }
    }

    if ( ! empty( $hidden ) ) {
        yz_update_option( 'yz_profile_hidden_widgets' , $hidden, 'no' );
    } else {
        yz_delete_option( 'yz_profile_hidden_widgets' );
    }

    yz_update_option( 'yz_patch_optimize_database', true );

	if ( ! yz_option( 'yz_install_yz_media_new_tables' ) ) {

		global $wpdb;

		$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $wpdb->prefix . "yz_media' AND column_name = 'component'"  );

		$row2 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $wpdb->prefix . "yz_media' AND column_name = 'type'"  );

		if ( empty( $row ) ) {
		   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "yz_media ADD component varchar(10) NULL DEFAULT 'activity'");
		}

		if ( empty( $row2 ) ) {
		   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "yz_media ADD type varchar(10) NULL DEFAULT 'none'");
		}

		update_option( 'yz_install_yz_media_new_tables', 1, 'no' );

	}

	echo json_encode( array( 'step' => 'done' ) ); exit;

}

add_action( 'wp_ajax_yz_patch_optimize_database', 'yz_patche_optimize_database_ajax' );


/**
 * Migration Process.
 */
function yz_patche_optimize_database_process_step( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$options = $wpdb->get_results( "SELECT option_id FROM " . $wpdb->prefix . "options WHERE autoload = 'yes' AND option_name LIKE 'yz_%' LIMIT $per_step OFFSET $offset", ARRAY_A );

		$options = wp_list_pluck( $options, 'option_id' );

		if ( empty( $options ) ) {
			return false;
		}


		$wpdb->query( "UPDATE " . $wpdb->prefix . "options SET autoload = 'no' WHERE option_name LIKE 'yz_%' " );

	} else {
        yz_update_option( 'yz_patch_optimize_database', true );
	}

	return $more;
}