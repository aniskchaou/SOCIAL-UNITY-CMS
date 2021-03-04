<?php

/**
 * Set Default ProfileTab.
 */
function yz_set_profile_default_tab() {

    if ( bp_is_user() ) {

        // Get Default Tab.
        $default_tab = yz_option( 'yz_profile_default_tab', 'overview' );

        if ( ! empty( $default_tab ) )  {

            buddypress()->active_components[ $default_tab ] = 1;

            // Set Default Tab
            if ( ! defined( 'BP_DEFAULT_COMPONENT' ) ) {
                define( 'BP_DEFAULT_COMPONENT', $default_tab );
            }

        }

    }
}

add_action( 'bp_init', 'yz_set_profile_default_tab', 3 );

/**
 * Youzer Options
 */
function yz_options( $option_id ) {

    // Get Option Value.
    if ( ! is_multisite() ) {
        $option_value = get_option( $option_id );
    } else {
        $option_value = get_blog_option( null, $option_id );
    }

    // Filter Option Value.
    $option_value = apply_filters( 'youzer_edit_options', $option_value, $option_id );

    if ( ! isset( $option_value ) || empty( $option_value ) ) {
        $Yz_default_options = yz_default_options();
        if ( isset( $Yz_default_options[ $option_id ] ) ) {
            $option_value = $Yz_default_options[ $option_id ];
        }
    }

    return $option_value;
}

/**
 * Get Option
 */
function yz_option( $option, $default = null ) {

    if ( ! is_multisite() ) {
        $option_value = get_option( $option, $default );
    } else {
        if ( apply_filters( 'yz_activate_blog_mode', true, $option ) ) {
            $option_value = get_blog_option( null, $option, $default );
        } else {
            $option_value = get_site_option( $option, $default );
        }
    }

    return $option_value;
}

/**
 * Update Option
 */
function yz_update_option( $option, $value = null, $autoload = false ) {

    if ( ! is_multisite() ) {
        $option_value = update_option( $option, $value, $autoload );
    } else {
        if ( apply_filters( 'yz_activate_blog_mode', true, $option ) ) {
            $option_value = update_blog_option( null, $option, $value );
        } else {
            $option_value = update_site_option( $option, $value );
        }
    }

    return $option_value;
}

/**
 * Delete Option
 */
function yz_delete_option( $option ) {

     if ( ! is_multisite() ) {
        $option_value = delete_option( $option );
    } else {

        if ( apply_filters( 'yz_activate_blog_mode', true, $option ) ) {
            $option_value = delete_blog_option( null, $option );
        } else {
            $option_value = delete_site_option( $option );
        }

    }

    return $option_value;
}

/**
 * Get Image
 */
function yz_get_image_attributes( $attachment_id, $size, $element ) {

    // Get Attachment URL.
    $url = yz_get_attachment_image_url( $attachment_id, $size, $element );

    return apply_filters( 'yz_get_image_attributes', "src='$url'", $url );

}

/**
 * Get Image
 */
function yz_get_image_attributes_by_link( $url) {
    return apply_filters( 'yz_get_image_attributes', "src='$url'", $url );
}

/**
 * Get Image Src
 */
function yz_get_attachment_image_url( $attachment_id, $size, $element ) {

    // Filter Size.
    $size = apply_filters( 'yz_get_attachment_image_size', $size, $element );

    return wp_get_attachment_image_url( $attachment_id, $size );
}

/**
 * # Get Option Array Values
 */
function yz_get_select_options( $option_id ) {

    // Set Up Variables
    $array_values  = array();
    $option_value  = yz_option( $option_id );

    // Get Default Value
    if ( ! $option_value ) {
        $Yz_default_options = yz_default_options();
        $option_value = $Yz_default_options[ $option_id ];
    }

    foreach ( $option_value as $key => $value ) {
        $array_values[ $value ] = $value;
    }

    return $array_values;
}

/**
 * # Youzer Default Options .
 */
