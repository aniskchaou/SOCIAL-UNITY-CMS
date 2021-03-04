<?php

class Youzer_Styling {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Return the instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function __construct() {

        // Add Filters
        add_action( 'wp_enqueue_scripts', array( $this, 'custom_scheme' ) );

        // Call Global Styling.
        // add_action( 'wp_enqueue_scripts', array( $this, 'global_styles' ) );
        
    }
    
    /**
     * Get All Styles
     */
    function get_all_styles( $query = null ) {

        // Get Styles
        $styles = array_merge(
            $this->posts_tab_styling(),
            $this->comments_tab_styling(),
            $this->group_styling(),
            $this->profile_styling(),
            $this->profile404_styling(),
            $this->global_styling()
        );

        if ( $query == 'ids' ) {

            // Get Styles Ids.
            $styles = wp_list_pluck( $styles, 'id' );
            
            foreach ( $this->get_gradient_elements( array(), true ) as $style ) {
                $styles[] = $style['left_color'];
                $styles[] = $style['right_color'];
            }

        }
            
        return $styles;
    }

    /**
     * Posts Tab Styling
     */
    function posts_tab_styling() {

        $data = array(
            array(
                'id'        =>  'yz_post_title_color',
                'selector'  =>  '.yz-tab-post .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_meta_color',
                'selector'  =>  '.yz-tab-post .yz-post-meta ul li, .yz-tab-post .yz-post-meta ul li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_meta_icons_color',
                'selector'  =>  '.yz-tab-post .yz-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_text_color',
                'selector'  =>  '.yz-tab-post .yz-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_button_color',
                'selector'  =>  '.yz-tab-post .yz-read-more',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_post_button_text_color',
                'selector'  =>  '.yz-tab-post .yz-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_post_button_icon_color',
                'selector'  =>  '.yz-tab-post .yz-rm-icon i',
                'property'  =>  'color'
            )
        );

        return $data;
    }

    /**
     * Comments Tab Styling
     */
    function comments_tab_styling() {

        $data = array(
            array(
                'id'        =>  'yz_comment_author_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-fullname',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_username_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-author',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_date_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-date',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_text_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-excerpt p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_button_bg_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_comment_button_text_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_button_icon_color',
                'selector'  =>  '.yz-tab-comment .view-comment-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_comment_author_border_color',
                'selector'  =>  '.yz-tab-comment .yz-comment-img',
                'property'  =>  'border-color'
            )
        );
        
        return $data;

    }

    /**
     * Global Styling
     */
    function global_styling() {

        $data = array(
            array(
                'id'        =>  'yz_plugin_content_width',
                'selector'  =>  '.yz-hdr-v1 .yz-cover-content .yz-inner-content,
                                #yz-profile-navmenu .yz-inner-content,
                                .yz-vertical-layout .yz-content,
                                .youzer .yz-boxed-navbar,
                                .youzer .wild-content,
                                #yz-members-directory,
                                #yz-groups-list,
                                .yz-page-main-content,
                                .yz-header-content,
                                .yz-cover-content',
                'property'  =>  'max-width',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_background',
                'selector'  =>  '.yz-page',
                'property'  =>  'background-color'
            ),
            // Spacing Styles
            array(
                'id'        =>  'yz_plugin_margin_top',
                'selector'  =>  '.yz-page',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_margin_bottom',
                'selector'  =>  '.yz-page',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_top',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_bottom',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_left',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-left',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_plugin_padding_right',
                'selector'  =>  '.yz-page',
                'property'  =>  'padding-right',
                'unit'      => 'px'
            ),
            // Auhtor Box Styling .
            array(
                'id'        =>  'yz_author_pattern_opacity',
                'selector'  =>  '.yzb-author.yz-header-pattern .yz-header-cover:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_author_overlay_opacity',
                'selector'  =>  '.yzb-author.yz-header-overlay .yz-header-cover:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_author_box_margin_top',
                'selector'  =>  '.yz-author-box-widget',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'yz_author_box_margin_bottom',
                'selector'  =>  '.yz-author-box-widget',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            )
        );
        
        return $data;
    }

