<?php

class Youzer_Admin {

	function __construct() {

		// Youzer Admin Pages
	    $this->admin_pages = array( 'youzer-panel', 'yz-profile-settings', 'yz-widgets-settings', 'yz-membership-settings', 'yz-extensions-settings' );

		// Init Admin Area
		add_action( 'init', array( $this, 'init' ) );

        // Add Plugin Links.
        add_filter( 'plugin_action_links_' . YOUZER_BASENAME, array( $this, 'plugin_action_links' ) );

        // Add Plugin Links in Multisite..
        add_filter( 'network_admin_plugin_action_links_' . YOUZER_BASENAME, array( $this, 'plugin_action_links' ) );

	}

	/**
	 * Init Admin Functions.
	 */
	function init() {

		require YZ_ADMIN_CORE . 'functions/yz-general-functions.php';
		require YZ_ADMIN_CORE . 'functions/yz-account-functions.php';
		require YZ_ADMIN_CORE . 'functions/yz-profile-functions.php';
		require YZ_ADMIN_CORE . 'yz-extensions.php';
		// require_once YZ_ADMIN_CORE . 'functions/yz-update-notifier.php';

		if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			require_once YZ_ADMIN_CORE . 'functions/kainelabs-plugins-updater.php';
		}

		// Extension Updaters.
		add_action( 'admin_enqueue_scripts', array( $this, 'extensions_updater' ) );

		// Add Youzer Plugin Admin Pages.
		add_action( 'admin_menu', array( $this, 'admin_init' ) );
		add_action( 'wp_ajax_yz_save_addon_key_license', array( $this, 'save_addon_key_license' ) );

	    if ( ! wp_doing_ajax() && ! is_youzer_panel() ) {
	    	return;
	    }

		add_action( 'admin_init',  array( $this, 'youzer_admin_init' ) );

