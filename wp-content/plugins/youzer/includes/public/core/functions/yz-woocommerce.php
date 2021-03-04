<?php

/**
 * Init Woocommerce
 */
function yz_load_woocommerce_files() {

    // if ( yz_is_woocommerce_active() ) {

    	// Init Actions
		add_action( 'bp_init', 'yz_is_cart_page' );
		add_action( 'bp_init', 'yz_is_checkout_page' );

    //     global $Youzer;
    //     require_once YZ_PUBLIC_CORE . 'woocommerce/yz-woocommerce.php';
    //     require_once YZ_PUBLIC_CORE . 'woocommerce/yz-wc-activity.php';
    //     require_once YZ_PUBLIC_CORE . 'woocommerce/yz-wc-templates.php';
    //     require_once YZ_PUBLIC_CORE . 'woocommerce/yz-wc-redirects.php';
    //     $Youzer->wc = new Youzer_Woocommerce();


    // }

}

// add_action( 'plugins_loaded', 'yz_load_woocommerce_files', 999 );

/**
 * Get Woomcommerce Supported Pages
 */
function yz_supported_wc_pages() {

    $wc_pages = array(
    	'cart' => wc_get_page_id( 'cart' ),
    	'checkout' => wc_get_page_id( 'checkout' ),
    	'myaccount' => wc_get_page_id( 'myaccount' )
    );

    return apply_filters( 'yz_supported_wc_pages', $wc_pages );

}

/**
 * Check if Woocommerce Integration Active.
 */
function yz_is_woocommerce_active() {

	$active = true;

    if ( ! class_exists( 'Woocommerce' ) || 'off' == yz_option( 'yz_enable_woocommerce', 'off' ) ) {
        $active = false;
    }

    return apply_filters( 'yz_is_woocommerce_active', $active );
}

/**
 * Init Woocommerce
 */
function yz_init_woocommerce() {

    if ( yz_is_woocommerce_tab() ) {

		// Activate Account
		add_filter( 'woocommerce_is_account_page', '__return_true' );

    	// Classes
		add_filter( 'body_class', 'yz_wc_add_single_order_page_class' );

		// Styling.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_action( 'wp_enqueue_scripts', 'yz_woocommerce_scripts' );

		// Filters.
		add_filter( 'pre_kses', 'yz_wc_pre_kses_decode', 10, 3 );
		add_filter( 'woocommerce_formatted_address_replacements', 'yz_wc_formatted_address_replacements' );
		add_filter( 'yz_is_current_tab_has_children', 'yz_wc_hide_woocommerce_default_subnav', 10, 3 );
		add_action( 'yz_profile_main_content', 'yz_wc_tab_filter_bar', 1 );
		add_filter( 'bp_get_options_nav_checkout', 'yz_wc_hide_empty_checkout_tab', 99 );
		add_filter( 'woocommerce_my_account_my_orders_query', 'yz_wc_set_account_orders_per_page', 10, 1 );

    }

}

add_action( 'bp_init', 'yz_init_woocommerce' );

/**
 * Woocommerce Template Path.
 */
function yz_wc_template_path() {
    return apply_filters( 'yz_wc_template_path', YZ_TEMPLATE . '/members/single/woocommerce/' );
}

/**
 * Get $key => $value array of WooCommerce Account menu items for BuddyPress Account menu
 */
function yz_get_wc_account_menu_items() {

	$new_items = array(
		'cart' => __( 'Shopping Cart', 'youzer' ),
		'checkout' => __( 'Checkout', 'youzer' ),
		'track' => __( 'Track', 'youzer' )
	);

	// Start with the WooCommerce Account menu items
	$wc_items = $new_items + yz_get_woocommerce_endpoints();

	return $wc_items;
}


/**
 * Get Woocommerce Endpoints.
 */
