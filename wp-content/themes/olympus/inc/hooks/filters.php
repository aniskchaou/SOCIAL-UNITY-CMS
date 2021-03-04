<?php

/**
 * Theme filters
 *
 * @package olympus-wp
 */

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 *
 * @param array $classes A list of existing body class values.
 *
 * @return array The filtered body class list.
 * @internal
 */
function olympus_filter_body_classes( $classes ) {
	global $post;

	$general_body_bg_color	 = olympus_general_body_bg_color();
	$general_body_bg_image	 = olympus_general_body_bg_image();

	$olympus	 = Olympus_Options::get_instance();
	$post_type	 = get_post_type();
	$post_style	 = $olympus->get_option_final( 'single_post_style', '', array('final-source' => 'customizer') );

	$is_shop		 = function_exists( 'is_shop' ) ? is_woocommerce() : false;
	$is_buddypress	 = function_exists( 'is_buddypress' ) ? is_buddypress() : false;
	$sticky_header_class = $olympus->get_option( 'header_top_sticky', 'enable-sticky-standard-header', $olympus::SOURCE_CUSTOMIZER );
	$left_panel_open = $olympus->get_option( 'left-panel-open', 'no', $olympus::SOURCE_CUSTOMIZER );
	
	if( $left_panel_open == 'yes' ){
		$classes[] = 'left-panel-always-open';
	}

	if ( $post && has_shortcode( $post->post_content, 'vc_row' ) ) {
		$classes[] = 'olymp-page-with-builder';
	}
	
	if($sticky_header_class){
		$classes[] = $sticky_header_class;
	}

	if ( $post_type && $post_style && is_singular('post') ) {
		$classes[] = "post-{$post_type}-{$post_style}";
	}

	if ( $general_body_bg_color ) {
		$classes[] = 'bg-custom-color';
	} elseif ( ( is_home() || is_archive() || is_singular( 'post' ) || is_page_template( 'blog-template.php' ) || $is_buddypress ) && !$is_shop ) {
		$classes[] = 'bg-body';
	} else {
		$classes[] = 'bg-white';
	}

	if ( $general_body_bg_image[ 'background-image' ] ) {
		$classes[] = 'bg-custom-image';
	}

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_archive() || is_search() ) {
		$classes[] = 'list-view';
	}

	if ( is_singular() && !is_front_page() ) {
		$classes[] = 'singular';
	}

	//Change classes by header visibility
	if ( olympus_is_top_user_panel_visible() ) {
		$classes[] = 'page-has-header-social';
	}

	if ( olympus_is_top_menu_panel_visible() ) {
		$classes[] = 'page-has-standard-header';
	}

	//Change classes by left panel visibility
	if ( olympus_is_left_panel_visible() && 'landing-template.php' !== get_page_template_slug() ) {
		if ( ! function_exists( 'elementor_location_exits' ) || ! elementor_location_exits( 'header', true ) ) {
		$classes[] = 'page-has-left-panels';
		}
	} else {
		$classes[] = 'page-has-not-panels';
	}

	$classes[] = is_user_logged_in() ? 'is-logged-in' : 'not-logged-in';

	$classes[] = 'olympus-theme';

	return $classes;
}

add_filter( 'body_class', 'olympus_filter_body_classes', 999 );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @param array $classes A list of existing post class values.
 *
 * @return array The filtered post class list.
 * @internal
 */
