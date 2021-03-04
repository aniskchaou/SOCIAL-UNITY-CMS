<?php
if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$olympus                 = Olympus_Options::get_instance();
$left_panel_option_value = $olympus->get_option( 'left-panel-fixed-options/show', 'no', $olympus::SOURCE_SETTINGS );
$default_sticky_header   = $olympus->get_option( 'header_top_sticky', 'enable-sticky-standard-header', $olympus::SOURCE_CUSTOMIZER );

$user_panel_option_value = $olympus->get_option( 'top-user-panel-options/show', 'yes', $olympus::SOURCE_SETTINGS );
$tracking_scripts_value = $olympus->get_option( 'tracking_scripts', array(), $olympus::SOURCE_SETTINGS );
$gmap_apikey_value = $olympus->get_option( 'gmap-key', '', $olympus::SOURCE_SETTINGS );
$twitter_consumer_key_value = $olympus->get_option( 'twitter-consumer-key', '', $olympus::SOURCE_SETTINGS );
$twitter_consumer_secret_value = $olympus->get_option( 'twitter-consumer-secret', '', $olympus::SOURCE_SETTINGS );
$twitter_access_token_value = $olympus->get_option( 'twitter-access-token', '', $olympus::SOURCE_SETTINGS );
$twitter_access_token_secret_value = $olympus->get_option( 'twitter-access-token-secret', '', $olympus::SOURCE_SETTINGS );