function yz_get_woocommerce_endpoints() {

	// Get Endpoints
    $items = get_transient( 'yz_get_woocommerce_endpoints' );

    if ( false === $items ) {

        // Get Woocommerce Endpoints
        $endpoints = array(
            'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
            'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
            'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
            'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
            'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
        );

        // Get items.
        $items = array(
            'orders'          => __( 'Orders', 'youzer' ),
            'downloads'       => __( 'Downloads', 'youzer' ),
            'edit-address'    => __( 'Addresses', 'youzer' ),
            'payment-methods' => __( 'Payment Methods', 'youzer' ),
            'edit-account'    => __( 'Account Details', 'youzer' ),
        );

	    // Remove missing endpoints.
	    foreach ( $endpoints as $endpoint_id => $endpoint ) {
	        if ( empty( $endpoint ) ) {
	            unset( $items[ $endpoint_id ] );
	        }
	    }

	    $items = apply_filters( 'woocommerce_account_menu_items', $items );

    	set_transient( 'yz_get_woocommerce_endpoints', $items, DAY_IN_SECONDS );
    }

	$items = apply_filters( 'woocommerce_account_menu_items', $items );

    // Hide Unwated Pages.
    $hide_pages = array( 'dashboard', 'customer-logout' );

    foreach ( $hide_pages as $page ) {
    	if ( isset( $items[ $page ] ) ) {
    		unset( $items[ $page ] );
    	}
    }

    return $items;
}

/**
 * Get Woocommerce Sub Tab.
 */
function yz_woocommerce_sub_tabs() {

	$wc_menu_items = yz_get_wc_account_menu_items();

	$subtabs = array();

	$position = 0;

    foreach ( $wc_menu_items as $key => $item_title ) {

        $position += 10;

        // WooCommerce Memberships: Don't link directly to a "My Membership Details" area because it requires unique ID in URL
        if ( strpos( $key, 'my-membership-details') !== false ) $key = 'members-area';
       	$tabs[ $key ] = array(
       		'slug' => $key,
       		'name' => $item_title,
       		'position' => $position
       	);

    }

	// Filter Sub Tabs.
	return apply_filters( 'yz_woocommerce_sub_tabs', $tabs );

}

/**
 * Hide Checkout If Is Empty.
 */
function yz_wc_hide_empty_checkout_tab( $nav_item ) {

	if ( WC()->cart->get_cart_contents_count() == 0 ) {
		return null;
	}

	return $nav_item;
}

/**
 * Is Woocommerce Sub Tab Exist
 */
function yz_wc_is_sub_tab_exist( $tab = null ) {

	// Init Var.
	$is_exist = false;

	// Get Sub Tab
	$sub_tabs = yz_woocommerce_sub_tabs();

	if ( isset( $sub_tabs[ $tab ] ) ) {
		$is_exist = true;
	}

	return apply_filters( 'yz_wc_is_sub_tab_exist', $is_exist );

}


/**
 * Woocommerce Scripts
 */
function yz_woocommerce_scripts() {
    // Call Script.
    wp_enqueue_style( 'yz-woocommerce', YZ_PA . 'css/yz-woocommerce.min.css', array(), YZ_Version );
}

/**
 * Add Pages Classes.
 */
function yz_wc_add_single_order_page_class( $classes ) {

	$classes[] = 'yz-' . bp_current_action();

	if ( bp_action_variables() ) {

		foreach ( bp_action_variables() as $key => $value) {
			$classes[] = 'yz-' . $value;
		}
	}

	return $classes;

}

/**
 * Get Woocommerce Address Class.
 */