function olympus_filter_post_classes( $classes ) {
	if ( !post_password_required() && !is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}

add_filter( 'post_class', 'olympus_filter_post_classes' );

/**
 * Get SVG attributes
 * @param $data
 * @param $id
 *
 * @return mixed
 */
function olympus_svg_meta_data( $data, $id ) {

	$attachment	 = get_post( $id ); // Filter makes sure that the post is an attachment
	$mime_type	 = $attachment->post_mime_type; // The attachment mime_type
	//If the attachment is an svg

	if ( $mime_type == 'image/svg+xml' ) {

		//If the svg metadata are empty or the width is empty or the height is empty
		//then get the attributes from xml.

		if ( empty( $data ) || empty( $data[ 'width' ] ) || empty( $data[ 'height' ] ) ) {

			$xml				 = simplexml_load_file( wp_get_attachment_url( $id ) );
			$attr				 = $xml->attributes();
			$viewbox			 = explode( ' ', $attr->viewBox );
			$data[ 'width' ]	 = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[ 0 ] : (count( $viewbox ) == 4 ? (int) $viewbox[ 2 ] : null);
			$data[ 'height' ]	 = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[ 0 ] : (count( $viewbox ) == 4 ? (int) $viewbox[ 3 ] : null);
		}
	}

	return $data;
}

add_filter( 'wp_update_attachment_metadata', 'olympus_svg_meta_data', 10, 2 );

/**
 * Display SVG thumbnails in wordpress media library.
 *
 * @param $response
 * @param $attachment
 * @param $meta
 *
 * @return mixed
 */
function olympus_fix_admin_preview( $response, $attachment, $meta ) {

	if ( $response[ 'mime' ] == 'image/svg+xml' ) {
		$possible_sizes = apply_filters( 'image_size_names_choose', array(
			'thumbnail'	 => esc_html__( 'Thumbnail', 'olympus' ),
			'medium'	 => esc_html__( 'Medium', 'olympus' ),
			'large'		 => esc_html__( 'Large', 'olympus' ),
			'full'		 => esc_html__( 'Full Size', 'olympus' ),
				) );

		$sizes = array();

		foreach ( $possible_sizes as $size => $label ) {
			$sizes[ $size ] = array(
				'height'		 => get_option( "{$size}_size_w", 2000 ),
				'width'			 => get_option( "{$size}_size_h", 2000 ),
				'url'			 => $response[ 'url' ],
				'orientation'	 => 'portrait',
			);
		}

		$response[ 'sizes' ] = $sizes;
		$response[ 'icon' ]	 = $response[ 'url' ];
	}

	return $response;
}

add_filter( 'wp_prepare_attachment_for_js', 'olympus_fix_admin_preview', 10, 3 );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 *
 * @return string The filtered title.
 * @internal
 */
function _filter_olympus_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'olympus' ), max( $paged, $page ) );
	}

	return $title;
}

add_filter( 'wp_title', '_filter_olympus_wp_title', 10, 2 );

function olympus_read_more_link() {
	return '<a class="btn btn-primary more-link" href="' . get_permalink() . '">' . esc_html__( 'Continue reading', 'olympus' ) . '</a>';
}

add_filter( 'the_content_more_link', 'olympus_read_more_link' );

// Replaces the excerpt "Read More" text by a link
function olympus_excerpt_more( $more ) {
	global $post;

	if ( is_search() ) {
		return $more;
	}

	if ( $post ) {
		$more = '<a class="btn btn-primary more-link" href="' . get_permalink( $post->ID ) . '">' . esc_html__( 'Continue reading', 'olympus' ) . '</a>';
	}

	return $more;
}

add_filter( 'excerpt_more', 'olympus_excerpt_more' );

/**
 * Move User name and email fields in comment form will be started from.
 *
 * @param $fields array Comment form fields
 *
 * @return array
 */
function _filter_olympus_move_comment_field_to_bottom( $fields ) {
	$comment_field		 = $fields[ 'comment' ];
	unset( $fields[ 'comment' ] );
	$fields[ 'comment' ] = $comment_field;

	return $fields;
}

add_filter( 'comment_form_fields', '_filter_olympus_move_comment_field_to_bottom' );


/**
 * Extend the default WordPress category title.
 *
 * Remove 'Category' word from cat title.
 *
 * @param string $title Original category title.
 *
 * @return string The filtered category title.
 * @internal
 */
function _filter_olympus_archive_title( $title ) {
	if ( is_home() ) {
		$title = esc_html__( 'Latest posts', 'olympus' );
	} elseif ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( ( is_singular( 'post' ) ) ) {
		$category	 = get_the_category( get_the_ID() );
		$title		 = $category[ 0 ]->name;
	} elseif ( is_singular( 'product' ) || is_singular( 'download' ) ) {
		$title = esc_html__( 'Product Details', 'olympus' );
	}

	return $title;
}