    /**
     * Profile 404 Styling
     */
    function profile404_styling() {

        $data = array(
            array(
                'id'        =>  'yz_profile_404_title_color',
                'selector'  =>  '.yz-box-404 h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_desc_color',
                'selector'  =>  '.yz-box-404 p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_button_txt_color',
                'selector'  =>  '.yz-box-404 .yz-box-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_404_button_bg_color',
                'selector'  =>  '.yz-box-404 .yz-box-button',
                'property'  =>  'background-color'
            )
        );
        
        return $data;
    }

    /**
     * Group Styling
     */
    function group_styling() {

        $data = array(
            array(
                'id'        =>  'yz_group_header_bg_color',
                'selector'  =>  '.yz-group .yz-header-overlay .yz-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_group_header_username_color',
                'selector'  =>  '.yz-group .yz-name h2,.yz-profile .yzb-head-content h2',
                'property'  =>  'color'
            ),array(
                'id'        =>  'yz_group_header_text_color',
                'selector'  =>  '.yz-group .yz-usermeta li span, .yz-profile .yzb-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_icons_color',
                'selector'  =>  '.yz-group .yz-usermeta li i, .yz-profile .yzb-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_statistics_nbr_color',
                'selector'  =>  '.yz-group .yz-user-statistics ul li .yz-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_statistics_title_color',
                'selector'  =>  '.yz-group .yz-user-statistics .yz-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_group_header_overlay_opacity',
                'selector'  =>  '.yz-group .yz-profile-header.yz-header-overlay .yz-header-cover:before,' .
                                '.yz-group .yz-hdr-v3 .yz-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_group_header_pattern_opacity',
                'selector'  =>  '.yz-group .yz-profile-header.yz-header-pattern .yz-header-cover:after,
                                 .yz-group .yz-hdr-v3 .yz-inner-content:after',
                'property'  =>  'opacity'
            )
        );

        return $data;
    }

