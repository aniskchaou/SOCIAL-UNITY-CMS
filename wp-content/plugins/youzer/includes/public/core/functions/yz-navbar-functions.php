<?php

/**
 * Profile Navigation Menu
 */
function yz_profile_navigation_menu() {

    ?>

    <ul class="yz-profile-navmenu">

        <?php yz_get_displayed_user_nav(); ?>

        <?php

        /**
         * Fires after the display of member options navigation.
         *
         * @since 1.2.4
         */
        do_action( 'bp_member_options_nav' ); ?>

    </ul>

    <?php
}

/**
 * Filter Navigation Menu
 */
function yz_get_displayed_user_nav() {

    // Init Vars.
    $display_icons = yz_option( 'yz_display_navbar_icons', 'on' );

    // Get Navbar Items.
    $profile_navbar = (array) yz_get_profile_primary_nav();

    // Get Menus Limit
    $menus_limit = yz_option( 'yz_profile_navbar_menus_limit', 5 );

    // Get Visible Menu
    $visible_menu = array_slice( $profile_navbar, 0, $menus_limit );

    // Get Hidden View More Menu
    $view_more_menu = array_slice( $profile_navbar, $menus_limit );

    $current_component = bp_current_component();

    foreach ( $visible_menu as $menu_item ) {

		// Get Link.
		if ( ! isset( $menu_item->custom_link ) ) {
			$link = bp_loggedin_user_domain() ? str_replace( bp_loggedin_user_domain(), bp_displayed_user_domain(), $menu_item->link ) : trailingslashit( bp_displayed_user_domain() . $menu_item->link );
		} else {
			$link = $menu_item->link;
		}

        ?>

        <li class="yz-navbar-item <?php echo $current_component == $menu_item->slug ? 'yz-active-menu': ''; ?>"><a href="<?php echo $link; ?>"><?php if ( 'on' == $display_icons ) { echo apply_filters( 'yz_profile_navbar_menu_icon', '<i class="' . $menu_item->icon .'"></i>', $menu_item ); } ?><?php echo $menu_item->name; ?></a>
    	</li>

        <?php

    }

    if ( empty( $view_more_menu ) ) {
    	return false;
    }

    echo '<li class="yz-navbar-item yz-navbar-view-more"><a>';

	if ( 'on' == $display_icons ) {
	    echo '<i class="fas fa-bars"></i>';
	}

	echo __( 'More', 'youzer' ) . '</a>';

    echo '<ul class="yz-nav-view-more-menu">';

    foreach ( $view_more_menu as $menu_item ) :

		// Get Link.
		if ( ! isset($menu_item->custom_link ) ) {
			$link = bp_loggedin_user_domain() ? str_replace( bp_loggedin_user_domain(), bp_displayed_user_domain(), $menu_item->link ) : trailingslashit( bp_displayed_user_domain() . $menu_item->link );
		} else {
			$link = $menu_item->link;
		}

        ?>

        <li class="yz-navbar-item <?php echo $current_component == $menu_item->slug ? 'yz-active-menu': ''; ?>"><a href="<?php echo $link; ?>"><?php if ( 'on' == $display_icons ) { echo apply_filters( 'yz_profile_navbar_menu_icon', '<i class="' . $menu_item->icon.'"></i>', $menu_item ); } ?><?php echo $menu_item->name; ?></a>

        <?php

    endforeach;

    echo '</ul></li>';

}

// add_filter( 'bp_nav_menu_args', 'yz_filter_profile_nav_menu', 10 );

/**
 * Get Profile Sub Navigation Menu Icon
 */
function yz_get_profile_subnav_menu_icon( $subnav_item ) {

    // Default Icon.
    $icon = 'fas fa-globe';

    // Get Tab Slug.
    $tab_slug = $subnav_item->parent_slug . '_' . $subnav_item->slug;

	// Get Option ID.
	$option_id = 'yz_ctabs_' . $tab_slug . '_icon';

	// Get Option.
	$icon_value = yz_options( $option_id );

	// Get Icon.
	if ( ! empty( $icon_value ) ) {
		$icon = $icon_value;
	}

    return apply_filters( 'yz_profile_subnav_menu_icon', $icon, $option_id );

}