add_filter( 'get_the_archive_title', '_filter_olympus_archive_title' );


/* ADMIN AREA */

if ( !function_exists( '_olympus_filter_fw_settings_form_header_buttons' ) ) :

	/**
	 * Add an extra options for post event
	 */
	function _olympus_filter_fw_settings_form_header_buttons( $arr ) {
		$arr2[]	 = '<a class="fw-theme-docs-link" target="_blank" href="https://support.crumina.net/help-center/categories/69/olympus-wp">' . esc_html__( 'Go to Docs', 'olympus' ) . '</a>';
		$arr	 = array_merge( $arr2, $arr );

		return $arr;
	}

endif;
add_filter( 'fw_settings_form_header_buttons', '_olympus_filter_fw_settings_form_header_buttons' );


if ( !function_exists( '_olympus_filter_fw_ext_backups_db_restore_keep_options' ) ) :

	/**
	 * Add an extra options for post event
	 */
	function _olympus_filter_fw_ext_backups_db_restore_keep_options( $options, $is_full ) {
		if ( !$is_full ) {
			$options[ 'crum_olympus_auto_install_state' ] = true;
		}

		return $options;
	}

endif;
add_filter( 'fw_ext_backups_db_restore_keep_options', '_olympus_filter_fw_ext_backups_db_restore_keep_options', 10, 2 );


/*
 * Multiple category list
 *  */
if ( !function_exists( 'wp_dropdown_cats_multiple' ) ) :

	function wp_dropdown_cats_multiple( $output, $r ) {

		if ( isset( $r[ 'multiple' ] ) && $r[ 'multiple' ] ) {

			$output = preg_replace( '/^<select/i', '<select multiple', $output );

			$output = str_replace( "name='{$r[ 'name' ]}'", "name='{$r[ 'name' ]}[]'", $output );

			if ( !is_array( $r[ 'chosen' ] ) ) {
				return $output;
			}

			foreach ( $r[ 'chosen' ] as $value ) {
				$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
			}
		}

		return $output;
	}

	add_filter( 'wp_dropdown_cats', 'wp_dropdown_cats_multiple', 10, 2 );
endif;

/*
 * Modifies the main WP query
 *  */
add_filter( 'pre_get_posts', '_filter_olympus_pre_get_posts' );

/**
 * This function modifies the main WordPress query to include an array of 
 * post types instead of the default 'post' post type.
 *
 * @param object $query  The original query.
 * @return object $query The amended query.
 */
function _filter_olympus_pre_get_posts( $query ) {

	// Modify search query
	if ( $query->is_search || $query->is_author ) {
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( !empty( $post_type ) ) {
			$query->set( 'post_type', $post_type );
		}
	}

	// Modify blog pages
	if ( $query->is_main_query() && (is_category() || is_page_template( 'blog-template.php' )) ) {
		$olympus = Olympus_Options::get_instance();

		$order = $olympus->get_option_final( 'post_order', 'DESC', array('final-source' => 'customizer') );
		if ( $order ) {
			$query->set( 'order', $order );
		}

		$postsPerPage = $olympus->get_option_final( 'posts_per_page', 12, array('final-source' => 'customizer') );
		if ( $postsPerPage ) {
			$query->set( 'posts_per_page', $postsPerPage );
		}

		$orderBy = $olympus->get_option_final( 'post_order_by', 'date', array('final-source' => 'customizer') );
		if ( $orderBy ) {
			$query->set( 'orderby', $orderBy );
		}

		$categories	 = $olympus->get_option_final( 'categories', array(), array('final-source' => 'customizer') );
		$cat_exclude = $olympus->get_option_final( 'cat_exclude', false, array('final-source' => 'customizer') );
		if ( !empty( $categories ) ) {

			foreach ( $categories as &$cat ) {
				$cat = $cat_exclude ? -(int) $cat : (int) $cat;
			}

			$query->set( 'cat', $categories );
		}
	}

	return $query;
}

