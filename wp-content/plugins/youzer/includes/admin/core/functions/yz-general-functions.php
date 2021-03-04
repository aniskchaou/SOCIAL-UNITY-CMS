<?php

/**
 * Disable Gravatars
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );

/**
 * Check Is Youzer Panel Page.
 */
function is_youzer_panel_page( $page_name ) {

    // Is Panel.
    $is_panel = isset( $_GET['page'] ) && $_GET['page'] == $page_name ? true : false;

    return apply_filters( 'is_youzer_panel_page', $is_panel, $page_name );
}

/**
 * Check Is Youzer Panel Page.
 */
function is_youzer_panel_tab( $tab_name ) {

    // Is Panel.
    $is_tab = isset( $_GET['tab'] ) && $_GET['tab'] == $tab_name ? true : false;

    return apply_filters( 'is_youzer_panel_tab', $is_tab, $tab_name );
}

/**
 * Top Bar Youzer Icon Css
 */
function yz_bar_icons_css() {

    // Show "Youzer Panel" Bar Icon
    if ( is_super_admin() ) {

        echo '<style>
            #adminmenu .toplevel_page_youzer-panel img {
                padding-top: 5px !important;
            }
            </style>';
    }

}

add_action( 'wp_head','yz_bar_icons_css' );
add_action( 'admin_head','yz_bar_icons_css' );


/**
* Add Documentation Submenu.
*/
function yz_add_documentation_submenu() {

    global $submenu;

    // Add Documentation Url
    $documentation_url = 'http://kainelabs.com/docs/youzer/';

    // Add Documentation Menu.
    $submenu['youzer-panel'][] = array(
        __( 'Documentation','youzer' ),
        'manage_options',
        $documentation_url
    );

}

add_action( 'admin_menu', 'yz_add_documentation_submenu', 100 );

/**
 * Check if page is an admin page  tab
 */
function yz_is_panel_tab( $page_name, $tab_name ) {

    if ( is_admin() && isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['page'] == $page_name && $_GET['tab'] == $tab_name ) {
        return true;
    }

    return false;
}


/**
 * Get Panel Profile Fields.
 */
function yz_get_panel_profile_fields() {

    // Init Panel Fields.
    $panel_fields = array();

    // Get All Fields.
    $all_fields = yz_get_all_profile_fields();

    foreach ( $all_fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $panel_fields[ $field_id ] = $field['name'];

    }

    // Add User Login Option Data.
    $panel_fields['user_login'] = __( 'Username', 'youzer' );

    return $panel_fields;
}

/**
 * Get Panel Profile Fields.
 */
function yz_get_user_tags_xprofile_fields() {

    // Init Panel Fields.
    $xprofile_fields = array();

    // Get xprofile Fields.
    $fields = yz_get_bp_profile_fields();

    foreach ( $fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $xprofile_fields[ $field_id ] = $field['name'];

    }

    return $xprofile_fields;
}

/**
 * Run WP TO BP Patch Notice.
 */