    /**
     * Styling Data.
     */
    function profile_styling() {

        $data = array(
            // Profile Header Styling,
            array(
                'id'        =>  'yz_profile_header_bg_color',
                'selector'  =>  '.yz-profile .yz-header-overlay .yz-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_profile_header_username_color',
                'selector'  =>  '.yz-profile .yz-name h2,.yz-profile .yzb-head-content h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_text_color',
                'selector'  =>  '.yz-profile .yz-usermeta li span, .yz-profile .yzb-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_icons_color',
                'selector'  =>  '.yz-profile .yz-usermeta li i, .yz-profile .yzb-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_statistics_nbr_color',
                'selector'  =>  '.yz-profile .yz-user-statistics ul li .yz-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_statistics_title_color',
                'selector'  =>  '.yz-profile .yz-user-statistics .yz-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_profile_header_overlay_opacity',
                'selector'  =>  '.yz-profile .yz-profile-header.yz-header-overlay .yz-header-cover:before,' .
                                '.yz-profile .yz-hdr-v3 .yz-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_profile_header_pattern_opacity',
                'selector'  =>  '.yz-profile .yz-profile-header.yz-header-pattern .yz-header-cover:after,
                                 .yz-profile .yz-hdr-v3 .yz-inner-content:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'yz_navbar_bg_color',
                'selector'  =>  '#yz-profile-navmenu',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_navbar_icons_color',
                'selector'  =>  '.youzer .yz-profile-navmenu li i,' .
                                '.yz-profile .yz-nav-settings .yz-settings-icon',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_icons_color',
                'selector'  =>  '.yz-profile .yz-responsive-menu span::before,' .
                                '.yz-profile .yz-responsive-menu span::after,' .
                                '.yz-profile .yz-responsive-menu span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_navbar_links_color',
                'selector'  =>  '.youzer .yz-profile-navmenu a,.yz-profile .yz-settings-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_links_hover_color',
                'selector'  =>  '.youzer .yz-profile-navmenu a:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_navbar_menu_border_color',
                'selector'  =>  '.youzer .yz-nav-effect .yz-menu-border',
                'property'  =>  'background-color'
            ),
            // Pagination Tab Styling .
            array(
                'id'        =>  'yz_paginationbg_color',
                'selector'  =>  '.yz-pagination .page-numbers,.yz-pagination .yz-pagination-pages',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_pagination_text_color',
               'selector'  =>  '.yz-pagination .page-numbers,.yz-pagination .yz-pagination-pages',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_paginationcurrent_bg_color',
                'selector'  =>  '.yz-pagination .page-numbers.current',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_pagination_current_text_color',
                'selector'  =>  '.yz-pagination .current .yz-page-nbr',
                'property'  =>  'color'
            ),
            // Widgets Styling .
            array(
                'id'        =>  'yz_wgs_title_bg',
                'selector'  =>  '.yz-widget .yz-widget-head',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wgs_title_border_color',
                'selector'  =>  '.yz-widget .yz-widget-head',
                'property'  =>  'border-color'
            ),
            array(
                'id'        =>  'yz_wgs_title_color',
                'selector'  =>  '.yz-widget .yz-widget-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wgs_title_icon_color',
                'selector'  =>  '.yz-widget-title i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wgs_title_icon_bg',
                'selector'  =>  '.yz-wg-title-icon-bg .yz-widget-title i',
                'property'  =>  'background-color'
            ),
            // Widget - About Me - Styling .
            array(
                'id'        =>  'yz_wg_aboutme_title_color',
                'selector'  =>  '.yz-aboutme-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_desc_color',
                'selector'  =>  '.yz-aboutme-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_txt_color',
                'selector'  =>  '.yz-aboutme-bio',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_aboutme_head_border_color',
                'selector'  =>  '.yz-aboutme-head:after',
                'property'  =>  'background-color'
            ),
            // Widget - Project - Styling .
            array(
                'id'        =>  'yz_wg_project_color',
                'selector'  =>  '.yz-project-container',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_type_bg_color',
                'selector'  =>  '.yz-project-content .yz-project-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_type_txt_color',
                'selector'  =>  '.yz-project-content .yz-project-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_title_color',
                'selector'  =>  '.yz-project-content .yz-project-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_meta_txt_color',
                'selector'  =>  '.yz-project-content .yz-project-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_meta_icon_color',
                'selector'  =>  '.yz-project-content .yz-project-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_desc_color',
                'selector'  =>  '.yz-project-content .yz-project-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_color',
                'selector'  =>  '.yz-project-content .yz-project-tags li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_bg_color',
                'selector'  =>  '.yz-project-content .yz-project-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_project_tags_hashtag_color',
                'selector'  =>  '.yz-project-content .yz-project-tags .yz-tag-symbole',
                'property'  =>  'color'
            ),
            // Widget - User Tags - Styling .
            array(
                'id'        =>  'yz_wg_user_tags_title_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_icon_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-name i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_desc_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_background',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_user_tags_color',
                'selector'  =>  '.yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item,
                .yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item a',
                'property'  =>  'color'
            ),
            // Widget - Post - Styling .
            array(
                'id'        =>  'yz_wg_post_type_bg_color',
                'selector'  =>  '.yz-post-content .yz-post-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_post_type_txt_color',
                'selector'  =>  '.yz-post-content .yz-post-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_title_color',
                'selector'  =>  '.yz-post-content .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_meta_txt_color',
                'selector'  =>  '.yz-post-content .yz-post-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_meta_icon_color',
                'selector'  =>  '.yz-post-content .yz-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_text_color',
                'selector'  =>  '.yz-post-content .yz-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_color',
                'selector'  =>  '.yz-post-content .yz-post-tags li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_bg_color',
                'selector'  =>  '.yz-post-content .yz-post-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_post_tags_hashtag_color',
                'selector'  =>  '.yz-post-content .yz-post-tags .yz-tag-symbole',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_color',
                'selector'  =>  '.yz-post .yz-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_icon_color',
                'selector'  =>  '.yz-post .yz-read-more i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_post_rm_bg_color',
                'selector'  =>  '.yz-post .yz-read-more',
                'property'  =>  'background-color'
            ),
            // Widget - Services - Styling .
            array(
                'id'        =>  'yz_wg_service_icon_bg_color',
                'selector'  =>  '.yz-service-item .yz-service-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_service_icon_color',
                'selector'  =>  '.yz-service-item .yz-service-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_service_title_color',
                'selector'  =>  '.yz-service-item .yz-item-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_service_text_color',
                'selector'  =>  '.yz-service-item .yz-item-content p',
                'property'  =>  'color'
            ),
            // Widget - Portfolio - Styling .
            array(
                'id'        =>  'yz_wg_portfolio_title_border_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption h3:after',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_txt_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_txt_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_portfolio_button_border_hov_color',
                'selector'  =>  '.yz-portfolio .yz-portfolio-content figcaption a:hover',
                'property'  =>  'border-color'
            ),
            // Widget - Instagram - Styling .
            array(
                'id'        =>  'yz_wg_instagram_img_icon_bg_color',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_color',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_bg_color_hover',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_instagram_img_icon_color_hover',
                'selector'  =>  '.yz-instagram .yz-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            // Widget - Flickr - Styling .
            array(
                'id'        =>  'yz_wg_flickr_img_bg_color',
                'selector'  =>  '.yz-flickr-photos figcaption',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_flickr_img_icon_bg_color',
                'selector'  =>  '.yz-flickr-photos figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_flickr_img_icon_color',
                'selector'  =>  '.yz-flickr-photos figcaption a i',
                'property'  =>  'color'
            ),
            // Widget - Recent Posts - Styling .
            array(
                'id'        =>  'yz_wg_rposts_title_color',
                'selector'  =>  '.yz-recent-posts .yz-post-head .yz-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_rposts_date_color',
                'selector'  =>  '.yz-recent-posts .yz-post-meta ul li',
                'property'  =>  'color'
            ),
            // Widget - infos - Styling .
            array(
                'id'        =>  'yz_infos_wg_title_color',
                'selector'  =>  '.youzer .yz-infos-content .yz-info-label',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_infos_wg_value_color',
                'selector'  =>  '.youzer .yz-infos-content .yz-info-data',
                'property'  =>  'color'
            ),
            // Widget - Quote - Styling .
            array(
                'id'        =>  'yz_wg_quote_content_bg',
                'selector'  =>  '.youzer .yz-quote-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_quote_icon_bg',
                'selector'  =>  '.youzer .yz-quote-icon',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_quote_txt',
                'selector'  =>  '.youzer .yz-quote-main-content blockquote',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_quote_icon',
                'selector'  =>  '.youzer .yz-quote-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_quote_owner',
                'selector'  =>  '.youzer .yz-quote-owner',
                'property'  =>  'color'
            // Widget - Link - Styling .
            ),
            array(
                'id'        =>  'yz_wg_link_content_bg',
                'selector'  =>  '.youzer .yz-link-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_link_icon_bg',
                'selector'  =>  '.youzer .yz-link-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_link_txt',
                'selector'  =>  '.youzer .yz-link-main-content p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_link_icon',
                'selector'  =>  '.youzer .yz-link-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_link_url',
                'selector'  =>  '.youzer .yz-link-url',
                'property'  =>  'color'
            ),
            // Widget - Video - Styling .
            array(
                'id'        =>  'yz_wg_video_title_color',
                'selector'  =>  '.youzer .yz-video-head .yz-video-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_wg_video_desc_color',
                'selector'  =>  '.youzer .yz-video-head .yz-video-desc',
                'property'  =>  'color'
            ),
            // Scroll to top Styling .
            array(
                'id'        =>  'yz_scroll_button_color',
                'selector'  =>  '.yz-scrolltotop i:hover',
                'property'  =>  'background-color'
            ),
            // Widget Slideshow .
            array(
                'id'        =>  'yz_wg_slideshow_pagination_color',
                'selector'  =>  '.youzer .owl-theme .owl-controls .owl-page span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_wg_slideshow_np_color',
                'selector'  =>  '.youzer .owl-buttons div::before, .owl-buttons div::after',
                'property'  =>  'background-color'
            ),
            // Author Box Widget
            array(
                'id'        =>  'yz_abox_button_icon_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_abox_button_txt_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button .yzb-button-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'yz_abox_button_bg_color',
                'selector'  =>  '.yz-author-box-widget .yzb-button',
                'property'  =>  'background-color'
            ),
            // Verified accounts
            array(
                'id'        =>  'yz_verified_badge_background_color',
                'selector'  =>  '.yz-account-verified',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'yz_verified_badge_icon_color',
                'selector'  =>  '.yz-account-verified',
                'property'  =>  'color'
            )
        );

        return $data;
    }

    /**
     * Custom Styling.
     */
    function custom_styling( $component = 'global' ) {

        // Get Active Styles.
        $active_styles = yz_option( 'yz_active_styles' );
        
        if ( empty( $active_styles ) ) {
            return;
        }

        if ( ! empty( $component ) ) {

            switch ( $component ) {

                case 'global':
                    $page_styles = $this->global_styling();
                    break;
                
                case 'posts':
                    $page_styles = $this->posts_tab_styling();
                    break;
                
                case 'comments':
                    $page_styles = $this->comments_tab_styling();
                    break;

                case 'profile':
                    $page_styles = $this->profile_styling();
                    break;

                case 'groups':
                    $page_styles = $this->group_styling();
                    break;
                    
                case '404_profile':
                    $page_styles = $this->profile404_styling();
                    break;
                
                default:
                    break;
            }

        }

        foreach ( $page_styles as $key => $data ) {
            if ( ! in_array( $data['id'], $active_styles ) ) {
                unset( $page_styles[ $key ] );
            }
        }

        if ( empty( $page_styles ) ) {
            return;
        }
        
        // Custom Styling File.
        wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );        

        // Print Styles
        foreach ( $page_styles as $key ) {

            // Get Data.
            $selector = $key['selector'];
            $property = $key['property'];

            $option = yz_option( $key['id'] );
            $option = isset( $option['color'] ) ? $option['color'] : $option;
            if ( empty( $key['type'] ) && ! empty( $option ) ) {
                $unit = isset( $key['unit'] ) ? $key['unit'] : null;
                $custom_css = "
                    $selector {
                	$property: $option$unit !important;
                    }";
                wp_add_inline_style( 'youzer-customStyle', $custom_css );
            }
        }
    }

