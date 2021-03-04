<?php
/*
  Remove actions
 *  */

remove_action( 'rtmedia_after_album_gallery_item', 'rtm_album_media_count' );
remove_action( 'rtmedia_before_item', 'action_buttons_before_media_thumbnail' );

if ( function_exists( 'rtmedia_update_site_option' ) && is_admin() ) {
	$options = rtmedia_get_site_option( 'rtmedia-options' );
	$options['buddypress_enableOnActivity'] = 0;
	$options['buddypress_enableOnComment'] = 0;
	$options['privacy_enabled'] = 0;
	rtmedia_update_site_option( 'rtmedia-update-template-notice-v3_9_4', 'hide' );
	rtmedia_update_site_option( 'rtmedia_premium_addon_notice', 'hide' );
	rtmedia_update_site_option( 'rtmedia_inspirebook_release_notice', 'hide' );

	rtmedia_update_site_option( 'rtmedia-options', $options );
}

/*
  Add filters
 *  */

add_action( 'wp_enqueue_scripts', '_action_olympus_buddypress_media_scripts', 999 );

function _action_olympus_buddypress_media_scripts() {
	$theme_version = olympus_get_theme_version();

	wp_enqueue_style( 'buddypress-media-customization', get_template_directory_uri() . '/css/buddypress-media-customization.css', false, $theme_version );
	if ( ! class_exists( 'Youzer' ) ){
		wp_enqueue_style( 'buddypress-customization', get_template_directory_uri() . '/css/buddypress-customization.css', false, $theme_version );
	}
}

add_filter( 'rtmedia_media_array_backbone', '_filter_olympus_rtmedia_backbone_template_filter_author_name', 10, 1 );

function _filter_olympus_rtmedia_backbone_template_filter_author_name( $media_array ) {
	$media_array->media_description = olympus_rtmedia_get_media_description( $media_array->id );
	return $media_array;
}

add_filter( 'rtmedia_media_thumb', '_filter_olympus_rtmedia_media_thumb', 10, 3 );

function _filter_olympus_rtmedia_media_thumb( $src, $id, $type ) {
	$thumbnail = get_the_post_thumbnail_url( rtmedia_media_id( $id ) );

	if ( strpos( $src, 'rtMedia/users' ) !== false ) {
		return $src;
	}

	if ( $type === 'music' || $type === 'video' ) {
		if ( $thumbnail ) {
			return $thumbnail;
		}

		return get_template_directory_uri() . '/images/empty-thumb.png';
	} else if ( $type === 'album' ) {
		if ( strpos( $src, 'rtMedia' ) === false ) {
			return get_template_directory_uri() . '/images/media-photo.png';
		} else {
			return $src;
		}
	}

	$media_post_id	 = rtmedia_media_id( $id );
	$media_data		 = wp_get_attachment_image_src( $media_post_id, 'crumina-rtmedia-thumb' );
	return isset( $media_data[ 0 ] ) ? $media_data[ 0 ] : $src;
}

add_filter( 'rtmedia_pro_options_save_settings', '_filter_olympus_rtmedia_pro_options_save_settings' );
add_filter( 'rtmedia_general_content_default_values', '_filter_olympus_rtmedia_pro_options_save_settings' );

function _filter_olympus_rtmedia_pro_options_save_settings( $settings ) {
	$settings[ 'general_masonry_layout' ]			 = 1;
	$settings[ 'general_masonry_layout_activity' ]	 = 0;
	return $settings;
}

/*
  Functions
 *  */

