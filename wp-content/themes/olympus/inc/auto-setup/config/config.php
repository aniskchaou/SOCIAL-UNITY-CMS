<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

return array(
	/**
	 * Array for demos
	 */
	'demos'              => array(
		'olympus-main-elementor'      => array(
			array(
				'name' => 'MailChimp for WordPress',
				'slug' => 'mailchimp-for-wp',
			),
			array(
				'name'     => esc_attr__( 'The Events Calendar', 'olympus' ),
				'slug'     => 'the-events-calendar',
				'required' => false,
			),
			array(
				'name'     => esc_attr__( 'WooCommerce', 'olympus' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name' => esc_attr__( 'BBPress', 'olympus' ),
				'slug' => 'bbpress',
			),
			array(
				'name'     => esc_attr__( 'Elementor builder', 'olympus' ),
				'slug'     => 'elementor',
				'required' => true,
			),
			array(
				'name'     => esc_attr__( 'Elementor Olympus Widgets', 'olympus' ),
				'slug'     => 'elementor-olympus',
				'source'   => 'http://up.crumina.net/plugins/elementor-olympus.zip',
				'required' => true,
				'version'  => '1.5.0',
			),
		),
		'olympus-main-visualcomposer' => array(
			array(
				'name' => 'MailChimp for WordPress',
				'slug' => 'mailchimp-for-wp',
			),
			array(
				'name'     => esc_attr__( 'The Events Calendar', 'olympus' ),
				'slug'     => 'the-events-calendar',
				'required' => false,
			),
			array(
				'name'     => esc_attr__( 'WooCommerce', 'olympus' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name' => esc_attr__( 'BBPress', 'olympus' ),
				'slug' => 'bbpress',
			),
			array(
				'name'     => 'WPBakery Visual Composer',
				'slug'     => 'js_composer',
				'source'   => 'http://up.crumina.net/plugins/js_composer.zip',
				'required' => true,
				'version'  => '6.6',

			),
		),
	),
	'plugins'            => array(
		array(
			'name'     => esc_attr__( 'BuddyPress', 'olympus' ),
			'slug'     => 'buddypress',
			'required' => true,
		),
		array(
			'name'         => esc_attr__( 'Envato Market', 'olympus' ),
			'slug'         => 'envato-market',
			'source'       => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required'     => true,
			'is_automatic' => true,
		),
		array(
			'name'     => esc_attr__( 'Youzer', 'olympus' ),
			'slug'     => 'youzer',
			'source'   => 'http://up.crumina.net/plugins/youzer.zip',
			'required' => true,
			'version'  => '2.6.2'
		),
		array(
			'name'     => 'WPBakery Visual Composer',
			'slug'     => 'js_composer',
			'source'   => 'http://up.crumina.net/plugins/js_composer.zip',
			'required' => true,
			'version'  => '6.6',
		),
	),
	'theme_id'           => 'olympus',
	'child_theme_source' => 'http://up.crumina.net/demo-data/olympus/olympus-child.zip',
	'has_demo_content'   => true,
);