$options = array(
	'panel_blog' => array(
		'title'   => esc_html__( 'Blog', 'olympus' ),
		'options' => array(
			fw()->theme->get_options( 'customizer-blog' ),
		),
	),
	'panel_typo'       => array(
		'title'   => esc_html__( 'Typography', 'olympus' ),
		'options' => array(
			fw()->theme->get_options( 'customizer-typography' ),
		),
	),
	'panel_aside_menu' => array(
		'title'   => esc_html__( 'Left Menu Panel', 'olympus' ),
		'options' => array(
			'left-panel-fixed-options' => array(
				'type'   => 'multi-picker',
				'label'  => false,
				'desc'   => false,
				'picker' => array(
					'show' => array(
						'label'   => esc_html__( 'Left Menu Panel', 'olympus' ),
						'type'    => 'radio',
						'value'   => $left_panel_option_value,
						'inline'  => false,
						'choices' => array(
							'yes_for_logged' => esc_html__( 'Show for logged in users only', 'olympus' ),
							'yes'            => esc_html__( 'Show', 'olympus' ),
							'no'             => esc_html__( 'Hide', 'olympus' ),
						),
						'desc'    => esc_html__( 'In order to display aside panel, you need to add the desired menu in Menu settings for "Left Menu Panel" menu location', 'olympus' ),
					),
				),
			),
			'left-panel-open'  => array(
				'label'   => esc_html__( 'Always open', 'olympus' ),
				'type'    => 'radio',
				'value'   => 'no',
				'inline'  => false,
				'choices' => array(
					'no'             => esc_html__( 'No', 'olympus' ),
					'yes'            => esc_html__( 'Yes', 'olympus' ),
				),
			),
			'left-panel-options-icon'  => array(
				'label'        => esc_html__( 'Menu icon', 'olympus' ),
				'desc'         => esc_html__( 'Icon for menu open button', 'olympus' ),
				'type'         => 'icon-v2',
				'preview_size' => 'small',
				'modal_size'   => 'medium',
			),
		),
	),
	'panel_header_top' => array(
		'title'   => esc_html__( 'Header options', 'olympus' ),
		'options' => array(
			'section_header_general'  => array(
				'title'   => esc_html__( 'Elements visibility', 'olympus' ),
				'options' => array(
					'header_top_sticky' => array(
						'label'   => esc_html__( 'Fix on scroll', 'olympus' ),
						'type'    => 'radio',
						'inline'  => true,
						'value'   => $default_sticky_header,
						'choices' => array(
							'disable-sticky-both-header'    => esc_html__( 'Both headers are not fixed', 'olympus' ),
							'enable-sticky-standard-header' => esc_html__( 'Make classic header fixed', 'olympus' ),
							'enable-sticky-social-header'   => esc_html__( 'Make social header fixed', 'olympus' ),
						),
					),
					fw()->theme->get_options( 'customizer-header-elements' ),
				),
			),
			'section_header_social'   => array(
				'title'   => esc_html__( 'Social header panel', 'olympus' ),
				'options' => array(
					'top-user-panel-options' => array(
						'type'   => 'multi-picker',
						'label'  => false,
						'desc'   => false,
						'picker' => array(
							'show' => array(
								'label'   => esc_html__( 'Section Visibility', 'olympus' ),
								'type'    => 'radio',
								'value'   => $user_panel_option_value,
								'inline'  => false,
								'choices' => array(
									'yes_for_logged' => esc_html__( 'Show for logged in users only', 'olympus' ),
									'yes'            => esc_html__( 'Show', 'olympus' ),
									'no'             => esc_html__( 'Hide', 'olympus' ),
								),
							),
						),
					),
					fw()->theme->get_options( 'settings-header-social-styles' ),
				),
			),
			'section_header_standard' => array(
				'title'   => esc_html__( 'Classic header panel', 'olympus' ),
				'options' => array(
					apply_filters(
						'crumina_option_top_menu_panel_visibility',
						fw()->theme->get_options( 'partial-top-menu-panel-visibility' )
					),
					fw()->theme->get_options( 'settings-header-general-styles' ),
				),
			),
			// 'section_header_login'    => array(
			// 	'title'   => esc_html__( 'Login | User Menu section', 'olympus' ),
			// 	'options' => array(
			// 		fw()->theme->get_options( 'customizer-header-login' ),
			// 	),
			// ),

		),
	),
	'panel_footer'     => array(
		'title'   => esc_html__( 'Footer options', 'olympus' ),
		'options' => array(
			fw()->theme->get_options( 'customizer-footer' ),
		),
	),
	'panel_api' => array(
		'title'   => esc_html__( 'Scripts Integrations', 'olympus' ),
		'options' => array(
			'tracking-scripts' => array(
				'title'   => esc_html__( 'Tracking Scripts', 'olympus' ),
				'options' => array(
                    'tracking_scripts' => array(
						'type'          => 'addable-popup',
						'size'          => 'medium',
						'label'         => esc_html__( 'Tracking Scripts', 'olympus' ),
						'desc'          => esc_html__( 'Add your tracking scripts (Hotjar, Google Analytics, etc)', 'olympus' ),
						'template'      => '{{=name}}',
						'value'			=> $tracking_scripts_value,
						'popup-options' => array(
							'name'   => array(
								'label' => esc_html__( 'Name', 'olympus' ),
								'desc'  => esc_html__( 'Enter a name (it is for internal use and will not appear on the front end)', 'olympus' ),
								'type'  => 'text',
							),
							'script' => array(
								'label' => esc_html__( 'Script', 'olympus' ),
								'desc'  => esc_html__( 'Copy/Paste the tracking script here', 'olympus' ),
								'type'  => 'textarea',
							)
						),
					),
                )
			),
			'api-keys' => array(
				'title'   => esc_html__( 'API Keys', 'olympus' ),
				'options' => array(
					'gmap-key' => array(
						'label' => esc_html__( 'Google Maps', 'olympus' ),
						'type'  => 'gmap-key',
						'value' => $gmap_apikey_value,
						'desc'  => sprintf( esc_html__( 'Create an application in %sGoogle Console%s and add the API Key here.', 'olympus' ), '<a target="_blank" href="https://console.developers.google.com/flows/enableapi?apiid=places_backend,maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true">', '</a>' )
					),
					'twitter-consumer-key'        => array(
						'label' => esc_html__( 'Twitter Consumer Key (API Key)', 'olympus' ),
						'type'  => 'text',
						'value' => $twitter_consumer_key_value,
						'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Consumer Key here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
					),
					'twitter-consumer-secret'     => array(
						'label' => esc_html__( 'Twitter Consumer Secret (API Secret)', 'olympus' ),
						'type'  => 'text',
						'value' => $twitter_consumer_secret_value,
						'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Consumer Secret here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
					),
					'twitter-access-token'        => array(
						'label' => esc_html__( 'Twitter Access Token', 'olympus' ),
						'type'  => 'text',
						'value' => $twitter_access_token_value,
						'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Access Token here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
					),
					'twitter-access-token-secret' => array(
						'label' => esc_html__( 'Twitter Access Token Secret', 'olympus' ),
						'type'  => 'text',
						'value' => $twitter_access_token_secret_value,
						'desc'  => sprintf( esc_html__( 'Create an application in %sApplication interface%s and add the Access Token Secret here.', 'olympus' ), '<a target="_blank" href="https://apps.twitter.com/">', '</a>' )
					),
				)
			),
			fw()->theme->get_options( 'customizer-additional' )
		),
	),
	'panel_not_found_page'     => array(
		'title'   => esc_html__( '404 page settings', 'olympus' ),
		'options' => array(
			fw()->theme->get_options( 'customizer-404' ),
		),
	),
	'panel_core_theme_options' => array(
		'title'   => esc_html__( 'Core theme options', 'olympus' ),
		'options' => array(
			'minify-theme-css' => array(
				'label' => esc_html__( 'Minify theme CSS', 'olympus' ),
				'type'  => 'switch',
				'value' => 'disable',
				'left-choice' => array(
					'value' => 'enable',
					'label' => esc_html__('Enable', 'olympus'),
				),
				'right-choice' => array(
					'value' => 'disable',
					'label' => esc_html__('Disable', 'olympus'),
				),
			),
		),
	)
);