function olympus_rtmedia_create_album() {

	if ( !is_rtmedia_album_enable() ) {
		return;
	}

	if ( !rtm_is_album_create_allowed() ) {
		return;
	}

	global $rtmedia_query;

	$user_id			 = get_current_user_id();
	$display			 = false;
	$context_type_array	 = array( 'profile', 'group' );

	if ( isset( $rtmedia_query->query[ 'context' ] ) && in_array( $rtmedia_query->query[ 'context' ], $context_type_array, true ) && 0 !== $user_id ) {
		switch ( $rtmedia_query->query[ 'context' ] ) {
			case 'profile':
				if ( $rtmedia_query->query[ 'context_id' ] === $user_id ) {
					$display = rtm_is_user_allowed_to_create_album();
				}

				break;
			case 'group':
				$group_id = $rtmedia_query->query[ 'context_id' ];

				if ( can_user_create_album_in_group( $group_id ) ) {
					$display = true;
				}

				break;
		}
	}

	if ( true !== $display ) {
		echo '';
	}
	?>
	<li class="photo-album-item-wrap rtmedia-list-item col-3-width">
		<div class="photo-album-item create-album">
			<a href='#rtmedia-create-album-modal' title="<?php echo esc_attr__( 'Create New Album', 'olympus' ); ?>" class="rtmedia-reveal-modal rtmedia-modal-link full-block"></a>

			<div class="content">
				<span class="btn btn-control bg-primary">
					<i class="olympus-icon-Plus-Icon"></i>
				</span>

				<span class="title h5"><?php esc_html_e( 'Create New Album', 'olympus' ); ?></span>
				<span class="sub-title"><?php esc_html_e( 'It only takes a few minutes!', 'olympus' ); ?></span>
			</div>
		</div>
	</li>
	<?php
}

function olympus_rtmedia_media_description( $template = '<p class="sub-title">%s</p>' ) {
	global $rtmedia_backbone;
	if ( $rtmedia_backbone[ 'backbone' ] ) {
		$descr = '<%= media_description %>';
	} else {
		$descr = olympus_rtmedia_get_media_description();
	}

	if ( is_string( $template ) && !empty( $descr ) ) {
		printf( $template, $descr );
	} else {
		olympus_render( $descr );
	}
}

function olympus_rtmedia_get_media_description( $id = false, $length = 80 ) {

	if ( $id ) {
		$media_post_id = rtmedia_media_id( $id );
	} else {
		global $rtmedia_media;

		$media_post_id = $rtmedia_media->media_id;
	}

	$descr = get_post_field( 'post_content', $media_post_id );

	if ( !$descr ) {
		return '';
	}

	return olympys_string_short( $descr, $length, '...', 'w' );
}

/**
 * Rendering comments section
 *
 * @global      object      $rtmedia_media
 *
 * @param       bool        $echo
 *
 * @return      string
 */
function olympus_rtmedia_comments( $echo = true ) {

	global $rtmedia_media;

	/* check is comment media */
	$comment_media = rtmedia_is_comment_media_single_page( rtmedia_id() );

	$html = "";

	if ( empty( $comment_media ) ) {
		$html			 = '<div id="rtm-comment-list-scroll-wrap"><ul id="rtmedia_comment_ul" class="rtm-comment-list" data-action="' . esc_url( get_rtmedia_permalink( rtmedia_id() ) ) . 'delete-comment/">';
		$comments		 = get_comments( array(
			'post_id'	 => $rtmedia_media->media_id,
			'order'		 => 'ASC',
				) );
		$comment_list	 = '';
		$count			 = count( $comments );
		$i				 = 0;

		foreach ( $comments as $comment ) {
			$comment_list .= rmedia_single_comment( (array) $comment, $count, $i );
			$i++;
		}

		if ( !empty( $comment_list ) ) {
			$html .= $comment_list;
		} else {
			$html .= "<li id='rtmedia-no-comments' class='rtmedia-no-comments'>" . apply_filters( 'rtmedia_single_media_no_comment_messege', esc_html__( 'There are no comments on this media yet.', 'olympus' ) ) . '</li>';
		}

		$html .= '</ul></div>';
	}


	if ( $html ) {
		olympus_render( $html ); // @codingStandardsIgnoreLine
	} else {
		return $html;
	}
}

/*
 * Add menu items
 *  */
add_action( 'admin_head-nav-menus.php', 'olympus_register_rtmedia_metabox' );

function olympus_register_rtmedia_metabox() {
	$addMtb = 'add_' . 'meta_' . 'box';
	$addMtb( 'olympus_rtmedia_metabox_id', esc_html__( 'Olympus rtMedia', 'olympus' ), 'olympus_render_rtmedia_metabox', 'nav-menus', 'side', 'default' );
}