		// Load Admin Scripts & Styles .
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Patches.
		require_once YZ_ADMIN_CORE . 'general-settings/yz-patches.php';
		require_once YZ_ADMIN_CORE . 'general-settings/yz-upgrade-wordpress-library.php';

	}

    /**
     * Youzer Action Links
     */
    function plugin_action_links( $links ) {

        // Get Youzer Plugin Pages.
        $panel_url 	 = esc_url( add_query_arg( array( 'page' => 'youzer-panel' ), admin_url( 'admin.php' ) ) );
        $plugin_url  = 'https://codecanyon.net/item/youzer-new-wordpress-user-profiles-era/19716647';
        $docs_url    = 'https://kainelabs.ticksy.com/articles/';
        $support_url = 'https://kainelabs.ticksy.com';
        $addons_url  = 'https://kainelabs.com';

        // Add a few links to the existing links array.
        return yz_array_merge( $links, array(
            'settings' => '<a href="' . $panel_url . '">' . esc_html__( 'Settings', 'youzer' ) . '</a>',
            'about'    => '<a href="' . $plugin_url . '">' . esc_html__( 'About', 'youzer' ) . '</a>',
            'docs'    => '<a href="' . $docs_url . '">' . esc_html__( 'Docs', 'youzer' ) . '</a>',
            'support'  => '<a href="' . $support_url . '">' . esc_html__( 'Support',  'youzer' ) . '</a>',
            'addons'  => '<a href="' . $addons_url . '">' . esc_html__( 'Add-Ons',  'youzer' ) . '</a>'
        ) );

    }

	/**
	 * # Initialize Youzer Admin Panel
	 */
	function youzer_admin_init() {
		// Init Admin Files.
		include YZ_ADMIN_CORE . 'yz-admin-ajax.php';
	}

	/**
	 * # Add Youzer Admin Pages .
	 */
	function admin_init() {

		// Show Youzer Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) && ! apply_filters( 'yz_show_youzer_panel', false ) ) {
			return false;
		}

	    // Add Youzer Plugin Admin Page.
	    add_menu_page(
	    	__( 'Youzer Panel', 'youzer' ),
	    	__( 'Youzer Panel', 'youzer' ),
	    	'administrator',
	    	'youzer-panel',
	    	array( $this, 'general_settings' ),
	    	YZ_AA . 'images/icon.png'
	    );

		// Add "General Settings" Page .
	    add_submenu_page(
	    	'youzer-panel',
	    	__( 'Youzer - General Settings', 'youzer' ),
	    	__( 'General Settings', 'youzer' ),
	    	'administrator',
	    	'youzer-panel',
	    	array( $this, 'general_settings' )
	    );

	    // Add "Profile Settings" Page .
	    add_submenu_page(
	    	'youzer-panel',
	    	__( 'Youzer - Profile Settings', 'youzer' ),
	    	__( 'Profile Settings', 'youzer' ),
	    	'administrator',
	    	'yz-profile-settings',
	    	array( $this, 'profile_settings' )
	    );

	    // Add "Widgets Settings" Page .
	    add_submenu_page(
	    	'youzer-panel',
	    	__( 'Youzer - Widgets Settings', 'youzer' ),
	    	__( 'Widgets Settings', 'youzer' ),
	    	'administrator',
	    	'yz-widgets-settings',
	    	array( $this, 'widgets_settings' )
	    );

	    if ( yz_is_membership_system_active() ) {

			// Add "General Settings" Page .
		    add_submenu_page(
		    	'youzer-panel',
		    	__( 'Youzer - Membership Settings', 'youzer' ),
		    	__( 'Membership Settings', 'youzer' ),
		    	'administrator',
		    	'yz-membership-settings',
		    	array( $this, 'membership_settings' )
		    );

	    }

	    if ( ! empty( apply_filters( 'yz_extensions_settings_menu', array() ) ) ) {
		    // Add Youzer Plugin Admin Page.
		    add_submenu_page(
		    	'youzer-panel',
		    	__( 'Extensions Settings', 'youzer' ),
		    	__( 'Extensions Settings', 'youzer' ),
		    	'administrator',
		    	'yz-extensions-settings',
		    	array( $this, 'extensions_settings' )
		    );
	    }

	}

	/**
	 * # Extensions Settings.
	 */
	function extensions_settings() {

		// Filter.
		$tabs = apply_filters( 'yz_extensions_settings_menu', array() );

		// Get Settings.
		$this->get_settings( $tabs, 'extensions-settings' );

	}

	/**
	 * # Admin Scripts.
	 */
	function admin_scripts() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}


	    if ( in_array( $_GET['page'], $this->admin_pages ) ) {

			// Set Up Variables
			$jquery = array( 'jquery' );

	    	// Admin Panel Script
	    	wp_enqueue_script( 'yz-panel', YZ_AA . 'js/yz-settings-page.min.js', $jquery, YZ_Version, true );
	        wp_enqueue_script( 'ukai-panel', YZ_AA . 'js/ukai-panel.min.js', $jquery, YZ_Version, true );
	        wp_localize_script( 'yz-panel', 'yz', array(
	            'reset_error' => __( 'An error occurred while resetting the options!', 'youzer' ),
	            'banner_url'  => __( 'Banner URL not working!', 'youzer' ),
	            'default_img' => YZ_PA . 'images/default-img.png',
	            'ajax_url'    => admin_url( 'admin-ajax.php' )
        	) );

	        // Load Color Picker
	        wp_enqueue_script( 'wp-color-picker' );
    		wp_enqueue_style( 'wp-color-picker' );

	        // Load Tags Script
	        wp_enqueue_script( 'yz-ukaitags', YZ_PA .'js/ukaitag.min.js', array( 'jquery' ), YZ_Version, true );

	        // Media
	        wp_enqueue_media();

		    if (
		    	'youzer-panel' == $_GET['page'] || 'yz-profile-settings' == $_GET['page'] || 'yz-extensions-settings' ==  $_GET['page']
		    	||
		    	( isset( $_GET['tab'] ) && in_array( $_GET['tab'], array( 'custom-widgets', 'user-tags', 'reaction-settings' ) ) )
		    ) {
			    // Admin Panel Script
			    wp_enqueue_script(
			    	'yz-functions',
			    	YZ_AA . 'js/yz-functions.min.js',
			    	array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'yz-iconpicker' ),
			    	YZ_Version, true
			    );

			    wp_localize_script(
			    	'yz-functions', 'Yz_Functions', array(
            			'name_exist' => __( 'This name already exists!', 'youzer' ),
            			'required_fields' => __( 'All fields are required!', 'youzer' ),
            			'save_changes' => __( 'Save Changes', 'youzer' ),
            			'done' => __( 'Save', 'youzer' )
			    	)
			    );
		    }

		    if ( $_GET['page'] == 'yz-extensions-settings' ) {
		    	wp_enqueue_script( 'yz-automatic-updates', YZ_AA . 'js/yz-automatic-updates.js', array(), YZ_Version );
			    wp_localize_script( 'yz-automatic-updates', 'Yz_Automatic_Updates', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		    }
	    }
	}

	/**
	 * # Panel Styles.
	 */
	function admin_styles() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		// Load Admin Panel Styles
	    if ( in_array( $_GET['page'], $this->admin_pages ) ) {
	    	// Load Settings Style
		    wp_enqueue_style( 'yz-panel', YZ_AA . 'css/yz-panel.min.css', array(), YZ_Version );
	        // Load Admin Panel Style
		    wp_enqueue_style( 'yz-admin', YZ_AA . 'css/yz-admin.min.css', array(), YZ_Version );
	        // Load Google Fonts
	        wp_enqueue_style( 'yz-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:100,400,600', array(), YZ_Version );
			// Icons.
			$this->icon_picker_scripts();
	    }

	}

	/**
	 * Icon
	 */
	function icon_picker_scripts() {
		// Loading Font Awesome.
    	wp_enqueue_style( 'yz-icons', YZ_AA . 'css/all.min.css', array(), YZ_Version );
	    yz_iconpicker_scripts();
	}

	/**
	 * Extension Updaters.
	 **/
	function extensions_updater() {

		global $pagenow;

		switch ( $pagenow ) {

			case 'widgets.php':
				$this->icon_picker_scripts();
				break;

			default:
				break;
		}

	}

	/**
	 * # General Settings.
	 */
	function general_settings() {

		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  => 'fas fa-cogs',
				'id'   	=> 'general',
				'function' => 'yz_general_settings',
				'title' => __( 'General Settings', 'youzer' ),
			),
			'wall' => array(
				'id' 		=> 'wall',
				'icon'  	=> 'fas fa-address-card',
				'function' 	=> 'yz_wall_settings',
				'title' 	=> __( 'Wall Settings', 'youzer' ),
			),
			'groups' => array(
				'id'    => 'groups',
				'icon'  => 'fas fa-users',
				'function'  => 'yz_groups_settings',
				'title' => __( 'Groups Settings', 'youzer' ),
			),
			'schemes' => array(
				'id'    => 'schemes',
				'icon'  => 'fas fa-paint-brush',
				'function' => 'yz_schemes_settings',
				'title' => __( 'Schemes Settings', 'youzer' ),
			),
			'panel' => array(
				'icon'  => 'fas fa-cogs',
				'id'    => 'panel',
				'function' => 'yz_panel_settings',
				'title' => __( 'Panel Settings', 'youzer' ),
			),
			'emoji' => array(
				'icon'  => 'fas fa-smile',
				'id'    => 'emoji',
				'function' => 'yz_emoji_settings',
				'title' => __( 'Emoji Settings', 'youzer' ),
			),
			'media' => array(
				'id' 	=> 'media',
				'icon'  => 'fas fa-photo-video',
				'function' => 'yz_media_settings',
				'title' => __( 'Media Settings', 'youzer' ),
			),
			'author-box' => array(
				'id'    => 'author-box',
				'icon'  => 'fas fa-address-book',
				'function' => 'yz_author_box_settings',
				'title' => __( 'Author Box Settings', 'youzer' ),
			),
			'custom-styling' => array(
				'id'    => 'custom-styling',
				'icon'  => 'fas fa-code',
				'function'  => 'yz_custom_styling_settings',
				'title' => __( 'Custom Styling Settings', 'youzer' ),
			),
			'social-networks' => array(
				'icon'  => 'fas fa-share-alt',
				'id'    => 'social-networks',
				'function'  => 'yz_social_networks_settings',
				'title' => __( 'Social Networks Settings', 'youzer' ),
			),
			'account-verification' => array(
				'id'    => 'account-verification',
				'icon'  => 'fas fa-check-circle',
				'function'  => 'yz_account_verification_settings',
				'title' => __( 'Account Verification Settings', 'youzer' ),
			),
			'reviews' => array(
				'id'    => 'reviews',
				'icon'  => 'fas fa-star',
				'function'  => 'yz_reviews_settings',
				'title' => __( 'Reviews Settings', 'youzer' ),
			),
			'bookmarks' => array(
				'id'    => 'bookmarks',
				'icon'  => 'fas fa-bookmark',
				'function'  => 'yz_bookmarks_settings',
				'title' => __( 'Bookmarks Settings', 'youzer' ),
			),
			'messages' => array(
				'id'    => 'messages',
				'icon'  => 'fas fa-envelope',
				'function'  => 'yz_messages_settings',
				'title' => __( 'Messages Settings', 'youzer' ),
			),
			'notifications' => array(
				'id'    => 'notifications',
				'icon'  => 'far fa-bell',
				'function'  => 'yz_notifications_settings',
				'title' => __( 'Notifications Settings', 'youzer' ),
			),
			'groups-directory' => array(
				'id'    => 'groups-directory',
				'icon'  => 'fas fa-list-alt',
				'function'  => 'yz_groups_directory_settings',
				'title' => __( 'Groups Directory Settings', 'youzer' ),
			),
			'members-directory' => array(
				'id'    => 'members-directory',
				'icon'  => 'fas fa-list',
				'function'  => 'yz_members_directory_settings',
				'title' => __( 'Members Directory Settings', 'youzer' ),
			)
		);

		// Add BBpress Settings.
        if ( class_exists( 'bbPress' ) ) {
			$tabs['bbpress'] = array(
		   	    'icon'  => 'far fa-comments',
		   	    'id'    => 'bbpress',
		   	    'function' => 'yz_bbpress_settings',
		   	    'title' => __( 'BBpress Settings', 'youzer' ),
		    );
		}

		// Add Woocommerce Settings.
        if ( class_exists( 'Woocommerce' ) ) {
			$tabs['woocommerce'] = array(
		        'id'    => 'woocommerce',
		   	    'icon'  => 'fas fa-shopping-cart',
		   	    'function' => 'yz_woocommerce_settings',
		   	    'title' => __( 'Woocommerce Settings', 'youzer' ),
		    );
		}

	    // Add Mycred Settings.
        if ( defined( 'myCRED_VERSION' ) ) {
			$tabs['mycred'] = array(
		   	    'icon'  => 'fas fa-trophy',
		   	    'id'    => 'mycred',
		   	    'function' => 'yz_mycred_settings',
		   	    'title' => __( 'MyCRED Settings', 'youzer' ),
		    );
		}

		// Filter.
		$tabs = apply_filters( 'yz_panel_general_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'general-settings' );

	}

	/**
	 * # Profile Settings.
	 */
	function profile_settings() {

		// Include Navbar Functions.
        include_once YZ_PUBLIC_CORE . 'functions/yz-navbar-functions.php';

		// Menu Tabs List.
		$tabs = array(
			'general' => array(
				'id' 		=> 'profile',
				'icon'  	=> 'fas fa-cogs',
				'function' 	=> 'yz_profile_general_settings',
				'title' 	=> __( 'General Settings', 'youzer' ),
			),
			'structure' => array(
				'id' 	=> 'structure',
				'icon'  => 'fas fa-sort-amount-down',
				'function' => 'yz_profile_structure_settings',
				'title' => __( 'Profile Structure', 'youzer' ),
			),
			'header' => array(
				'id' 	=> 'header',
				'icon'  => 'fas fa-heading',
				'function' => 'yz_header_settings',
				'title' => __( 'Header Settings', 'youzer' ),
			),
			'navbar' => array(
				'icon'  => 'fas fa-list',
				'id' 	=> 'navbar',
				'function' => 'yz_navbar_settings',
				'title' => __( 'Navbar Settings', 'youzer' ),
			),
			'ads' => array(
				'id'    => 'ads',
				'icon'  => 'fas fa-bullhorn',
				'function' => 'yz_get_ads_settings',
				'title' => __( 'Ads Settings', 'youzer' ),
			),
			'tabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'far fa-list-alt',
				'function' => 'yz_tabs_settings',
				'title' => __( 'Tabs Settings', 'youzer' ),
			),
			'subtabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'fas fa-indent',
				'function' => 'yz_profile_subtabs_settings',
				'title' => __( 'Subtabs Settings', 'youzer' ),
			),
			'custom-tabs' => array(
				'id' 	=> 'custom-tabs',
				'icon'  => 'fas fa-plus-square',
				'function' => 'yz_profile_custom_tabs_settings',
				'title' => __( 'Custom Tabs Settings', 'youzer' ),
			),
			'info' => array(
				'id' 	=> 'info',
				'icon'  => 'fas fa-info',
				'function' => 'yz_profile_info_tab_settings',
				'title' => __( 'Info Tab Settings', 'youzer' ),
			),
			'posts' => array(
				'id' 	=> 'posts',
				'icon'  => 'fas fa-file-alt',
				'function' => 'yz_posts_settings',
				'title' => __( 'Posts Tab Settings', 'youzer' ),
			),
			'media' => array(
				'id' 	=> 'media',
				'icon'  => 'fas fa-photo-video',
				'function' => 'yz_profile_media_tab_settings',
				'title' => __( 'Media Tab Settings', 'youzer' ),
			),
			'comments' => array(
				'id' 	=> 'comments',
				'icon'  => 'far fa-comments',
				'function' => 'yz_comments_settings',
				'title' => __( 'Comments Tab Settings', 'youzer' ),
			),
			'profile-404' => array(
				'id'   => 'profile-404',
				'icon'  => 'fas fa-exclamation-triangle',
				'function' => 'yz_profile_404_settings',
				'title' => __( 'Profile 404 Settings', 'youzer' ),
			)
		);

		// Add Third Party Plugins Subnavs Settings
        $third_party_tabs = yz_get_profile_third_party_tabs();

        if ( empty( $third_party_tabs ) ) {
			unset( $tabs['subtabs'] );
        }

		$tabs = apply_filters( 'yz_panel_profile_settings_menus', $tabs );


		// Get Settings.
		$this->get_settings( $tabs, 'profile-settings' );

	}

	/**
	 * # Widgets Settings.
	 */
	function widgets_settings() {

		// Widgets Tabs List.
		$tabs = array(
			'widgets' => array(
				'id' 	=> 'widgets',
				'function' => 'yz_general_widgets_settings',
				'title' => __( 'Widgets Settings', 'youzer' ),
				'icon'  => 'fas fa-cogs'
			),
			'about-me' => array(
				'id' 	=> 'about_me',
				'title' => __( 'About Me Settings', 'youzer' ),
				'function' => 'yz_about_me_widget_settings',
				'icon'  => 'fas fa-user'
			),
			'post' => array(
				'id' 	=> 'post',
				'title' => __( 'Post Settings', 'youzer' ),
				'function' => 'yz_post_widget_settings',
				'icon'  => 'fas fa-pencil-alt'
			),
			'project' => array(
				'id' 	=> 'project',
				'title' => __( 'Project Settings', 'youzer' ),
				'function' => 'yz_project_widget_settings',
				'icon'  => 'fas fa-suitcase'
			),
			'skills' => array(
				'id' 	=> 'skills',
				'function' => 'yz_skills_widget_settings',
				'title' => __( 'Skills Settings', 'youzer' ),
				'icon'  => 'fas fa-tasks'
			),
			'services' => array(
				'id' 	=> 'services',
				'title' => __( 'Services Settings', 'youzer' ),
				'function' => 'yz_services_widget_settings',
				'icon'  => 'fas fa-wrench'
			),
			'portfolio' => array(
				'id' 	=> 'portfolio',
				'title' => __( 'Portfolio Settings', 'youzer' ),
				'function' => 'yz_portfolio_widget_settings',
				'icon'  => 'fas fa-camera-retro'
			),
			'slideshow' => array(
				'id' 	=> 'slideshow',
				'title' => __( 'Slideshow Settings', 'youzer' ),
				'function' => 'yz_slideshow_widget_settings',
				'icon'  => 'fas fa-film'
			),
			'quote' => array(
				'id' 	=> 'quote',
				'title' => __( 'Quote Settings', 'youzer' ),
				'function' => 'yz_quote_widget_settings',
				'icon'  => 'fas fa-quote-right'
			),
			'link' => array(
				'id' 	=> 'link',
				'title' => __( 'Link Settings', 'youzer' ),
				'function' => 'yz_link_widget_settings',
				'icon'  => 'fas fa-unlink'
			),
			'video' => array(
				'id' 	=> 'video',
				'title' => __( 'Video Settings', 'youzer' ),
				'function' => 'yz_video_widget_settings',
				'icon'  => 'fas fa-video'
			),
			'instagram' => array(
				'id' 	=> 'instagram',
				'title' => __( 'Instagram Settings', 'youzer' ),
				'function' => 'yz_instagram_widget_settings',
				'icon'  => 'fab fa-instagram'
			),
			'media' => array(
				'id' 	=> 'wall_media',
				'title' => __( 'Media Settings', 'youzer' ),
				'function' => 'yz_media_widget_settings',
				'icon'  => 'fas fa-photo-video'
			),
			'flickr' => array(
				'id' 	=> 'flickr',
				'title' => __( 'Flickr Settings', 'youzer' ),
				'function' => 'yz_flickr_widget_settings',
				'icon'  => 'fab fa-flickr'
			),
			'user-balance' => array(
				'id' 	=> 'user_balance',
				'title' => __( 'User Balance Settings', 'youzer' ),
				'function' => 'yz_user_balance_widget_settings',
				'icon'  => 'fas fa-gem'
			),
			'user-badges' => array(
				'id' 	=> 'user_badges',
				'title' => __( 'User Badges Settings', 'youzer' ),
				'function' => 'yz_user_badges_widget_settings',
				'icon'  => 'fas fa-trophy'
			),
			'friends' => array(
				'id' 	=> 'friends',
				'title' => __( 'Friends Settings', 'youzer' ),
				'function' => 'yz_friends_widget_settings',
				'icon'  => 'far fa-handshake'
			),
			'groups' => array(
				'id' 	=> 'groups',
				'title' => __( 'Groups Settings', 'youzer' ),
				'function' => 'yz_groups_widget_settings',
				'icon'  => 'fas fa-users'
			),
			'reviews' => array(
				'id' 	=> 'reviews',
				'title' => __( 'Reviews Settings', 'youzer' ),
				'function' => 'yz_reviews_widget_settings',
				'icon'  => 'far fa-star'
			),
			'info-boxes' => array(
				'id' 	=> 'info_box',
				'title' => __( 'Info Boxes Settings', 'youzer' ),
				'function' => 'yz_info_boxes_widget_settings',
				'icon'  => 'fas fa-clipboard'
			),
			'user-tags' => array(
				'id' 	=> 'user_tags',
				'title' => __( 'User Tags Settings', 'youzer' ),
				'function' => 'yz_user_tags_widget_settings',
				'icon'  => 'fas fa-tags'
			),
			'recent-posts' => array(
				'id' 	=> 'recent_posts',
				'title' => __( 'Recent Posts Settings', 'youzer' ),
				'function' => 'yz_recent_posts_widget_settings',
				'icon'  => 'far fa-newspaper'
			),
			'social-networks' => array(
				'id' 	=> 'social_networks',
				'title' => __( 'Social Networks Settings', 'youzer' ),
				'function' => 'yz_social_networks_widget_settings',
				'icon'  => 'fas fa-share-alt'
			),
			'custom-widgets' => array(
				'id' 	=> 'custom_widgets',
				'title' => __( 'Custom Widgets Settings', 'youzer' ),
				'function' => 'yz_custom_widget_settings',
				'icon'  => 'fas fa-plus'
			)
		);

		// Filter
		$tabs = apply_filters( 'yz_panel_widgets_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'widgets-settings' );

	}

	/**
	 * Membership settings.
	 */
	function membership_settings() {


		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  	=> 'fas fa-cogs',
				'id' 		=> 'general',
				'function' 	=> 'logy_general_settings',
				'title' 	=> __( 'General Settings', 'youzer' ),
			),
			'login'	=> array(
				'id' 		=> 'login',
				'icon'  	=> 'fas fa-sign-in-alt',
				'function' 	=> 'logy_login_settings',
				'title' 	=> __( 'Login Settings', 'youzer' ),
			),
			'register' => array(
				'icon'  	=> 'fas fa-pencil-alt',
				'id' 		=> 'register',
				'function' 	=> 'logy_register_settings',
				'title' 	=> __( 'Register Settings', 'youzer' ),
			),
			'lost-password' => array(
				'icon'  	=> 'fas fa-lock',
				'id' 		=> 'lost_password',
				'function' 	=> 'logy_lost_password_settings',
				'title' 	=> __( 'Lost Password Settings', 'youzer' ),
			),
			'captcha' => array(
				'id' 		=> 'captcha',
				'icon'  	=> 'fas fa-user-secret',
				'function' 	=> 'logy_captcha_settings',
				'title' 	=> __( 'Captcha Settings', 'youzer' ),
			),
			'social-login' => array(
				'icon'  	=> 'fas fa-share-alt',
				'id' 		=> 'social_login',
				'function' 	=> 'logy_social_login_settings',
				'title' 	=> __( 'Social Login Settings', 'youzer' ),
			),
			'limit-login' => array(
				'icon'  	=> 'fas fa-user-clock',
				'id' 		=> 'limit_login',
				'function' 	=> 'logy_limit_login_settings',
				'title' 	=> __( 'Login Attempts Settings', 'youzer' ),
			),
			'newsletter' => array(
				'icon'  	=> 'far fa-envelope',
				'id' 		=> 'newsletter',
				'function' 	=> 'logy_newsletter_settings',
				'title' 	=> __( 'Newsletter Settings', 'youzer' ),
			),
			'login-styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'login_styling',
				'function' 	=> 'logy_login_styling_settings',
				'title' 	=> __( 'Login Styling Settings', 'youzer' ),
			),
			'register-styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'register_styling',
				'function' 	=> 'logy_register_styling_settings',
				'title' 	=> __( 'Register Styling Settings', 'youzer' ),
			)
		);

		// Filter
		$tabs = apply_filters( 'yz_panel_membership_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'membership-settings' );

	}

	/**
	 * Get Page Settings
	 */
	function get_settings( $tabs, $page = false ) {

        // require_once YZ_PUBLIC_CORE . 'class-yz-widgets.php';

		global $Yz_Settings;

		// Get Tabs Keys
		$settings_tabs = array_keys( $tabs );

		// Get Current Tab.
		$current_tab = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $settings_tabs ) ? (string) $_GET['tab'] : (string) key( $tabs );

		// Append Class to the active tab.
		$tabs[ $current_tab ]['class'] = 'yz-active-tab';

		// Get Tab Data.
		$tab = $tabs[ $current_tab ];

		// Get Tab Function Name.
		$settings_function = isset( $tab['function'] ) ?  $tab['function']: null;

		ob_start();

        $Yz_Settings->get_field(
        	array(
	            'type'  => 'start',
	            'id'    => $tab['id'],
	            'icon'  => $tab['icon'],
	            'title' => $tab['title'],
       		)
        );


        $file = YZ_ADMIN_CORE . $page . '/yz-settings-' . $current_tab . '.php';

        if ( file_exists( $file ) ) {
			include $file;
        }

		$settings_function();

        $Yz_Settings->get_field( array( 'type' => 'end' ) );

		$content = ob_get_contents();

		ob_end_clean();

		// Print Panel
		$this->admin_panel( $tabs, $content );

	}

	/**
	 * Add License Activation Notice.
	 */
	function extension_validate_license_notice( $args = null ) {

		?>

		<style type="text/css">

			.yz-addon-license-area input {
				margin-right: 8px;
			}

			.yz-addon-license-area .yz-activate-addon-key {
				background-color: #03A9F4;
				height: 27px;
				line-height: 27px;
				padding: 0 15px;
				color: #fff;
				border-radius: 2px;
				font-weight: 600;
				cursor: pointer;
				font-size: 13px;
				min-width: 80px;
				text-align: center;
			}

			.yz-addon-license-area input,
			.yz-addon-license-area .yz-activate-addon-key {
				display: inline-block;vertical-align: middle;
			}

			.yz-addon-license-msg {
				color: #616060;
				margin: 12px 0;
				font-size: 13px;
				background: #fff;
				font-weight: 600;
				border-radius: 2px;
				padding: 10px 25px;
				border-left: 5px solid #9E9E9E;
			}

			.yz-addon-error-msg {
				border-color: #F44336;
			}

			.yz-addon-success-msg {
				border-color: #8BC34A;
			}

		</style>

		<tr class="active">
			<td>&nbsp;</td>
			<td colspan="2">
				<div class="yz-addon-license-area">
					<div class="yz-addon-license-content">
						<?php _e( 'Please enter and activate your license key to enable automatic updates:', 'youzer' ); ?>
						<input type="text" class="yz-addon-license-key" name="<?php echo $args['field_name']; ?>"><div data-product-name="<?php echo $args['product_name']; ?>" data-nounce="<?php echo wp_create_nonce( 'yz_addon_license_notice' ); ?>" class="yz-activate-addon-key"><?php _e( 'Verify Key', 'youzer' ); ?></div>
					</div>
				</div>
		    </td>
		</tr>

		<?php

	}

	/**
	 * # Youzer Panel Form.
	 */
	function admin_panel( $menu = null, $settings = null ) {

	?>

	<?php do_action( 'youzer_admin_before_form' ); ?>

	<div id="ukai-panel" class="<?php echo yz_option( 'yz_panel_scheme', 'uk-yellow-scheme' ); ?>">

	    <div class="uk-sidebar">
	        <div class="ukai-logo">
	        	<img src="<?php echo YZ_AA . 'images/logo.png'; ?>" alt="">
	        </div>
	        <a class="yz-tab-extensions" href="<?php echo apply_filters( 'youzer_panel_extensions_page_link', menu_page_url( 'yz-extensions', false ) ); ?>"><i class="fas fa-plug"></i><?php _e( 'Extensions <span class="new">New</span>') ?></a>
			<div class="kl-responsive-menu">
				<?php _e( 'Menu', 'youzer' ); ?>
				<input class="kl-toggle-btn" type="checkbox" id="kl-toggle-btn">
	  			<label class="kl-toggle-icon" for="kl-toggle-btn"></i><span class="kl-icon-bars"></span></label>
			</div>

			<!-- Panel Menu. -->
	        <?php $this->get_menu( $menu ); ?>
	    </div>

	    <div id="ukai-panel-content" class="ukai-panel">
	        <div class="youzer-main-content">
	            <?php
	            	// Get Panel Settings
	            	echo $settings;
	            ?>
			</div>
	    </div>

	</div>

	<div class="yz-md-overlay"></div>

	<!-- Reset Dialog -->
	<?php yz_popup_dialog( 'reset_tab' ); ?>

	<!-- Errors Dialog -->
	<?php yz_popup_dialog( 'error' ); ?>

	<?php do_action( 'youzer_admin_after_form' ); ?>

	<?php if ( 'on' == yz_option( 'yz_enable_panel_fixed_save_btn', 'on' ) ) : ?>
		<div class="yz-fixed-save-btn"><i class="fas fa-save"></i></div>
	<?php endif; ?>

	<?php

	}
	/**
	 * # Get Menu Content.
	 */
	function get_menu( $tabs_list ) {

		// Get Current Page Url.
		$current_url = yz_get_current_page_url();

		echo '<ul class="yz-panel-menu youzer-form-menu">';

		foreach ( $tabs_list as $key => $tab ) {

			if ( isset( $tab['hide_menu'] ) && $tab['hide_menu'] === true ) {
				continue;
			}

 			// Add Tab ID to url.
			$tab_url = add_query_arg( 'tab', $key, $current_url );

			// Get Tab Class Name.
			$class = isset( $tab['class'] ) ? 'class="yz-active-tab"' : null; ?>

			<li>
				<a href="<?php echo $tab_url; ?>" <?php echo $class; ?>><i class="<?php echo $tab['icon']; ?>"></i><?php echo $tab['title']; ?></a>
			</li>

			<?php

		}

	    echo '</ul>';
	}

	/**
	 * License Settings.
	 */
	function get_license_settings( $args = array() ) {

		// Get License.
		$license = isset( $args['license'] ) ? $args['license'] : get_option( $args['option_id'] );

        global $Yz_Settings;

		// Get License Dates.
		$support_date = get_option( $args['option_id'] . '_expires' );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'License Settings', 'youzer' ),
                'type'  => 'openBox',
                'class' => 'yz-addon-license-settings'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title'  => __( 'License Key', 'youzer' ),
                'desc'  => sprintf( __( '<a href="%s">How to find your product license key?</a>', 'youzer' ), 'https://kainelabs.ticksy.com/article/15685/' ),
                'id'    => 'license',
                'type'  => 'text',
                'class' => 'yz-addon-license-key',
                'std'   => $license,
                'hide_name' => true,
            )
        );

        if ( ! $license || empty( get_option( $args['option_id'] . '_expires' ) ) || ( $support_date != 'lifetime' && strtotime( $support_date ) < time() )  ) {

            $Yz_Settings->get_field(
                array(
                    'button_title' => __( 'Verify License', 'youzer' ),
                    'id'    => 'yz-verify-license',
                    'type'  => 'button',
                    'button_class' => 'yz-activate-addon-key',
                    'button_data' => array(
                        'product-name' => $args['product_id'],
                        'option-name' => $args['option_id'],
                    )
                )
            );

        }

        // Get License Status.
        $this->get_license_status( $args );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

	}

	/**
	 * License Status.
	 */
	function get_license_status( $args ) {

		// Get License.
		$license = get_option( $args['option_id'] );

		if ( $license ) {

			// Get Dates.
			$support_date = get_option( $args['option_id'] . '_expires' );

            if ( empty( $support_date ) ) {
                return;
            }

			// Compare Support Date.
			if ( $support_date == 'lifetime' ) {
				$status = 'lifetime';
			} else {
				$status = strtotime( $support_date ) > time() ? 'active' : 'expired';
			}

			if ( ( $status == 'active' || $status == 'lifetime' ) && ( isset( $args['position'] ) && $args['position'] == 'top' ) ) {
				return;
			}

			// Get License Expiration Date.
			$expiration_date = date_i18n( get_option( 'date_format' ), strtotime( $support_date, current_time( 'timestamp' ) ) );

		    echo '<div class="yz-addon-expire-notice yz-addon-license-' . $status . '">';

		    if ( $status == 'expired' ) {
		    	echo sprintf( __( 'Your license key expired on %s.', 'youzer' ), $expiration_date );
		    } elseif ( $status == 'lifetime' ) {
		    	echo sprintf( __( 'Your license key is valid forever.', 'youzer' ), $expiration_date );
			} else {
		    	echo sprintf( __( 'Your license key will expire on %s.', 'youzer' ), $expiration_date );
			}

			if ( $status == 'expired' ) {
		    	echo '<a href="https://www.kainelabs.com/checkout/?edd_license_key=' . $license . '&download_id=' . $args['product_id'] . '">' . __( 'Renew License with 30% OFF.', 'youzer' ) . '</a>';
			}

			echo '</div>';

	    }

	}

	/**
	 * Save Add On Key License
	 */
	function save_addon_key_license() {

		// retrieve the license from the database
		$license = trim( $_POST['license'] );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'  	 => urlencode( $_POST['product_name'] ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( EDD_KAINELABS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success && $license_data->error != 'expired' ) {

				switch( $license_data->error ) {

					// case 'expired' :
					// 	$message = sprintf(
					// 		__( 'Your license key expired on %s.', 'youzer' ),
					// 		date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					// 	);
					// 	break;

					case 'disabled' :
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'youzer' );
						break;

					case 'missing' :
						$message = __( 'Invalid license.', 'youzer' );
						break;

					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'youzer' );
						break;

					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'youzer' ), $_POST['product_name'] );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'youzer' );
						break;

					default :
						$message = __( 'An error occurred, please try again.', 'youzer' );
						break;
				}

			}

		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_send_json_error( array( 'message' => $message ) );
		} else {

			yz_update_option( $_POST['name'] . '_expires', $license_data->expires );
			yz_update_option( $_POST['name'], $license );

			wp_send_json_success( array( 'message' => __( 'Success !', 'youzer' ) ) );
		}

		exit();

	}

}

global $Youzer_Admin;

$Youzer_Admin = new Youzer_Admin();