function yz_default_options() {

    $default_options = array(

        // Author Box
        // 'yz_author_photo_effect'        => 'on',
        'yz_display_author_networks'    => 'on',
        'yz_enable_author_pattern'      => 'on',
        'yz_enable_author_overlay'      => 'on',
        'yz_author_photo_border_style'  => 'circle',
        'yz_author_sn_bg_type'          => 'silver',
        'yz_author_sn_bg_style'         => 'radius',
        'yz_author_meta_type'           => 'full_location',
        'yz_author_meta_icon'           => 'fas fa-map-marker',
        'yz_author_layout'              => 'yzb-author-v1',
        'yz_display_author_first_statistic' => 'on',
        'yz_display_author_third_statistic' => 'on',
        'yz_display_author_second_statistic'=> 'on',
        'yz_author_first_statistic' => 'posts',
        'yz_author_third_statistic' => 'views',
        'yz_author_second_statistic'=> 'comments',

        // Author Statistics.
        'yz_author_use_statistics_bg' => 'on',
        'yz_display_widget_networks' => 'on',
        'yz_author_use_statistics_borders' => 'on',

        // User Profile Header
        'yz_profile_photo_effect'           => 'on',
        'yz_display_header_site'            => 'on',
        // 'yz_display_header_networks'        => 'on',
        // 'yz_display_header_location'        => 'on',
        'yz_enable_header_pattern'          => 'on',
        'yz_enable_header_overlay'          => 'on',
        'yz_header_enable_user_status'      => 'on',
        // 'yz_header_use_photo_as_cover'      => 'off',
        'yz_header_photo_border_style'      => 'circle',
        'yz_header_sn_bg_type'              => 'colorful',
        'yz_header_sn_bg_style'             => 'radius',
        'yz_header_layout'                  => 'hdr-v1',
        'yz_header_meta_type'               => 'full_location',
        'yz_hheader_meta_type_1'            => 'full_location',
        'yz_hheader_meta_type_2'            => 'user_url',
        'yz_header_meta_icon'               => 'fas fa-map-marker-alt',
        'yz_hheader_meta_icon_1'            => 'fas fa-map-marker-alt',
        'yz_hheader_meta_icon_2'       	    => 'fas fa-link',
        'yz_header_use_statistics_bg'       => 'on',
        'yz_header_use_statistics_borders'  => 'off',
        'yz_display_header_first_statistic' => 'on',
        'yz_display_header_third_statistic' => 'on',
        'yz_display_header_second_statistic'=> 'on',
        'yz_header_first_statistic'         => 'posts',
        'yz_header_third_statistic'         => 'views',
        'yz_header_second_statistic'        => 'comments',

        // Group Header
        'yz_group_photo_effect'                 => 'on',
        'yz_display_group_header_privacy'       => 'on',
        'yz_display_group_header_posts'         => 'on',
        'yz_display_group_header_members'       => 'on',
        'yz_display_group_header_networks'      => 'on',
        'yz_display_group_header_activity'      => 'on',
        'yz_enable_group_header_pattern'        => 'on',
        'yz_enable_group_header_overlay'        => 'on',
        'yz_enable_group_header_avatar_border'  => 'on',
        'yz_group_header_use_avatar_as_cover'   => 'on',
        'yz_group_header_sn_bg_type'            => 'silver',
        'yz_group_header_sn_bg_style'           => 'circle',
        'yz_group_header_layout'                => 'hdr-v1',
        'yz_group_header_avatar_border_style'   => 'circle',
        'yz_group_header_use_statistics_bg'     => 'on',
        'yz_group_header_use_statistics_borders'=> 'off',

        // WP Navbar
        'yz_disable_wp_menu_avatar_icon' => 'on',

        // Navbar
        'yz_display_navbar_icons' => 'on',
        'yz_profile_navbar_menus_limit' => 5,
        'yz_navbar_icons_style' => 'navbar-inline-icons',
        'yz_vertical_layout_navbar_type' => 'wild-navbar',

        // Posts Tab
        'yz_profile_posts_per_page'  => 5,
        'yz_display_post_meta'       => 'on',
        'yz_display_post_excerpt'    => 'on',
        'yz_display_post_date'       => 'on',
        'yz_display_post_cats'       => 'on',
        'yz_display_post_comments'   => 'on',
        'yz_display_post_readmore'   => 'on',
        'yz_display_post_meta_icons' => 'on',

        // Overview Tab
        // 'yz_wall_tab_icon'    => 'fas fa-address-card',
        // 'yz_wall_tab_title'   => __( 'Wall', 'youzer' ),

        // Comments Tab
        'yz_profile_comments_nbr'     => 5,
        'yz_display_comment_date'     => 'on',
        'yz_display_view_comment'     => 'on',
        'yz_display_comment_username' => 'on',

        // Media Tab
        'yz_user-media_tab_icon'        => 'fas fa-photo-video',

        // Widgets Settings
        'yz_display_wg_title_icon' => 'on',
        'yz_use_wg_title_icon_bg'  => 'on',
        'yz_wgs_border_style'      => 'radius',
        'yz_profile_layout'        => 'yz-right-sidebar',
        'yz_profile_main_sidebar'  => 'yz-right-sidebar',
        'yz_profile_vertical_header_position'  => 'left',

        // Display Widget Titles
        'yz_wg_link_display_title'      => 'off',
        'yz_wg_quote_display_title'     => 'off',
        'yz_wg_slideshow_display_title' => 'off',
        'yz_wg_user_tags_display_title' => 'off',
        'yz_wg_media_display_title'     => 'on',
        'yz_wg_video_display_title'     => 'on',
        'yz_wg_rposts_display_title'    => 'on',
        'yz_wg_skills_display_title'    => 'on',
        'yz_wg_flickr_display_title'    => 'on',
        'yz_wg_about_me_display_title'  => 'on',
        'yz_wg_services_display_title'  => 'on',
        'yz_wg_portfolio_display_title' => 'on',
        'yz_wg_friends_display_title'   => 'on',
        'yz_wg_reviews_display_title'   => 'on',
        'yz_wg_groups_display_title'    => 'on',
        'yz_wg_instagram_display_title' => 'on',
        'yz_wg_user_badges_display_title' => 'on',
        'yz_wg_user_balance_display_title' => 'off',
        'yz_wg_social_networks_display_title' => 'on',

        // Widget Titles
        'yz_wg_post_title'      => __( 'Post', 'youzer' ),
        'yz_wg_project_title'   => __( 'Project', 'youzer' ),
        'yz_wg_link_title'      => __( 'Link', 'youzer' ),
        'yz_wg_video_title'     => __( 'Video', 'youzer' ),
        'yz_wg_media_title'     => __( 'Media', 'youzer' ),
        'yz_wg_quote_title'     => __( 'Quote', 'youzer' ),
        'yz_wg_skills_title'    => __( 'Skills', 'youzer' ),
        'yz_wg_flickr_title'    => __( 'Flickr', 'youzer' ),
        'yz_wg_reviews_title'   => __( 'Reviews', 'youzer' ),
        'yz_wg_friends_title'   => __( 'Friends', 'youzer' ),
        'yz_wg_groups_title'    => __( 'Groups', 'youzer' ),
        'yz_wg_aboutme_title'   => __( 'About Me', 'youzer' ),
        'yz_wg_services_title'  => __( 'Services', 'youzer' ),
        'yz_wg_portfolio_title' => __( 'Portfolio', 'youzer' ),
        'yz_wg_instagram_title' => __( 'Instagram', 'youzer' ),
        'yz_wg_user_tags_title' => __( 'User Tags', 'youzer' ),
        'yz_wg_slideshow_title' => __( 'Slideshow', 'youzer' ),
        'yz_wg_rposts_title'    => __( 'Recent Posts', 'youzer' ),
        'yz_wg_sn_title'        => __( 'Keep In Touch', 'youzer' ),
        'yz_wg_user_badges_title'  => __( 'User Badges', 'youzer' ),
        'yz_wg_user_balance_title' => __( 'User Balance', 'youzer' ),

        // Social Networks
        'yz_wg_sn_bg_style'   => 'radius',
        'yz_wg_sn_bg_type'    => 'colorful',
        'yz_wg_sn_icons_size' => 'full-width',

        // Badges.
        'yz_wg_max_user_badges_items' => 12,

        // Skills
        'yz_wg_max_skills' => 5,

        // Media
        'yz_enable_groups_media' => 'on',
        'yz_profile_media_tab_layout' => '4columns',
        'yz_profile_media_subtab_layout' => '3columns',
        'yz_profile_media_tab_per_page' => 8,
        'yz_profile_media_subtab_per_page' => 24,
        'yz_show_profile_media_tab_photos' => 'on',
        'yz_show_profile_media_tab_videos' => 'on',
        'yz_show_profile_media_tab_audios' => 'on',
        'yz_show_profile_media_tab_files' => 'on',
        'yz_group_media_tab_layout' => '4columns',
        'yz_group_media_subtab_layout' => '3columns',
        'yz_group_media_tab_per_page' => 8,
        'yz_group_media_subtab_per_page' => 24,
        'yz_show_group_media_tab_photos' => 'on',
        'yz_show_group_media_tab_videos' => 'on',
        'yz_show_group_media_tab_audios' => 'on',
        'yz_show_group_media_tab_files' => 'on',
        'yz_wg_max_media_photos' => 9,
        'yz_wg_max_media_videos' => 9,
        'yz_wg_max_media_audios' => 6,
        'yz_wg_max_media_files'  => 6,
        'yz_wg_media_filters'    => 'photos,videos,audios,files',

        // About Me
        'yz_wg_aboutme_img_format' => 'circle',

        // Live Notifications
        'yz_enable_live_notifications' => 'on',
        'yz_live_notifications_interval' => 30,

        // Project
        'yz_display_prjct_meta' => 'on',
        'yz_display_prjct_tags' => 'on',
        'yz_display_prjct_meta_icons' => 'on',
        'yz_wg_project_types' => array(
            __( 'Featured Project', 'youzer' ),
            __( 'Recent Project', 'youzer' )
        ),

        // Post
        'yz_display_wg_post_meta'       => 'on',
        'yz_display_wg_post_readmore'   => 'on',
        'yz_display_wg_post_tags'       => 'on',
        'yz_display_wg_post_excerpt'    => 'on',
        'yz_display_wg_post_date'       => 'on',
        'yz_display_wg_post_comments'   => 'on',
        'yz_display_wg_post_meta_icons' => 'on',
        'yz_wg_post_types'              => array(
            __( 'Featured Post', 'youzer' ),
            __( 'Recent Post', 'youzer' )
        ),

        // Login Page Settings.
        'yz_login_page_type' => 'url',
        'yz_enable_ajax_login' => 'off',
        'yz_enable_login_popup' => 'off',

        // Services
        'yz_wg_max_services' => 4,
        'yz_display_service_icon' => 'on',
        'yz_display_service_text' => 'on',
        'yz_display_service_title' => 'on',
        // 'yz_wg_service_icon_bg_format' => 'circle',
        'yz_wg_services_layout' => 'vertical-services-layout',

        // Slideshow
        'yz_wg_max_slideshow_items' => 3,
        'yz_slideshow_height_type' => 'fixed',

        // Portfolio
        'yz_wg_max_portfolio_items' => 9,

        // Flickr
        'yz_wg_max_flickr_items' => 6,

        // Friends
        'yz_wg_max_friends_items' => 5,
        'yz_wg_friends_layout' => 'list',

        // Groups
        'yz_wg_max_groups_items' => 3,

        // Instagram
        'yz_wg_max_instagram_items' => 9,

        // Recent Posts
        'yz_wg_max_rposts' => 3,

        // Use Profile Effects
        'yz_use_effects' => 'off',
        'yz_profile_login_button' => 'on',

        // Profile Main Content Available Widgets
        'yz_profile_main_widgets' => array(
            'slideshow'  => 'visible',
            'project'    => 'visible',
            'skills'     => 'visible',
            'portfolio'  => 'visible',
            'quote'      => 'visible',
            'instagram'  => 'visible',
            'services'   => 'visible',
            'post'       => 'visible',
            'link'       => 'visible',
            'video'      => 'visible',
            'reviews'    => 'visible',
        ),

        // Profile Sidebar Available Widgets
        'yz_profile_sidebar_widgets' => array (
            'login'           => 'visible',
            'user_balance'    => 'visible',
            'user_badges'     => 'visible',
            'about_me'        => 'visible',
            'wall_media'      => 'visible',
            'social_networks' => 'visible',
            'friends'         => 'visible',
            'flickr'          => 'visible',
            'groups'          => 'visible',
            'recent_posts'    => 'visible',
            'user_tags'       => 'visible',
            'email'           => 'visible',
            'address'         => 'visible',
            'website'         => 'visible',
            'phone'           => 'visible',
        ),

        // Profile 404
        'yz_profile_404_button' => __( 'Go Back Home', 'youzer' ),
        'yz_profile_404_desc'   => __( "We're sorry, the profile you're looking for cannot be found.", 'youzer' ),

        // Profil Scheme.
        'yz_profile_scheme' => 'yz-blue-scheme',
        'yz_enable_profile_custom_scheme' => 'off',

        // Panel Options.
        'yz_enable_panel_fixed_save_btn' => 'on',
        'yz_panel_scheme' => 'uk-yellow-scheme',
        'yz_tabs_list_icons_style' => 'yz-tabs-list-gradient',

        // Panel Messages.
        'yz_msgbox_mailchimp' => 'on',
        'yz_msgbox_captcha' => 'on',
        'yz_msgbox_logy_login' => 'on',
        'yz_msgbox_mail_tags' => 'off',
        'yz_msgbox_mail_content' => 'on',
        'yz_msgbox_ads_placement' => 'on',
        'yz_msgbox_profile_schemes' => 'on',
        'yz_msgbox_profile_structure' => 'on',
        'yz_msgbox_instagram_wg_app_setup_steps' => 'on',
        'yz_msgbox_custom_widgets_placement' => 'on',
        'yz_msgbox_user_badges_widget_notice' => 'on',
        'yz_msgbox_user_balance_widget_notice' => 'on',

        // Account Settings
        'yz_enable_account_scroll_button' => 'on',
        'yz_files_max_size' => 3,

        // Wall Settings
        'yz_activity_privacy' => 'on',
        'yz_activity_mood' => 'on',
        'yz_activity_tag_friends' => 'on',
        // 'yz_enable_youzer_activity_filter' => 'on',
        'yz_enable_wall_url_preview' => 'on',
        'yz_enable_wall_activity_loader' => 'on',
        'yz_enable_wall_activity_effects' => 'on',
        'yz_enable_wall_posts_shares' => 'on',
        'yz_enable_wall_posts_reply' => 'on',
        'yz_enable_wall_posts_likes' => 'on',
        'yz_enable_wall_posts_comments' => 'on',
        'yz_enable_wall_posts_deletion' => 'on',
        'yz_wall_comments_gif' => 'on',
        'yz_enable_activity_directory_filter_bar' => 'on',
        'yz_attachments_max_size' => 10,
        'yz_attachments_max_nbr'  => 200,
        'yz_atts_allowed_images_exts' => array( 'png', 'jpg', 'jpeg', 'gif' ),
        'yz_atts_allowed_videos_exts' => array( 'mp4', 'ogg', 'ogv', 'webm' ),
        'yz_atts_allowed_audios_exts' => array( 'mp3', 'ogg', 'wav' ),
        'yz_atts_allowed_files_exts'  => array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'ogg', 'pfi' ),

        // Comments Attachments.
        'yz_wall_comments_attachments' => 'on',
        'yz_wall_comments_attachments_max_size' => 10,
        'yz_wall_comments_attachments_extensions' => array(
            'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi'
        ),

        // Messages Attachments.
        'yz_messages_attachments' => 'on',
        'yz_messages_attachments_max_size' => 10,
        'yz_messages_attachments_extensions' => array(
            'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
            'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi'
        ),

        // Reviews Settings
        'yz_enable_reviews' => 'off',
        'yz_user_reviews_privacy' => 'public',
        'yz_enable_author_box_ratings' => 'on',
        'yz_allow_users_reviews_edition' => 'off',
        'yz_profile_reviews_per_page' => 25,
        'yz_wg_max_reviews_items' => 3,


        // Bookmarking Posts.
        'yz_enable_bookmarks' => 'on',
        'yz_enable_bookmarks_privacy' => 'private',

        // Sticky Posts.
        'yz_enable_groups_sticky_posts' => 'on',
        'yz_enable_activity_sticky_posts' => 'on',

        // Share Posts
        'yz_share_activity_posts' => 'on',

        // Scroll to top.
        'yz_lazy_load' => 'on',
        'yz_images_compression_quality' => 90,
        'yz_compress_images' => 'on',
        'yz_display_scrolltotop' => 'off',
        'yz_display_group_scrolltotop' => 'off',

        // Wall Posts Per Page
        'yz_activity_wall_posts_per_page' => 5,
        'yz_profile_wall_posts_per_page' => 5,
        'yz_groups_wall_posts_per_page' => 5,

        // Wall Settings.
        'yz_enable_wall_file' => 'on',
        'yz_enable_wall_link' => 'on',
        'yz_enable_wall_photo' => 'on',
        'yz_enable_wall_audio' => 'on',
        'yz_enable_wall_video' => 'on',
        'yz_enable_wall_quote' => 'on',
        'yz_enable_wall_status' => 'on',
        'yz_enable_wall_giphy' => 'on',
        'yz_enable_wall_comments' => 'off',
        'yz_enable_wall_new_cover' => 'on',
        'yz_enable_wall_new_member' => 'on',
        'yz_enable_wall_slideshow' => 'on',
        'yz_enable_wall_filter_bar' => 'on',
        'yz_enable_wall_new_avatar' => 'on',
        'yz_enable_wall_joined_group' => 'on',
        'yz_enable_wall_posts_embeds' => 'on',
        'yz_enable_wall_new_blog_post' => 'on',
        'yz_enable_wall_created_group' => 'on',
        'yz_enable_wall_comments_embeds' => 'on',
        'yz_enable_wall_updated_profile' => 'off',
        'yz_enable_wall_new_blog_comment' => 'off',
        'yz_enable_wall_friendship_created' => 'on',
        'yz_enable_wall_friendship_accepted' => 'on',

        // Profile Settings
        'yz_allow_private_profiles' => 'off',

        // Members Directory
        'yz_md_users_per_page' => 18,
        'yz_md_card_meta_icon' => 'at',
        'yz_enable_md_cards_cover' => 'on',
        'yz_enable_md_cards_status' => 'on',
        'yz_show_md_cards_online_only' => 'on',
        'yz_enable_md_users_statistics' => 'on',
        'yz_md_card_meta_field' => 'user_login',
        'yz_enable_md_custom_card_meta' => 'off',
        'yz_enable_md_cards_avatar_border' => 'off',
        'yz_enable_md_user_followers_statistics' => 'on',
        'yz_enable_md_user_following_statistics' => 'on',
        'yz_enable_md_user_points_statistics' => 'on',
        'yz_enable_md_user_views_statistics' => 'on',
        'yz_enable_md_cards_actions_buttons' => 'on',
        'yz_enable_md_user_posts_statistics' => 'on',
        'yz_enable_md_user_friends_statistics' => 'on',
        'yz_enable_md_user_comments_statistics' => 'on',

        // Groups Directory
        'yz_gd_groups_per_page' => 18,
        'yz_enable_gd_cards_cover' => 'on',
        'yz_enable_gd_groups_statistics' => 'on',
        'yz_enable_gd_cards_avatar_border' => 'on',
        'yz_enable_gd_cards_actions_buttons' => 'on',
        'yz_enable_gd_group_posts_statistics' => 'on',
        'yz_enable_gd_group_members_statistics' => 'on',
        'yz_enable_gd_group_activity_statistics' => 'on',

        // Groups Directory - Styling
        'yz_gd_cards_buttons_border_style' => 'oval',
        'yz_gd_cards_avatar_border_style' => 'circle',
        'yz_gd_cards_buttons_layout' => 'block',

        // Members Directory - Styling
        'yz_md_cards_buttons_layout' => 'block',
        'yz_md_cards_buttons_border_style' => 'oval',
        'yz_md_cards_avatar_border_style' => 'circle',

        // Custom Styling.
        'yz_enable_global_custom_styling'   => 'off',
        'yz_enable_profile_custom_styling'  => 'off',
        'yz_enable_account_custom_styling'  => 'off',
        'yz_enable_activity_custom_styling' => 'off',
        'yz_enable_groups_custom_styling'   => 'off',
        'yz_enable_groups_directory_custom_styling'  => 'off',
        'yz_enable_members_directory_custom_styling' => 'off',

        // Emoji Settings.
        'yz_enable_posts_emoji' => 'on',
        'yz_enable_comments_emoji' => 'on',
        'yz_enable_messages_emoji' => 'on',
        'yz_enable_messages_attachments' => 'on',

        // General.
        'yz_buttons_border_style' => 'oval',
        'yz_activate_membership_system' => 'on',

        // Account Verification
        'yz_enable_account_verification' => 'on',

        // Login Form
        'logy_login_form_enable_header'     => 'on',
        'logy_user_after_login_redirect'    => 'home',
        'logy_after_logout_redirect'        => 'login',
        'logy_admin_after_login_redirect'   => 'dashboard',
        'logy_login_form_layout'            => 'logy-field-v1',
        'logy_login_icons_position'         => 'logy-icons-left',
        'logy_login_actions_layout'         => 'logy-actions-v1',
        'logy_login_btn_icons_position'     => 'logy-icons-left',
        'logy_login_btn_format'             => 'logy-border-radius',
        'logy_login_fields_format'          => 'logy-border-flat',
        'logy_login_form_title'             => __( 'Login', 'youzer' ),
        'logy_login_signin_btn_title'       => __( 'Log In', 'youzer' ),
        'logy_login_register_btn_title'     => __( 'Create New Account', 'youzer' ),
        'logy_login_lostpswd_title'         => __( 'Lost password?', 'youzer' ),
        'logy_login_form_subtitle'          => __( 'Sign in to your account', 'youzer' ),

        // Social Login
        'logy_social_btns_icons_position'   => 'logy-icons-left',
        'logy_social_btns_format'           => 'logy-border-radius',
        'logy_social_btns_type'             => 'logy-only-icons',
        'logy_enable_social_login'          => 'on',
        'logy_enable_social_login_email_confirmation' => 'on',

        // Lost Password Form
        'logy_lostpswd_form_enable_header'  => 'on',
        'logy_lostpswd_form_title'          => __( 'Forgot your password?', 'youzer' ),
        'logy_lostpswd_submit_btn_title'    => __( 'Reset Password', 'youzer' ),
        'logy_lostpswd_form_subtitle'       => __( 'Reset your account password', 'youzer' ),

        // Register Form
        'logy_show_terms_privacy_note'      => 'on',
        'logy_signup_form_enable_header'    => 'on',
        'logy_signup_actions_layout'        => 'logy-regactions-v1',
        'logy_signup_btn_icons_position'    => 'logy-icons-left',
        'logy_signup_btn_format'            => 'logy-border-radius',
        'logy_signup_signin_btn_title'      => __( 'Log In', 'youzer' ),
        'logy_signup_form_title'            => __( 'Sign Up', 'youzer' ),
        'logy_signup_register_btn_title'    => __( 'Sign Up', 'youzer' ),
        'logy_signup_form_subtitle'         => __( 'Create New Account', 'youzer' ),

        // Limit Login Settings
        'logy_long_lockout_duration'    => 86400,
        'logy_short_lockout_duration'   => 43200,
        'logy_retries_duration'         => 1200,
        'logy_enable_limit_login'       => 'on',
        'logy_allowed_retries'          => 4,
        'logy_allowed_lockouts'         => 2,

        // User Tags Settings
        'yz_enable_user_tags' => 'on',
        'yz_enable_user_tags_icon' => 'on',
        'yz_enable_user_tags_description' => 'on',
        'yz_wg_user_tags_border_style' => 'radius',

        // Mail Settings
        'yz_enable_woocommerce' => 'off',
        'yz_enable_mailster' => 'off',
        'yz_enable_mailchimp' => 'off',

        // Admin Toolbar & Dashboard
        'logy_hide_subscribers_dash' => 'off',

        // Captcha.
        'logy_enable_recaptcha' => 'on',

        // Panel Messages.
        'logy_msgbox_captcha' => 'on',
        'yz_active_styles' => array(),

    );

    if ( yz_is_mycred_installed() ) {

        // Options.
        $mycred_options = array(
            'yz_enable_mycred' => 'on',
            'yz_badges_tab_icon' => 'fas fa-trophy',
            'yz_enable_cards_mycred_badges' => 'on',
            'yz_wg_max_card_user_badges_items' => 4,
            'yz_mycred-history_tab_icon' => 'fas fa-history',
            'yz_author_box_max_user_badges_items' => 3,
            'yz_enable_author_box_mycred_badges' => 'on',
            'yz_mycred_badges_tab_title' => __( 'Badges', 'youzer' ),
            'yz_ctabs_mycred-history_thismonth_icon' => 'far fa-calendar-alt',
            'yz_ctabs_leaderboard_month_icon' => 'far fa-calendar-alt',
            'yz_ctabs_mycred-history_today_icon' => 'fas fa-calendar-check',
            'yz_ctabs_leaderboard_today_icon' => 'fas fa-calendar-check',
            'yz_ctabs_mycred-history_mycred-history_icon' => 'fas fa-calendar',
            'yz_ctabs_mycred-history_thisweek_icon' => 'fas fa-calendar-times',
            'yz_ctabs_leaderboard_week_icon' => 'fas fa-calendar-plus',
            'yz_ctabs_mycred-history_yesterday_icon' => 'fas fa-calendar-minus',
            'yz_ctabs_achievements_all_icon' => 'fas fa-award',
            'yz_ctabs_achievements_earned_icon' => 'fas fa-user-check',
            'yz_ctabs_achievements_unearned_icon' => 'fas fa-user-times',
        );

        $default_options = yz_array_merge( $default_options, $mycred_options );
    }

    return apply_filters( 'yz_default_options', $default_options );
}

