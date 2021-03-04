<?php

class Youzer_WC_Redirects {


    public function __construct() {

        add_action( 'template_redirect', array( $this, 'get_redirect_link' ) );
        add_filter( 'page_link',  array( $this, 'redirect_link' ), 10, 2 );
        add_filter( 'woocommerce_get_myaccount_page_permalink',  array( $this, 'account_url' ) );
        add_filter( 'woocommerce_get_view_order_url', array( $this, 'get_view_order_url' ), 10, 2 );
        add_filter( 'woocommerce_get_endpoint_url', array( $this, 'endpoint_url' ), 10, 4 );

    }

    /*
     * Change End Point.
     */
    public function endpoint_url( $url, $endpoint, $value, $permalink ) {

        // Init Vars.
        $default = $url;

        // Get Supported Pages.
        $supported_pages = yz_supported_wc_pages();

        if ( in_array( $endpoint, array( 'order-pay', 'order-received' ) ) && ! isset( $supported_pages['checkout'] ) ) {
            return $default;
        }

        $base_path = yz_get_wocommerce_url();

        switch ( $endpoint ) {

            case 'order-pay':
                $url = $base_path . '/checkout/' . $endpoint . '/' . $value;
                break;

            case 'orders':
            case 'edit-address':
                $url = $base_path . $endpoint . '/' . $value;
                break;

            case 'payment-methods':
                $url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'payment-methods' );
                break;

            case 'order-received':
                $url = $base_path . '/checkout/' . $endpoint . '/' . $value;
                break;

            case 'set-default-payment-method':
            case 'delete-payment-method':
                $url = add_query_arg( $endpoint, $value, $base_path . 'payment' );
                break;

            case 'view-subscription':
                $url = $base_path . 'subscriptions/view-subscription/' . $value;
                break;

            case 'add-payment-method':
                $url = add_query_arg( $endpoint, 'w2ewe3423ert', $base_path . 'payment-methods' );
                break;
        }