function yz_wc_get_user_address_data( $field ) {

	// Default Icon.
	$icon = array(
		'title' => $field,
		'icon' => '<i class="fas fa-info"></i>'
	);

	switch ( $field ) {

		case 'name':

			return array(
				'title' => __( 'Full Name', 'youzer' ),
				'icon' => '<i class="fas fa-pencil-alt"></i>'
			);

		case 'company':

			return array(
				'title' => __( 'Company', 'youzer' ),
				'icon' => '<i class="fas fa-building"></i>'
			);

		case 'address_1':

			return array(
				'title' => __( 'Address 1', 'youzer' ),
				'icon' => '<i class="fas fa-address-card"></i>'
			);

			break;

		case 'address_2':

			return array(
				'title' => __( 'Address 2', 'youzer' ),
				'icon' => '<i class="far fa-address-card"></i>'
			);

			break;

		case 'city':

			return array(
				'title' => __( 'City', 'youzer' ),
				'icon' => '<i class="fas fa-city"></i>'
			);

			break;

		case 'state':

			return array(
				'title' => __( 'State', 'youzer' ),
				'icon' => '<i class="fas fa-synagogue"></i>'
			);

			break;

		case 'postcode':

			return array(
				'title' => __( 'Postcode', 'youzer' ),
				'icon' => '<i class="fas fa-map-pin"></i>'
			);

			break;

		case 'country':

			return array(
				'title' => __( 'Country', 'youzer' ),
				'icon' => '<i class="fas fa-globe-asia"></i>'
			);

		case 'email':

			return array(
				'title' => __( 'E-mail', 'youzer' ),
				'icon' => '<i class="fas fa-envelope-open"></i>'
			);

		case 'phone':

			return array(
				'title' => __( 'Phone', 'youzer' ),
				'icon' => '<i class="fas fa-phone-volume"></i>'
			);

			break;

	}

	return apply_filters( 'yz_wc_get_user_address_icon', $icon, $field );
}

/**
 * Remove Woocommerce Select2.
 */
function yz_wc_disable_niceselect_on_edit_address_tab() {
    if ( yz_is_woocommerce_tab( 'edit-address' ) ) {
    	wp_dequeue_script( 'yz-nice-selector' );
    }
}

add_action( 'wp_enqueue_scripts', 'yz_wc_disable_niceselect_on_edit_address_tab', 100 );

/**
 * Formatted Address Replacement.
 */
function yz_wc_formatted_address_replacements( $fields ) {

	if ( yz_is_woocommerce_tab( 'cart' ) ) {
		return $fields;
	}

	foreach ( $fields as $key => $value ) {

		if ( empty( $value ) ) {
			continue;
		}

		$class = str_replace( array('{', '}' ), '', $key );


		$fields[ $key ] = yz_wc_get_address_item_html( $class, $value );

	}

	return $fields;

}

/**
 * Get Address Item HTML.
 **/
function yz_wc_get_address_item_html( $class, $value ) {

	// Get Item Data.
	$data = yz_wc_get_user_address_data( $class );

	return apply_filters( 'yz_wc_get_address_item_html', '<div class="yzwc-address-item yz-address-' . $class .' "><div class="yz-bullet"></div><div class="yzcw-item-head">' . $data['icon'] . '<span class="yzcw-item-title">' . $data['title'] . '</span></div><span class="yzwc-address-value">' . $value . '</span></div>' );
}

/**
 * Add Customert Phone & E-mail.
 */
function yz_add_wc_customer_billing_address( $order ) {

	echo '<br>';

	if ( $order->get_billing_phone() ) {
		echo yz_wc_get_address_item_html( 'phone', $order->get_billing_phone() );
	}

	echo '<br>';

	if ( $order->get_billing_email() ) {
		echo yz_wc_get_address_item_html( 'email', $order->get_billing_email() );
	}

}

add_action( 'yz_woocommerce_customer_billing_address', 'yz_add_wc_customer_billing_address' );

/**
 * Decode Html
 */
function yz_wc_pre_kses_decode( $string, $allowed_html, $allowed_protocols ) {
    return html_entity_decode( $string );
}

/**
 * Get User Address Type Icon
 */
function yz_wc_get_user_address_type_icon( $type ) {

	switch ( $type ) {

		case 'billing':
			$icon = '<i class="fas fa-address-card"></i>';
			break;

		case 'shipping':
			$icon = '<i class="fas fa-shipping-fast"></i>';
			break;

		case 'tracking':
			$icon = '<i class="fas fa-truck-moving"></i>';
			break;

		case 'shopping':
			$icon = '<i class="fas fa-shopping-cart"></i>';
			break;

		default:
			$icon = '<i class="fas fa-address-card"></i>';
			break;

	}

	return apply_filters( 'yz_wc_get_user_address_type_icon', $icon, $type );

}

/**
 * Check for Woocommerce Tab.
 */