/**
 * # Is Youzer Membership system is active.
 */
function yz_is_membership_system_active() {
    $active = yz_option( 'yz_activate_membership_system', 'on' ) == 'off' ? false : true;
    return apply_filters( 'yz_is_membership_system_active', $active );
}

/**
 * Get Current Page Url
 */
function yz_get_current_page_url() {

    // Build the redirect URL.
    $redirect_url = is_ssl() ? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']: 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    return $redirect_url;
}

/**
 * # Class Generator.
 */
function yz_generate_class( $classes ) {
    // Convert Array to String.
    return implode( ' ' , array_filter( (array) $classes ) );
}

/**
 * # Get Profile Photo.
 */
function yz_get_image_url( $img_url = null ) {
    return ! empty( $img_url ) ? $img_url : YZ_PA . 'images/default-img.png';
}

/**
 * Get Wordpress Pages
 */
function yz_get_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    // Add Default Page.
    $pages[] = __( '-- Select --', 'youzer' );

    // Add Wordpress Pages
    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )','youzer' ), $page->post_title, $page->ID );
    }

    return $pages;
}

/**
 * Popup Dialog Message
 */
function yz_popup_dialog( $type = null ) {

    // Init Alert Types.
    $alert_types = array( 'reset_tab', 'reset_all' );

    // Get Dialog Class.
    $form_class = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? 'alert' : 'error';

    // Get Dialog Name.
    $form_type  = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? $type : 'error';

    ?>

    <div id="uk_popup_<?php echo $form_type; ?>" class="uk-popup uk-<?php echo $form_class; ?>-popup" style="display: none">
        <div class="uk-popup-container">
            <div class="uk-popup-msg"><?php

                if ( 'reset_all' == $form_type ) : ?>

                <span class="dashicons dashicons-warning"></span>
                <h3><?php _e( 'Are you sure you want to reset all the settings?', 'youzer' ); ?></h3>
                <p><?php _e( 'Be careful! this will reset all the youzer plugin settings.', 'youzer' ); ?></p>

                <?php elseif ( 'reset_tab' == $form_type ) : ?>

                <span class="dashicons dashicons-warning"></span>
                <h3><?php _e( 'Are you sure you want to do this?', 'youzer' ); ?></h3>
                <p><?php _e( 'Be careful! This will reset all the current tab settings.', 'youzer' ); ?></p>

                <?php elseif ( 'error' == $form_type ) : ?>

                <i class="fas fa-exclamation-triangle"></i>
                <h3><?php _e( 'Oops!', 'youzer' ); ?></h3>
                <div class="uk-msg-content"></div>

            <?php endif; ?>
            </div>

            <ul class="uk-buttons"><?php

                // Get Cancel Button title.
                $confirm = __( 'Confirm', 'youzer' );
                $cancel  = ( 'error' == $form_type ) ? __( 'Got it!', 'youzer' ) : __( 'Cancel', 'youzer' );

                if ( 'reset_all' == $form_type ) : ?>
                    <li>
                        <a class="uk-confirm-popup yz-confirm-reset" data-reset="all"><?php echo $confirm; ?></a>
                    </li>
                <?php elseif ( 'reset_tab' == $form_type ) : ?>
                    <li>
                        <a class="uk-confirm-popup yz-confirm-reset" data-reset="tab"><?php echo $confirm; ?></a>
                    </li>
                <?php endif; ?>

                <li><a class="uk-close-popup"><?php echo $cancel; ?></a></li>

                <?php

             ?></ul>
            <i class="fas fa-times uk-popup-close"></i>
        </div>
    </div>

    <?php
}

