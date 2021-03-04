<?php

/**
 * Check if Buddypress Installed and active.
 */
function yz_is_bp_active() {

    if ( function_exists( 'bp_is_active' ) ) {
        return true;
    }

    return false;
}


/**
 * Change Buddypress Assets Path
 */
function yz_buddypress_assets_path( $options ) {
    return YZ_PA;
}

add_filter( 'bp_get_theme_compat_url', 'yz_buddypress_assets_path' );

/**
 * Disable Buddypress Default CSS.
 */
function yz_deregister_bp_styles() {

    if ( apply_filters( 'yz_deregister_bp_styles', true ) ) {
        wp_dequeue_style( 'bp-nouveau' );
        wp_dequeue_style( 'bp-parent-css' );
        wp_dequeue_style( 'bp-legacy-css' );
        wp_dequeue_style( 'bp-legacy-css-rtl' );
        wp_dequeue_style( 'bp-messages-autocomplete' );
    }

}

add_action( 'wp_print_styles', 'yz_deregister_bp_styles', 15 );

/**
 * Register Youzer Buddypress Templates Folder Location
 */
function yz_plugin_template_location() {
    return YZ_TEMPLATE . '/';
}

/**
 * Theme Template location.
 */
function yz_theme_template_location() {
    return yz_get_theme_template_path() . '/youzer';
}

/**
 * Replace Buddypress Templates With Youzer Templates
 */
function yz_bp_replace_template( $old_template, $slug, $name ) {

    $new_template = null;

    if ( 'members/single/home' == $slug || 'activity/single/home' == $slug  ) {
        if ( yz_is_account_page() ) {
            $new_template =  array( 'account-template.php' );
        } else {
            $new_template = array( 'profile-template.php' );
        }
    }

    $new_template = ! empty( $new_template ) ? $new_template : $old_template;

    return apply_filters( 'yz_bp_replace_template', $new_template, $old_template );

}

/**
 * Over Load Templates.
 */
function yz_bp_overload_templates() {

    // Get New Templates Location
    if ( function_exists( 'bp_register_template_stack' ) ) {
        bp_register_template_stack( 'yz_theme_template_location', 0 );
        bp_register_template_stack( 'yz_plugin_template_location', 1 );
    }

    // If Viewing A Member Page, Overload The Template
    if ( bp_is_user() ) {
        add_filter( 'bp_get_template_part', 'yz_bp_replace_template', 10, 3 );
    }

}

add_action( 'bp_init', 'yz_bp_overload_templates' );

/**
 * Translate Some Buddypress Words.
 */
function yz_bp_multiple_translate_text( $translated_text ) {
    switch ( $translated_text ) {
        case 'Remove Favorite' :
            $translated_text = __( 'Unlike', 'youzer' );
            break;
        case 'Favorite' :
            $translated_text = __( 'Like', 'youzer' );
            break;
        case 'Favorites' :
            $translated_text = __( 'Likes', 'youzer' );
            break;
        case '(required)' :
            $translated_text = __( 'Required', 'youzer' );
            break;
        case 'Cancel Friendship Request' :
            $translated_text = __( 'Cancel Request', 'youzer' );
            break;
    }
    return $translated_text;
}

add_filter( 'gettext', 'yz_bp_multiple_translate_text', 20 );

/**
 * Edit Friendship Buttons Text
 */
function yz_edit_friendship_buttons_text( $button ) {

    switch ( $button['id'] ) {

        case 'is_friend' :
        $button['link_text'] = __( 'Unfriend', 'youzer' );
        break;

        // case 'awaiting_response' :
        // $button['link_text'] = __( 'Confirm Request', 'youzer' );
        // break;

        case 'pending' :
        $button['link_text'] = __( 'Cancel Request', 'youzer' );
        break;

    }

    return $button;
}

add_filter( 'bp_get_add_friend_button', 'yz_edit_friendship_buttons_text', 9 );

/**
 * Is Youzer Custom Component
 */
function yz_is_youzer_custom_component() {
    if (
        bp_is_current_component( 'posts' ) || bp_is_current_component( 'comments' ) ||
        bp_is_current_component( 'info' ) || bp_is_current_component( 'overview' )
    ) {
        return true;
    }

    return false;
}


/**
 * Max upload file size for any attachment.
 */
function yz_set_max_attachments_file_size( $size = null ) {
    // 10MB.
    return 10240000;
}

add_filter( 'bp_attachments_get_max_upload_file_size', 'yz_set_max_attachments_file_size', 999 );

/**
 * Edit My Profile Page LiNK
 */
function yz_edit_my_profile_menu_link( $items ) {

    foreach( $items as $item ) {

        if ( in_array( 'bp-yz-home-nav', $item->classes ) ) {
            // Get Logged-in User Domain.
            $item->url = bp_loggedin_user_domain();
        }
    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'yz_edit_my_profile_menu_link', 10 );

/**
 * Check is User Online.
 */
function yz_is_user_online( $user_id = null ) {

    $is_online = false;
    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Last Activity.
    $last_user_activity = bp_get_user_last_activity( $user_id );

    // Check if the last activity is exist.
    if ( ! empty( $last_user_activity ) ) {

        // Calculate some times.
        $current_time  = bp_core_current_time( true, 'timestamp' );
        $last_activity = strtotime( $last_user_activity );
        $still_online  = strtotime( '+5 minutes', $last_activity );

        // Has the user been active recently ?
        if ( $current_time <= $still_online ) {
            $is_online = true;
        }

    }

    return apply_filters( 'yz_is_user_online', $is_online, $user_id );

}

/**
 * Get User Online Icon.
 */
function yz_add_user_online_status_icon( $username = null, $user_id = null ) {

    if ( 'on' == yz_option( 'yz_header_enable_user_status', 'on' ) ) {
        if ( yz_is_user_online( $user_id ) ) {
            $username .= "<span class='yz-user-status yz-user-online'>" . __( 'Online', 'youzer' ) . '</span>';
        } else {
            $username .= "<span class='yz-user-status yz-user-offline'>" . __( 'Offline', 'youzer' ) . '</span>';
        }
    }

    return $username;

}

add_filter( 'yz_user_profile_username', 'yz_add_user_online_status_icon', 999 );

/**
 * Set Search Page.
 */
function yz_buddypress_bp_init() {

    if ( isset( $_GET['s'] ) ) {

        // if ( bp_is_active( 'groups' ) && bp_is_active( '' ) ) {
        //     // retur
        // }

        $hashtag = substr( $_GET['s'], 0, 1 );

        $bp_pages = yz_option( 'bp-pages' );

        if ( $hashtag == '#' || yz_option( 'page_on_front' ) == $bp_pages['activity'] ) {

            $bp = buddypress();

            if ( $bp->pages->activity->id == yz_option( 'page_on_front' ) ) {
                $bp->current_component = 'activity';
            }

        }

    }

}

add_action( 'bp_init', 'yz_buddypress_bp_init', 3 );