<?php
/**
 * My Account > Subscriptions page
 *
 * @author   Prospress
 * @category WooCommerce Subscriptions/Templates
 * @version  2.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

		// $current_page = isset( $wp->query_vars['orders'] ) ? $wp->query_vars['orders'] : 1;
echo '<div class="yzwc-main-content yzwc-view-order-content">';
WC_Subscriptions::get_my_subscriptions_template( $current_page );
echo '</div>';