/**
 * Get Profile Tabs Icons
 */
function yz_profile_subnav_menu_default_icons( $icon = null, $option_id = null ) {

	switch ( $option_id ) {

		case 'yz_ctabs_events_profile_icon':
		case 'yz_ctabs_activity_just-me_icon':
			$icon = 'fas fa-user-circle';
			break;

		case 'yz_ctabs_activity_following_icon':
			$icon = 'fas fa-rss';
			break;

		case 'yz_ctabs_follows_followers_icon':
			$icon = 'fas fa-share';
			break;

		case 'yz_ctabs_follows_following_icon':
			$icon = 'fas fa-reply';
			break;

		case 'yz_ctabs_friends_my-friends_icon':
		case 'yz_ctabs_groups_my-groups_icon':
		case 'yz_ctabs_activity_groups_icon':
			$icon = 'fas fa-users';
			break;

		case 'yz_ctabs_friends_requests_icon':
		case 'yz_ctabs_activity_friends_icon':
			$icon = 'fas fa-handshake';
			break;

		case 'yz_ctabs_activity_mentions_icon':
			$icon = 'fas fa-at';
			break;

		case 'yz_ctabs_activity_favorites_icon':
			$icon = 'fas fa-heart';
			break;

		case 'yz_ctabs_notifications_unread_icon':
			$icon = 'fas fa-eye-slash';
			break;

		case 'yz_ctabs_notifications_read_icon':
			$icon = 'fas fa-eye';
			break;

		case 'yz_ctabs_messages_inbox_icon':
			$icon = 'fas fa-inbox';
			break;

		case 'yz_ctabs_messages_starred_icon':
			$icon = 'fas fa-star';
			break;

		case 'yz_ctabs_messages_compose_icon':
			$icon = 'fas fa-edit';
			break;

		case 'yz_ctabs_messages_notices_icon':
			$icon = 'fas fa-bullhorn';
			break;

		case 'yz_ctabs_messages_sentbox_icon':
			$icon = 'fas fa-paper-plane';
			break;

		case 'yz_ctabs_groups_invites_icon':
			$icon = 'fas fa-paper-plane';
			break;

		case 'yz_ctabs_user-media_all_icon':
		case 'yz_ctabs_media_all_icon':
			$icon = 'fas fa-photo-video';
			break;

		case 'yz_ctabs_user-media_videos_icon':
		case 'yz_ctabs_media_videos_icon':
			$icon = 'fas fa-film';
			break;

		case 'yz_ctabs_user-media_audios_icon':
		case 'yz_ctabs_media_audios_icon':
			$icon = 'fas fa-volume-up';
			break;

		case 'yz_ctabs_user-media_photos_icon':
		case 'yz_ctabs_media_photos_icon':
			$icon = 'fas fa-image';
			break;

		case 'yz_ctabs_user-media_files_icon':
		case 'yz_ctabs_media_files_icon':
			$icon = 'fas fa-file-import';
			break;

		case 'yz_ctabs_events_attending_icon':
			$icon = 'fas fa-calendar-check';
			break;

		case 'yz_ctabs_events_my-locations_icon':
			$icon = 'fas fa-map-marked-alt';
			break;

		case 'yz_ctabs_groups_group-events_icon':
		case 'yz_ctabs_events_my-events_icon':
			$icon = 'fas fa-calendar-alt';
			break;

		case 'yz_ctabs_events_my-bookings_icon':
			$icon = 'fas fa-ticket-alt';
			break;

	}

	return $icon;
}

add_filter( 'yz_profile_subnav_menu_icon', 'yz_profile_subnav_menu_default_icons', 10, 2 );

/**
 * Primary Tabs Slugs
 */
function yz_get_profile_primary_nav_slugs() {

	// Get Youzer Custom Tabs
	$primary_tabs = yz_get_profile_primary_nav();

	if ( empty( $primary_tabs ) ) {
		return false;
	}

	// Get Only Custom Tabs slugs.
	$tabs_slugs = wp_list_pluck( $primary_tabs, 'slug' );

	// Filter Tabs Slugs
	$tabs_slugs = apply_filters( 'yz_profile_primary_nav_slugs', $tabs_slugs );

	return $tabs_slugs;

}