/**
 * # Form Messages.
 */
add_action( 'youzer_admin_after_form', 'yz_form_messages' );
add_action( 'youzer_account_footer', 'yz_form_messages' );

function yz_form_messages() {

    ?>

    <div class="youzer-form-msg">
        <div id="youzer-action-message"></div>
        <div id="youzer-wait-message">
            <div class="youzer_msg wait_msg">
                <div class="youzer-msg-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <span><?php _e( 'Please wait ...', 'youzer' ); ?></span>
            </div>
        </div>
    </div>

    <?php

}


/**
 * # Get User Data
 */
function yz_data( $key, $user_id = null ) {

    do_action( 'yz_before_get_data', $key, $user_id );

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get user informations.
    $user_data = get_the_author_meta( $key, $user_id );

    return apply_filters( 'yz_get_user_data', $user_data, $user_id, $key );

}

/**
 * Check if tab is a Custom Tab.
 */
function yz_is_custom_tab( $tab_name ) {
    if ( false !== strpos( $tab_name, 'yz_custom_tab_' ) ) {
        return true;
    }
    return false;
}

/**
 * # Get Youzer Page Template.
 */
function youzer_template( $old_template ) {

    if ( yz_is_ajax_call() ) {
        return $old_template;
    }

    // New Template.
    $new_template = $old_template;

    // Check if its youzer plugin page
    if ( apply_filters( 'yz_enable_youzer_page', bp_current_component() ) ) {

        // Get Data.
        $file = 'youzer-template.php';
        $path = yz_get_theme_template_path();

        if ( file_exists( $path . '/youzer/' . $file ) ) {
            $new_template = $path . '/youzer/' . $file;
        } else {
            $new_template = YZ_TEMPLATE . 'youzer-template.php';
        }

    }

    return apply_filters( 'youzer_template', $new_template, $old_template );

}

