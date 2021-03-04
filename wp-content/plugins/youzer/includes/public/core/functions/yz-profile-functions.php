<?php

/**
 * # Get Profile Class.
 */
function yz_get_profile_class() {

    // New Array
    $profile_class = array();

    // Get Profile Layout
    $profile_class[] = yz_get_profile_layout();

    // Get Profile Width Type
    $profile_class[] = 'yz-wild-content';

    // Get Tabs List Icons Style
    $profile_class[] = yz_option( 'yz_tabs_list_icons_style', 'yz-tabs-list-gradient' );

    // Get Elements Border Style.
    $profile_class[] = 'yz-wg-border-' . yz_option( 'yz_wgs_border_style', 'radius' );

    // Get Navbar Layout
    // $navbar_layout = yz_option( 'yz_vertical_layout_navbar_type', 'wild-navbar' );

    // Get Page Buttons Style
    $profile_class[] = 'yz-page-btns-border-' . yz_option( 'yz_buttons_border_style', 'oval' );

    // Add Vertical Wild Navbar.
    if ( yz_is_wild_navbar_active() ) {
        $profile_class[] = "yz-vertical-wild-navbar";
    }

    return apply_filters( 'yz_profile_class', yz_generate_class( $profile_class ) );
}

/**
 * Check is Wild Navbar Activated
 */
function yz_is_wild_navbar_active() {
    // Add Vertical Wild Navbar.
    if ( 'yz-vertical-layout' == yz_get_profile_layout() && 'wild-navbar' == yz_option( 'yz_vertical_layout_navbar_type', 'wild-navbar' ) ) {
        return true;
    }

    return false;
}

/**
 * # Add Login Button to Profile Page.
 */
// function yz_sidebar_login_button() {

//     // Check Visibility Requirements.
//     if ( is_user_logged_in() || 'off' == yz_option( 'yz_profile_login_button', 'on' ) || 'yz-vertical-layout' == yz_get_profile_layout() ) {
//         return false;
//     }

//     ? ><a href="<?php echo yz_get_login_page_url(); ? >" data-show-youzer-login="true" class="yz-profile-login yz_effect" data-effect="fadeIn"><i class="fas fa-user-circle"></i>< ?php _e( 'Sign in to your account', 'youzer' ); ? ></a>< ?php
// }

// add_action( 'yz_profile_sidebar', 'yz_sidebar_login_button', 1 );

/**
 * Get Post Thumbnail
 */
function yz_post_img() {

    global $post;


    if ( has_post_thumbnail() ) {

    ?>

    <div class="yz-post-img" style="background-image: url(<?php echo get_the_post_thumbnail_url( 'large' ); ?>);"></div>

    <?php

    } elseif ( ! has_post_thumbnail() ) {
        // Get Post Format
        $post_format = get_post_format();
        $post_format = ! empty( $post_format ) ? $post_format : 'standard';
        echo '<div class="ukai-alt-thumbnail"><div class="thumbnail-icon"><i class="'. yz_get_format_icon( $post_format ) .'"></i></div></div>';
    }
}


/**
 * # Get Pagination Loading Spinner.
 */
function yz_loading() { ?>
    <div class="yz-loading">
        <div class="youzer_msg wait_msg">
            <div class="youzer-msg-icon">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <span><?php _e( 'Please wait ...', 'youzer' ); ?></span>
        </div>
    </div><?php
}

/**
 * # Get Post Categories
 */
function yz_get_post_categories( $post_id , $hide_icon = false ) {

    $post_categories = get_the_category_list( ', ', '', $post_id );

    if ( $post_categories ) {
        echo '<li>';
        if ( 'on' == $hide_icon ) {
            echo '<i class="fas fa-tags"></i>';
        }
        echo $post_categories;
        echo '</li>';
    }

}

/**
 * # Get Project Tags
 */
