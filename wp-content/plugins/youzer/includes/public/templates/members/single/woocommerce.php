<?php
/**
 * BuddyPress - Woocommerce
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

/**
 * Fires before the display of member friends content.
 *
 * @since 1.2.0
 */

do_action( 'yz_before_woocommerce_content' );

switch ( bp_current_action() ) :

	// Cart Page.
	case 'cart' :
		bp_get_template_part( 'members/single/woocommerce/cart' );
		break;

	// Checkout Page.
	case 'checkout' :
		bp_get_template_part( 'members/single/woocommerce/checkout' );
		break;

	// Orders Page.
	case 'orders' :
		bp_get_template_part( 'members/single/woocommerce/orders' );
		break;

	// Tracking Page.
	case 'track' :
		bp_get_template_part( 'members/single/woocommerce/track' );
		break;

	// Downloads Page.
	case 'downloads' :
		bp_get_template_part( 'members/single/woocommerce/downloads' );
		break;

	// Addresses Page.
	case 'edit-address' :
		bp_get_template_part( 'members/single/woocommerce/edit-address' );
		break;

	// Payment Methods Page.
	case 'payment-methods' :
		bp_get_template_part( 'members/single/woocommerce/payment-methods' );
		break;

	// Account Details Page.
	case 'edit-account' :
		bp_get_template_part( 'members/single/woocommerce/account-details' );
		break;

	// Any other
	default :
	    wc_print_notices();
	    do_action( 'woocommerce_account_content' );
		// bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