add_filter( 'template_include', 'youzer_template', 99999 );

/**
 * Get Template Path.
 */
function yz_get_theme_template_path() {
    // Get Path.
    $path = is_child_theme() ? get_theme_file_path() : get_template_directory();
    return apply_filters( 'yz_get_theme_template_path', $path );
}


/**
 * Write Log.
 **/
function yz_write_log( $log )  {
    if ( is_array( $log ) || is_object( $log ) ) {
        error_log( print_r( $log, true ) );
    } else {
        error_log( $log );
    }
}

/**
 * Get File URL By Name.
 */
function yz_get_file_url( $file ) {

    if ( empty( $file ) ) {
        return false;
    }

    global $YZ_upload_url;

    // Init Vars.
    $file_name = null;

    $compression_enabled = apply_filters( 'yz_enable_attachments_compression', true );

    // Prepare Url.
    if ( $compression_enabled ) {
        if ( isset( $file['thumbnail'] ) && $file['thumbnail'] != 'false' ) {
            $file_name = $file['thumbnail'];
        } else {
            $file_name = yz_save_image_thumbnail( $file );
        }
    }

    if ( empty( $file_name ) ) {

        // Get Backup File.
        $backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

        // Get File Name.
        $file_name = isset( $file['original'] ) ? $file['original'] : $backup_file;

    }

    // Return File Url.
    return apply_filters( 'yz_get_file_url', $YZ_upload_url . $file_name, $file_name, $file );

}

