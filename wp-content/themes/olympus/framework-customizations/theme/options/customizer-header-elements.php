<?php

if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$header_menu_cart = array();
/*
    * Get default option value, previously stored in Theme Settings
    * TODO: Remove that feature in future releases.
    *
    * */
$olympus    = Olympus_Options::get_instance();
$cart_value = $olympus->get_option( 'menu_cart_icon', 'show', $olympus::SOURCE_SETTINGS );

$search_value          = $olympus->get_option( 'top-panel-search', 'yes', $olympus::SOURCE_SETTINGS );
$friend_requests_value = $olympus->get_option( 'top-panel-friend-requests', 'yes', $olympus::SOURCE_SETTINGS );
$messages_value        = $olympus->get_option( 'top-panel-messages', 'yes', $olympus::SOURCE_SETTINGS );
$notifications_value   = $olympus->get_option( 'top-panel-notifications', 'yes', $olympus::SOURCE_SETTINGS );

$user_menu_value       = $olympus->get_option( 'top-panel-users-menu', 'yes', $olympus::SOURCE_SETTINGS );

if ( class_exists( 'WooCommerce' ) ) {

	$header_menu_cart = array(
		'menu_cart_icon' => array(
			'type'         => 'switch',
			'label'        => esc_html__( 'Woocommerce cart', 'olympus' ),
			'desc'         => esc_html__( 'Cart icon with dropdown', 'olympus' ),
			'help'         => esc_html__( 'Work only if Woocommerce plugin installed', 'olympus' ),
			'right-choice' => array(
				'value' => 'show',
				'label' => esc_html__( 'Show', 'olympus' )
			),
			'left-choice'  => array(
				'value' => 'hide',
				'label' => esc_html__( 'Hide', 'olympus' )
			),
			'value'        => $cart_value,
		),
	);
}
$top_panel_elements = array(
	'top-panel-users-menu'      => array(
		'label'        => esc_html__( 'User menu / Login', 'olympus' ),
		'desc'         => esc_html__( 'Enable user menu', 'olympus' ),
		'type'         => 'switch',
		'left-choice'  => array(
			'value' => 'no',
			'label' => esc_html__( 'Disable', 'olympus' )
		),
		'right-choice' => array(
			'value' => 'yes',
			'label' => esc_html__( 'Enable', 'olympus' )
		),
		'value'        => $user_menu_value,
	),
	'top-panel-search'          => array(
		'label'        => esc_html__( 'Search', 'olympus' ),
		'desc'         => esc_html__( 'Enable search.', 'olympus' ),
		'type'         => 'switch',
		'right-choice' => array(
			'value' => 'yes',
			'label' => esc_html__( 'Enable', 'olympus' ),
		),
		'left-choice'  => array(
			'value' => 'no',
			'label' => esc_html__( 'Disable', 'olympus' ),
		),
		'value'        => $search_value,
	),
	'top-panel-friend-requests' => array(
		'label'        => esc_html__( 'Friend requests', 'olympus' ),
		'desc'         => esc_html__( 'Enable friend requests. BuddyPress is required!', 'olympus' ),
		'type'         => 'switch',
		'left-choice'  => array(
			'value' => 'no',
			'label' => esc_html__( 'Disable', 'olympus' )
		),
		'right-choice' => array(
			'value' => 'yes',
			'label' => esc_html__( 'Enable', 'olympus' )
		),
		'value'        => $friend_requests_value,
	),
	'top-panel-messages'        => array(
		'label'        => esc_html__( 'Messages', 'olympus' ),
		'desc'         => esc_html__( 'Enable messages. BuddyPress is required!', 'olympus' ),
		'type'         => 'switch',
		'left-choice'  => array(
			'value' => 'no',
			'label' => esc_html__( 'Disable', 'olympus' )
		),
		'right-choice' => array(
			'value' => 'yes',
			'label' => esc_html__( 'Enable', 'olympus' )
		),
		'value'        => $messages_value,
	),
	'top-panel-notifications'   => array(
		'label'        => esc_html__( 'Notifications', 'olympus' ),
		'desc'         => esc_html__( 'Enable notifications. BuddyPress is required!', 'olympus' ),
		'type'         => 'switch',
		'left-choice'  => array(
			'value' => 'no',
			'label' => esc_html__( 'Disable', 'olympus' )
		),
		'right-choice' => array(
			'value' => 'yes',
			'label' => esc_html__( 'Enable', 'olympus' )
		),
		'value'        => $notifications_value,
	),

);

$options = array_merge(
	$top_panel_elements,
	$header_menu_cart
);