/*
 * Modifies metabox fields
 *  */

add_filter( 'crumina_option_page_tabs', '_filter_crumina_option_page_tabs' );

if ( !function_exists( '_filter_crumina_option_page_tabs' ) ) {

	function _filter_crumina_option_page_tabs( $opt ) {
		global $post;
		if ( !empty( $post ) ) {

			if ( get_page_template_slug( $post->ID ) !== 'blog-template.php' ) {
				unset( $opt[ 'tab-blog-general' ] );
				unset( $opt[ 'tab-blog-panel' ] );
			}
		}

		return $opt;
	}

}

add_filter( 'crumina_option_blog_sort_panel', '_filter_crumina_option_blog_sort_panel' );

if ( !function_exists( '_filter_crumina_option_blog_sort_panel' ) ) {

	function _filter_crumina_option_blog_sort_panel( $opt ) {
		$page = filter_input( INPUT_GET, 'page' );

		if ( is_customize_preview() ) {
			return $opt;
		}

		$opt[ 'blog_sort_panel' ][ 'picker' ][ 'type' ][ 'value' ]	 = 'default';
		$opt[ 'blog_sort_panel' ][ 'picker' ][ 'type' ][ 'choices' ] = array_merge( array(
			'default' => esc_html__( 'Default', 'olympus' ),
				), $opt[ 'blog_sort_panel' ][ 'picker' ][ 'type' ][ 'choices' ] );

		return $opt;
	}

}

add_filter( 'crumina_option_top_menu_panel_visibility', '_filter_crumina_option_top_menu_panel_visibility' );

if ( !function_exists( '_filter_crumina_option_top_menu_panel_visibility' ) ) {

	function _filter_crumina_option_top_menu_panel_visibility( $opt ) {
		$opt[ 'top-menu-panel-options' ][ 'picker' ][ 'show' ][ 'value' ] = 'yes';
		unset( $opt[ 'top-menu-panel-options' ][ 'picker' ][ 'show' ][ 'choices' ][ 'default' ] );
		return $opt;
	}

}

add_filter( 'crumina_option_top_user_panel_visibility', '_filter_crumina_option_top_user_panel_visibility' );

if ( !function_exists( '_filter_crumina_option_top_user_panel_visibility' ) ) {

	function _filter_crumina_option_top_user_panel_visibility( $opt ) {
		$opt[ 'top-user-panel-options' ][ 'picker' ][ 'show' ][ 'value' ] = 'yes';
		unset( $opt[ 'top-user-panel-options' ][ 'picker' ][ 'show' ][ 'choices' ][ 'default' ] );
		return $opt;
	}

}

add_filter( 'crumina_option_left_panel_fixed_tab', '_filter_crumina_option_left_panel_fixed_tab' );

if ( !function_exists( '_filter_crumina_option_left_panel_fixed_tab' ) ) {

	function _filter_crumina_option_left_panel_fixed_tab( $opt ) {
		$opt[ 'options' ][ 'left-panel-fixed-options' ][ 'picker' ][ 'show' ][ 'value' ] = 'no';
		unset( $opt[ 'options' ][ 'left-panel-fixed-options' ][ 'picker' ][ 'show' ][ 'choices' ][ 'default' ] );
		return $opt;
	}

}

add_filter( 'crumina_option_single_post_style', '_filter_crumina_option_single_post_style' );

function _filter_crumina_option_single_post_style( $opt ) {
	$opt[ 'choices' ] = array_merge( array(
		'default' => array(
			'label'	 => esc_html__( 'Default', 'olympus' ),
			'small'	 => array(
				'height' => 90,
				'src'	 => get_template_directory_uri() . '/images/admin/default.png'
			),
		),
			), $opt[ 'choices' ] );
	return $opt;
}