function olympus_render_rtmedia_metabox( $object, $args ) {
	global $nav_menu_selected_id;

	$items = array(
		1	 => array(
			'type'	 => 'olympus-rtmedia-photos-menu',
			'title'	 => esc_html__( 'rtMedia Photos', 'olympus' ),
		),
		2	 => array(
			'type'	 => 'olympus-rtmedia-videos-menu',
			'title'	 => esc_html__( 'rtMedia Videos', 'olympus' ),
		),
		3	 => array(
			'type'	 => 'olympus-rtmedia-music-menu',
			'title'	 => esc_html__( 'rtMedia Music', 'olympus' ),
		),
	);

	class olympRtItem {

		public $object			 = 'olympus-rtmedia-menu-slug';
		public $classes			 = array();
		public $menu_item_parent = 0;
		public $post_parent		 = 0;
		public $db_id			 = 0;
		public $target			 = '';
		public $attr_title		 = '';
		public $description		 = '';
		public $xfn				 = '';
		public $url				 = '';

	}

	$prepared = array();

	foreach ( $items as $key => $item ) {
		$prepared[ $key ] = new olympRtItem;

		$prepared[ $key ]->type_label	 = esc_html__( 'rtMedia page link', 'olympus' );
		$prepared[ $key ]->ID			 = $key;
		$prepared[ $key ]->object_id	 = $key;
		$prepared[ $key ]->title		 = $item[ 'title' ];
		$prepared[ $key ]->type			 = $item[ 'type' ];
	}

	$walker = new Walker_Nav_Menu_Checklist( false );

	$removed_args = array(
		'action',
		'customlink-tab',
		'edit-menu-item',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);
	?>
	<div id="olympus-rtmedia-wrap" class="posttypediv">
		<h4><?php _e( 'Logged-In', 'olympus' ) ?></h4>
		<p><?php _e( '<em>Logged-In</em> links are relative to the current user, and are not visible to visitors who are not logged in.', 'olympus' ) ?></p>
		<div id="tabs-panel-olympus-rtmedia-all" class="tabs-panel tabs-panel-active">
			<ul id="olympus-rtmedia-checklist-pop" class="categorychecklist form-no-clear" >
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $prepared ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>
		</div>

		<p class="button-controls">
			<span class="list-controls">
				<a href="<?php
				echo esc_url( add_query_arg( array(
					'olympus-rtmedia-all'	 => 'all',
					'selectall'				 => 1,
								), remove_query_arg( $removed_args ) ) );
				?>#olympus_rtmedia_metabox_id" class="select-all"><?php esc_html_e( 'Select All', 'olympus' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'olympus' ); ?>" name="olympus-rtmedia-menu-item" id="submit-olympus-rtmedia-wrap" />
				<span class="spinner"></span>
			</span>
		</p>
	</div>
	<?php
}

add_filter( 'wp_setup_nav_menu_item', 'olympus_setup_rtmedia_nav_menu_item', 10, 1 );

function olympus_setup_rtmedia_nav_menu_item( $menu_item ) {
	if ( is_admin() ) {
		return $menu_item;
	}

	// Prevent a notice error when using the customizer.
	$menu_classes = $menu_item->classes;


	switch ( $menu_item->type ) {
		case 'olympus-rtmedia-photos-menu' :
			if ( is_user_logged_in() ) {
				$menu_item->url = trailingslashit( get_rtmedia_user_link( get_current_user_id() ) ) . RTMEDIA_MEDIA_SLUG . '/photo';
			} else {
				$menu_item->_invalid = true;
			}
			break;
		case 'olympus-rtmedia-videos-menu' :
			if ( is_user_logged_in() ) {
				$menu_item->url = trailingslashit( get_rtmedia_user_link( get_current_user_id() ) ) . RTMEDIA_MEDIA_SLUG . '/video';
			} else {
				$menu_item->_invalid = true;
			}
			break;
		case 'olympus-rtmedia-music-menu' :
			if ( is_user_logged_in() ) {
				$menu_item->url = trailingslashit( get_rtmedia_user_link( get_current_user_id() ) ) . RTMEDIA_MEDIA_SLUG . '/music';
			} else {
				$menu_item->_invalid = true;
			}
			break;
	}

	// If component is deactivated, make sure menu item doesn't render.
	if ( empty( $menu_item->url ) ) {
		$menu_item->_invalid = true;
	} else {
		$current = bp_get_requested_url();
		if ( strpos( $current, $menu_item->url ) !== false ) {
			if ( is_array( $menu_item->classes ) ) {
				$menu_item->classes[]	 = 'current_page_item';
				$menu_item->classes[]	 = 'current-menu-item';
			} else {
				$menu_item->classes = array( 'current_page_item', 'current-menu-item' );
			}
		}
	}

	return $menu_item;
}