        return apply_filters( 'yz_woocommerce_get_endpoint_url', $url, $default );

    }

    /**
     * Filter the account root url if needed.
     * WooCommerce use this to redirect the user when a form is submitted.
     */
    public function account_url( $url = '' ) {

        global $wp;

        if ( ! yz_is_woocommerce_tab() && ! apply_filters( 'yz_wc_account_url_use_everywhere', false ) && ! isset( $wp->query_vars['customer-logout'] ) ) {
            return $url;
        }

        if ( isset( $wp->query_vars['customer-logout'] ) ) {
            return bp_get_root_domain();
        } elseif ( isset( $wp->query_vars['edit-address'] ) ) {
            return yz_get_current_page_url();
        } else {
            return trailingslashit( bp_loggedin_user_domain() . yz_woocommerce_tab_slug() );
        }

    }

    /**
     * Redirect Woocommerce My Account Page.
     */
    function redirect_myaccount_page() {
        if ( is_user_logged_in() && is_account_page() && apply_filters( 'yz_wc_enable_my_account_redirect', true ) ) {
            $redirect_url = bp_loggedin_user_domain() . yz_woocommerce_tab_slug();
            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Redirect.
     */
    function get_redirect_link( $page_link ) {

        if ( apply_filters( 'yz_wc_enable_my_account_redirect', true ) ) {

            global $post;

            if ( ! is_user_logged_in() || is_admin() || empty( $post->ID ) ) {
                return $page_link;
            }

            $link = $this->get_page_slug( $post->ID );

            if ( ! empty( $link ) ) {
                wp_safe_redirect( $link );
                exit;
            }

        }
        return $page_link;

    }

    /**
     * Get Page Slug.
     */
    function get_page_slug( $page_id = null ) {


        $supported_wc_pages = yz_supported_wc_pages();

        if ( ! in_array( $page_id, $supported_wc_pages, true ) ) {
            return false;
        }

        switch ( $page_id ) {

            case $supported_wc_pages['cart']:
                return yz_get_wocommerce_url( 'cart' );

            case $supported_wc_pages['checkout']:

                global $wp;

                $slug = 'checkout';

                // Get Order Recieved Slug.
                if ( isset( $wp->query_vars['order-received'] ) && ! empty( $wp->query_vars['order-received'] ) ) {
                    $order_key = isset( $_GET['key'] ) ? $_GET['key'] : 0;
                    $slug .= '/order-received/' . $wp->query_vars['order-received'] . '/?key=' . $order_key;
                }

                return yz_get_wocommerce_url( $slug );

            case $supported_wc_pages['myaccount']:

                global $wp;

                $slug = '';

                // Get Subscriptions Slug.
                if ( isset( $wp->query_vars['subscriptions'] )  ) {
                    $slug = 'subscriptions';
                }

                // Get Orders Slug.
                if ( isset( $wp->query_vars['orders'] )  ) {
                    $slug = 'orders';
                }

                // Get View Subscription Slug.
                if ( isset( $wp->query_vars['view-subscription'] ) && ! empty( $wp->query_vars['view-subscription'] ) ) {
                    $slug = 'subscriptions/view-subscription/'. $wp->query_vars['view-subscription'];
                }

                // Get View Order Slug.
                if ( isset( $wp->query_vars['view-order'] ) && ! empty( $wp->query_vars['view-order'] ) ) {
                    $slug = 'orders/view-order/'. $wp->query_vars['view-order'];
                }


                return yz_get_wocommerce_url( $slug );

            default:
                return;
        }

    }

    /**
     * Edit Woocoomerce Links.
     */
    public function redirect_link( $page_link, $post_id = false ) {

        $supported_wc_pages = yz_supported_wc_pages();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            if ( in_array( $post_id, $supported_wc_pages, true ) ) {
                return $this->get_woocommerce_page_link_by_id( $post_id, $page_link );
            } else {
                return $page_link;
            }
        }

        if ( empty( $post_id ) ) {
            return $page_link;
        }

        /**
         * Add more endpoint to avoid the rewrite of the url for the plugin
         *
         * @param array String values of the endpoint to by pass the url transform
         */
        $avoid_woo_endpoints = apply_filters( 'yz_avoid_woo_endpoints', array( 'order-received', 'order-pay' ) );

        global $bp, $wp;

        if ( ( isset( $wp->query_vars['name'] ) && in_array( $wp->query_vars['name'], $avoid_woo_endpoints ) ) ) {
            return false;
        }

        foreach ( $avoid_woo_endpoints as $avoid_woo_endpoint ) {
            if ( isset( $wp->query_vars[ $avoid_woo_endpoint ] ) ) {
                return false;
            }
        }

        if ( ! empty( $bp->pages ) ) {

            // Get WC Pages.
            $loggedin_user_id = bp_loggedin_user_id();
            $cart_page_id     = wc_get_page_id( 'cart' );
            $checkout_page_id = wc_get_page_id( 'checkout' );
            $account_page_id  = wc_get_page_id( 'myaccount' );

            $granted_selected_pages_id = array();

            if ( in_array( $post_id, $supported_wc_pages, true ) ) {

                switch ( $post_id ) {

                    case $cart_page_id:
                        return yz_get_wocommerce_url( 'cart' );

                    case $checkout_page_id:
                        return yz_get_wocommerce_url( 'checkout');

                    case $account_page_id:
                        return yz_get_wocommerce_url();
                }

                return $page_link;
            } else {
                return $page_link;
            }
        } else {
            return $page_link;
        }

        return $page_link;
    }

    /**
     * Get Page Link.
     */
    function get_woocommerce_page_link_by_id( $page_id, $page_link ) {

        // Get WC Pages.
        $loggedin_user_id = bp_loggedin_user_id();
        $cart_page_id     = wc_get_page_id( 'cart' );
        $checkout_page_id = wc_get_page_id( 'checkout' );
        $account_page_id  = wc_get_page_id( 'myaccount' );

        switch ( $page_id ) {

            case $cart_page_id:
                return yz_get_wocommerce_url( 'cart', $loggedin_user_id );

            case $checkout_page_id:
                return yz_get_wocommerce_url( 'checkout', $loggedin_user_id );

            case $account_page_id:
                return yz_get_wocommerce_url();

            default :
            return $page_link;
        }
    }

    /**
     * Change url for view order endpoint.
     */
    function get_view_order_url( $view_order_url, $order ){

        $orders_url = yz_get_wocommerce_url( 'orders' );
        $view_order_url = wc_get_endpoint_url( 'view-order', $order->get_id(), $orders_url );

        return $view_order_url;
    }

}