function yz_get_project_tags( $tags_list ) {

    if ( ! $tags_list ) {
        return false;
    }

    ?>

    <ul class="yz-project-tags"><?php foreach( $tags_list as $tag ) { echo "<li><span class='yz-tag-symbole'>#</span>$tag</li>"; } ?></ul>

    <?php

}

/**
 * Check if is widget = AD widget
 */
function yz_is_ad_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'yz_ad_' ) ) {
        return true;
    }
    return false;
}

/**
 * Check if is widget = Custom widget
 */
function yz_is_custom_widget( $widget_name ) {
    if ( false !== strpos( $widget_name, 'yz_custom_widget_' ) ) {
        return true;
    }
    return false;
}

/**
 * # Check Link HTTP .
 */
function yz_esc_url( $url ) {
    $url = esc_url( $url );
    $disallowed = array( 'http://', 'https://' );
    foreach( $disallowed as $protocole ) {
        if ( strpos( $url, $protocole ) === 0 ) {
            return str_replace( $protocole, '', $url );
        }
    }
    return $url;
}

/**
 * #  Enable Widgets Shortcode.
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Make Profile Tab Private for other users.
 */
function yz_hide_profile_settings_page_for_other_users() {

    if ( apply_filters( 'yz_hide_profile_settings_page_for_other_users', true ) ) {
        if ( bp_is_user() && ! is_super_admin() && ! bp_is_my_profile() ) {
            bp_core_remove_nav_item( bp_get_profile_slug() );
        }
    }

}

add_action( 'bp_setup_nav', 'yz_hide_profile_settings_page_for_other_users', 15 );

/**
 * Display Profile
 */
function yz_display_profile() {

    if ( 'off' == yz_option( 'yz_allow_private_profiles', 'off' ) ) {
        return true;
    }

    // Get Profile Visitbily.
    $display_profile = yz_data( 'yz_enable_private_account', bp_displayed_user_id() );
    $profile_visibility = $display_profile ? $display_profile : 'off';

    if ( 'off' == $profile_visibility || yz_displayed_user_is_friend() ) {
        return true;
    }

    return false;
}

/**
 * Private Account Message.
 */
function yz_private_account_message() { ?>

    <div id="yz-not-friend-message">
        <i class="fas fa-user-secret"></i>
        <strong><?php _e( 'Private Account', 'youzer' ); ?></strong>
        <p><?php _e( 'You must be friends in order to access this profile.', 'youzer' ); ?></p>
    </div>

    <?php
}

/**
 * Change Cover Image Size.
 */
function yz_attachments_get_cover_image_dimensions( $wh ) {
    return array( 'width' => 1350, 'height' => 350 );
}

add_filter( 'bp_attachments_get_cover_image_dimensions', 'yz_attachments_get_cover_image_dimensions' );

/**
 * Replace Author Url By Buddypress Profile Url.
 */
function yz_edit_author_link_url( $link, $author_id ) {
    return bp_core_get_user_domain( $author_id );
}

add_filter( 'author_link', 'yz_edit_author_link_url', 9999, 2 );

/**
 * Redirect Author Page to Buddypress Profile Page.
 */
function yz_redirect_author_page_to_bp_profile() {

    if ( is_author() && ! is_feed() ) {

        // Get Author ID.
        $author_id = get_queried_object_id();

        // Redirect.
        bp_core_redirect( bp_core_get_user_domain( $author_id ) );

    }

}

add_action( 'template_redirect', 'yz_redirect_author_page_to_bp_profile', 5 );

/**
 * Check if User Has Gravatar
 */
function yz_user_has_gravatar( $email_address ) {

    // Get User Hash
    $hash = md5( strtolower( trim ( $email_address ) ) );

    // Build the Gravatar URL by hasing the email address
    $url = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';

    // Now check the headers...
    $headers = @get_headers( $url );

    // If 200 is found, the user has a Gravatar; otherwise, they don't.
    return preg_match( '|200|', $headers[0] ) ? true : false;

}