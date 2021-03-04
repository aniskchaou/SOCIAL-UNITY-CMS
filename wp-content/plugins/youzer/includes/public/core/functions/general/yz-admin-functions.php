<?php

/**
 * Check Is Youzer Panel Page.
 */
function yz_admin_pages() {
    // Youzer Admin Pages
    $admin_pages = array(
        'youzer-panel', 'yz-profile-settings', 'yz-widgets-settings', 'yz-membership-settings', 'yz-extensions-settings'
    );

    return apply_filters( 'yz_admin_pages', $admin_pages );
}

/**
 * Check Is Youzer Panel Page.
 */
function is_youzer_panel() {

    // Admin Pages.
    $admin_pages = yz_admin_pages();

    // Is Panel.
    $is_panel = is_admin() && isset( $_GET['page'] ) && in_array( $_GET['page'], $admin_pages ) ? true : false;

    return apply_filters( 'is_youzer_panel', $is_panel );
}

/**
 * Register & Load Youzer widgets
 */
function yz_load_author_widget() {

    // Wordpress Widgets.
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-media-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-author-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-hashtags-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-rss-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-my-account-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-mods-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-post-author-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-admins-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-smart-author-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-activity-rss-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-notifications-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-suggestions-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-friend-suggestions-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-group-description-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-mycred-balance-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-verified-users-widget.php';
    require_once YZ_PUBLIC_CORE . 'widgets/wp-widgets/class-yz-community-hashtags-widget.php';

    // Register Widgets
    register_widget( 'YZ_Author_Widget' );
    register_widget( 'YZ_Group_Rss_Widget' );
    register_widget( 'YZ_My_Account_Widget' );
    register_widget( 'YZ_Notifications_Widget' );
    register_widget( 'YZ_Post_Author_Widget' );
    register_widget( 'YZ_Activity_Rss_Widget' );
    register_widget( 'YZ_Smart_Author_Widget' );
    register_widget( 'YZ_Group_Admins_Widget' );
    register_widget( 'YZ_Group_Mods_Widget' );
    register_widget( 'YZ_Community_Media_Widget' );
    register_widget( 'YZ_Group_Description_Widget' );
    register_widget( 'YZ_Group_Suggestions_Widget' );
    register_widget( 'YZ_Friend_Suggestions_Widget' );
    register_widget( 'YZ_Verified_Users_Widget' );
    register_widget( 'YZ_Hashtags_Widget' );
    register_widget( 'YZ_Community_Hashtags_Widget' );
}

add_action( 'widgets_init', 'yz_load_author_widget' );


/**
 * Customize WordPress Toolbar
 */
function yz_bp_customize_toolbar( $wp_admin_bar ) {

    // Get Login Node.
    $login_node = $wp_admin_bar->get_node( 'bp-login' );

    if ( $login_node ) {

        // Edit Buddypress Toolbar Login Url
        $wp_admin_bar->add_node(
            array(
                'id'   => 'bp-login',
                'href' => yz_get_login_page_url()
            )
        );

    }

    if ( ! is_user_logged_in() ) {
        return false;
    }

    // Get Current User Domain.
    $user_domain = bp_core_get_user_domain( bp_displayed_user_id() );
    $profile_url = $user_domain . bp_get_profile_slug() . '/';

    // Get Edit Member.
    $edit_member = $wp_admin_bar->get_node( 'user-admin' );

    if ( $edit_member ) {

        // Modify "Edit Profile " Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'user-admin-edit-profile',
                'href' => $profile_url
            )
        );
    }

    // Get My Account.
    $my_account = $wp_admin_bar->get_node( 'my-account' );

    if ( $my_account ) {

        // Get Edit profile link.
        $edit_my_profile_link = yz_get_profile_settings_url( null, bp_loggedin_user_id() );

        // Mofidy "Edit My Profile" Link.
        $wp_admin_bar->add_node(
            array(
                'id'   => 'edit-profile',
                'href' => $edit_my_profile_link
            )
        );

        if (  bp_is_active( 'xprofile' ) ) {

            // Modify "Profile - View " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-public',
                    'href' =>  bp_loggedin_user_domain()
                )
            );

            // Modify "Profile - Edit " Link.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-xprofile-edit',
                    'href' => $edit_my_profile_link
                )
            );

        }

        if (  bp_is_active( 'notifications' ) ) {

            // Modify "Settings - Email" Title.
            $wp_admin_bar->add_node(
                array(
                    'id'   => 'my-account-settings-notifications',
                    'title'=> __( 'Notifications', 'youzer' )
                )
            );

        }

        // if (  bp_is_active( 'activity' ) ) {

        //     // Modify "Activity" Title.
        //     $wp_admin_bar->add_node(
        //         array(
        //             'id'   => 'my-account-activity',
        //             'title'=> yz_options( 'yz_wall_tab_title' )
        //         )
        //     );

        // }

        // Remove "Settings( General & Profile )" Link.
        $wp_admin_bar->remove_node( 'my-account-settings-general' );
        $wp_admin_bar->remove_node( 'my-account-settings-profile' );

    }
}

add_action( 'admin_bar_menu', 'yz_bp_customize_toolbar', 999 );

/**
 * Install Buddypress Activity Privacy.
 */
// function yz_install_bp_activity_privacy() {

//     if ( ! yz_option( 'yz_install_bp_activity_privacy' ) ) {

//         update_option( 'yz_install_bp_activity_privacy', 1, 'no' );

//     }

// }

// add_action( 'admin_init', 'yz_install_bp_activity_privacy' );