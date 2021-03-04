<?php

class Youzer_Account {

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
		}

		return self::$instance;
	}

	function __construct() {

		// Get Settings.
		$this->settings();

		// Save Settings.
		$this->save_settings();

		// Delete Account
		add_action( 'bp_actions', array( $this, 'delete_user_account' ) );
	}

	/**
	 * Settings.
	 */
	function settings()  {

		// Rename Account Tabs.
		add_action( 'bp_actions', array( $this, 'rename_tabs' ), 10 );

		// Add Account Settings Pages.
		add_action( 'bp_actions', array( $this, 'account_setting_menus' ) );

		// Settings Sidebar Menu
		add_action( 'youzer_settings_menus', array( $this, 'settings_header' ) );

		// Change Icons.
		add_filter( 'yz_account_menu_icon', array( $this, 'get_account_menu_icon' ), 10, 2 );

		//  Settings Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'settings_scripts' ) );

		if ( bp_is_current_component( 'profile' ) ) {
			// Redirect Default Tab.
			add_action( 'bp_screens', array( $this, 'redirect_to_default_profile_settings_tab' ), 10 );
			// Get User Settings.
			add_action( 'youzer_profile_settings', array( $this, 'get_profile_settings' ) );
		}

		if ( bp_is_current_component( 'settings' ) ) {
			// Get Account Settings
			add_action( 'youzer_profile_settings', array( $this, 'get_account_settings' ) );
		}

		if ( bp_is_current_component( 'widgets' ) ) {
			// Get Widgets Settings
			add_action( 'youzer_profile_settings', array( $this, 'get_widgets_settings' ) );
		}

	    if ( 'on' == yz_option( 'yz_enable_account_scroll_button', 'on' ) ) {
			add_action( 'youzer_account_footer', 'yz_scroll_to_top' );
		}

	}

	/**
	 * Setup Widget Settings Pages.
	 */
	function account_setting_menus() {

	    if ( ! bp_core_can_edit_settings() ) {
	        return false;
	    }


    	global $bp;

    	$user_id = bp_displayed_user_id();

	    if ( apply_filters( 'yz_create_widgets_settings_page', true ) ) {

		    // Profile Widgets Settings.
		    bp_core_new_nav_item(
		        array(
		            'position' => 60,
		            'slug' => 'widgets',
		            'parent_slug' => $bp->profile->slug,
		            'show_for_displayed_user' => bp_core_can_edit_settings(),
		            'default_subnav_slug' => 'widgets',
		            'name' => __( 'Widgets Settings', 'youzer' ),
		            'screen_function' => array( $this, 'widgets_settings_screen' ),
		            'parent_url' => bp_loggedin_user_domain() . '/widgets/'
		        )
		    );

		    // Add Widgets Pages The Settings Page List.
		    $widgets_url = yz_get_widgets_settings_url();

		    foreach ( yz_widgets()->get_settings_widgets() as $page ) {
		        bp_core_new_subnav_item( array(
		                'slug' => $page['id'],
		                'name' => $page['name'],
		                'parent_slug' => 'widgets',
		                'parent_url' => $widgets_url,
		                'screen_function' => array( $this, 'load_template' ),
		            )
		        );
		    }

	    }

	    if ( bp_is_active( 'settings' ) ) {

		    if ( apply_filters( 'yz_create_account_settings_page', true ) ) {

			    $settings_url = bp_core_get_user_domain( $user_id ) . bp_get_settings_slug();
			    $settings_slug = bp_get_settings_slug();
			    $is_super_admin = is_super_admin( bp_displayed_user_id() );

			    foreach ( $this->account_settings_pages() as $slug => $page ) {

			        // Get Navbar Args
			        $nav_args = array(
			            'slug' => $slug,
			            'name' => $page['name'],
			            'parent_url' => $settings_url,
			            'parent_slug' => $settings_slug,
			            'screen_function' => array( $this, 'load_template' ),
			        );

			        if ( 'delete-account' == $slug ) {
			            $nav_args['user_has_access'] = ! $is_super_admin;
			        }

			        bp_core_new_subnav_item( $nav_args );
			    }

		    }
	    }

	    if ( bp_is_active( 'xprofile' ) && apply_filters( 'yz_create_profile_settings_page', true ) ) {

		    foreach ( $this->profile_settings_pages() as $slug => $page ) {

		        bp_core_new_subnav_item( array(
		                'slug' => $slug,
		                'name' => $page['name'],
		                'position' => $page['order'],
		                'parent_slug' => bp_get_profile_slug(),
		                'parent_url' => yz_get_profile_settings_url(),
		                'screen_function' => array( $this, 'load_template' ),
		            )
		        );
		    }

		}

		unset( $page );

	}

	/**
	 * Load Template.
	 */
	function load_template() {
	    bp_core_load_template( 'buddypress/members/single/plugins' );
	}

	/**
	 * Redirect Default.
	 */
	function redirect_to_default_profile_settings_tab() {

		if ( bp_current_action() == 'public' && ! bp_action_variable( 1 ) ) {
			$redirect_url = yz_get_profile_settings_url();
			bp_core_redirect( trailingslashit( $redirect_url ) );
		}

	}

	/**
	 * # Account Settings Pages.
	 */
	function get_account_menu_icon( $icon = null, $slug ) {

		switch ( $slug ) {

			case 'general':
				$icon = 'fas fa-lock';
				break;

			case 'notifications':
				$icon = 'fas fa-bell';
				break;

			case 'change-username':
				$icon = 'fas fa-sync-alt';
				break;

			case 'account-deactivator':
				$icon = 'fas fa-user-cog';
				break;

			case 'blocked':
			case 'blocked-members':
				$icon = 'fas fa-ban';
				break;

		}

		return $icon;
	}

	/**
	 * # Account Settings Pages.
	 */
	function account_settings_pages() {

		// Init Pages.
		$pages = array();

		// Add Spam Account nav item.
		if ( bp_current_user_can( 'bp_moderate' ) && ! bp_is_my_profile() ) {

			$pages['capabilities'] = array(
				'name'	=> __( 'Capabilities Settings', 'youzer' ),
				'icon'	=> 'fas fa-wrench',
				'order'	=> 50
			);

		}

	    if ( 'on' == yz_option( 'yz_allow_private_profiles', 'off' ) || 'on' == yz_option( 'yz_enable_woocommerce', 'off' ) ) {
	    	$pages['account-privacy'] = array(
				'name'	=> __( 'Account Privacy', 'youzer' ),
				'icon'	=> 'fas fa-user-secret',
				'order'	=> 60
			);
	    }

	    if ( apply_filters( 'bp_settings_show_user_data_page', true ) ) {

			$pages['data'] = array(
				'name'	=> __( 'Export Data', 'youzer' ),
				'icon'	=> 'fas fa-file-export',
				'order'	=> 80
			);

	    }

	    if ( ( ! bp_disable_account_deletion() && bp_is_my_profile() ) || bp_current_user_can( 'delete_users' ) ) {
	    	if ( ! is_super_admin( bp_displayed_user_id() ) ) {
		    	$pages['delete-account'] = array(
					'name'	=> __( 'Delete Account', 'youzer' ),
					'icon'	=> 'fas fa-trash-alt',
					'order'	=> 60
				);
	    	}
	    }

		// Filter
		$pages = apply_filters( 'yz_account_settings_pages', $pages );

	    return $pages;
	}

	/**
	 * # Profile Settings Pages.
	 */
	function profile_settings_pages() {

        if ( bp_is_active( 'xprofile' ) ) {

            // Fields Groups.
            $groups = bp_profile_get_field_groups();
            $i = 1;
            foreach ( $groups as $group ) {

                // Hide Empty Fields Groups
                if ( count( $group->fields ) <= 0 ) {
                    continue;
                }

                $group_slug = 'edit/group/' . $group->id;

                // Prepare Item Data.
                $page_item = array(
                    'name'  => $group->name,
                    'id'   => $group->name,
                    'icon'  => yz_get_xprofile_group_icon( $group->id ),
                    'order' => 10 * $i
                );

                // Add Groups Pages List.
                $pages[ $group_slug ] = $page_item;
                $i++;
            }

        }

        if ( isset( $pages['change-avatar'] ) ) {
        	unset( $pages['change-avatar'] );
        }

        if ( isset( $pages['change-cover-image'] ) ) {
        	unset( $pages['change-cover-image'] );
        }

        if ( apply_filters( 'yz_display_user_account_profile_avatar_cover_pages', false ) ) {

	        if ( buddypress()->avatar->show_avatars ) {

		        $pages['change-avatar'] = array(
		            'name'  => __( 'Profile Picture', 'youzer' ),
		            'icon'  => 'fas fa-user-circle',
		            'order' => 200
		        );

	        }

	        if ( bp_displayed_user_use_cover_image_header() ) {

		        $pages['change-cover-image'] = array(
		            'name'  => __( 'Profile Cover', 'youzer' ),
		            'icon'  => 'fas fa-camera-retro',
		            'order' => 210
		        );

	        }
        }

        $pages['social-networks'] = array(
            'name'  => __( 'Social Networks', 'youzer' ),
            'icon'  => 'fas fa-share-alt',
            'order' => 230
        );

        // Filter
        $pages = apply_filters( 'yz_profile_settings_pages', $pages );

        return $pages;
	}

	/**
	 * # Profile Settings Menu.
	 */
	function profile_menu() {

		// Get Menu Data.
		$menu_settings = array(
			'slug'		=> 'profile',
			'menu_list'  => $this->profile_settings_pages(),
			'menu_title' => __( 'Profile Settings', 'youzer' )
		);

		$this->get_menu( $menu_settings, 'profile' );

	}

	/**
	 * # Account Settings Menu.
	 */
	function account_menu() {

		// Get Menu Data.
		$menu_settings = array(
			'slug'		=> 'settings',
			'menu_list'  => $this->account_settings_pages(),
			'menu_title' => __( 'Account Settings', 'youzer' )
		);

		$this->get_menu( $menu_settings, 'settings' );

	}

	/**
	 * # Widgets Settings Menu.
	 */
	function widgets_menu() {

		// Get Widgets Menu List.
		$menu_list = yz_widgets()->get_settings_widgets();

		// Filter.
		$menu_list = apply_filters( 'account_widgets_settings_pages', $menu_list );

		// Prepare Account Menu List
		$menu_settings = array(
			'slug'		 => 'widgets',
			'menu_title' => __( 'Widgets Settings', 'youzer' ),
			'menu_list'	 => $menu_list
		);

		// Print Menu's
		$this->get_menu( $menu_settings, 'widgets' );

	}

	/**
	 * Convert Widgets to Pages.
	 */
	function convert_widgets_to_pages( $widgets ) {

		$pages = null;

		foreach ( $widgets as $widget ) {

			// Get Page Data.
			$pages[ $widget['id'] ] = array(
				'name' => $widget['name'],
				'icon' => $widget['icon']
			);

		}

		return $pages;
	}

	/**
	 * # Menu Content
	 */
	function get_menu( $args, $current_component ) {

		// Get Menu.
		$menu = $args['menu_list'];

		// Get Current Page.
		$current = bp_current_action();

		// Get Current Widget Name.
		if ( 'widgets' == $current_component ) {
			$current_widget = bp_current_action() && bp_current_action() != bp_current_component() ? bp_current_action() : $menu[0]['id'];
			$menu = $this->convert_widgets_to_pages( $menu );
		} elseif ( 'edit' == $current ) {

	        // Get Widget Name.
	        $current_widget = 'edit/group/' . bp_get_current_profile_group_id();

		} else {
			$current_widget = $current;
		}

	    // Get Buddypress Variables.
	    $bp = buddypress();

	    // Get Tab Navigation  Menu
	    $account_nav = $bp->members->nav->get_secondary( array( 'parent_slug' => $current_component ) );

	    if ( empty( $account_nav ) ) {
	    	return;
	    }

	    // Show Menu
	    $show_menu = bp_is_current_component( $current_component ) ? 'yz-account-menus yz-show-account-menus' : 'yz-account-menus';

	    echo "<div class='$show_menu'>";
	    echo "<ul>";

	    // Hide Following Pages For Menus.
		$hide_pages = array( 'classic', 'home', 'social-networks', 'change-avatar', 'change-cover-image' );

	  	$user_id = bp_displayed_user_id();

	    // Get Menu.
		foreach ( $account_nav as $page ) {

			// Get Page Slug.
			$slug = $page['slug'];

			// Hide Pages & Hide Tab if user has no access
	        if ( in_array( $slug, $hide_pages ) || empty( $page['user_has_access'] ) || 'edit' == $slug  ) {
	        	continue;
	        }

			// Get Menu Data.
			$menu_data = isset( $menu[ $slug ] ) ? $menu[ $slug ] : null;

			// Get Menu Class Name.
			$menu_class = ( $current_widget == $slug ) ? 'class="yz-active-menu"' : null;

			// Get Page Url.
			if ( isset( $page['group_slug'] ) ) {
				$page_url = yz_get_profile_settings_url( $page['group_slug'] );
			} elseif ( 'settings' == $args['slug'] ) {
				$page_url =  bp_core_get_user_domain( $user_id ) . bp_get_settings_slug() . '/' . $slug ;
			} elseif ( 'profile' == $args['slug'] ) {
				$page_url = yz_get_profile_settings_url( $slug );
			} elseif ( 'widgets' == $args['slug'] ) {
				$page_url = yz_get_widgets_settings_url( $slug );
			}

			// Filter URL.
			$page_url = apply_filters( 'yz_account_menu_page_url', $page_url, $slug );

			// Get Icon
			$icon = isset( $menu_data['icon'] ) ? $menu_data['icon'] : 'fas fa-cogs';

			// Filter Icon.
			$icon = apply_filters( 'yz_account_menu_icon', $icon, $slug );

			$class = str_replace( '/', '-', $slug );

			echo '<li class="yz-' . $class .  '">';
			echo '<i class="'. $icon. '"></i>';
			echo "<a $menu_class href='$page_url'>{$page['name']}</a>";
			echo '</li>';

		}

	    echo '</ul></div>';

	}

	/**
	 * # Settings Header.
	 */
	function settings_header() {

		// Get Data.
		$user_id = bp_displayed_user_id();
		$account_pages = $this->get_account_main_menu();
		$icon_url = yz_get_profile_settings_url( 'change-avatar' );
		$member_year = date( 'Y', strtotime( yz_data( 'user_registered' ) ) );
		$profile_url = bp_core_get_user_domain( $user_id ) . bp_get_profile_slug() . '/';
		$header_buttons = apply_filters( 'yz_account_menu_header_buttons', array(
			'home' => array(
				'icon' => 'fas fa-home',
				'title' => __( 'Home', 'youzer' ),
				'url' => home_url()
			),
			'profile' => array(
				'icon' => 'fas fa-user',
				'title' => __( 'View Profile', 'youzer' ),
				'url' => bp_core_get_user_domain( $user_id )
			),
			'networks' => array(
				'icon' => 'fas fa-share-alt',
				'title' => __( 'Social Networks', 'youzer' ),
				'url' => $profile_url . 'social-networks'
			),
			'avatar' => array(
				'icon' => 'fas fa-user-circle',
				'title' => __( 'Profile Avatar', 'youzer' ),
				'url' => $profile_url . 'change-avatar'
			),
			'cover' => array(
				'icon' => 'fas fa-camera-retro',
				'title' => __( 'Profile Cover', 'youzer' ),
				'url' => $profile_url . 'change-cover-image'
			),
			'logout' => array(
				'url' => wp_logout_url(),
				'icon' => 'fas fa-power-off',
				'title' => __( 'Logout', 'youzer' )
			)
		) );

        if ( ! buddypress()->avatar->show_avatars ) {
        	if ( isset(  $header_buttons['avatar'] ) ) {
        		unset( $header_buttons['avatar'] );
        	}
        }

        if ( ! bp_displayed_user_use_cover_image_header() ) {
        	if ( isset(  $header_buttons['cover'] ) ) {
        		unset( $header_buttons['cover'] );
        	}
        }

        // if there's no networks don't show the networks form..
        $networks = yz_option( 'yz_social_networks' );

        if ( empty( $networks ) ) {
            unset( $header_buttons['networks'] );
        }

        $count = count( $header_buttons );

		?>

		<div class="yz-account-header">

			<div class="yz-account-head">
				<div class="yz-account-img">
					<?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'full' ) ); ?>

				</div>
				<div class="yz-account-head-content">
					<h2><?php echo bp_get_displayed_user_fullname(); ?></h2>
					<span><?php printf( esc_html__( 'Member since %1$s', 'youzer' ), $member_year ); ?></span>
				</div>
			</div>

			<div class="yz-head-buttons">
				<div class="yz-head-buttons-inner">
				<?php foreach ( $header_buttons as $key => $button ) :?>
					<div class="yz-button-item yz-<?php echo $key; ?>-button" style="width: <?php echo 100 / $count; ?>%;"><a href="<?php echo $button['url'] ?>" data-yztooltip="<?php echo $button['title']; ?>" ><i class="<?php echo $button['icon'] ?>"></i></a></div>
				<?php endforeach;?>
				</div>
			</div>

			<div class="yz-account-settings-menu">
				<?php foreach ( $account_pages as $key => $page ) : ?>

				<?php
					if ( isset( $page['visibility'] ) && false == $page['visibility'] ) {
						continue;
					}
				?>

				<div class="yz-account-menu">
	                <div class="yz-menu-icon"><i class="<?php echo $page['icon'] ?> <?php echo $page['class'] ?>"></i></div>
	                <div class="yz-menu-head">
	                    <div class="yz-menu-title"><?php echo $page['title']; ?></div>
	                    <div class="yz-menu-description"><?php echo $page['description']; ?></div>
	                </div>
	               	<div class="yz-arrow-bottom yzpc-show-fields"></div>
	            </div>
	            <?php
	            	if ( $key == 'profile' ) {
	            		$this->profile_menu();
	            	} elseif( $key == 'widgets' ) {
	            		$this->widgets_menu();
	            	} elseif( $key == 'settings' ) {
	            		$this->account_menu();
	            	}
	            ?>
				<?php  ?>
				<?php endforeach; ?>
			</div>

		</div>
		<?php
	}

	/**
	 * Get Profile Settings
	 */
	function get_profile_settings() {

	    // Get Current Sub Page.
		$page = bp_current_action();

	    switch ( $page ) {

			// Edit
			case 'edit':
	            $this->group_fields();
				break;

			case 'change-avatar':
	            $this->profile_picture();
				break;

			case 'change-cover-image':
	            $this->profile_cover();
				break;

			case 'social-networks':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-social-networks.php';
				yz_social_networks_widget_settings();
				break;

			default:
				bp_get_template_part( 'members/single/plugins' );
				break;
	    }
	}

	/**
	 * Account Page Main Menu
	 */
	function get_account_main_menu() {

		// Init Menu
		$menu =  array();

		$user_id = bp_displayed_user_id();

		// Profile Settings Page
		$profile = array( 'profile' => array(
			'icon' => 'fas fa-user-circle',
			'class' => 'yza-profile-settings',
			'visibility' =>  bp_is_active( 'xprofile' ) && apply_filters( 'yz_create_profile_settings_page',true ),
			// 'href' => yz_get_profile_settings_url(),
			'title' => __( 'Profile Settings', 'youzer' ),
			'description' => __( 'Profile Information Fields', 'youzer' )
		) );

		// Account Settings Page
		$account = array( 'settings' => array(
			'icon' => 'fas fa-cogs',
			// 'href' => bp_core_get_user_domain( $user_id ) . bp_get_settings_slug(),
			'class' => 'yza-account-settings',
			'visibility' => bp_is_active( 'settings' ) && apply_filters( 'yz_create_account_settings_page', true ),
			'title' => __( 'Account Settings', 'youzer' ),
			'description' => __( 'Email, Password, Notifications ...', 'youzer' )
		) );

		// Widgets Settings Page
		$widgets = array( 'widgets' => array(
			'icon' => 'fas fa-sliders-h',
			'class' => 'yza-widgets-settings',
			// 'href' => yz_get_widgets_settings_url(),
			'visibility' => apply_filters( 'yz_create_widgets_settings_page', true ),
			'title' => __( 'Widgets Settings', 'youzer' ),
			'description' => __( 'Profile Widgets Settings', 'youzer' )
		) );

		// Get Current Component.
        $current_component = bp_current_component();

        if ( $current_component == 'profile' ) {
        	$menu = array_merge( $profile, $account, $widgets );
        } elseif ( $current_component == 'settings' ) {
        	$menu = array_merge( $account, $profile, $widgets );
        } elseif ( $current_component == 'widgets' ) {
        	$menu = array_merge( $widgets, $profile, $account );
        }

		return apply_filters( 'yz_account_page_main_menu', $menu );

	}
	/**
	 * Get Account Settings
	 */
	function get_account_settings() {

	    switch ( bp_current_action() ) {

			case 'capabilities':
	            $this->user_capabilities();
				break;

			case 'delete-account':
	            $this->delete_account();
				break;

			case 'account-privacy':
	            $this->account_privacy();
				break;

			case 'general':
	            $this->general();
				break;

			case 'data':
	            $this->data();
	            break;

			case 'export-data':
	            $this->export_data();
				break;

			case 'notifications':
	            $this->notifications_settings();
				break;

			case 'change-username':
	            $this->change_username();
				break;

			case 'account-deactivator':
	            $this->account_deactivator();
				break;

			case 'blocked':
	            $this->members_block();
				break;

			default:
				bp_get_template_part( 'members/single/plugins' );
				break;
	    }
	}

	/**
	 * Get Widgets Settings
	 */
	function get_widgets_settings() {

	    // Get Current Sub Page.
		$page = bp_current_action();

		if ( empty( $page ) || $page == 'widgets' ) {

			// Widgets.
			$widgets = yz_widgets();

    		// Get Widgets Settings.
    		$widgets_menu = $widgets->get_settings_widgets();

    		// Set First Widget Form Menu as Default.
    		$default_widget = $widgets_menu[0]['id'];

    		// Get File Path.
    		$file = YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-' . str_replace( '_', '-', $default_widget ) . '.php';

    		if ( file_exists( $file ) ) {
				include $file;
    		}

			$function_name = 'yz_' . $default_widget . '_widget_settings';

			if ( is_callable( $function_name ) ) {
				$function_name();
			}

		}

	    switch ( $page ) {

			case 'slideshow':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-slideshow.php';
				yz_slideshow_widget_settings();
				break;

			case 'instagram':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-instagram.php';
				yz_instagram_widget_settings();
				break;

			case 'portfolio':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-portfolio.php';
				yz_portfolio_widget_settings();
				break;

			case 'services':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-services.php';
				yz_services_widget_settings();
				break;

			case 'about_me':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-about-me.php';
				yz_about_me_widget_settings();
				break;

			case 'project':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-project.php';
				yz_project_widget_settings();
				break;

			case 'flickr':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-flickr.php';
				yz_flickr_widget_settings();
				break;

			case 'skills':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-skills.php';
				yz_skills_widget_settings();
				break;

			case 'quote':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-quote.php';
				yz_quote_widget_settings();
				break;

			case 'video':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-video.php';
				yz_video_widget_settings();
				break;

			case 'link':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-link.php';
				yz_link_widget_settings();
				break;

			case 'post':
				include YZ_PUBLIC_CORE . 'widgets/settings/yz-settings-post.php';
				yz_post_widget_settings();
				break;

				// $widgets->get_widget_class( $page )->settings( $args );
				// break;

			default:
				do_action( 'yz_profile_widget_settings_content' );
				break;

		}

	}

	/**
	 * # Settings Actions.
	 */
	function rename_tabs() {

		if ( bp_is_active( 'settings' ) ) {


			$bp = buddypress();

			// Get Settings Slug.
			$settings_slug = bp_get_settings_slug();

			// Remove Settings Profile, General Pages
			bp_core_remove_subnav_item( $settings_slug, 'profile' );

			// Change Notifications Title from "Email" to "Notifications".
			$bp->members->nav->edit_nav(array('name' => __( 'Notifications', 'youzer' ) ), 'notifications', $settings_slug );
			$bp->members->nav->edit_nav(array('name' => __( 'Email & Password', 'youzer' ) ,  'position' => 1 ), 'general', $settings_slug );

		}

		// Remove Profile Public, Edit Pages
		bp_core_remove_subnav_item( bp_get_profile_slug(), 'public' );

	}

	/**
	 * Handles the deleting of a user.
	 */
	function delete_user_account() {

	    // Bail if not a POST action.
	    if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
	        return;

	    // Bail if no submit action.
	    if ( ! isset( $_POST['yz-delete-account-understand'] ) )
	        return;

	    // // Bail if not in settings.
	    if ( ! bp_is_settings_component() || 'delete-account' != bp_current_action() )
	        return false;

	    // 404 if there are any additional action variables attached
	    if ( bp_action_variables() ) {
	        bp_do_404();
	        return;
	    }

	    // Bail if account deletion is disabled.
	    if ( bp_disable_account_deletion() && ! bp_current_user_can( 'delete_users' ) ) {
	        return false;
	    }

	    // Nonce check.
	    check_admin_referer( 'delete-account' );

	    // Get username now because it might be gone soon!
	    $username = bp_get_displayed_user_fullname();

	    // Delete the users account.
	    if ( bp_core_delete_account( bp_displayed_user_id() ) ) {

	        // Add feedback after deleting a user.
	        bp_core_add_message( sprintf( __( '%s was successfully deleted.', 'youzer' ), $username ), 'success' );

	        // Redirect to the root domain.
	        bp_core_redirect( bp_get_root_domain() );
	    }
	}

	/**
	 * Activate Avatar Upload On Front Page.
	 */
	// function avatar_is_front_edit(){
	//     // Get Current Sub Page.
	//     if ( 'profile-picture' == bp_current_action() ) {
	//         add_filter( 'bp_avatar_is_front_edit', array( $this, 'change_avatar_template' ) );
	//     }
	// }

	// /**
	//  * Get Change Avatar Template
	//  */
	// function change_avatar_template( $template ) {
	//     return 'members/single/plugins';
	// }

	/*
	 * # Account Scripts .
	 */
	function settings_scripts() {

		// Custom Styling.
		yz_styling()->custom_snippets( 'account' );

	    // Widgets Builder
	    wp_register_script( 'yz-builder', YZ_PA . 'js/ukai-builder.min.js', array( 'jquery-ui-sortable', 'jquery-ui-draggable' ), YZ_Version, true );
	    wp_localize_script( 'yz-builder', 'Youzer_Builder', apply_filters( 'yz_account_builder', array( 'drag_widgets_items' => 1) ) );

        // Load Profile Settings CSS.
        wp_enqueue_style( 'yz-account', YZ_PA . 'css/yz-account-style.min.css', array( 'yz-panel' ), YZ_Version );

	    // Admin Panel Script
	    wp_enqueue_style( 'yz-panel', YZ_AA . 'css/yz-panel.min.css', array(), YZ_Version );
	    wp_enqueue_script( 'yz-panel', YZ_AA . 'js/yz-settings-page.min.js', array( 'jquery' ), YZ_Version, true );

        // Load Profile Settings Script
        wp_enqueue_script( 'yz-account', YZ_PA . 'js/yz-account.min.js', array( 'jquery' ), YZ_Version, true );
	    wp_localize_script( 'yz-account', 'Yz_Account', array( 'default_img' => YZ_PA . 'images/default-img.png' ) );

	}

	/**
	 * Save Widgets Settings.
	 */
	function save_settings() {

	    // If its xprofile fields exit.
	    if ( isset( $_POST['field_ids'] ) ) {
	        return;
	    }

	    // if its not Profile Settings go out.
	    if ( isset( $_POST['action'] ) && 'youzer_profile_settings_save_data' == $_POST['action'] ) {

		    // Check Nonce Security
		    check_admin_referer( 'yz_nonce_security', 'security' );

		    // Before Save Settings Action.
		    do_action( 'youzer_before_save_user_settings', $_POST );

		    // Get Form Data
		    $data = $_POST;

		    unset( $data['security'], $data['action'] );

		    $die = isset( $_POST['die'] ) ? true : false;

		    // Call Update Profile Settings Function
		    if ( isset( $data['youzer_options'] ) ) {
		        $this->save_widgets_settings( $data['youzer_options'] );
		    }

		    // Save Notification Settings
		    if ( isset( $data['youzer_notifications'] ) ) {
		        $this->save_notification( $data['youzer_notifications'] );
		    }

		    // Save Skills
		    if ( isset( $data['yz_data']['yz-skills-data'] ) ) {
		        $this->save_widget_items(
		            array(
		                'option_name' => 'youzer_skills',
		                'max_items'   => 'yz_wg_max_skills',
		                'items'       => isset( $data['youzer_skills'] ) ? $data['youzer_skills'] : null,
		                'data_keys'   => array( 'title', 'barpercent' )
		            )
		        );
		    }

		    // Save Services.
		    if ( isset( $data['yz_data']['yz-services-data'] ) ) {
		        $this->save_widget_items(
		            array(
		                'option_name' => 'youzer_services',
		                'max_items'   => 'yz_wg_max_services',
		                'items'       => isset( $data['youzer_services'] ) ? $data['youzer_services'] : null,
		                'data_keys'   => array( 'title' )
		            )
		        );
		    }

		    // Save Portfolio
		    if ( isset( $data['yz_data']['yz-portfolio-data'] ) ) {

		    	// Get Old Portfolio.
		    	$old_portfolio = yz_data( 'youzer_portfolio' );

		    	// Get Portfolio Items.
		    	$portfolio_items = isset( $data['youzer_portfolio'] ) ? $data['youzer_portfolio'] : null;

		        // Remove Deleted Attachments.
		        $this->remove_deleted_attachments( $old_portfolio, $portfolio_items );

		        $this->save_widget_items(
		            array(
		                'items'       => $portfolio_items,
		                'option_name' => 'youzer_portfolio',
		                'max_items'   => 'yz_wg_max_portfolio_items',
		                'data_keys'   => array( 'image' )
		            ),
		            $die
		        );

		    }

		    // Save SlideShow
		    if ( isset( $data['yz_data']['yz-slideshow-data'] ) ) {

		    	// Get Old Slideshows.
		    	$old_slideshow = yz_data( 'youzer_slideshow' );

		    	// Get Slideshows Items.
		    	$slideshow_items = isset( $data['youzer_slideshow'] ) ? $data['youzer_slideshow'] : null;

		        // Remove Deleted Attachments.
		        $this->remove_deleted_attachments( $old_slideshow, $slideshow_items );

		        $this->save_widget_items(
		            array(
		                'items'       => $slideshow_items,
		                'option_name' => 'youzer_slideshow',
		                'max_items'   => 'yz_wg_max_slideshow_items',
		                'data_keys'   => array( 'image' )
		            ),
		            $die
		        );
		    }

		    // After Save Settings Action.
		    do_action( 'youzer_after_save_user_settings', $data );

		    if ( ! $die ) {
			    $this->redirect( 'success', __( 'Changes Saved.', 'youzer' ) );
		    }

		    die();
	    }

	}

	/*
	 * Remove Deleted Attachments
	 **/
	function remove_deleted_attachments( $old_data = null, $new_data = null ) {

        if ( empty( $old_data ) || $old_data == $new_data ) {
        	return;
        }

    	$old_images = array();
    	$new_images = array();

    	foreach ( $old_data as $old_item ) {
    		$old_images[] = $old_item['image'];
    	}

    	foreach ( $new_data as $new_item ) {
    		$new_images[] = $new_item['image'];
    	}

    	$removed_images = array_diff( $old_images, $new_images );

    	if ( ! empty( $removed_images ) ) {
    		foreach ( $removed_images as $attachment_id ) {
    			wp_delete_attachment( $attachment_id );
    		}
    	}

	}

	/**
	 * Save Notification
	 **/
	function save_notification( $notifications ) {

	    // Init New Array();
	    $bp_notification = array();

	    // Change 'On' To 'Yes'.
	    foreach ( $notifications as $key => $value ) {

	        // Get Notification Key
	        $notification_key = str_replace( 'yz_', '', $key );

	        // Get Notification Value.
	        $bp_notification[ $notification_key ] = ( 'on' == $value ) ? 'yes': 'no';

	    }

	    // Update Buddypress Notification Settings.
	    bp_settings_update_notification_settings( bp_displayed_user_id(), (array) $bp_notification );

	    // Save Youzer Options
	    $this->save_widgets_settings( $notifications );
	}

	/**
	 * Save Widgets Settings.
	 */
	function save_widgets_settings( $profile_options ) {

	    if ( empty( $profile_options ) ) {
	        return false;
	    }

	    $user_id = bp_displayed_user_id();

	    // Remove Tags.
	    if ( isset( $profile_options['wg_project_title'] ) ) {

	        // Get Tags Fields.
	        $tags_fields = array( 'wg_project_tags', 'wg_project_categories' );

	        foreach ( $tags_fields as $tag_id ) {
	            if ( ! isset( $profile_options[ $tag_id ] ) ) {
	                delete_user_meta( $user_id, $tag_id );
	            }
	        }

	    }

	    foreach ( $profile_options as $option => $value ) {

	        if ( ! is_array( $value ) ) {
	            $the_value = stripslashes( $value );
	        } else {
	            $the_value = $value;
	        }

	        if ( isset( $option ) ) {

	            // Check For Empty
	            if ( is_array( $the_value ) && isset( $the_value['original'] ) && empty( $the_value['original'] ) ) {
	                $the_value = null;
	            }

	            if ( 'wg_flickr_account_id' == $option ) {

	                // Delete Flickr ID.
	                if ( empty( $the_value ) ) {
	                    $update_options = delete_user_meta( $user_id, 'wg_flickr_account_id' );
	                } else {
	                    // Check Flickr ID format
	                    if ( false === strpos( $the_value, '@N' ) ) {
	                        yz_account_redirect( 'error', __( 'Flickr ID format is invalid', 'youzer' ) );
	                    } else {
	                        // Update Flickr
	                        $update_options = update_user_meta( $user_id, $option, $the_value );
	                    }
	                }

	                if ( $update_options ) {
	                    do_action( 'yz_after_saving_account_options', $user_id, $option, $the_value );
	                }

	            } else {
	                // Update Options
	                $update_options = update_user_meta( $user_id, $option, $the_value );
	                if ( $update_options ) {
	                    do_action( 'yz_after_saving_account_options', $user_id, $option, $the_value );
	                }
	            }

	        } else {
	            delete_user_meta( $user_id, $option );
	        }

	    }

	}

	/**
	 * #  Save Options.
	 */
	function save_widget_items( $args, $die = null ) {

	    // Get User ID
	    $user_id = bp_displayed_user_id();

	    // Get items Data.
	    $items = ! empty( $args['items'] ) ? $args['items'] : null;

	    if ( empty( $items ) ) {
	        $update_option = delete_user_meta( $user_id, $args['option_name'] );
	    } else {

	        // Get Maximum Number OF Allowed Items
	        $max_items = yz_options( $args['max_items'] );

	        // Re-order & Remove Empty Items
	        $items = $this->filter_items( $items, $args['data_keys'] );

	        // Save Options
	        if ( count( $items ) <= $max_items ) {
	            $update_option = update_user_meta( $user_id, $args['option_name'], $items );
	        }

	    }

	    if ( $update_option ) {
	        // Hook
	        do_action( 'yz_account_save_widget_item', $user_id, $args['option_name'], $items );
	        // Redirect.
	        if ( ! $die ) {
	            $this->redirect( 'success', __( 'Changes Saved.', 'youzer' ) );
	        }
	    }

	}

	/**
	 * #  Re-order & Remove Empty Items.
	 */
	function filter_items( $items, $keys ) {

	    // Re-Order Items
	    $items = array_combine( range( 1, count( $items ) ), array_values( $items ) );

	    // Remove Empty items
	    foreach ( $items as $item_key => $item ) {
	        foreach ( $keys as $key ) {
	            if ( empty( $item[ $key ] ) ) {
	                unset( $items[ $item_key ] );
	            }
	        }
	    }

	    return $items;
	}

	/**
	 * Get Widgets Settings Tab Content
	 */
	function widgets_settings_screen() {

	    // Call Posts Tab Content.
	    add_action( 'bp_template_content', array( $this, 'get_widgets_settings' ) );

	    // Load Tab Template
	    bp_core_load_template( 'buddypress/members/single/plugins' );

	}
	/**
	 * Redirect Page.
	 */
	function redirect( $action, $msg ) {

	    // Get Reidrect page.
	    $redirect_to = ! empty( $redirect_to ) ? $redirect_to : yz_get_current_page_url();

	    // Add Message.
	    bp_core_add_message( $msg, $action );

	    // Redirect User.
	    bp_core_redirect( $redirect_to );

	}

    /**
     * # Profile Picture Settings.
     */
    function profile_picture() {

        wp_enqueue_style( 'yz-bp-uploader' );

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Profile Picture', 'youzer' ),
                'id'    => 'profile-picture',
                'icon'  => 'fas fa-user-circle',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-uploader-change-item yz-change-avatar-item">';
        bp_get_template_part( 'members/single/profile/change-avatar' );
        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # User Capabilities Settings.
     */
    function user_capabilities() {

        global $Yz_Settings;

        do_action( 'bp_before_member_settings_template' );

        $Yz_Settings->get_field(
            array(
                'form_action'   => bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/',
                'title'         => __( 'User Capabilities Settings', 'youzer' ),
                'form_name'     => 'account-capabilities-form',
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit',
                'id'            => 'capabilities-settings',
                'icon'          => 'fas fa-wrench',
                'type'          => 'open',
            )
        );

        bp_get_template_part( 'members/single/settings/capabilities' );

        $Yz_Settings->get_field(
            array(
                'type' => 'close',
                'hide_action' => true,
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit'
            )
        );

        do_action( 'bp_after_member_settings_template' );

    }

    /**
     * # Profile Fields Group Settings.
     */
    function group_fields() {

        global $Yz_Settings, $group;

        $group_data = BP_XProfile_Group::get(
            array( 'profile_group_id' => bp_get_current_profile_group_id() )
        );

        $Yz_Settings->get_field(
            array(
                'icon'  => yz_get_xprofile_group_icon( $group_data[0]->id ),
                'title' => $group_data[0]->name,
                'id'    => 'profile-picture',
                'type'  => 'open'
            )
        );

        bp_get_template_part( 'members/single/profile/edit' );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Account Privacy Settings.
     */
    function account_privacy() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Account Privacy', 'youzer' ),
                'id'    => 'account-privacy',
                'icon'  => 'fas fa-user-secret',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Private Account', 'youzer' ),
                'desc'  => __( 'Make your profile private, only friends can access.', 'youzer' ),
                'id'    => 'yz_enable_private_account',
                'type'  => 'checkbox',
                'std'   => 'off',
            ), true
        );

        if ( function_exists( 'yz_is_woocommerce_active' ) && yz_is_woocommerce_active() ) {
            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Activity Stream Purchases', 'youzer' ),
                    'desc'  => __( 'Post my purchases in the activity stream.', 'youzer' ),
                    'id'    => 'yz_wc_purchase_activity',
                    'type'  => 'checkbox',
                    'std'   => apply_filters( 'yz_wc_purchase_activity', 'on' ),
                ), true, 'youzer_options'
            );
        }

        do_action( 'yz_user_account_privacy_settings', $Yz_Settings );

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Delete Account Settings.
     */
    function data() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Export Data', 'youzer' ),
                'id'    => 'export-data',
                'icon'  => 'fas fa-file-export',
                'type'  => 'bpDiv'
            )
        );

        bp_get_template_part( 'members/single/settings/data' );

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # Delete Account Settings.
     */
    function delete_account() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Delete Account', 'youzer' ),
                'id'    => 'delete-account',
                'icon'  => 'fas fa-trash-alt',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-delete-account-item">';
        bp_get_template_part( 'members/single/settings/delete-account' );
        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # Profile Notifications Settings.
     */
    function notifications_settings() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Notifications Settings', 'youzer' ),
                'id'    => 'notifications-settings',
                'icon'  => 'fas fa-bell',
                'type'  => 'open'
            )
        );

        // # Activity Notifications.

        if ( bp_is_active( 'activity' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Mentions Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member mentions me in a post', 'youzer' ),
                    'id'    => 'yz_notification_activity_new_mention',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Replies Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member replies to a post or comment I have posted", 'youzer' ),
                    'id'    => 'yz_notification_activity_new_reply',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Messages Notifications.

        if ( bp_is_active( 'messages' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Messages Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member sends me a new message", 'youzer' ),
                    'id'    => 'yz_notification_messages_new_message',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Friends Notifications.

        if ( bp_is_active( 'friends' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Friendship Requested Notifications', 'youzer' ),
                    'desc'  => __( "Email me when a member sends me a friendship request", 'youzer' ),
                    'id'    => 'yz_notification_friends_friendship_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Friendship Accepted Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member accepts my friendship request', 'youzer' ),
                    'id'    => 'yz_notification_friends_friendship_accepted',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        // # Groups Notifications.

        if ( bp_is_active( 'groups' ) ) :

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Invitations Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member invites me to join a group', 'youzer' ),
                    'id'    => 'yz_notification_groups_invite',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group information Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a group information is updated', 'youzer' ),
                    'id'    => 'yz_notification_groups_group_updated',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Admin Promotion Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when I am promoted to a group administrator or moderator', 'youzer' ),
                    'id'    => 'yz_notification_groups_admin_promotion',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Join Group Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when a member requests to join a private group for which I am an admin', 'youzer' ),
                    'id'    => 'yz_notification_groups_membership_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Group Membership Request Notifications', 'youzer' ),
                    'desc'  => __( 'Email me when my request to join a group has been approved or denied', 'youzer' ),
                    'id'    => 'yz_notification_membership_request_completed',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzer_notifications'
            );

        endif;

        $Yz_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * # Profile Cover Settings.
     */
    function profile_cover() {

        // Cover Image Uploader Script.
        wp_enqueue_style( 'yz-bp-uploader' );
        bp_attachments_enqueue_scripts( 'BP_Attachment_Cover_Image' );

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Profile Cover', 'youzer' ),
                'id'    => 'profile-cover',
                'icon'  => 'fas fa-camera-retro',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="yz-uploader-change-item yz-change-cover-item">';
        bp_get_template_part( 'members/single/profile/change-cover-image' );
        echo '</div>';

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * # Password Settings.
     */
    function general() {

        global $Yz_Settings;


        /**
         * Fires after the display of the submit button for user general settings saving.
         *
         * @since 1.5.0
         */
        do_action( 'bp_core_general_settings_after_submit' );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Email & Password', 'youzer' ),
                'id'    => 'change-password',
                'icon'  => 'fas fa-lock',
                'type'  => 'open'
            )
        );

        if ( ! is_super_admin() ) {

            $Yz_Settings->get_field(
                array(
                    'title' => __( 'Current Password', 'youzer' ),
                    'desc'  => __( 'Required to update email or change current password', 'youzer' ),
                    'id'    => 'pwd',
                    'no_options' => true,
                    'type'  => 'password'
                ), true
            );

        }

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Account Email', 'youzer' ),
                'desc'  => __( 'Change your account email', 'youzer' ),
                'std'   => bp_get_displayed_user_email(),
                'id'    => 'email',
                'no_options' => true,
                'type'  => 'text' ), true
            );


        $Yz_Settings->get_field(
            array(
                'title' => __( 'New Password', 'youzer' ),
                'desc'  => __( 'Type your new password', 'youzer' ),
                'id'    => 'pass1',
                'no_options' => true,
                'type'  => 'password' ), true
            );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Confirm Password', 'youzer' ),
                'desc'  => __( 'Confirm your new password', 'youzer' ),
                'id'    => 'pass2',
                'no_options' => true,
                'type'  => 'password'
            ), true
        );

        wp_nonce_field( 'bp_settings_general' );

        /**
         * Fires before the display of the submit button for user general settings saving.
         *
         * @since 1.5.0
         */
        do_action( 'bp_core_general_settings_before_submit' );

        $Yz_Settings->get_field( array( 'type' => 'close', 'button_name' => 'submit', 'hide_action' => true ) );

    }

    /**
     * Block Members Plugin.
     */
    function members_block() {

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Block Members', 'youzer' ),
                'id'    => 'block-member',
                'icon'  => 'fas fa-ban',
                'type'  => 'bpDiv'
            )
        );

        bp_my_blocked_members_screen();

        $Yz_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * Change Username Plugin.
     */
    function change_username() {

        $bp = buddypress();

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Change Username', 'youzer' ),
                'button_name' => 'change_username_submit',
                'id'    => 'change-username',
                'form_name' => 'username_changer',
                'icon'  => 'fas fa-sync-alt',
                'type'  => 'open'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Current Username', 'bp-username-changer' ),
                'desc'  => __( 'This is your current username', 'bp-username-changer' ),
                'id'    => 'current_user_name',
                'no_options' => true,
                'type'  => 'text',
                'disabled' => true,
                'std'   => esc_attr( $bp->displayed_user->userdata->user_login )
            ), true
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'New Username', 'bp-username-changer' ),
                'desc'  => __( 'Enter the new username of your choice', 'bp-username-changer' ),
                'id'    => 'new_user_name',
                'no_options' => true,
                'type'  => 'text'
            ), true
        );

        wp_nonce_field( 'bp-change-username' );

        $Yz_Settings->get_field( array( 'type' => 'close', 'button_name' => 'change_username_submit', 'hide_action' => true ) );

    }

    /**
     * Buddypress Deactivator Plugin.
     */
    function account_deactivator() {

        $user_id = bp_displayed_user_id();

        // not used is_displayed_user_inactive to avoid conflict.
        $is_inactive = bp_account_deactivator()->is_inactive( $user_id ) ? 1 : 0;

        if ( $is_inactive ) {
            $class= 'inactive';
            $message = __( 'Activate your account', 'bp-deactivate-account' );
            $status  = __( 'Deactivated', 'bp-deactivate-account' );
            update_user_meta( bp_displayed_user_id(), '_bp_account_deactivator_status', 0 );

        } else {

            $class= 'active';
            $message = __( 'Deactivate your account', 'bp-deactivate-account' );
            $status  = __( 'Active', 'bp-deactivate-account' );
            update_user_meta( bp_displayed_user_id(), '_bp_account_deactivator_status', 1 );
        }

        $bp = buddypress();

        global $Yz_Settings;

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Account Status', 'bp-deactivate-account' ),
                'button_name' => 'bp_account_deactivator_update_settings',
                'id'    => 'bp-account-deactivator-settings',
                'form_name' => 'bp-account-deactivator-settings',
                'icon'  => 'fas fa-user-cog',
                'button_value' => 'save',
                'type'  => 'open'
            )
        );

        echo '<div class="yz-bp-deactivator-' . $class . '">' . __( 'Your current account status: ', 'bp-deactivate-account' ) . '<span>' . $status . '</span></div>';

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Update Status', 'bp-deactivate-account' ),
                'desc'  => __( 'If you select deactivate, you will be hidden from the users.', 'bp-deactivate-account' ),
                'id'    => '_bp_account_deactivator_status',
                'opts'  => array( '1' => __( 'Activate', 'bp-deactivate-account' ), '0' => __( 'Deactivate', 'bp-deactivate-account' )),
                'no_options' => true,
                'type'  => 'radio',
            ), true
        );

        wp_nonce_field( 'bp-account-deactivator' );

        $Yz_Settings->get_field( array( 'type' => 'close', 'button_name' => 'bp_account_deactivator_update_settings', 'hide_action' => true, 'button_value' => 'save' ) );

    }
}

/**
 * Get a unique instance of Youzer Account.
 */
function yz_account() {
	return Youzer_Account::get_instance();
}

/**
 * Launch Youzer Account!
 */
yz_account();