add_filter( 'crumina_option_blog_style', '_filter_crumina_option_add_default_item' );
add_filter( 'crumina_option_blog_nav_style', '_filter_crumina_option_add_default_item' );
add_filter( 'crumina_option_blog_post_order', '_filter_crumina_option_add_default_item' );
add_filter( 'crumina_option_blog_post_order_by', '_filter_crumina_option_add_default_item' );

if ( !function_exists( '_filter_crumina_option_add_default_item' ) ) {

	function _filter_crumina_option_add_default_item( $opt ) {
		$page = filter_input( INPUT_GET, 'page' );

		if ( is_customize_preview() ) {
			return $opt;
		}

		$opt[ 'value' ] = 'classic';

		$opt[ 'choices' ] = array_merge( array(
			'default' => esc_html__( 'Default', 'olympus' ),
				), $opt[ 'choices' ] );

		return $opt;
	}

}

/**
 *  Demo install config
 *
 * @param FW_Ext_Backups_Demo[] $demos
 *
 * @return FW_Ext_Backups_Demo[]
 */
function _filter_olympus_fw_ext_backups_demos( $demos ) {
	$demos_array = array(
		'olympus-main-elementor' => array(
			'title'			 => esc_html__( 'Demo for Elementor Builder plugin', 'olympus' ),
			'screenshot'	 => get_template_directory_uri() . '/images/demo-elementor.png',
			'preview_link'	 => 'https://olympus.crumina.net/',
		),
		'olympus-main-visualcomposer' => array(
			'title'			 => esc_html__( 'Demo for WPBakery builder plugin', 'olympus' ),
			'screenshot'	 => get_template_directory_uri() . '/images/demo-wpbakery.png',
			'preview_link'	 => 'https://olympus.crumina.net/',
		),
	);

	$download_url = 'http://up.crumina.net/demo-data/olympus/upload.php';

	foreach ( $demos_array as $id => $data ) {
		$demo = new FW_Ext_Backups_Demo( $id, 'piecemeal', array(
			'url'		 => $download_url,
			'file_id'	 => $id,
				) );
		$demo->set_title( $data[ 'title' ] );
		$demo->set_screenshot( $data[ 'screenshot' ] );
		$demo->set_preview_link( $data[ 'preview_link' ] );

		$demos[ $demo->get_id() ] = $demo;

		unset( $demo );
	}

	return $demos;
}

add_filter( 'fw:ext:backups-demo:demos', '_filter_olympus_fw_ext_backups_demos' );

/**
 *  Wrap current page to span
 *
 * @param $link Current page html
 * @param $i    Current page number
 *
 * @return string
 */
function _filter_olympus_wp_link_pages_linkfunction( $link, $i ) {
	return is_numeric( $link ) ? "<span>{$link}</span>" : $link;
}

add_filter( 'wp_nav_menu_items', '_filter_olympus_add_logout_to_user_menu', 10, 2 );

function _filter_olympus_add_logout_to_user_menu( $items, $args ) {
	if ( $args->theme_location === 'user' ) {
		$items .= '<li class="menu-item menu-item-has-icon"><a href="' . wp_logout_url( home_url( '/' ) ) . '"><svg class="olymp-menu-icon"><use xlink:href="#olymp-logout-icon"></use></svg>' . esc_html__( 'Log Out', 'olympus' ) . '</a></li>';
	}
	return $items;
}

//add_filter( 'nav_menu_link_attributes', '_filter_olympus_replace_ampersand', 99, 4 );

function _filter_olympus_replace_ampersand( $atts, $item, $args, $depth ) {
	if ( isset( $atts[ 'href' ] ) ) {
		$atts[ 'href' ] = str_replace( '&amp;', '&', $atts[ 'href' ] );
		$atts[ 'href' ] = str_replace( '&#038;', '&', $atts[ 'href' ] );
	}
	return $atts;
}

add_filter( 'wp_nav_menu_objects', '_filter_olympus_update_login_url', 10, 2 );