function yz_is_woocommerce_tab( $tab = false, $sub_tab = false ) {

	// Init Var.
	$is_woocommerce = false;

	// Get Woocommerce Slug.
	$slug = yz_woocommerce_tab_slug();

	if ( empty( $tab ) && empty( $sub_tab ) && bp_is_current_component( $slug ) ) {
		$is_woocommerce = true;
	}

	if ( ! empty( $tab ) && empty( $sub_tab ) && bp_is_current_component( $slug ) && bp_current_action() == $tab ) {
		$is_woocommerce = true;
	}

	if ( ! empty( $sub_tab ) && bp_is_current_component( $slug ) && bp_current_action() == $tab && bp_action_variable( 0 ) == $sub_tab ) {
		$is_woocommerce = true;
	}

	return apply_filters( 'yz_is_woocommerce_tab', $is_woocommerce );

}

/**
 * Get Woocommerce Url.
 */
function yz_get_wocommerce_url( $slug = false, $user_id = null ) {

	// Get User.
	$default_user_id = bp_displayed_user_id() ? bp_displayed_user_id() : bp_loggedin_user_id();

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : $default_user_id;

    // Get User Profile Settings Page Url.
    $url = bp_core_get_user_domain( $user_id ) . yz_woocommerce_tab_slug() . '/';

    if ( $slug ) {
        $url = $url . $slug;
    }

    return $url;
}

/**
 * Disable Woocommerce Default Tab.
 */
function yz_wc_hide_woocommerce_default_subnav( $has_children ) {
    return false;
}

/**
 * Add WC Page Filter Bar.
 */
function yz_wc_tab_filter_bar() { ?>

	<div class="item-list-tabs yz-default-subnav no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'youzer' ); ?>" role="navigation">
	    <ul>

	        <?php bp_get_options_nav(); ?>

	    </ul>
	</div><!-- .item-list-tabs -->

	<?php
}

/**
 * Buddypress Set Is "Order Received" Page.
 */
function yz_is_order_recieved_page( $is_page ) {


	if ( is_user_logged_in() && ( yz_is_woocommerce_tab( 'checkout' ) || yz_is_woocommerce_tab( 'cart' ) ) ) {

		global $wp;

		if (  isset( $wp->query_vars['order-received'] ) ) {
			return true;
		}

	}

	return $is_page;
}

add_filter( 'woocommerce_is_order_received_page', 'yz_is_order_recieved_page' );

/**
 * Buddypress Set Is Cart Page.
 */
function yz_is_cart_page() {

	if ( is_user_logged_in() && ! defined( 'WOOCOMMERCE_CART' ) && yz_is_woocommerce_tab( 'cart' ) && yz_wc_is_sub_tab_exist( 'cart' ) ) {
		define( 'WOOCOMMERCE_CART', true );
	}

}

/**
 * Add Payment Method.
 */
function yz_wc_is_add_payment_method() {
	global $wp;
	return ( yz_is_woocommerce_tab( 'payment-methods' ) && ( isset( $wp->query_vars['payment-methods'] ) || isset( $wp->query_vars['add-payment-method'] ) ) );
}

/**
 * Get Available Payment Gateway.
 */
function yz_wc_available_payment_gateways( $_available_gateways ) {

	if ( ! yz_wc_is_add_payment_method() ) {
		return $_available_gateways;
	}

	$default = $_available_gateways;

	try {
		global $bp;
		$available_gateways = array();
		if ( 'payment-methods' === $bp->current_action ) {
			foreach ( $_available_gateways as $key => $gateway ) {
				if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
					$available_gateways[ $key ] = $gateway;
				}
			}
		} else {
			$available_gateways = $_available_gateways;
		}

		return $available_gateways;
	} catch ( Exception $exception ) {
		return $default;
	}

}

add_filter( 'woocommerce_available_payment_gateways', 'yz_wc_available_payment_gateways' );

/**
 * Buddypress Set Is Checkout Page.
 */
function yz_is_checkout_page( $is_checkout ) {
	if ( bp_is_current_component( yz_woocommerce_tab_slug() ) && bp_current_action() == 'checkout' ) {
		$is_checkout = true;
	}

	return $is_checkout;
}

add_filter( 'woocommerce_is_checkout', 'yz_is_checkout_page' );

/**
 * Get Wooroomcer Activitiy Product Args.
 */