/**
 * Check Is Navigation  deleted by slug.
 */
function yz_is_profile_tab_deleted( $slug ) {

	// Get Delete Tab Value.
	$delete_tab = yz_option( 'yz_delete_' . $slug . '_tab' );

	if ( ! empty( $delete_tab ) && 'on' == $delete_tab ) {
		return true;
	}

	return false;
}

/**
 * Youzer Default Tabs.
 */
function yz_get_youzer_default_tabs() {
	return apply_filters( 'youzer_default_tabs', array( 'overview', 'info', 'posts', 'comments', 'media', 'badges', 'reviews', 'bookmarks', 'activity', 'shop' ) );
}

/**
 * Get Custom BP Tabs.
 */
function yz_get_custom_bp_tabs() {

	// Get Primary Tabs.
	$tabs = yz_get_profile_primary_nav();

	// Get User Default Tabs
	$youzer_tabs = array();

	// Remove Default Youzer Tabs.
	foreach ( $tabs as $key => $tab ) {
		if ( in_array( $tab['slug'], $youzer_tabs ) ) {
			unset( $tabs[ $key] );
		}
	}

	return $tabs;
}

/**
 * Get Third Party Plugins Sub Tabs.
 */
function yz_get_profile_third_party_subtabs() {

    // Get Tabs
    $custom_tabs = yz_get_profile_third_party_tabs();

    if ( empty( $custom_tabs ) ) {
        return false;
    }

    // Init Vars.
    $bp = buddypress();
    $secondary_tabs = array();

    foreach ( $custom_tabs as $tab ) {

        // Get Tab Slug
        $tab_slug = isset( $tab['slug'] ) ? $tab['slug'] : null;

        // Get Tab Navigation  Menu
        $secondary_nav = $bp->members->nav->get_secondary( array( 'parent_slug' => $tab_slug ) );

        if ( empty( $secondary_nav ) ) {
            continue;
        }

        // Get Settings.
        $secondary_tabs[] = $secondary_nav;
    }

    return $secondary_tabs;
}

/**
 * Get Third Party Navigation Tabs.
 */
function yz_get_profile_third_party_tabs() {

	global $bp;

	// Init Array().
	$third_party_tabs = array();

	// Get Original Primary Nav
	$primary_tabs = $bp->members->nav->get_primary();

	// Hidden Tabs ( All Youzer Default + Custom Tabs ).
	$youzer_tabs = yz_get_all_youzer_tabs_slugs();

	foreach ( $primary_tabs as $tab ) {

		// Don't Show Youzer Hidden Tabs.
		if ( in_array( $tab['slug'], $youzer_tabs ) ) {
			continue;
		}

		// Add Tab.
		$third_party_tabs[] = $tab;
	}

	return $third_party_tabs;
}

/**
 * Get All Youzer Tabs.
 */
function yz_get_all_youzer_tabs_slugs() {

	// Get All Youzer Default Tabs.
	$youzer_tabs = array( 'yz-home', 'profile', 'settings', 'widgets', 'messages', 'notifications', 'friends', 'groups', 'comments', 'media', 'posts', 'activity', 'overview', 'info', 'badges', 'follows' );

	// Get Youzer Custom Tabs Slugs.
	$custom_tabs = (array) yz_custom_youzer_tabs_slugs();

	// Merge Arrays.
	$all_tabs = array_merge( $youzer_tabs, $custom_tabs );

	return $all_tabs;
}

/**
 * Youzer Custom Tabs
 */