function _filter_olympus_update_login_url( $sorted_menu_items, $args ) {
	if ( ! ( function_exists( 'yz_is_logy_active' ) && yz_is_logy_active()) ) {
		return $sorted_menu_items;
	}

	foreach ( $sorted_menu_items as $item ) {
		if ( preg_match( '/\/wp\-login\.php\?redirect/i', $item->url ) ) {
			$item->url = logy_page_url( 'login' );
		} else {
			$item->url = str_replace( '&#038;', '&', $item->url );
		}
	}

	return $sorted_menu_items;
}

/**
 * Add Top User Menu Meta Box
*/
function _filter_olympus_add_user_menu_meta_box( $object ) {
	$m = 'add' . '_' . 'meta_box';
	if ( class_exists( 'Youzer' ) ){
		$m( 'olympus-user-menu-metabox', esc_html__( 'Top user menu', 'olympus'), 'olympus_user_menu_meta_box', 'nav-menus', 'side', 'default' );
	}
	return $object;
}
add_filter( 'nav_menu_meta_box_object', '_filter_olympus_add_user_menu_meta_box', 10, 1);

function olympus_user_menu_meta_box( $object, $args ) {
	global $nav_menu_selected_id;

	$walker = new Walker_Nav_Menu_Checklist(false);

	$menu_items = array();
	$menu_items_data = array();
	$menu_items_data = array(
		array(
			'object' => 'olympus_user_menu_profile_settings',
			'title' => esc_html__( 'Profile Settings', 'olympus' ),
			'url' => '#',
			'class' => 'olympus-user-profile-settings'
		),
		array(
			'object' => 'olympus_user_menu_account_settings',
			'title' => esc_html__( 'Account Settings', 'olympus' ),
			'url' => '#',
			'class' => 'olympus-user-account-settings'
		),
		array(
			'object' => 'olympus_user_menu_widgets_settings',
			'title' => esc_html__( 'Widgets Settings', 'olympus' ),
			'url' => '#',
			'class' => 'olympus-user-widgets-settings'
		),
		array(
			'object' => 'olympus_user_menu_avatar_settings',
			'title' => esc_html__( 'Change Avatar', 'olympus' ),
			'url' => '#',
			'class' => 'olympus-user-avatar-settings'
		),
		array(
			'object' => 'olympus_user_menu_cover_image_settings',
			'title' => esc_html__( 'Change Cover Image', 'olympus' ),
			'url' => '#',
			'class' => 'olympus-user-cover-image-settings'
		)
	);

	foreach($menu_items_data as $menu_items_data_key => $menu_items_data_val){
		$link_settings = new stdClass();
		$link_settings->object_id = $menu_items_data_key + 1;
		$link_settings->db_id = 0;
		$link_settings->object = $menu_items_data_val['object'];
		$link_settings->menu_item_parent = 0;
		$link_settings->type = 'custom';
		$link_settings->title = $menu_items_data_val['title'];
		$link_settings->url = $menu_items_data_val['url'];
		$link_settings->target = '';
		$link_settings->attr_title = '';
		$link_settings->classes = array('olympus-user-menu', $menu_items_data_val['class']);
		$link_settings->xfn = '';
		$menu_items[] = $link_settings;
	}

	$removed_args = array( 'action', 'customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab', '_wpnonce' );
	?>
	<div id="olympusmenuitems" class="categorydiv">
		<div id="tabs-panel-olympusmenuitems-all" class="tabs-panel tabs-panel-active">
			<ul id="olympusmenuitems-checklist-all" class="categorychecklist form-no-clear" >
			<?php
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $menu_items), 0, (object) array( 'walker' => $walker) );
			?>
			</ul>
		</div>

		<p class="button-controls wp-clearfix">
			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu','olympus'); ?>" name="add-olympusmenuitems-menu-item" id="submit-olympusmenuitems" />
				<span class="spinner"></span>
			</span>
		</p>
	</div>
	<?php
}