    /**
     * Custom Scheme.
     */
    function custom_scheme() {

        // Check if is using a custom scheme is enabled.
        if ( 'on' != yz_option( 'yz_enable_profile_custom_scheme', 'off' ) ) {
            return;
        }

        // Get Custom Scheme Color
        $scheme_color = yz_option( 'yz_profile_custom_scheme_color' );
        $scheme_color = $scheme_color['color'];

        if ( empty( $scheme_color ) )  {
            return;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );

        $pattern = 'url(' . YZ_PA . 'images/dotted-bg.png)';

        $custom_css = "
.youzer div.item-list-tabs li.yz-activity-show-search .yz-activity-show-search-form i,
#yz-wall-nav .item-list-tabs li#activity-filter-select label,
.yz-media-filter .yz-filter-item .yz-current-filter,
.yz-community-hashtags .yz-hashtag-item:hover,
.youzer table tfoot tr,
.youzer table thead tr,
#yz-group-body h1:before,
.yz-product-actions .yz-addtocart,
.youzer .checkout_coupon,
.youzer .yzwc-box-title h3,
.youzer .woocommerce-customer-details h2,
.youzer .yzwc-main-content .track_order .form-row button,
.yz-view-order .yzwc-main-content > p mark.order-status,
.youzer .yzwc-main-content button[type='submit'],
.youzer .yzwc-main-content #payment #place_order,
.youzer .yzwc-main-content h3,
.youzer .wc-proceed-to-checkout a.checkout-button,
.youzer .wc-proceed-to-checkout a.checkout-button:hover,
.youzer .yzwc-main-content .woocommerce-checkout-review-order table.shop_table tfoot .order-total,
.youzer .yzwc-main-content .woocommerce-checkout-review-order table.shop_table thead,
.youzer .yzwc-main-content table.shop_table td a.woocommerce-MyAccount-downloads-file:before,
.youzer .yzwc-main-content table.shop_table td a.view:before,
.youzer table.shop_table.order_details tfoot tr:last-child,
.youzer .yzwc-main-content table.shop_table td.actions .coupon button,
.youzer .yzwc-main-content table.shop_table td.woocommerce-orders-table__cell-order-number a,
.youzer .yzwc-main-content table.shop_table thead,
.yz-forums-topic-item .yz-forums-topic-icon i,
.yz-forums-forum-item .yz-forums-forum-icon i,
div.bbp-submit-wrapper button,
#bbpress-forums li.bbp-header,
#bbpress-forums .bbp-search-form #bbp_search_submit,
#bbpress-forums #bbp-search-form #bbp_search_submit,
.widget_display_search #bbp_search_submit,
.widget_display_forums li a:before,
.widget_display_views li .bbp-view-title:before,
.widget_display_topics li:before,
#bbpress-forums li.bbp-footer,
.bbp-pagination .page-numbers.current,
.yz-items-list-widget .yz-list-item .yz-item-action .yz-add-button i,
#yz-members-list .yzm-user-actions .friendship-button .requested,
.yz-wall-embed .yz-embed-action .friendship-button a.requested,
.yz-widget .yz-user-tags .yz-utag-values .yz-utag-value-item,
.item-list-tabs #search-message-form #messages_search_submit,
#yz-groups-list .action .group-button .membership-requested,
#yz-members-list .yzm-user-actions .friendship-button a,
#yz-groups-list .action .group-button .request-membership,
.yz-wall-embed .yz-embed-action .friendship-button a,
.yz-group-manage-members-search #members_search_submit,
#yz-groups-list .action .group-button .accept-invite,
.notifications-options-nav #notification-bulk-manage,
.notifications .notification-actions .mark-read span,
.sitewide-notices .thread-options .activate-notice,
#yz-groups-list .action .group-button .join-group,
.yz-social-buttons .friendship-button a.requested,
#yz-directory-search-box form input[type=submit],
.yzm-user-actions .friendship-button a.requested,
.yz-wall-embed .yz-embed-action .group-button a,
#yz-group-buttons .group-button a.join-group,
.messages-notices .thread-options .read span,
.yz-social-buttons .friendship-button a,
#search-members-form #members_search_submit,
.messages-options-nav #messages-bulk-manage,
.yz-group-settings-tab input[type='submit'],
.yzm-user-actions .friendship-button a.add,
#group-settings-form input[type='submit'],
.yz-product-content .yz-featured-product,
.my-friends #friend-list .action a.accept,
.yz-wall-new-post .yz-post-more-button,
.group-request-list .action .accept a,
#message-recipients .highlight-icon i,
.yz-pagination .page-numbers.current,
.yz-project-content .yz-project-type,
.yzb-author .yzb-account-settings,
.yz-product-actions .yz-addtocart,
.group-button.request-membership,
#send_message_form .submit #send,
#send-invite-form .submit input,
#send-reply #send_reply_button,
.yz-wall-actions .yz-wall-post,
.yz-post-content .yz-post-type,
.yz-nav-effect .yz-menu-border,
#group-create-tabs li.current,
.group-button.accept-invite,
.yz-tab-post .yz-read-more,
.group-button.join-group,
.yz-service-icon i:hover,
.yz-loading .youzer_msg,
.yz-scrolltotop i:hover,
.yz-post .yz-read-more,
.yzb-author .yzb-login,
.pagination .current,
.yz-tab-title-box,
.yzw-file-post,
.button.accept {
            background-color: $scheme_color !important;
        }

@media screen and ( max-width: 768px ) {
#youzer .yz-group div.item-list-tabs li.last label,
#youzer .yz-profile div.item-list-tabs li.last label,
#youzer .yz-directory-filter .item-list-tabs li#groups-order-select label,
#youzer .yz-directory-filter .item-list-tabs li#members-order-select label {
    background-color: $scheme_color !important;
    color: #fff;
}
}
        .yz-bbp-topic-head-meta .yz-bbp-head-meta-last-updated a:not(.bbp-author-name),
        .widget_display_topics li .topic-author a.bbp-author-name,
        .activity-header .activity-head p a:not(:first-child),
        #message-recipients .highlight .highlight-meta a,
        .thread-sender .thread-from .from .thread-count,
        .yz-profile-navmenu .yz-navbar-item a:hover i,
        .widget_display_replies li a.bbp-author-name,
        .yz-profile-navmenu .yz-navbar-item a:hover,
        .yz-link-main-content .yz-link-url:hover,
        .yz-wall-new-post .yz-post-title a:hover,
        .yz-recent-posts .yz-post-title a:hover,
        .yz-post-content .yz-post-title a:hover,
        .yz-group-settings-tab fieldset legend,
        .yz-wall-link-data .yz-wall-link-url,
        .yz-tab-post .yz-post-title a:hover,
        .yz-project-tags .yz-tag-symbole,
        .yz-post-tags .yz-tag-symbole,
        .yz-group-navmenu li a:hover {
            color: $scheme_color !important;
        }
        