function yz_move_wp_fields_to_bp_notice() {

    $patch_url = add_query_arg( array( 'page' => 'youzer-panel&tab=patches' ), admin_url( 'admin.php' ) );

    if ( ! yz_option( 'yz_patch_new_media_system' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzer - New Media System Important Patch:<br> </strong>Please run the following patch <strong><a href='%1s'>Migrate to The New Youzer Media System.</a></strong> This operation will move all the old activity posts media ( images, videos, audios, files ) to a new database more organized and structured.", 'youzer' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! yz_option( 'yz_patch_new_media_system2' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzer - Media Optimization Patch :<br> </strong>Please run the following patch <strong><a href='%1s'> Upgrade Media System Database.</a></strong> This operation will improve the media system structure.", 'youzer' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! yz_option( 'yz_patch_new_wp_media_library' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( "<strong>Youzer - Upgrade to Wordpress Media Library Patch :</strong><br><br>Please run the following patch <strong><a href='%1s'> Upgrade to Wordpress Media Library.</a></strong> This operation will improve the media system and make it more optimized and very fast.", $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! yz_option( 'yz_patch_optimize_database' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzer - Database Optimization Patch :<br> </strong>Please run the following patch <strong><a href='%1s'>Optimize Youzer Database</a></strong> This will increase your website speed.", 'youzer' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( yz_option( 'install_youzer_2.1.5_options' ) ) {

        if ( ! yz_option( 'yz_patch_move_wptobp' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzer - Important Patch :<br> </strong>Please run the following patch <strong><a href='%1s'>Move Wordpress Fields To The Buddypress Xprofile Fields.</a></strong> This patch will move all the previews users fields values to the new created Buddypress fields so now you can have the full control over profile info tab and contact info tab fields also : Re-order them, Control their visibility or even remove them if you want.</strong>", 'youzer' ), $patch_url ); ?></p>
        </div>

        <?php

        }
    }

}

/**
 * New Extension Notice
 **/
function yz_display_new_extension_notice() {

    $yzea_notice = 'yz_hide_yzea_notice';
    $yzpc_notice = 'yz_hide_yzpc_notice';
    $yzbm_notice = 'yz_hide_yzbm_notice';
    $yzbmr_presale_notice = 'yz_hide_yzbmr_presale_notice';
    $yz_hide_yasale3_notice = 'yz_hide_yasale3_notice';
    $load_lightbox = false;

    if ( isset( $_GET['yz-dismiss-extension-notice'] ) ) {
        yz_update_option( $_GET['yz-dismiss-extension-notice'], 1 );
    }
        // if ( $_GET['yz-dismiss-extension-notice'] == $yzea_notice ) {
        // }

        // if ( $_GET['yz-dismiss-extension-notice'] == $yzpc_notice ) {
        //     update_option( $yzpc_notice, 1 );
        // }

        // if ( $_GET['yz-dismiss-extension-notice'] == $yzbm_notice ) {
        //     update_option( $yzbm_notice, 1 );
        // }

        // if ( $_GET['yz-dismiss-extension-notice'] == $yzbmr_presale_notice ) {
        //     update_option( $yzbmr_presale_notice, 1 );
        // }

        // if ( $_GET['yz-dismiss-extension-notice'] == 'yz_social_login_upgrade_1' ) {
        //     update_option( 'yz_social_login_upgrade_1', 1 );
        // }

        // if ( $_GET['yz-dismiss-extension-notice'] == $yz_hide_yasale3_notice ) {
        //     update_option( $yz_hide_yasale3_notice, 1 );
        // }


    // if ( ! yz_option( $yz_hide_yasale3_notice ) ) {

    //     $start_date = new DateTime( '2020/04/16' );
    //     $end_date = new DateTime( '2020/05/01' );
    //     $now = new DateTime();

    //     if ( $now >= $start_date && $now < $end_date ) {
    //         // Get Extension.
    //         yz_get_notice_addon( array(
    //             'class' => 'yz-sale-notice',
    //             'notice_id' => $yz_hide_yasale3_notice,
    //             'utm_campaign' => 'youzer-3rd-anniversary-2020',
    //             'utm_medium' => 'admin-banner',
    //             'utm_source' => 'clients-site',
    //             'title' => 'Youzer 3rd Anniversary Sale - 40% OFF Discount ! ',
    //             'link' => 'https://www.kainelabs.com/',
    //             'buy_now' => 'https://www.kainelabs.com/?',
    //             'image' => 'https://kainelabs.com/tmp/youzer-anniversary-3.png',
    //             'description' => "Use code <strong>YOUZER3RD</strong> & Save <strong>40%</strong> on all Youzer Extensions.<br>Limited Time Offer.",
    //             'buy_now_title' => "Let's Shop"
    //         ) );
    //     }
    // }

    if ( ! yz_option( $yzbmr_presale_notice ) ) {
        $load_lightbox = true;
        $data = array(
            'notice_id' => $yzbmr_presale_notice,
            'utm_campaign' => 'youzer-membership-restrictions',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Buddypress Membership Restrictions',
            'link' => 'https://www.kainelabs.com/downloads/buddypress-membership-restrictions/',
            'buy_now' => 'https://www.kainelabs.com/checkout/?edd_action=add_to_cart&download_id=46428&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/03/thumbnail.png',
            'description' => 'Instead of spending thousands of dollars on customizations Meet the most complete buddypress membership restrictions plugin to restrict buddypress community features and content for visitors, members or by user role to take the full control over what your website users get exclusive access to.',
            'features' => array(
                'Set Members, Visitors ( Non Logged In Users ) Or By User Role Restrictions.',
                'Set Custom Redirect Page for Visitors, Members or By User Role.',
                'Set Buddypress Components Restrictions.',
                'Set Wordpress Pages & Buddypress Pages Restrictions.',
                'Set The Maximum Restrictions & Minimum Requirements For Activity Posts & Comments.',
                'Set Public, Private, Hidden Groups Restrictions.',
                'Set Friendship, Messages, Follows, Reviews Restrictions.',
                'Set Profile Tabs & Profile Widgets Restrictions.',
                'And much much more ... check all the detailed features on the add-on description page !'
            ),
            'images' => array(
                array( 'title' => 'Components Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/components-restrictions.png' ),
                array( 'title' => 'Activity Form Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-form-restrictions.png' ),
                array( 'title' => 'Activity Posting Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-posting-restrictions.png' ),
                array( 'title' => 'Activity Posting Requirements', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-posting-requirements.png' ),
                array( 'title' => 'Activity Feed Post Types Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-feed-post-types-restrictions.png' ),
                array( 'title' => 'Activity Post Buttons Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-post-buttons-restrictions.png' ),
                array( 'title' => 'Activity Comments Requirements', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-comments-requirements.png' ),
                array( 'title' => 'Activity Comments Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/activity-comments-restrictions.png' ),
                array( 'title' => 'Groups Creation Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/groups-creation-restrictions.png' ),
                array( 'title' => 'Public Groups Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/public-groups-restrictions.png' ),
                array( 'title' => 'Hidden Groups Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/hidden-groups-restrictions.png' ),
                array( 'title' => 'Private Group Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/private-groups-restrictions.png' ),
                array( 'title' => 'Buddypress Pages Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/buddypress-pages-restrictions.png' ),
                array( 'title' => 'Friendship Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/friendship-restrictions.png' ),
                array( 'title' => 'Messages Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/messages-restrictions.png' ),
                array( 'title' => 'Follows Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/follows-restrictions.png' ),
                array( 'title' => 'Profile Tab Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/profile-tabs-restrictions.png' ),
                array( 'title' => 'Reviews Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/reviews-restrictions.png' ),
                array( 'title' => 'Wordpress Pages Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/wordpress-pages-restrictions.png' ),
                array( 'title' => 'Profile Widgets Creation Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/profile-widgets-creation-restrictions.png' ),
                array( 'title' => 'Profile Widgets Visibility Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2020/04/profile-widgets-visibility-restrictions.png' ),
            )
         );

        // Get Extension.
        yz_get_notice_addon( $data );
    }

    if ( ! yz_option( $yzea_notice ) ) {
        $load_lightbox = true;
        $data = array(
            'notice_id' => $yzea_notice,
            'utm_campaign' => 'youzer-edit-activity',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzer - Buddypress Edit Activity',
            'link' => 'https://www.kainelabs.com/downloads/buddypress-edit-activity/',
            'buy_now' => 'https://www.kainelabs.com/checkout/?edd_action=add_to_cart&download_id=22081&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/Untitled-1.png',
            'description' => 'Allow members to edit their activity posts, comment and replies from the front-end with real time modifications. Set users that can edit their own activities and moderators by role and control editable activities by post type and set a timeout for how long they should remain editable and much more ...',
            'features' => array(
                'Set Members That Can Edit Their Own Activities and Comments by Role.',
                'Set Editable Activities By Post Type.',
                'Set Moderators That Can Edit All The Site Activities by Role.',
                'Set Edit Button Timeout ( How long activities should remain editable ).',
                'Enable / Disable Attachments Edition.',
                'Enable / Disable Comments & Replies Edition.',
                'Real Time Modifications. No Refresh Page Required !'
            ),
            'images' => array(
                array( 'title' => 'Post & Comments Edit Buttons', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/normal-post.png' ),
                array( 'title' => 'Photos Post Edit Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/photospost.png' ),
                array( 'title' => 'Live URL Preview Post Edit Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/link.png' ),
                array( 'title' => 'Quote Post Edit Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/edit-quote-form.png' ),
                array( 'title' => 'Quote Post Edit Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/quote-post-edit-buttton.png' ),
                array( 'title' => 'Link Post Edit Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/link-post-edit.png' )
            )
         );

        // Get Extension.
        yz_get_notice_addon( $data );
    }

    if ( ! yz_option( $yzpc_notice ) ) {
        $load_lightbox = true;
        $data2 = array(
            'notice_id' => $yzpc_notice,
            'utm_campaign' => 'youzer-profile-completeness',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzer - Buddypress Profile Completeness',
            'link' => 'https://www.kainelabs.com/downloads/buddypress-profile-completeness/',
            'buy_now' => 'https://www.kainelabs.com/?edd_action=add_to_cart&download_id=21146&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/05/youzer-profile-completeness.png',
            'description' => 'Say good bye to the blank profiles, buddypress profile completeness is the best way to force or encourage users to complete their profile fields, profile widgets and more. also gives you the ability to apply restrictions on incomplete profiles.',
            'features' => array(
                '3 Fields Status ( Forced, Required, Optional ).',
                'Apply Profile Completeness System For Specific Roles.',
                'Enable / Disable Hiding Incomplete Profiles from Members Directory.',
                'Enable / Disable Marking Complete Profiles as Verified.',
                'Enable / Disable The following Actions For Incomplete Profiles : Posts, Comments, Replies, Follows, Groups, Messages ...',
                'Supported Fields : All Buddypress Fields, Youzer Widgets, Buddypress Avatar & Cover Images.',
                'Profile Completeness Shortcode : [youzer_profile_completeness].',
                'Ajaxed Profile Completeness Widget.'
            ),
            'images' => array(
                array( 'title' => 'Profile Completeness Widget', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/06/complete-profile.png' ),
                array( 'title' => 'Profile Completeness – Profile Info', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/06/profile-info.png' ),
                array( 'title' => 'Profile Completeness – Profile Images', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/06/upload-images.png' ),
                array( 'title' => 'Profile Completeness – Widgets Settings', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/06/widgets-settings.png' ),
                array( 'title' => 'Profile Completeness – Account Restrictions', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/06/account-restrictions.png' )
            )
         );

        // Get Extension.
        yz_get_notice_addon( $data2 );
    }

    if (  ! yz_option( $yzbm_notice ) ) {
        $load_lightbox = true;
        $data3 = array(
            'notice_id' => $yzbm_notice,
            'utm_campaign' => 'youzer-buddypress-moderation',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzer - Buddypress Moderation',
            'link' => 'https://www.kainelabs.com/downloads/buddypress-moderation-plugin/',
            'buy_now' => 'https://www.kainelabs.com/?edd_action=add_to_cart&download_id=27779&edd_options%5Bprice_id%5D=1',
            'image' => 'https://cldup.com/m9j-9YtX-C.png',
            'description' => "Moderating your online community is not an option — it’s a must. Meet the most complete buddyPress moderation solution with an advanced features to take the full control over your community and keep it safe with automatic moderation features and automatic restrictions.",
            'features' => array(
                'Moderation Components : Members, Activities, Comments, Messages, Groups.',
                'Set What Roles Can Reports Items & Moderator Roles.',
                'Automatic Moderation After an item reach a certain numner of reports.',
                'Apply Temporary or Official Restrictions for Specific Periods. ( Disable posts, comments, messages, friends, follows ... )',
                'Allow Visitors to Report Items & Add Unlimited Reports Subjects.',
                'Customizable Notification Emails when a New Reports is Added, Restored, Deleted, Hidden & More ...',
                'Advanced Moderation Table With Bulk and Single Actions : View, Close, Restore, Delete, Delete & Punish, Mark as Spammer & More ...',
                'And Many Many Other Features You Can Check Them On The Extension Page.'
            ),
            'images' => array(
                array( 'title' => 'Reports Table ( Moderation Queue )', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/reports2.png' ),
                array( 'title' => 'Restrictions Table', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/restrictions.png' ),
                array( 'title' => 'Activity Posts & Comments Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/posts-comments.png' ),
                array( 'title' => 'Members Directory – User Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/members.png' ),
                array( 'title' => 'User Profile Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/user-profile.png' ),
                array( 'title' => 'Groups Directory – Group Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/groups.png' ),
                array( 'title' => 'Single Group Page Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/groups-single-page.png' ),
                array( 'title' => 'Messages Report Button', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/message.png' ),
                array( 'title' => 'Logged-In Users Report Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/members-report-form.png' ),
                array( 'title' => 'Visitors Report Form', 'link' => 'https://www.kainelabs.com/wp-content/uploads/edd/2019/11/visitors-report.png' )
            )
         );

        // Get Extension.
        yz_get_notice_addon( $data3 );
    }

    if ( $load_lightbox ) {
        // Load Light Box CSS and JS.
        wp_enqueue_style( 'yz-lightbox', YZ_PA . 'css/lightbox.min.css', array(), YZ_Version );
        wp_enqueue_script( 'yz-lightbox', YZ_PA . 'js/lightbox.min.js', array( 'jquery' ), YZ_Version, true );
    }

}

add_action( 'admin_notices', 'yz_display_new_extension_notice' );
add_action( 'admin_notices', 'yz_move_wp_fields_to_bp_notice' );


/**
 * Get Notice Add-on
 */
function yz_get_notice_addon( $data ) {
    ?>

    <style type="text/css">

        body .yz-addon-notice {
            padding: 0;
            border: none;
            overflow: hidden;
            box-shadow: none;
            margin-top: 15px;
            max-width: 870px;
            position: relative;
            margin-bottom: 15px;
            margin: 0 auto 15px !important;
        }

        .yz-addon-notice .yz-addon-notice-content {
            /*float: left;*/
            /*width: 80%;*/
            /*margin-left: 20%;*/
            padding: 25px 35px;
        }
/*
        .yz-addon-notice.yz-horizontal-layout .yz-addon-notice-img {
            display: block;
            background-size: cover;
            background-position: center;
            float: left;
            width: 20%;
            height: 100%;
            position: absolute;
        }
*/
        .yz-addon-notice .yz-addon-notice-img {
            display: block;
            background-size: cover;
            background-position: center;
            width: 100%;
        }

        .yz-addon-notice .yz-addon-notice-title {
            font-size: 17px;
            font-weight: 600;
            color: #646464;
            margin-bottom: 10px;
        }

        .yz-addon-notice .yz-addon-notice-title .yz-addon-notice-tag {
            color: #fff;
            display: inline-block;
            text-transform: uppercase;
            font-weight: 600;
            margin-left: 8px;
            font-size: 10px;
            padding: 0px 8px;
            border-radius: 2px;
            background-color: #FFC107;
        }

        .yz-addon-notice .yz-addon-notice-description {
            font-size: 13px;
            color: #646464;
            line-height: 24px;
            margin-bottom: 15px;
        }

        .yz-addon-notice .yz-addon-notice-buttons a {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            min-width: 110px;
            text-align: center;
            margin-right: 12px;
            padding: 15px 25px;
            border-radius: 3px;
            display: inline-block;
            vertical-align: middle;
            text-decoration: none;
        }

        .yz-addon-notice .yz-addon-notice-buttons a:focus {
            box-shadow: none !important;
        }

        .yz-addon-notice .notice-dismiss {
            text-decoration: none;
        }

        .yz-addon-notice .yz-addon-notice-buttons a.yz-addon-view-features {
            background-color: #03A9F4;
        }

        .yz-addon-notice .yz-addon-notice-buttons a.yz-addon-buy-now {
            background-color: #8bc34a;
        }


        .yz-addon-notice .yz-addon-notice-buttons a.yz-addon-delete-notice {
            background: #F44336;
        }

        .yz-addon-features {
            margin-bottom: 25px;
        }

        .yz-addon-notice .yz-addon-features p {
            margin: 0 0 12px;
        }

        .yz-addon-notice .yz-addon-features p:last-of-type {
            margin-bottom: 0;
        }

        .yz-addon-screenshots {
            margin-bottom: 20px;
        }

        .yz-addon-screenshots .yz-screenshot-item {
            width: 60px;
            height: 60px;
            border-radius: 3px;
            /*margin-right: 10px;*/
            /*margin-bottom: 5px;*/
            margin: 5px 10px 5px 0;
            display: inline-block;
            background-size: cover;
        }

        .yz-addon-section-title {
            color: #646464;
            font-size: 13px;
            font-weight: 600;
            background: #eee;
            border-radius: 3px;
            margin-bottom: 15px;
            display: inline-block;
            padding: 4px 12px 5px;
        }

        .yz-sale-notice .yz-addon-view-features {
            display: none !important;
        }

    </style>

    <?php

        // <a href="<?php echo add_query_arg( 'yz-dismiss-extension-notice', $data['notice_id'], yz_get_current_page_url() ); ? >" type="button" class="notice-dismiss">Dismiss.</a>
        $link = $data['link'] .'?utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=view-all-features';

       $img_link = $data['link'] .'?utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=notice-cover';

        $buy = $data['buy_now'] .'&utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=buy-now';

        ?>

    <div id="<?php echo $data['notice_id']; ?>" class="yz-addon-notice updated notice notice-success <?php echo isset( $data['class'] ) ? $data['class'] : ''; ?>">
        <!-- <div class="yz-addon-notice-img" style="background-image:url(<?php echo $data['image']; ?>);"></div> -->
        <a href="<?php echo $img_link; ?>"><img class="yz-addon-notice-img" src="<?php echo $data['image']; ?>" alt=""></a>
        <div class="yz-addon-notice-content">
            <div class="yz-addon-notice-title"><?php echo $data['title']; ?><span class="yz-addon-notice-tag">New</span></div>
            <div class="yz-addon-notice-description"><?php echo $data['description']; ?></div>
            <?php if ( isset( $data['features'] ) ) : ?>
            <div class="yz-addon-features">
                <div class="yz-addon-section-title">Features</div><br>
                <?php foreach ( $data['features'] as $feature ) : ?>
                <p>- <?php echo $feature; ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ( isset( $data['images'] ) ) : ?>
            <div class="yz-addon-screenshots" data-lightbox="<?php echo $data['notice_id']; ?>">
                <div class="yz-addon-section-title">Screenshots</div><br>
                <?php foreach ( $data['images'] as $image ) : ?>
                <a href="<?php echo $image['link']; ?>" data-lightbox="<?php echo $data['notice_id']; ?>" data-title="<?php echo $image['title']; ?>"><div class="yz-screenshot-item" style="background-image: url(<?php echo $image['link']; ?>)"></div></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="yz-addon-notice-buttons">
                <a href="<?php echo $link; ?>" class="yz-addon-view-features">View All Features</a>
                <a href="<?php echo $buy; ?>" class="yz-addon-buy-now"><?php echo isset( $data['buy_now_title'] ) ? $data['buy_now_title'] : __( 'Buy Now', 'youzer' ); ?></a>
                <a href="<?php echo add_query_arg( 'yz-dismiss-extension-notice', $data['notice_id'], yz_get_current_page_url() ); ?>" type="button" class="yz-addon-delete-notice">Delete Notice</a>
            </div>
        </div>
    </div>

    <?php
}

// /**
//  * Widgets Enqueue scripts.
//  */
// function yz_widgets_enqueue_scripts( $hook_suffix ) {

//     if ( 'widgets.php' !== $hook_suffix ) {
//         return;
//     }

//     yz_iconpicker_scripts();

// }

// add_action( 'admin_enqueue_scripts', 'yz_widgets_enqueue_scripts' );
/**
 * Get Activity Posts Types
 */
function yz_activity_post_types() {

    // Get Post Types Visibility
    $post_types = array(
        'activity_status'       => __( 'Status', 'youzer' ),
        'activity_photo'        => __( 'Photo', 'youzer' ),
        'activity_slideshow'    => __( 'Slideshow', 'youzer' ),
        'activity_link'         => __( 'Link', 'youzer' ),
        'activity_quote'        => __( 'Quote', 'youzer' ),
        'activity_giphy'        => __( 'GIF', 'youzer' ),
        'activity_video'        => __( 'Video', 'youzer' ),
        'activity_audio'        => __( 'Audio', 'youzer' ),
        'activity_file'         => __( 'File', 'youzer' ),
        'activity_share'        => __( 'Share', 'youzer' ),
        'new_cover'             => __( 'New Cover', 'youzer' ),
        'new_avatar'            => __( 'New Avatar', 'youzer' ),
        'new_member'            => __( 'New Member', 'youzer' ),
        'friendship_created'    => __( 'Friendship Created', 'youzer' ),
        'friendship_accepted'   => __( 'Friendship Accepted', 'youzer' ),
        'created_group'         => __( 'Group Created', 'youzer' ),
        'joined_group'          => __( 'Group Joined', 'youzer' ),
        'new_blog_post'         => __( 'New Blog Post', 'youzer' ),
        'new_blog_comment'      => __( 'New Blog Comment', 'youzer' ),
        // 'activity_comment'      => __( 'Comment Post', 'youzer' ),
        'updated_profile'       => __( 'Updates Profile', 'youzer' )
    );

    if ( class_exists( 'Woocommerce' ) ) {
        $post_types['new_wc_product'] = __( 'New Product', 'youzer' );
        $post_types['new_wc_purchase'] = __( 'New Purchase', 'youzer' );
    }

    if ( class_exists( 'bbPress' ) ) {
        $post_types['bbp_topic_create'] = __( 'Forum Topic', 'youzer' );
        $post_types['bbp_reply_create'] = __( 'Forum Reply', 'youzer' );
    }

    return apply_filters( 'yz_activity_post_types', $post_types );
}

/**
 * Admin Modal Form
 */
function yz_panel_modal_form( $args, $modal_function ) {

    $title        = $args['title'];
    $button_id    = $args['button_id'];
    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'Save', 'youzer' );

    ?>

    <div class="yz-md-modal yz-md-effect-1" id="<?php echo $args['id'] ;?>">
        <h3 class="yz-md-title" data-title="<?php echo $title; ?>">
            <?php echo $title; ?>
            <i class="fas fa-times yz-md-close-icon"></i>
        </h3>
        <div class="yz-md-content">
            <?php $modal_function(); ?>
        </div>
        <div class="yz-md-actions">
            <button id="<?php echo $button_id; ?>" data-add="<?php echo $button_id; ?>" class="yz-md-button yz-md-save">
                <?php echo $button_title ?>
            </button>
            <button class="yz-md-button yz-md-close">
                <?php _e( 'Close', 'youzer' ); ?>
            </button>
        </div>
    </div>

    <?php
}

/**
 * Exclude Youzer Media from Wordpress Media Library.
 */
add_filter( 'parse_query', 'yz_exclude_youzer_media_from_media_library' );

function yz_exclude_youzer_media_from_media_library( $wp_query ) {

    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        $term = get_term_by( 'slug', 'youzify_media', 'category' );
        $wp_query->set( 'category__not_in', array( $term->term_id ) );
    }

}