function yz_wc_get_activity_product_args( $product = null ) {

	if ( ! $product ) {
		return;
	}

	// Get Product Args.
	$args = array(
		'id' 	=> $product->get_id(),
		'type' 	=> $product->get_type(),
		'price' => $product->get_price_html(),
		'title' => $product->get_name(),
		'sales' => $product->get_total_sales(),
		'featured' => $product->get_featured(),
		'link' 	=> get_permalink( $product->get_id() ),
		'stock_status' => $product->get_stock_status(),
		'description' => $product->get_description(),
		'short_description' => $product->get_short_description(),
		'date' => $product->get_date_created()->format( 'F j, Y' ),

		// Taxonomies
		'tags' 		 => get_the_terms( $product->get_id(), 'product_tag' ),
		'categories' => wc_get_product_category_list( $product->get_id() ),

		// Thumbnails
		'original'  => get_the_post_thumbnail_url( $product->get_id(), 'full' ),
		'thumbnail' => get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_thumbnail' ),

		// Sale,
		'is_on_sale' => $product->is_on_sale(),
		'sale_save'  => yz_wc_get_sale_percent( $product ),
	);

	// Reviews
	if ( $product->get_reviews_allowed() ) {
		$args['reviews'] = array(
			'count' => $product->get_review_count(),
			'ratings' => $product->get_rating_counts(),
			'average' => $product->get_average_rating()
		);
	}

	return apply_filters( 'yz_wc_activity_product_args', $args, $product );
}

/**
 * Wall Embed User
 */
function yz_get_woocommerce_product( $product = false ) {

	if ( ! $product ) {
		return false;
	}

	ob_start();

	?>
	<div class="yz-product">

	<div class="yz-product-content">

		<?php if ( apply_filters( 'yz_display_product_sale', true ) && $product['is_on_sale'] == true ) : ?>
			<div class="yz-product-sale"><?php _e( 'Sale!', 'youzer' ); ?></div>
        <?php endif; ?>

		<?php yz_get_product_image( array( 'id' => $product['id'], 'thumbnail' => $product['thumbnail'], 'original' => $product['original'] ) ); ?>

        <div class="yz-product-container">

            <div class="yz-product-inner-content">

                <div class="yz-product-price<?php echo $product['type'] == 'variable' ? ' yz-variable-price' : '';?>"><?php echo $product['price'] ?></div>

                <div class="yz-product-head">

                    <?php if ( ! empty( $product['featured'] ) ) : ?><a class="yz-product-type yz-featured-product"><?php echo __( 'Featured Product', 'youzer'); ?></a><?php endif; ?>

                    <?php if ( apply_filters( 'yz_display_product_sale_save' , true ) && ! empty( $product['sale_save'] ) ) : ?><a class="yz-product-type yz-sale-save"><?php echo sprintf( __( 'Save %1s ', 'youzer' ), $product['sale_save'] ); ?></a><?php endif; ?>

                    <h2 class="yz-product-title">
                    	<a href="<?php echo $product['link']; ?>"><?php echo $product['title']; ?></a>
                    </h2>

                    <?php if ( apply_filters( 'yz_display_product_meta', true ) ) : ?>

                    <div class="yz-product-meta">

                        <ul>

                            <?php if ( apply_filters( 'yz_display_product_date' , true ) ) : ?>
                                <li><?php echo '<i class="far fa-calendar-alt"></i>' . $product['date']; ?></li>
                            <?php endif; ?>

                            <?php if ( apply_filters( 'yz_display_product_categories' , true ) ) : ?>
                                <li><?php echo '<i class="fas fa-tags"></i>' . $product['categories']; ?></li>
                            <?php endif; ?>

                            <?php if ( apply_filters( 'yz_display_product_sales' , true ) ) : ?>
                                <li><?php echo '<i class="fas fa-shopping-cart"></i>' . sprintf( _n( '%s Sale', '%s Sales', $product['sales'], 'youzer' ), $product['sales'] );; ?></li>
                            <?php endif; ?>

                            <?php if ( apply_filters( 'yz_display_product_stock_status' , true ) && ! empty( $product['stock_status'] ) ) : ?>
                                <li><i class="fas fa-warehouse"></i><?php echo yz_wc_get_stock_status( $product['stock_status'] ); ?></li>
                            <?php endif; ?>
                        </ul></div>

                    <?php endif; ?>

                </div>

				<?php if ( apply_filters( 'yz_display_product_description', true )  ) : ?>
				<div class="yz-product-text">
					<p>
					<?php echo yz_get_product_description( $product['short_description'], $product['description'] ); ?>
					</p>
				</div>
				<?php endif; ?>

                <?php if ( apply_filters( 'yz_display_product_tags', true ) && ! empty( $product['tags'] ) ) {
			        ?>

			        <ul class="yz-product-tags">

			        <?php
						foreach ($product['tags'] as $tag) {
			                echo '<li><span class="yz-tag-symbole">#</span><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li>';
						}

			        ?>

			        </ul>

			        <?php } ?>

				<?php if ( apply_filters( 'yz_display_product_view_more_button', true ) && $product['stock_status'] != 'outofstock' ) : ?>

				<div class="yz-product-actions">

					<?php if ( $product['type'] != 'variable' ) : ?>

					<a href="<?php echo yz_wc_get_add_to_cart_link( $product['id'] ); ?>" data-yz-product-id="<?php echo $product['id']; ?>" class="yz-product-action-button yz-addtocart  yz-addtocart-ajax"><div class="yz-btn-icon"><i class="fas fa-cart-plus"></i></div><?php _e( 'Add to Cart', 'youzer' ); ?></a>

					<a href="<?php echo $product['link'] ?>" class="yz-product-action-button" style="background-color: #94adb9;"><div class="yz-btn-icon"><i class="fas fa-angle-double-right"></i></div><?php _e( 'Read More', 'youzer' ); ?></a>
				<?php else : ?>

					<a href="<?php echo $product['link']; ?>" class="yz-product-action-button yz-addtocart"><div class="yz-btn-icon"><i class="far fa-hand-pointer"></i></div><?php _e( 'Select Options', 'youzer' ); ?></a>

                <?php endif; ?>
				</div>

                <?php endif; ?>

            </div>

        </div>

    </div>
	</div>

	<?php

	ob_flush();

	$content = ob_get_contents();

	ob_end_clean();

	return $content;

}