function yz_custom_youzer_tabs( $query = null ) {

	// Get Custom Tabs
	$custom_tabs = yz_option( 'yz_custom_tabs' );

	if ( empty( $custom_tabs ) ) {
		return false;
	}

	// Init Array().
	$tabs = array();

	foreach ( $custom_tabs as $tab_id => $data ) {

		if ( 'false' == $data['display_nonloggedin'] && ! is_user_logged_in() ) {
			continue;
		}

		// Add tab to the tabs list.
		$tabs[ $tab_id ] = array(
			'tab_name'    => $tab_id,
			'tab_title'   => $data['title'],
            'tab_slug'	  => isset( $data['slug'] ) ? $data['slug'] : yz_get_custom_tab_slug( $data['title'] )
		);

	}

	// Filter Tabs.
	$tabs = apply_filters( 'yz_custom_youzer_tabs', $tabs );

	return $tabs;
}

/**
 * Youzer Custom Tabs List
 */
function yz_custom_youzer_tabs_slugs() {

	// Init Array.
	$tabs_slugs = array();

	// Get Youzer Custom Tabs
	$custom_tabs = yz_custom_youzer_tabs();

	if ( empty( $custom_tabs ) ) {
		return false;
	}

	foreach ( $custom_tabs as $tab ) {
		$tabs_slugs[] = $tab['tab_slug'];
	}

	// Filter Tabs Slugs
	$tabs_slugs = apply_filters( 'yz_custom_youzer_tabs_slugs', $tabs_slugs );

	return $tabs_slugs;

}

/**
 * Get Custom Tab ID By Slug
 */
function yz_get_custom_tab_id_by_slug( $slug ) {

	// Init Vars.
	$tab_id = null;

	// Get Custom Tabs
	$custom_tabs = yz_custom_youzer_tabs();

	if ( empty( $custom_tabs ) ) {
		return $slug;
	}

	// Search For Id.
	foreach ( $custom_tabs as $key => $tab ) {
	    if ( $tab['tab_slug'] == $slug ) {
	    	$tab_id = $key;
	    	break;
	    }
	}

	return $tab_id;
}

/**
 * Check Is Current Tab has a Secondary Tab Menu.
 */
function yz_is_current_tab_has_children() {

	// Init Vars.
	$bp = buddypress();
	$has_children = true;

	// Get Current Tab Slug.
	$parent_slug = ! empty( $bp->displayed_user ) ? bp_current_component() : bp_get_root_slug( bp_current_component() );

	// Get Tab Navigation  Menu
	$nav_menu = $bp->members->nav->get_secondary( array( 'parent_slug' => $parent_slug ) );

	if ( empty( $nav_menu ) ) {
		$has_children = false;
	}

	return apply_filters( 'yz_is_current_tab_has_children', $has_children );
}

/**
 * Prepare Secondary Tabs Icons.
 */
function yz_get_secondary_tabs_icons( $menu_item, $subnav_item, $selected_item ) {

	// Get Current Tab Icon.
	$tab_icon = yz_get_profile_subnav_menu_icon( $subnav_item );

	// If the current action or an action variable matches the nav item id, then add a highlight CSS class.
	if ( $subnav_item->slug === $selected_item ) {
		$selected = ' class="current selected"';
	} else {
		$selected = '';
	}

	// List type depends on our current component.
	$list_type = bp_is_group() ? 'groups' : 'personal';
	$icon_html = apply_filters( 'yz_profile_tab_submenu_icons', '<i class="' . $tab_icon . '"></i>', $subnav_item );

	return '<li id="' . esc_attr( $subnav_item->parent_slug . '-' . $subnav_item->css_id . '-' . $list_type . '-li' ) . '" ' . $selected . '><a id="' . esc_attr( $subnav_item->css_id ) . '" href="' . esc_url( $subnav_item->link ) . '">' . $icon_html . $subnav_item->name . '</a></li>';

}

/**
 * Add Third Party Sub Tabs Icons.
 */
function yz_add_profile_third_party_subtabs_icons() {

	// Init Vars.
	$bp = buddypress();

	// Get Current Tab Slug.
	$parent_slug = ! empty( $bp->displayed_user ) ? bp_current_component() : bp_get_root_slug( bp_current_component() );

	// Get Tab Navigation  Menu
	$custom_tabs = $bp->members->nav->get_secondary( array( 'parent_slug' => $parent_slug ) );

	// Pass Buddpress Default Tabs.
	if ( empty( $custom_tabs ) ) {
		return false;
	}

    foreach ( $custom_tabs as $tab ) {
		// Filter Custom Tabs Menu.
		add_filter( 'bp_get_options_nav_'. $tab['css_id'], 'yz_get_secondary_tabs_icons', 10, 3 );
    }

}

