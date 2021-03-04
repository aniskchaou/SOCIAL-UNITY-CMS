<?php

// Add Product to Activity Stream.
add_action( 'transition_post_status', 'yz_wc_add_new_product_activity' , 10, 3 );

/**
 * Add prodcut to activity stream.
 */
function yz_wc_add_new_product_activity( $new_status, $old_status, $post ) {

    if ( ! bp_is_active( 'activity' ) || $post->post_type !== 'product' || 'publish' !== $new_status || 'publish' === $old_status ) return;
 
    if ( ! $product = wc_get_product( $post ) ) {
        return;
    }

    $user_link = bp_core_get_userlink( $post->post_author );

    // Get Activity Action.
    $action = apply_filters( 'yz_new_wc_product_action', sprintf( __( '%s added new product', 'youzer' ), $user_link ), $post->ID );

    // record the activity
    bp_activity_add( array(
        'user_id'   => $post->post_author,
        'action'    => $action,
        'item_id'   => $post->ID,
        'component' => yz_woocommerce_tab_slug(),
        'type'      => 'new_wc_product',
    ) );

}

/**
 * Woocommerce Tab Slug.
 */
function yz_woocommerce_tab_slug() {
	return apply_filters( 'yz_woocommerce_tab_slug', 'shop' );
}

/**
 * Adds an activity stream item when a user has purchased a new product(s).
 */
add_action( 'woocommerce_order_status_completed', 'yz_wc_add_new_order_activity'  );

function yz_wc_add_new_order_activity( $order_id ) {

	if ( ! bp_is_active( 'activity' ) || yz_option( 'yz_enable_wc_purchase_activity', 'off' ) != 'on' ) {
		return false;
	}

	$order = new WC_Order( $order_id );

	if ( $order->get_status() != 'completed' ) {
		return false;
	}

	// Check is user is enabling purchases activities.
	$purchase_activity = get_user_meta( $order->get_customer_id(), 'yz_wc_purchase_activity', true );
	
	if ( 'on' != $purchase_activity ) {
		return false;
	}

	$user_link = bp_core_get_userlink( $order->get_customer_id() );

	// if several products - combine them, otherwise - display the product name
	$items = $order->get_items();
	$names    = array();

	/** @var WC_Order_Item_Product $item */
	foreach ( $items as $item ) {

		$product_name = '<a href="' . $item->get_product()->get_permalink() . '">' . $item->get_product()->get_name() . '</a>';

		/**
		 * Modify the string to insert into the BuddyPress Activity Stream on Order Complete
		 */
		$action = apply_filters( 'yz_new_wc_purchase_action', sprintf( __( '%s purchased %s', 'youzer' ), $user_link, $product_name ), $order, $items );

		// record the activity
		bp_activity_add( array(
			'user_id'   => $order->get_user_id(),
			'action'    => $action,
			'item_id'   => $item->get_product()->get_id(),
			'secondary_item_id' => $order_id,
			'component' => yz_woocommerce_tab_slug(),
			'type'      => 'new_wc_purchase',
		) );
		
	}

	return true;
}