/**
 * Get File URL By Name.
 */
function yz_get_media_url( $file, $show_original = false ) {

    if ( empty( $file ) ) {
        return false;
    }

    global $YZ_upload_url;

    $file_name = '';

    // Get Compressed Image.
    if ( ! $show_original && apply_filters( 'yz_enable_attachments_compression', true ) ) {
        $file_name = isset( $file['thumbnail'] ) ? $file['thumbnail'] : '';
    }

    if ( empty( $file_name ) ) {
        $file_name = isset( $file['original'] ) ? $file['original'] : '';
    }

    // Return File Url.
    return apply_filters( 'yz_get_media_url', $YZ_upload_url . $file_name, $file_name );

}

/**
 * Save New Thumbnail
 */
function yz_save_image_thumbnail( $file, $activity_id = null ) {

    global $YZ_upload_dir;

    // Get image from file
    $img = false;

    // Get Backup File.
    $backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

    // Get Filename.
    $filename = isset( $file['original'] ) ? $file['original'] : $backup_file;

    // Get File Type.
    $file_type = wp_check_filetype( $filename );

    // Get File Name.
    $file_name = pathinfo( $filename, PATHINFO_FILENAME );

    // Get File Path.
    $file_path = $YZ_upload_dir . $filename;

    switch ( $file_type['type'] ) {

        case 'image/jpeg': {
            $img = imagecreatefromjpeg( $file_path );
            break;
        }

        case 'image/png': {
            $img = imagecreatefrompng( $file_path );
            break;
        }

    }

    if ( empty( $img ) ) {
        return false;
    }

    // Get Compression Quality.
    $quality = apply_filters( 'yz_attachments_compression_quality', 80 );

    // Get New Image Path.
    $thumb_filename = wp_unique_filename( $YZ_upload_dir, $file_name . '-thumb.jpg' );

    if ( imagejpeg( $img, $YZ_upload_dir . $thumb_filename , $quality ) ) {

        imagedestroy( $img );

        return $thumb_filename;

    }

    return false;

}

/**
 * Get Notification Icon.
 */