add_action( 'bp_ready', 'yz_add_profile_third_party_subtabs_icons', 999 );

/**
 * Filter Custom Tab Url.
 */
function yz_profile_custom_tab_url( $link = null ) {

    // Get Displayed profile username.
    $displayed_username = bp_core_get_username( bp_displayed_user_id() );

    // Replace Tags.
    $link = wp_kses_decode_entities( str_replace( '{username}', $displayed_username, $link ) );

    return $link;

}

add_filter( 'yz_profile_custom_tab_url', 'yz_profile_custom_tab_url', 999 );

/**
 * Get Custom Tab Slug.
 */
function yz_get_custom_tab_slug( $tab_title ) {
    // Get Slug.
    return strtolower( str_replace( ' ', '-', $tab_title ) );
}

/**
 * Get Custom Tab Settings.
 */
function yz_get_custom_tab_data( $tab_name, $data_type  ) {
    $tabs = yz_option( 'yz_custom_tabs' );
    return $tabs[ $tab_name ][ $data_type ];
}

/**
 * Get Custom Tab Slug.
 */
function yz_get_tab_name_by_slug( $current_tab_slug ) {

    // Init Var.
    $current_tab = false;

    // Get All Custom Tabs.
    $tabs = yz_option( 'yz_custom_tabs' );

    if ( empty( $tabs ) ) {
        return false;
    }

    foreach ( $tabs as $tab_id => $data ) {

        // Get Tab Slug.
        $tab_slug = isset( $data['slug'] ) ? $data['slug'] : yz_get_custom_tab_slug( $data['title'] );

        if ( $current_tab_slug == $tab_slug ) {
            $current_tab = $tab_id;
            break;
        }

    }

    return $current_tab;
}

/**
 * Update Profile Navigation Menu
 */
function yz_update_profile_navigation_menu() {

	if ( ! bp_is_user() ) {
		return;
	}

	$tabs = apply_filters( 'yz_profile_tabs', yz_option( 'yz_profile_tabs' ) );

	$bp = buddypress();

    // Get Primary Tabs.
    $primary_tabs = yz_get_profile_primary_nav();
    $default_tabs = yz_profile_tabs_default_value();

    foreach ( $primary_tabs as $tab ) {

    	// Get Tab Slug
    	$slug = $tab['slug'];

		if ( isset( $tabs[ $slug ]['deleted'] ) ) {
			bp_core_remove_nav_item( $slug );
		} else {

	    	// Init Array.
	    	$args = array();

	    	// Change Tab Title.
			if ( isset( $tabs[ $slug ]['name'] ) ) {
				$args['name'] = $tabs[ $slug ]['name'];
			}

	    	// Change Tab Position.
			if ( isset( $tabs[ $slug ]['position'] ) ) {
				$args['position'] = $tabs[ $slug ]['position'];
			}

	    	// Change Tab Position.
			if ( isset( $tabs[ $slug ]['visibility'] ) ) {
				$args['visibility'] = 'off';
			}

			// Change Tab Icon.
			if ( isset( $tabs[ $slug ]['icon'] ) ) {
				$args['icon'] = $tabs[ $slug ]['icon'];
			} else {
				$args['icon'] = isset( $default_tabs[ $slug ]['icon'] ) ? $default_tabs[ $slug ]['icon'] : 'fas fa-globe-asia';
			}

		    // Get Custom Tab Link.
			// if ( false !== strpos( $slug, 'yz_custom_tab_' ) ) {
			// 	$args['link'] = yz_get_custom_tab_data( $slug, 'link' );
			// }

	    	$bp->members->nav->edit_nav( $args, $tab['slug'] );

		}

    }

    unset( $bp, $args );

}

add_action( 'bp_actions', 'yz_update_profile_navigation_menu' );