        .yz-bbp-topic-head,
        .youzer .yzwc-main-content address .yz-bullet,
        .yz-profile-navmenu .yz-navbar-item.yz-active-menu,
        .yz-group-navmenu li.current {
            border-color: $scheme_color !important;
        }

        body .quote-with-img:before,body .yz-link-content,body .yz-no-thumbnail,body a.yz-settings-widget {
            background: $scheme_color $pattern !important;
        }
    ";

    wp_add_inline_style( 'youzer-customStyle', $custom_css );
    }

    /**
     * Gradient Elements.
     */
    function get_gradient_elements( $elements = null, $get_array = false ) {

        $elements = array();

        $elements[] = array(
            'selector'      => 'body .quote-with-img:before',
            'left_color'    => 'yz_wg_quote_gradient_left_color',
            'right_color'   => 'yz_wg_quote_gradient_right_color'
        );

        $elements[] = array(
            'selector'      => '.yz-box-email',
            'left_color'    => 'yz_ibox_email_bg_left',
            'right_color'   => 'yz_ibox_email_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-phone',
            'left_color'    => 'yz_ibox_phone_bg_left',
            'right_color'   => 'yz_ibox_phone_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-website',
            'left_color'    => 'yz_ibox_website_bg_left',
            'right_color'   => 'yz_ibox_website_bg_right'
        );

        $elements[] = array(
            'selector'      => '.yz-box-address',
            'left_color'    => 'yz_ibox_address_bg_left',
            'right_color'   => 'yz_ibox_address_bg_right'
        );

        $elements[] = array(
            'target'        => 'youzer',
            'pattern'       => 'geometric',
            'selector'      => '.yz-user-balance-box',
            'left_color'    => 'yz_user_balance_gradient_left_color',
            'right_color'   => 'yz_user_balance_gradient_right_color'
        );

        return $elements;
    }

    /**
     * Gradient Styling.
     */
    function gradient_styling( $element ) {

        // Get Options Data
        $left_color  = yz_option( $element['left_color'] );
        $right_color = yz_option( $element['right_color'] );

        // Get Colors
        $left_color  = isset( $left_color['color'] ) ? $left_color['color'] : null;
        $right_color =  isset( $right_color['color'] ) ? $right_color['color'] : null;

        // if the one of the values are empty go out.
        if ( ! empty( $left_color ) || ! empty( $right_color ) ) {

            // Get Pattern Data.
            $pattern_type = isset( $element['pattern'] ) ? 'geopattern' : 'dotted-bg'; 
            $pattern = 'url(' . YZ_PA . 'images/' . $pattern_type . '.png)';

            echo '<style type="text/css">';
            echo "{$element['selector']} {
                    background: $pattern,linear-gradient(to right, $left_color , $right_color ) !important;
                    background: $pattern,-webkit-linear-gradient(left, $left_color , $right_color ) !important;
                }";
            echo '</style>';
        }
    }

    /**
     * Custom Snippets.
     */
    function custom_snippets( $component ) {

        if ( 'off' == yz_option( 'yz_enable_' . $component . '_custom_styling', 'off' ) ) {
            return false;
        }

        // Get CSS Code.
        $custom_css = yz_option( 'yz_' . $component . '_custom_styling' );

        if ( empty( $custom_css ) ) {
            return false;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );

        wp_add_inline_style( 'youzer-customStyle', $custom_css );

    }
}


/**
 * Get a unique instance of Youzer Styling.
 */
function yz_styling() {
    return Youzer_Styling::get_instance();
}

/**
 * Launch Youzer Styling!
 */
yz_styling();