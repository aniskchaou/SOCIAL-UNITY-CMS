<?php

/**
 * Edit Navigation Menu
 */
function logy_edit_nav_menu( $items, $args ) {

	if ( ! is_user_logged_in() ) {
		return $items;
	}

    // Set up Array's.
    $forms_pages = array( 'register' => logy_page_id( 'register' ), 'login' => logy_page_id( 'login' ) );

    foreach ( $items as $key => $item ) {

        // if user logged-in change the Login Page title to Logout.
        if ( $item->object_id == $forms_pages['login'] ) {
            $item->url   = wp_logout_url();
            $item->title = __( 'Logout', 'youzer' );
        }

        // if user is logged-in remove the register page from menu.
        if ( ! empty( $forms_pages['register'] ) && $item->object_id == $forms_pages['register'] ) {
            unset( $items[ $key ] );
        }

    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'logy_edit_nav_menu', 10, 2 );

/**
 * # Get Page ID.
 */
function logy_page_id( $page ) {

    if ( 'register' == $page || 'activate' == $page ) {
        // Get Buddypress Pages.
        $bp_pages = yz_option( 'bp-pages' );
        // Get Page ID.
        $page_id = isset( $bp_pages[ $page ] ) ? $bp_pages[ $page ] : false;
    } else {
        // Get Logy Pages.
        $pages = yz_option( 'logy_pages' );
        $page_id = isset( $pages[ $page ] ) ? $pages[ $page ] : false;
    }

    return $page_id;
}

/**
 * # Get Page URL.
 */
function logy_page_url( $page_name ) {

    // Get Page Data
    $page_id = logy_page_id( $page_name );

    // Get Page Url.
    $page_url = trailingslashit( get_permalink( $page_id ) );

    // Return Page Url.
    return apply_filters( 'logy_page_url', $page_url, $page_name, $page_id );

}

/**
 * Redirect to custom page after the user has been logged out.
 */
add_action( 'wp_logout', 'yz_redirect_after_logout' );

function yz_redirect_after_logout() {

    // Get Redirect Page
    $redirect_to = yz_option( 'logy_after_logout_redirect', 'login' );

    // Get Redirect Url
    if ( 'login' == $redirect_to ) {
        $redirect_url = logy_page_url( 'login' ) . '?logged_out=true';
    } elseif ( 'profile' == $redirect_to ) {
        $redirect_url = bp_loggedin_user_domain( get_current_user_id() );
    } elseif ( 'members_directory' == $redirect_to ) {
        $redirect_url = bp_get_members_directory_permalink();
    } else {
        $redirect_url = home_url();
    }

    // Redirect User
    wp_safe_redirect( $redirect_url );
    exit;
}

/**
 * Get Available Social Networks.
 */
function logy_get_providers() {
    return apply_filters( 'logy_providers_list', array( 'Facebook', 'Twitter', 'Google', 'LinkedIn', 'Instagram', 'TwitchTV' ) );
}

/**
 * Get Providers Data.
 */
function logy_get_provider_data( $provider ) {

    $data = array(
        'Facebook' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-facebook-f'
        ),
        'Twitter' => array(
            'app'      => 'key',
            'icon'     => 'fab fa-twitter'
        ),
        'Google' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-google'
        ),
        'LinkedIn' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-linkedin-in'
        ),
        'Instagram' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-instagram'
        ),
        'TwitchTV' => array(
            'app'      => 'id',
            'icon'     => 'fab fa-twitch'
        )
    );

    $data = apply_filters( 'logy_providers_data', $data );

    return $data[ $provider ];
}

/**
 * Delete Stored User Data form Database.
 */
add_action( 'delete_user', 'logy_delete_stored_user_data' );

function logy_delete_stored_user_data( $user_id ) {

    global $wpdb;

    // Delete Data.
    $wpdb->query(
        $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "logy_users where user_id = %d", $user_id )
    );

    // Delete User Meta
    if ( is_multisite() ) {
        delete_user_option( $user_id, 'logy_avatar' );
    } else {
        delete_user_meta( $user_id, 'logy_avatar' );
    }

}

/**
 * Edit User Activity Default Avatar.
 */
function yz_set_social_media_default_avatar_url( $avatar_url = null, $params = null ) {

    if ( ! isset( $params['item_id'] ) ) {
        return $avatar_url;
    }

    // Get User Custom Avatar.
    $user_custom_avatar = yz_get_user_social_avatar( $params['item_id'] );

    if ( $user_custom_avatar ) {
        return esc_url( $user_custom_avatar );
    }

    return $avatar_url;
}

add_filter( 'yz_set_default_profile_avatar', 'yz_set_social_media_default_avatar_url', 10, 2 );

/**
 * Get User Social Login Avatar
 */
function yz_get_user_social_avatar( $user_id = null ) {

    $user_id = ! empty( $user_id ) ? $user_id : bp_loggedin_user_id();

    if ( is_multisite() ) {
        return get_user_option( 'logy_avatar', $user_id );
    }

    return get_user_meta( $user_id, 'logy_avatar', true );

}

// add_action( 'init', 'logy_hide_dashboard' );

/**
 * Override Youzer Login Page
 */

add_filter( 'yz_get_login_page_url', 'yz_override_youzer_login_page_url' );

function yz_override_youzer_login_page_url( $login ) {
    return logy_page_url( 'login' );
}


/**
 * # Redirect Users to Home Page.
 */
add_action( 'template_redirect', 'yz_redirect_to_home_page' );

function yz_redirect_to_home_page() {

    if ( is_user_logged_in() && ! is_front_page() ) {

        $page_id = get_queried_object_id();

        if ( $page_id ) {

            // Redirect To home if user is logged-in and he/she want to visit one of these pages.
            $forbidden_pages = array(
                logy_page_id( 'login' ),
                logy_page_id( 'lost-password' ),
                logy_page_id( 'complete-registration' ),
            );

            // Redirect User to home page.
            if ( in_array( $page_id , $forbidden_pages ) ) {
                wp_redirect( site_url() , 301 );
                exit;
            }

        }
    }

}
