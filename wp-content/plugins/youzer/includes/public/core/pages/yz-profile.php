<?php

class Youzer_Profile {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 *
	 * @since 3.0.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
			self::$instance->init();
		}

		return self::$instance;
	}

	function __construct() {
		// add_action( 'bp_init', array( $this, 'init' ) );
	}

	/**
	 * Init Profile.
	 */
	function init() {

		// Call Tabs.
		add_action( 'yz_profile_main_column', array( $this, 'get_tabs' ) );

		// Profile Custom Styling
		$styling = yz_styling();
		$styling->custom_styling( 'profile' );
		$styling->custom_snippets( 'profile' );
		unset( $styling );

		add_action( 'wp_head', array( $this, 'open_graph' ) );

		// Load Profile Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'profile_scripts' ) );

		// Profile Navbar Content
		if ( yz_get_profile_layout() == 'yz-horizontal-layout' ) {
				add_action( 'youzer_profile_navbar', array( $this, 'navbar' ) );
		} else {
			if ( 'wild-navbar' == yz_option( 'yz_vertical_layout_navbar_type', 'wild-navbar' ) ) {
				add_action( 'youzer_profile_before_header', array( $this, 'navbar' ) );
			} else {
				add_action( 'yz_profile_main_column', array( $this, 'navbar' ), 1 );
			}
		}

		// Profile Main Content
		add_action( 'yz_profile_main_content', array( $this, 'profile_main_content' ) );

	}

	/**
	 * # Navbar Menu.
	 */
	function navbar() {

		if ( ! apply_filters( 'yz_display_profile_navbar', true ) ) {
			return;
		}

		// Get Navbar Options.
		$navbar_effect = yz_option( 'yz_navbar_load_effect', 'fadeIn' );

		// Get Navbar Data.
		$navbar_data  = yz_widgets()->get_loading_effect( $navbar_effect );

		echo "<nav id='yz-profile-navmenu' class='" . $this->get_navbar_class() . "' $navbar_data>";
		echo '<div class="yz-inner-content">';

		// Get Toogle Menu Code.
		echo apply_filters( 'yz_profile_navbar_toggle_menu', '<div class="yz-open-nav"><button class="yz-responsive-menu"><span>toggle menu</span></button></div>' );


		// Get Primary Navigation Menu
		yz_profile_navigation_menu();

	    // Get Account Settings
		$this->account_settings_menu();

		echo '</div></nav>';

	}

	/**
	 * # Navbar Settings Menu.
	 */
	function account_settings_menu() {

	    do_action( 'yz_profile_navbar_right_area' );

	    // Get Header Layout.
	    $header_layout = yz_get_profile_layout();

	    if ( ! bp_is_my_profile() && 'yz-horizontal-layout' == $header_layout  ) {
	        yz_get_social_buttons();
	        return false;
	    }

	    if ( ! bp_is_my_profile() ) {
	        return false;
	    }

    	if ( apply_filters( 'yz_display_user_profile_navigation_right_menu', true ) ) {

	    ?>

	    <div class="yz-settings-area">

	        <?php

	            // Get Navbar Quick Buttons.
	            if ( 'yz-horizontal-layout' == $header_layout || yz_is_wild_navbar_active() ) {
	                yz_user_quick_buttons( bp_loggedin_user_id() );
	            }

	        ?>

	        <?php if ( apply_filters( 'yz_display_user_profile_quick_menu', true ) ):  ?>
		        <div class="yz-nav-settings">
		            <div class="yz-settings-img"><?php echo bp_core_fetch_avatar( array( 'item_id' => bp_displayed_user_id(), 'type' => 'thumb', 'width' => 35, 'height' => 35 ) ); ?></div><i class="fas fa-angle-down yz-settings-icon"></i></div>
		        <?php $this->user_settings_menu(); ?>
	        <?php endif; ?>

	    </div>

	    <?php

    	}
	}

	/**
	 * # User Settings Menu.
	 */
	function user_settings_menu( $user_id = null ) {

	    // Get User ID.
	    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// New Array
		$links = array();
		$is_xprofile_active = bp_is_active( 'xprofile' );
		$is_settings_active = bp_is_active( 'settings' );

		// Profile Settings
		if ( $is_xprofile_active ) {

			$links['profile'] = array(
				'icon'	=> 'fas fa-user',
				'href'	=> yz_get_profile_settings_url( false, $user_id ),
				'title'	=> __( 'Profile Settings', 'youzer' )
			);

		}

		// Account Settings
    	if ( $is_settings_active ) {
			$links['account'] = array(
				'icon'	=> 'fas fa-cogs',
				'href'	=> bp_core_get_user_domain( $user_id ) . bp_get_settings_slug(),
				'title'	=> __( 'Account Settings', 'youzer' )
			);
    	}

		// Widgets Settings
    	if ( apply_filters( 'yz_create_widgets_settings_page', true ) ) {
			$links['widgets'] = array(
				'icon'	=> 'fas fa-sliders-h',
				'href'	=> yz_get_widgets_settings_url( false, $user_id ),
				'title'	=> __( 'Widgets Settings', 'youzer' )
			);
    	}

		if ( $is_xprofile_active ) {

			// Change Photo Link
			$links['change-photo'] = array(
				'icon'	=> 'fas fa-camera-retro',
				'href'	=> yz_get_profile_settings_url( 'change-avatar', $user_id ),
				'title'	=> __( 'Change Avatar', 'youzer' )
			);
		}

		if ( $is_settings_active ) {

			// Change Password Link
			$links['change-password'] = array(
				'icon'	=> 'fas fa-lock',
				'href'	=> bp_core_get_user_domain( $user_id ) . bp_get_settings_slug() . '/general',
				'title'	=> __( 'Change Password', 'youzer' )
			);

		}

		// Logout Link
		$links['logout'] = array(
			'icon'	=> 'fas fa-power-off',
			'href'	=> wp_logout_url(),
			'title'	=> __( 'Logout', 'youzer' )
		);

		// Filter.
		$links = apply_filters( 'yz_get_profile_account_menu', $links, $user_id );

		?>

		<div class="yz-settings-menu">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( $link['href'] ); ?>">
					<div class="yz-icon"><i class="<?php echo $link['icon'];?>"></i></div>
					<span class="yzb-button-title"><?php echo $link['title']; ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * # Navbar Class.
	 */
	function get_navbar_class() {

		// Create Empty Array.
		$navbar_class = array();

		$navbar_effect = yz_option( 'yz_navbar_load_effect', 'fadeIn' );

		// Add Header Main Class
		$navbar_class[] = yz_widgets()->get_loading_effect( $navbar_effect, 'navbar' );

		// Get Icons Style
		$navbar_class[] = 'yz-' . yz_option( 'yz_navbar_icons_style', 'navbar-inline-icons' );

		if ( 'yz-horizontal-layout' == yz_get_profile_layout() ) {

			// Get Options.
			$header_layout = yz_option( 'yz_header_layout', 'hdr-v1' );

			// Add a class depending on another one.
			if ( 'hdr-v2' == $header_layout || 'hdr-v7' == $header_layout ) {
				$navbar_class[] = 'yz-boxed-navbar';
			}

		} else {
			$navbar_class[] = 'yz-boxed-navbar';
		}

		// // Add Boxed Navbar Class.
		// if ( 'yz-vertical-layout' == yz_get_profile_layout() ) {
		// }

	 	// Return Class Name.
		return yz_generate_class( $navbar_class );
	}

	/**
	 * # Profile Main Content.
	 */
	function profile_main_content() {

        // Hide sidebar if profile is private.
        if ( ! yz_display_profile() ) {
            yz_private_account_message();
            return;
        }

        $layout = yz_option( 'yz_profile_layout', 'yz-right-sidebar' );

        if ( $layout == 'yz-3columns' && apply_filters( 'yz_disable_3columns_sidebar', true ) ) {
        	$accepted_3columns_tabs= array( 'activity' => 1, 'overview' => 1, 'info' => 1 );
        	if ( ! isset( $accepted_3columns_tabs[ bp_current_component() ] ) ) {
       			$layout = yz_option( 'yz_profile_main_sidebar', 'yz-right-sidebar' );
        	}
        }

		?>

		<div class="<?php echo $layout; ?>-layout">

			<?php do_action( 'yz_before_profile_layout' ); ?>

			<div class="yz-main-column grid-column">
				<?php do_action( 'yz_profile_main_column' ); ?>
			</div>

			<?php if ( apply_filters( 'yz_display_profile_sidebar', true ) ) : ?>

			<?php if ( $layout != 'yz-right-sidebar' ) : ?>
			<div class="yz-sidebar-column grid-column yz-profile-sidebar yz-left-sidebar"><?php $this->sidebar_widgets( 'left', yz_option( 'yz_profile_left_sidebar_widgets' ) ); ?>
			</div><?php endif; ?>

			<?php if ( $layout != 'yz-left-sidebar' ) :  ?>
			<div class="yz-sidebar-column grid-column yz-profile-sidebar yz-right-sidebar"><?php $this->sidebar_widgets( 'right', yz_option( 'yz_profile_sidebar_widgets', array(
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
			        'phone'           => 'visible'
			    ) ) ); ?></div>
			<?php endif; endif; ?>

		</div>

		<!-- Scroll to top -->
		<?php $this->get_scroll_to_top(); ?>

		<?php do_action( 'yz_profile_content' ); ?>

		<?php
	}

	/**
	 * # Scroll to top .
	 */
	function get_scroll_to_top() {
		if ( 'on' == yz_option( 'yz_display_scrolltotop', 'off' ) ) {
			yz_scroll_to_top();
		}
	}

	/**
	 * # Sidebar Content .
	 */
	function sidebar_widgets( $position, $sidebar_widgets = false ) {

		// if ( ! yz_display_profile() ) {
  //           return;
  //       }

		do_action( 'yz_before_' . $position . '_sidebar_widgets', $position );

        $sidebar_widgets = apply_filters( 'yz_profile_sidebar_widgets', $sidebar_widgets );

		// Get Widget Content.
		if ( $position == 'right' ) {
			do_action( 'yz_profile_sidebar' );
		}

        if ( ! empty( $sidebar_widgets ) ) {
			yz_widgets()->get_widget_content( $sidebar_widgets );
        }

		do_action( 'yz_after_' . $position . '_sidebar_widgets', $position );

	}

	//*****

	/**
	 * Add Profiles Open Graph Support.
	 */
	function open_graph() {

	    if ( bp_is_single_activity() ) {
	        return false;
	    }

	    // Get Displayed Profile user id.
	    $user_id = bp_displayed_user_id();

	    // Get Username
	    $user_name = bp_core_get_user_displayname( $user_id );

	    // Get User Cover Image
	    $user_image = apply_filters( 'yz_og_profile_cover_image', bp_attachments_get_attachment( 'url', array( 'object_dir' => 'members', 'item_id' => $user_id ) ) );

	    // Get Avatar if Cover Not found.
	    if ( empty( $user_image ) ) {
	        $user_image = apply_filters( 'yz_og_profile_default_thumbnail', null );
	    }

	    // Get User Description.
	    $user_desc = get_the_author_meta( 'description', $user_id );

	    // Get Page Url !
	    $url = bp_core_get_user_domain( $user_id );

	    // if description empty get about me description.
	    if ( empty( $user_desc ) ) {
	        $user_desc = get_the_author_meta( 'wg_about_me_bio', $user_id );
	    }

	    yz_get_open_graph_tags( 'profile', $url, $user_name, $user_desc, $user_image );

	}

	/*
	 * # Profile Scripts .
	 */
	function profile_scripts() {

        // Load Profile Schemes.
        wp_enqueue_style( 'yz-schemes' );

        // Load Profile Style
        wp_enqueue_style( 'yz-profile', YZ_PA . 'css/yz-profile-style.min.css', array(), YZ_Version );

        // Load Profile Script.
	    wp_enqueue_script( 'yz-profile', YZ_PA . 'js/yz-profile.min.js', array( 'jquery', 'jquery-effects-fade' ), YZ_Version, true );

        // If Effects are enabled active effects scripts.
        if ( 'on' == yz_option( 'yz_use_effects', 'off' ) ) {
            // Profile Animation CSS
            wp_enqueue_style( 'yz-animation', YZ_PA . 'css/animate.min.css', array(), YZ_Version );
	        // Load View Port Checker Script
	        wp_enqueue_script( 'yz-viewchecker', YZ_PA . 'js/yz-viewportChecker.min.js', array( 'jquery' ), YZ_Version, true  );
        }

	}

	/**
	 * Get Tabs Content
	 */
	public function get_tabs() {

		// Show Private Account Message.
		if ( ! yz_display_profile() ) {
			yz_private_account_message();
			return false;
		}

		// If page is single activity show single activity template.
	    if ( bp_is_single_activity() ) {
	        yz_get_single_wall_post();
	        return;
	    }

		/**
		 * Fires before the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_front() ) :
			bp_displayed_user_front_template_part();

		elseif ( bp_is_user_activity() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );


		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_body' );

	}

}

/**
 * Get a unique instance of Youzer Profile.
 */
function yz_profile() {
	return Youzer_Profile::get_instance();
}

/**
 * Launch Youzer Account!
 */
yz_profile();
