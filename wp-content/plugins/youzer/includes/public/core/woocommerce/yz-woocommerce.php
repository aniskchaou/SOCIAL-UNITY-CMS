<?php

class Youzer_Woocommerce {

	public function __construct() {

		// Init Vars.
		$this->name = __( 'Shop', 'youzer' );
		$this->slug = yz_woocommerce_tab_slug();

		// Init Classes.
		$this->templates = new Youzer_WC_Templates();

		if ( is_user_logged_in() && apply_filters( 'yz_woocommerce_enable_redirects', true ) ) {
			$this->redirects = new Youzer_WC_Redirects();
		}

		$this->activity = new Youzer_WC_Activity();

		// Hooks.
		add_action( 'bp_setup_nav',  array( $this, 'setup_tabs' ) );
		add_action( 'bp_setup_admin_bar',  array( $this, 'topbar_menu' ), 300 );
		add_filter( 'yz_default_options', array( $this, 'default_options' ), 10 );

	}

	/**
	 * # Setup Tabs.
	 */
	function setup_tabs() {

		// $is_youzer_panel = is_youzer_panel();

		$bp = buddypress();

		$parent_slug = bp_displayed_user_domain() . $this->slug . '/';

		// Add Woocommerce Tab.
		bp_core_new_nav_item(
			array(
				'position' => 250,
				'slug' => $this->slug,
				'parent_url' => $parent_slug,
				'default_subnav_slug' => apply_filters( 'yz_profile_woocommerce_default_tab', 'cart' ),
				'parent_slug' => $bp->profile->slug,
				'name' => __( 'Shop' , 'youzer' ),
				'screen_function' =>  array( $this, 'screen' ),
			)
		);

		if ( bp_is_my_profile() && yz_is_woocommerce_tab() ) {
			$access = bp_core_can_edit_settings();
			$sub_tabs = yz_woocommerce_sub_tabs();

			foreach ( $sub_tabs as $key => $tab ) {
				bp_core_new_subnav_item( array(
						'slug' => $tab['slug'],
						'name' => $tab['name'],
						'parent_slug' => $this->slug,
						'parent_url' => $parent_slug,
						'position' => $tab['position'],
						'screen_function' => array( $this, 'screen' ),
						'user_has_access' => $access
					)
				);
			}

		}
	}

	/**
	 * Woocommerce Screen.
	 **/
	function screen() {

		do_action( 'yz_woocommerce_screen' );

	    add_action( 'bp_template_content', array( $this, 'get_user_woocommerce_template' ) );

	    // Load Tab Template
	    bp_core_load_template( 'buddypress/members/single/plugins' );

	}

	/**
	 * Get Woocommerce Tab Content.
	 */
	function get_user_woocommerce_template() {
		bp_get_template_part( 'members/single/woocommerce' );
	}

	/**
	 * Add Top Bar Menu.
	 */
	function topbar_menu() {

		if ( ! apply_filters( 'yz_enable_woocommerce_top_bar_menu', true ) ) {
			return;
		}

		global $wp_admin_bar, $bp;

		if ( ! bp_use_wp_admin_bar() || defined( 'DOING_AJAX' ) ) {
			return;
		}

		$user_domain = bp_loggedin_user_domain();
		$item_link = trailingslashit( $user_domain . $this->slug );

		// Get Main Menu
		$wp_admin_bar->add_menu(
			array(
				'parent'  => $bp->my_account_menu_id,
				'id'      => 'yz-wc-shop',
				'title'   => $this->name,
				'href'    => trailingslashit( $item_link ),
				'meta'    => array( 'class' => 'menupop' )
			)
		);

		// // Get Woocommerce Tabs.
		$tabs = yz_woocommerce_sub_tabs();

		if ( ! is_admin() && WC()->cart->get_cart_contents_count() == 0 ) {
			unset( $tabs['checkout'] );
		}

		// Get Submenu.
	    foreach ( $tabs as $slug => $tab ) {
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'yz-wc-shop',
					'id'     => 'yz-wc-' . $slug,
					'title'  => $tab['name'],
					'href'   => trailingslashit( $item_link ) . $slug
				)
			);
	    }

	}

	/**
	 * # Default Options
	 */
	function default_options( $options ) {

	    // Options.
	    $yzsq_options = array(
			'yz_shop_tab_icon' => 'fas fa-shopping-cart',
			'yz_enable_wc_product_activity' => 'on',
			'yz_enable_wc_purchase_activity' => 'off',
			'yz_ctabs_' . $this->slug . '_cart_icon' => 'fas fa-shopping-cart',
			'yz_ctabs_' . $this->slug . '_checkout_icon' => 'far fa-credit-card',
			'yz_ctabs_' . $this->slug . '_track_icon' => 'fas fa-truck-moving',
			'yz_ctabs_' . $this->slug . '_orders_icon' => 'fas fa-shopping-basket',
			'yz_ctabs_' . $this->slug . '_downloads_icon' => 'fas fa-download',
			'yz_ctabs_' . $this->slug . '_edit-address_icon' => 'fas fa-address-card',
			'yz_ctabs_' . $this->slug . '_payment-methods_icon' => 'fas fa-credit-card',
			'yz_ctabs_' . $this->slug . '_edit-account_icon' => 'far fa-user-circle',
			'yz_ctabs_' . $this->slug . '_subscriptions_icon' => 'fas fa-clipboard-list',
	    );

	    return yz_array_merge( $options, $yzsq_options );
	}

}