function yz_get_notification_icon( $args ) {

    switch ( $args->component_action ) {

        case 'new_at_mention':
            $icon = '<i class="fas fa-at"></i>';
            break;

        case 'membership_request_accepted':
            $icon = '<i class="fas fa-thumbs-up"></i>';
            break;

        case 'membership_request_rejected':
            $icon = '<i class="fas fa-thumbs-down"></i>';
            break;

        case 'member_promoted_to_admin':
            $icon = '<i class="fas fa-user-secret"></i>';
            break;

        case 'member_promoted_to_mod':
            $icon = '<i class="fas fa-shield-alt"></i>';
            break;

        case 'bbp_new_reply':
            $icon = '<i class="fas fa-reply"></i>';
            break;

        case 'update_reply':
            $icon = '<i class="far fa-comment"></i>';
            break;

        case 'comment_reply':
            $icon = '<i class="fas fa-reply-all"></i>';
            break;

        case 'new_message':
            $icon = '<i class="far fa-envelope"></i>';
            break;

        case 'friendship_request':
            $icon = '<i class="fas fa-handshake"></i>';
            break;

        case 'friendship_accepted':
            $icon = '<i class="fas fa-hand-peace"></i>';
            break;

        case 'new_membership_request':
            $icon = '<i class="fas fa-sign-in-alt"></i>';
            break;

        case 'group_invite':
            $icon = '<i class="fas fa-user-plus"></i>';
            break;

        case 'new_follow':
            $icon = '<i class="fas fa-share-alt"></i>';
            break;

        case 'yz_new_like':
            $icon = '<i class="far fa-heart"></i>';
            break;

        case 'yz_new_share':
            $icon = '<i class="far fa-share-square"></i>';
            break;

        default:
            $icon = '<i class="fas fa-bell"></i>';
            break;
    }

    return $icon;
}

/**
 * # Get Posts Excerpt.
 */
function yz_get_excerpt( $content, $limit = 12 ) {


    $limit = apply_filters( 'yz_excerpt_limit', $limit );

    // Strip Shortcodes
    $excerpt = do_shortcode( $content );

    // Strip Remaining shortcodes.
    $excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $excerpt);

    // Strip Tag.
    $excerpt = wp_strip_all_tags( $excerpt );

    $excerpt = explode( ' ', $excerpt, $limit );

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( " ", $excerpt ) . '...';
    } else {
        $excerpt = implode( " ", $excerpt );
    }

    $excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

    $excerpt = apply_filters( 'yz_get_excerpt', $excerpt, $content, $limit );

    return $excerpt;
}

/**
 * # Get Post Format Icon.
 */
function yz_get_format_icon( $format = "standard" ) {

    switch ( $format ) {
        case 'video':
            return "fas fa-video";
            break;

        case 'image':
            return "fas fa-image";
            break;

        case 'status':
            return "fas fa-pencil-alt";
            break;

        case 'quote':
            return "fas fa-quote-right";
            break;

        case 'link':
            return "fas fa-link";
            break;

        case 'gallery':
            return "fas fa-images";
            break;

        case 'standard':
            return "fas fa-file-alt";
            break;

        case 'audio':
            return "fas fa-volume-up";
            break;

        default:
            return "fas fa-pencil-alt";
            break;
    }
}

/**
 * Get Product Images
 */
function yz_get_product_image( $args = null ) {

    if ( $args ) {
        echo "<a data-lightbox='yz-product-{$args['id']}' href='{$args['original']}' class='yz-product-thumbnail' style='background-image: url({$args['thumbnail']});'></a>";
    } else {
        echo '<div class="yz-no-thumbnail">';
        echo '<div class="thumbnail-icon"><i class="fas fa-image"></i></div>';
        echo '</div>';
    }

}

/**
 * Check is Mycred is Installed & Active.
 */
function yz_is_mycred_installed() {

    if ( ! defined( 'myCRED_VERSION' ) )  {
        return false;
    }

    return true;

}

/**
 * Check is bbpress is Installed & Active.
 */
function yz_is_bbpress_active() {
    return yz_option( 'yz_enable_bbpress', 'on' ) == 'on' ? true : false;
}

/**
 * Check is Woocommerce is Installed & Active.
 */
function yz_is_woocommerce_installed() {

    if ( ! class_exists( 'Woocommerce' ) )  {
        return false;
    }

    return true;

}

/**
 * Register New Sidebars
 */