/**
 * Get Product Description.
 */
function yz_get_product_description( $short_description = null, $description = null ) {

	$desc = null;

	if ( ! empty( $short_description ) ) {
		$desc = $short_description;
	}

	if ( empty( $short_description ) && ! empty( $description ) && apply_filters( 'yz_display_product_description_excerpt', true ) ) {
		$desc = yz_get_excerpt( $description, 35 );
	}

	return $desc;

}

/**
 * Get Add To Cart Link.
 */
function yz_wc_get_add_to_cart_link( $product_id = null ) {

	if ( empty( $product_id ) ) {
		return;
	}

	// Get Current Page Link.
	$current_page = yz_get_current_page_url();

	// Get Add To Cart Link.
	$link = add_query_arg( 'add-to-cart', $product_id, $current_page );

	return apply_filters( 'yz_wc_add_to_cart_link', $link );

}

/**
 * Get Stock Status.
 */
function yz_wc_get_stock_status( $status ) {
	switch ( $status ) {

		case 'instock':
			return __( 'In stock', 'youzer' );

		case 'outofstock':
			return __( 'Out of stock', 'youzer' );

		case 'onbackorder':
			return __( 'On backorder', 'youzer' );

		default:
			return null;
	}
}

/**
 * Get Sale Percent.
 */
function yz_wc_get_sale_percent( $product ) {

    // Only on sale products on frontend and excluding min/max price on variable products
    if ( $product->is_on_sale() && ! is_admin() && ! $product->is_type( 'variable' ) ) {

        // Get product prices
        $regular_price = (float) $product->get_regular_price(); // Regular price
        $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)

        // "Saving price" calculation and formatting
        $saving_price = wc_price( $regular_price - $sale_price );

        // Append to the formated html price
        return $saving_price;
    }

    return null;

}

/**
 * Set Orders Per Page.
 */
function yz_wc_set_account_orders_per_page( $args ) {
    // Set the post per page
    $args['limit'] = 25;
    return $args;
}