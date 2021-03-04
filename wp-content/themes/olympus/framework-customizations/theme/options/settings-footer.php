<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$grid_link					 = '<a href="http://getbootstrap.com/css/#grid" target="_blank">Bootstrap Grid</a>';
$theme_template_directory	 = get_template_directory_uri();

$options = array(
	'footer-options' => array(
		'title'		 => esc_html__( 'Footer', 'olympus' ),
		'type'		 => 'tab',
		'options'	 => array(
			'footer-widgets-tab'	 => array(
				'title'		 => esc_html__( 'Widgets section', 'olympus' ),
				'type'		 => 'tab',
				'options'	 => array(
					'footer-widgets' => array(
						'title'		 => esc_html__( 'Widgets section', 'olympus' ),
						'type'		 => 'box',
						'options'	 => array(
							'footer-widget-block-columns'	 => array(
								'type'		 => 'slider',
								'value'		 => 3,
								'properties' => array(
									'min'		 => 2,
									'max'		 => 12,
									'step'		 => 1,
									'grid_snap'	 => true,
								),
								'label'		 => esc_html__( 'Widget block width', 'olympus' ),
								'desc'		 => esc_html__( 'Select width in 12 column grid', 'olympus' ),
								'help'		 => esc_html__( 'More about grid and columns you can read here', 'olympus' ) . ' - ' . $grid_link,
							),
							'site_description'				 => array(
								'type'		 => 'multi-picker',
								'label'		 => false,
								'desc'		 => false,
								'picker'	 => array(
									'show' => array(
										'label'			 => esc_html__( 'Show text block', 'olympus' ),
										'type'			 => 'switch',
										'left-choice'	 => array(
											'value'	 => 'no',
											'label'	 => esc_html__( 'Disable', 'olympus' )
										),
										'right-choice'	 => array(
											'value'	 => 'yes',
											'label'	 => esc_html__( 'Enable', 'olympus' )
										),
										'value'			 => 'no',
										'desc'			 => esc_html__( 'Text block with description in footer', 'olympus' ),
									),
								),
								'choices'	 => array(
									'yes' => array(
										'text_columns'		 => array(
											'type'		 => 'slider',
											'value'		 => 4,
											'properties' => array(
												'min'		 => 2,
												'max'		 => 12,
												'step'		 => 1,
												'grid_snap'	 => true,
											),
											'label'		 => esc_html__( 'Text block width', 'olympus' ),
											'desc'		 => esc_html__( 'Select width in 12 column grid', 'olympus' ),
											'help'		 => esc_html__( 'More about grid and columns you can read here', 'olympus' ) . ' - ' . $grid_link,
										),
										'footer-logo'		 => array(
											'type'		 => 'multi-picker',
											'label'		 => false,
											'desc'		 => false,
											'picker'	 => array(
												'show' => array(
													'label'			 => esc_html__( 'Show logotype block?', 'olympus' ),
													'type'			 => 'switch',
													'right-choice'	 => array(
														'value'	 => 'yes',
														'label'	 => esc_html__( 'Show', 'olympus' ),
													),
													'left-choice'	 => array(
														'value'	 => 'no',
														'label'	 => esc_html__( 'Hide', 'olympus' ),
													),
													'value'			 => 'no',
												),
											),
											'choices'	 => array(
												'yes' => array(
													'logo-options' => fw()->theme->get_options( 'settings-logo' ),
												),
											),
										),
										'text'				 => array(
											'type'			 => 'wp-editor',
											'label'			 => esc_html__( 'Text block Content', 'olympus' ),
											'desc'			 => esc_html__( 'Text in left footer column', 'olympus' ),
											'tinymce'		 => true,
											'media_buttons'	 => true,
											'wpautop'		 => true,
											'size'			 => 'small',
											'editor_type'	 => 'tinymce',
											'editor_height'	 => 200,
										),
										'text-alignment'	 => array(
											'label'		 => esc_html__( 'Text block alignment', 'olympus' ),
											'desc'		 => esc_html__( 'Choose the block alignment', 'olympus' ),
											'type'		 => 'image-picker',
											'value'		 => 'flex-start',
											'choices'	 => array(
												'flex-start' => array(
													'small' => array(
														'height' => 50,
														'src'	 => $theme_template_directory . '/images/admin/left-position.jpg',
														'title'	 => esc_html__( 'Left', 'olympus' )
													),
												),
												'center'	 => array(
													'small' => array(
														'height' => 50,
														'src'	 => $theme_template_directory . '/images/admin/center-position.jpg',
														'title'	 => esc_html__( 'Center', 'olympus' )
													),
												),
												'flex-end'	 => array(
													'small' => array(
														'height' => 50,
														'src'	 => $theme_template_directory . '/images/admin/right-position.jpg',
														'title'	 => esc_html__( 'Right', 'olympus' )
													),
												),
											),
										),
										'social_networks'	 => array(
											'type'				 => 'addable-box',
											'label'				 => esc_html__( 'Social networks', 'olympus' ),
											'box-options'		 => array(
												'link'	 => array(
													'label'	 => esc_html__( 'Link to social network page', 'olympus' ),
													'type'	 => 'text',
												),
												'icon'	 => array(
													'label'		 => esc_html__( 'Icon', 'olympus' ),
													'type'		 => 'select',
													'choices'	 => olympus_social_network_icons( true ),
												),
											),
											'template'			 => 'Icon - {{- icon }}',
											'limit'				 => 0,
											'add-button-text'	 => esc_html__( 'Add icon', 'olympus' ),
											'desc'				 => esc_html__( 'Icons of social networks with links to profile', 'olympus' ),
											'sortable'			 => true,
										),
									),
								),
							),
						),
					),
				),
			),
			'footer-copyright-tab'	 => array(
				'title'		 => esc_html__( 'Copyright section', 'olympus' ),
				'type'		 => 'tab',
				'options'	 => array(
					'footer-copyright' => array(
						'title'		 => esc_html__( 'Copyright section', 'olympus' ),
						'type'		 => 'box',
						'options'	 => array(
							'footer_copyright' => array(
								'type'	 => 'textarea',
								'label'	 => esc_html__( 'Copyright text', 'olympus' ),
								'value'	 => 'Site is built on <a href="https://wordpress.org">WordPress</a> by Crumina <a href="https://crumina.net">Theme Development</a>',
							),
						),
					),
				),
			),
			'scroll-to-top-tab'		 => array(
				'title'		 => esc_html__( 'Scroll to top', 'olympus' ),
				'type'		 => 'tab',
				'options'	 => array(
					'scroll-top-box' => array(
						'title'		 => esc_html__( 'Scroll Up Button', 'olympus' ),
						'type'		 => 'box',
						'options'	 => array(
							'scroll_top_icon' => array(
								'type'	 => 'multi-picker',
								'label'	 => false,
								'desc'	 => false,
								'picker' => array(
									'value' => array(
										'label'			 => esc_html__( 'Scroll to top button', 'olympus' ),
										'type'			 => 'switch',
										'right-choice'	 => array(
											'value'	 => 'show',
											'label'	 => esc_html__( 'Show', 'olympus' ),
										),
										'left-choice'	 => array(
											'value'	 => 'hide',
											'label'	 => esc_html__( 'Hide', 'olympus' ),
										),
										'value'			 => 'show',
										'desc'			 => esc_html__( 'Show or hide button that scroll page to top on click.', 'olympus' ),
									),
								)
							),
						)
					),
				),
			),
		)
	)
);