function yz_new_sidebars() {

    register_sidebar(
        array (
            'name' => __( 'Wall Sidebar', 'youzer' ),
            'id' => 'yz-wall-sidebar',
            'description' => __( 'Activity sidebar', 'youzer' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    register_sidebar(
        array (
            'name' => __( 'Groups Sidebar', 'youzer' ),
            'id' => 'yz-groups-sidebar',
            'description' => __( 'Groups sidebar', 'youzer' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    if ( yz_is_bbpress_active() ) {

        register_sidebar(
            array (
                'name' => __( 'Forum Sidebar', 'youzer' ),
                'id' => 'yz-forum-sidebar',
                'description' => __( 'Forums pages sidebar', 'youzer' ),
                'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
                'after_widget' => "</div>",
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
        );

    }
}

add_action( 'widgets_init', 'yz_new_sidebars' );

/**
 * # Get Post ID .
 */
function yz_get_post_id( $post_type, $key_meta , $meta_value ) {

    // Get Posts
    $posts = get_posts(
        array(
            'post_type'  => $post_type,
            'meta_key'   => $key_meta,
            'meta_value' => $meta_value )
        );

    if ( isset( $posts[0] ) && ! empty( $posts ) ) {
        return $posts[0]->ID;
    }

    return false;
}

/**
 * Get Multi-Checkboxes.
 */
function yz_get_multicheckbox_options( $option_id, $type = 'on' ) {

    // Init Array.
    $new_values = array();

    // Get Option Values.
    $options = yz_options( $option_id );

    if ( ! empty( $options ) ) {
        // Get Values
        foreach ( $options as $option => $value ) {
            if ( $value == $type ) {
                $new_values[] = $option;
            }
        }
    } else {
        $new_values = $options;
    }

    return apply_filters( 'yz_get_multicheckbox_options', $new_values );
}

/**
 * Get Site Roles
 */
function yz_get_site_roles() {

    $checkbox_roles = array();

    foreach ( get_editable_roles() as $id => $role ) {
        $checkbox_roles[ $id ] = $role['name'];
    }

    return apply_filters( 'yz_get_site_roles', $checkbox_roles );

}

/**
 * Is RT-Media Ajax Call.
 */
function yz_is_ajax_call() {

    $is_ajax = false;

    $rt_ajax_request = yz_get_server_var( 'HTTP_X_REQUESTED_WITH', 'FILTER_SANITIZE_STRING' );

    if ( 'xmlhttprequest' === strtolower( $rt_ajax_request ) ) {
        $is_ajax = true;
    }

    return apply_filters( 'yz_is_ajax_call', $is_ajax );

}

/**
 * Get server variable
 */
function yz_get_server_var( $server_key, $filter_type = 'FILTER_SANITIZE_STRING' ) {

    $server_val = '';

    if ( function_exists( 'filter_input' ) && filter_has_var( INPUT_SERVER, $server_key ) ) {
        $server_val = filter_input( INPUT_SERVER, $server_key, constant( $filter_type ) );
    } elseif ( isset( $_SERVER[ $server_key ] ) ) {
        $server_val = $_SERVER[ $server_key ];
    }

    return $server_val;

}

/**
 * Check Is Buddypress Followers installed !
 */
function yz_is_bpfollowers_active() {
    return apply_filters( 'yz_is_follows_active', defined( 'BP_FOLLOW_DIR' ) ? true : false );
}

/**
 * Upload Image By Url.
 **/
function yz_upload_image_by_url( $link = false ) {

    if ( empty( $link ) ) {
        return false;
    }

    // Decode Image.
    $url_image = file_get_contents( $link );

    if ( empty( $url_image ) ) {
        return false;
    }

    global $YZ_upload_dir, $YZ_upload_url;

    // Get Uploaded File extension
    $ext = strtolower( pathinfo( $link, PATHINFO_EXTENSION ) );

    if ( empty( $ext ) ) {
        $ext = 'jpg';
    }

    // Get Unique File Name.
    $filename = uniqid( 'file_' ) . '.' . $ext;

    // Get File Link.
    $file_link = $YZ_upload_dir . $filename;

    // Get Unique File Name for the file.
    while ( file_exists( $file_link ) ) {
        $filename = uniqid( 'file_' ) . '.' . $ext;
    }

    // Get File Link.
    $file_link = $YZ_upload_dir . $filename;

    // Upload Image.
    $image_uploaded = file_put_contents( $file_link, $url_image );

    if ( $image_uploaded ) {
        return $YZ_upload_url . $filename;
    }

    return false;

}

// Buddypress ID.
function yz_buddypress_id() {
    return 'buddypress';
}

/*
 * Get File Format Size.
 **/
function yz_file_format_size( $size ) {

    // Get Sizes.
    $sizes = array(
        __( 'Bytes', 'youzer' ),
        __( 'KB', 'youzer' ),
        __( 'MB', 'youzer' )
    );

    if ( 0 == $size ) {
        return( 'n/a' );
    } else {
        return ( round( $size/pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $sizes[ $i ] );
    }

}

/**
 * Array Merge.
 */
function yz_array_merge( $array, $array2 ) {
    foreach( $array2 as $k => $i ) {
        $array[ $k ] = $i;
    }
    return $array;
}


/**
 * # Get Files Name Excerpt.
 */
function yz_get_filename_excerpt( $name, $lenght = 25 ) {

    // Get Name Lenght.
    $text_lenght = strlen( $name );

    // If Name is not too long keep it.
    if ( $text_lenght < $lenght ) {
        return $name;
    }

    // Return The New Name.
    return substr_replace( $name, '...', $lenght / 2, $text_lenght - $lenght );
}

/**
 * Disable Gravatars
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );


/**
 * Get Image Size.
 */
function yz_get_image_size( $url, $referer = null ) {

    if ( ! function_exists( 'curl_version' ) ) {
        return array( 0, 0 );
    }

    // Set headers
    $headers = array( 'Range: bytes=0-131072' );

    if ( ! empty( $referer ) ) { array_push( $headers, 'Referer: ' . $referer ); }

    // Get remote image
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6' );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt($ch, CURLOPT_TIMEOUT,  5);
    $data = curl_exec( $ch );
    $http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    $curl_errno = curl_errno( $ch );
    curl_close( $ch );

    // Get network stauts
    if ( $http_status != 200 ) {
        // echo 'HTTP Status[' . $http_status . '] Error [' . $curl_errno . ']';
        return array( 0, 0 );
    }

    // Process image
    $image = imagecreatefromstring( $data );
    $dims = array( imagesx( $image ), imagesy( $image ) );
    imagedestroy( $image );

    return $dims;
}

/**
 * Check if Review Option is Enabled.
 */
function yz_is_reviews_active() {
    $activate = yz_option( 'yz_enable_reviews', 'off' ) == 'on' ? true : false;
    return apply_filters( 'yz_is_reviews_active', $activate );
}

/**
 * Init Reviews
 */
function yz_init_reviews() {

    if ( yz_is_reviews_active() ) {
        global $Youzer;
        require YZ_PUBLIC_CORE . 'class-yz-reviews.php';
        require YZ_PUBLIC_CORE . 'functions/yz-reviews-functions.php';
        require YZ_PUBLIC_CORE . 'reviews/yz-reviews-query.php';
        $Youzer->reviews = new Youzer_Reviews();
    }

}

add_action( 'plugins_loaded', 'yz_init_reviews', 999 );


/**
 * Check is Mycred is Installed & Active.
 */
function yz_is_mycred_active() {

    if ( ! yz_is_mycred_installed() ) {
        return false;
    }

    return apply_filters( 'yz_is_mycred_active', 'on' == yz_option( 'yz_enable_mycred', 'on' ) ? true : false );

}

/**
 * Get Image Size.
 */
function yz_getimagesize( $file ) {
    return getimagesize( $file );
}

/**
 * Get Tag Attributes.
 */
function yz_get_tag_attributes( $args = null ) {
    if ( empty( $args ) ) {
        return;
    }

    $atts = '';

    foreach ( $args as $key => $value ) {
        if ( $key == 'icon' ) {
            continue;
        }
        $atts .= "data-$key='$value'";
    }

    return apply_filters( '', $atts, $args );
}

/**
 * Get Group Cover.
 */
function yz_get_group_cover( $group_id = null ) {

    $group_id = ! empty( $group_id ) ? $group_id : bp_get_group_id();

    // Get Cover Photo Path.
    $cover_path = bp_attachments_get_attachment( 'url', array( 'item_id' => $group_id, 'object_dir' => 'groups') );

    // Get Default Cover.
    if ( empty( $cover_path ) ) {
        $cover_path = yz_option( 'yz_default_groups_cover' );
    }

    // If Cover not exist use .
    if ( empty( $cover_path ) ) {
        return "<div style='background-image:url(" . YZ_PA . "images/geopattern.png);' class='yz-cover-pattern' loading='lazy'></div>";
    }

    return apply_filters( 'yz_group_profile_cover', '<img loading="lazy" ' . yz_get_image_attributes_by_link( $cover_path ) . ' alt="">', $group_id );

}

/**
 * Get user display name.
 */
function yz_get_user_display_name( $user_id ) {
    // Get Username.
    $username = bp_core_get_user_displayname( $user_id );
    return apply_filters( 'yz_user_profile_username', $username );
}

/**
 * Ajax - Exclude Youzer Media from Wordpress Media Library.
 */
add_filter( 'ajax_query_attachments_args', 'yz_ajax_exclude_youzer_media_from_media_library', 10, 1 );

function yz_ajax_exclude_youzer_media_from_media_library( $query = array() ) {
    $term = get_term_by( 'slug', 'youzify_media', 'category' );
    $query['category__not_in'] = array( $term->term_id );
   return $query;
}