add_filter( 'wp_setup_nav_menu_item','olympus_user_menu_links' );
function olympus_user_menu_links($item) {
	$user_id = get_current_user_id();
	if ( class_exists( 'Youzer' ) && !is_admin() ){
		if(in_array('olympus-user-profile-settings', $item->classes)){
			$item->url = yz_get_profile_settings_url( false, $user_id );
		}elseif(in_array('olympus-user-account-settings', $item->classes)){
			if(bp_is_active( 'settings' )){
				$item->url = olympus_get_settings_url( 'settings', $user_id );
			}
		}elseif(in_array('olympus-user-widgets-settings', $item->classes)){
			$item->url = yz_get_widgets_settings_url( false, $user_id );
		}elseif(in_array('olympus-user-avatar-settings', $item->classes)){
			$item->url = yz_get_profile_settings_url( 'change-avatar', $user_id );
		}elseif(in_array('olympus-user-cover-image-settings', $item->classes)){
			$item->url = yz_get_profile_settings_url( 'change-cover-image', $user_id );
		}
	}

    return $item;
}

// Custom container width
add_filter( 'body_class', 'olympus_add_body_class' );
function olympus_add_body_class( $classes ) {
	$olympus = Olympus_Options::get_instance();
	$content_width = $olympus->get_option_final( "full-content", 'default', array( 'final-source' => 'current-type' ) );
	$global_content_width = $olympus->get_option( "full-content", 'default', $olympus::SOURCE_CUSTOMIZER );
	if($content_width == 'default' ) {
		$content_width = $global_content_width;
	}

	if(isset($content_width) && $content_width == 'container'){
		$classes[] = 'boxed-width';
	}

	return $classes;
}

// Disable Unyson session_start functionality
function olympus_disable_fw_use_sessions() {
	return false;
}
add_filter( 'fw_use_sessions', 'olympus_disable_fw_use_sessions' );

// Login via the blog post
function olympus_comment_form_default_must_log_in( $fields ) {
	if ( function_exists( 'yz_is_logy_active' ) && yz_is_logy_active() ) {
		$account_page_url = logy_page_url( 'login' );
	} else {
		$account_page_url = wc_get_page_permalink( 'myaccount' );
	}

    $fields['must_log_in'] = sprintf(
		'<p class="must-log-in">%s</p>',
		sprintf(
			/* translators: %s: Login URL. */
			__( 'You must be <a href="%s">logged in</a> to post a comment.', 'olympus' ),
			/** This filter is documented in wp-includes/link-template.php */
			$account_page_url
		)
	);
    return $fields;
}
add_filter( 'comment_form_defaults', 'olympus_comment_form_default_must_log_in' );

// Stunning header
add_filter( 'fw_post_options', 'olympus_stunning_header_crumina_metabox', 999, 2 );
add_filter( 'fw_taxonomy_options', 'olympus_stunning_header_crumina_metabox', 999, 2 );

function olympus_stunning_header_crumina_metabox( $options ) {
    return array_merge( $options, fw()->theme->get_options( 'metabox-stunning-header' ) );
}

//Disable stunning on same pages
add_filter( 'fw_ext_stunning_header_visibility', 'olympus_stunning_header_visibility' );
function olympus_stunning_header_visibility( $visibility ) {
	$olympus = Olympus_Options::get_instance();
    if ( function_exists( 'bp_is_group' ) && bp_is_group() ) {
        $visibility = false;
    }

    if ( function_exists( 'bp_is_user' ) && bp_is_user() ) {
        $visibility = false;
    }

    $post_style = $olympus->get_option_final( 'single_post_style', 'classic', array('final-source' => 'customizer') );
    if ( is_single() && get_post_type() === 'post' && $post_style === 'modern' ) {
        $visibility = false;
    }

    return $visibility;
}

// Filter for features images sizes
add_filter( 'olympus_blog_loop_features_images', 'olympus_filter_blog_loop_features_images' );
function olympus_filter_blog_loop_features_images( $width_height = array() ){
	$return_width = (isset($width_height['width'])) ? $width_height['width'] : '';
	$return_height = (isset($width_height['height'])) ? $width_height['height'] : 285;
	if( $return_width == '' ){
		$return_width = 401;
	}

	return array('width' => $return_width, 'height' => $return_height);
}