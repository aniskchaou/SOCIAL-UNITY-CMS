<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'post-quote'		 => array(
		'type'		 => 'box',
		'title'		 => esc_html__( 'Quote post options', 'olympus' ),
		'options'	 => array(
			'quote_author'	 => array(
				'type'	 => 'text',
				'label'	 => esc_html__( 'Quote author', 'olympus' ),
			),
			'quote_dopinfo'	 => array(
				'type'	 => 'text',
				'label'	 => esc_html__( 'Author profession', 'olympus' ),
			),
			'quote_avatar'	 => array(
				'type'			 => 'upload',
				'images_only'	 => true,
				'label'			 => esc_html__( 'Author avatar', 'olympus' ),
			),
			'overlay_color'	 => array(
				'type'	 => 'rgba-color-picker',
				'value'	 => 'rgba(124, 90, 194, 0.95)',
				'label'	 => esc_html__( 'Overlay Color', 'olympus' ),
				'help'	 => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
			),
		),
		'context'	 => 'side',
		'priority'	 => 'high',
	),
	'post-video'		 => array(
		'type'		 => 'box',
		'options'	 => array(
			'video_oembed' => array(
				'type'		 => 'oembed',
				'label'		 => esc_html__( 'Link to video', 'olympus' ),
				'desc'		 => esc_html__( 'Enter link for video that will be embedded', 'olympus' ),
				'help'		 => esc_html__( 'More information about WordPress embeds:', 'olympus' ) . '<br> <a href="https://codex.wordpress.org/Embeds">https://codex.wordpress.org/Embeds</a>',
				'preview'	 => array(
					'width'		 => 420, // optional, if you want to set the fixed width to iframe
					'height'	 => 240, // optional, if you want to set the fixed height to iframe
					/**
					 * if is set to false it will force to fit the dimensions,
					 * because some widgets return iframe with aspect ratio and ignore applied dimensions
					 */
					'keep_ratio' => false
				)
			)
		),
		'title'		 => esc_html__( 'Video post options', 'olympus' ),
		'context'	 => 'normal',
		'priority'	 => 'high',
	),
	'post-audio'		 => array(
		'type'		 => 'box',
		'options'	 => array(
			'audio_oembed' => array(
				'type'		 => 'oembed',
				'label'		 => esc_html__( 'Link to audio', 'olympus' ),
				'value'		 => 'https://soundcloud.com/',
				'desc'		 => esc_html__( 'Enter link for audio that will be embedded', 'olympus' ),
				'help'		 => esc_html__( 'More information about WordPress embeds:', 'olympus' ) . '<br> <a href="https://codex.wordpress.org/Embeds">https://codex.wordpress.org/Embeds</a>',
				'preview'	 => array(
					'width'		 => 690, // optional, if you want to set the fixed width to iframe
					'height'	 => 180, // optional, if you want to set the fixed height to iframe
					'keep_ratio' => true
				)
			)
		),
		'title'		 => esc_html__( 'Audio post options', 'olympus' ),
		'context'	 => 'normal',
		'priority'	 => 'high',
	),
	'post-gallery'		 => array(
		'type'		 => 'box',
		'options'	 => array(
			'gallery_images' => array(
				'type'			 => 'multi-upload',
				'label'			 => esc_html__( 'Images in slider on post list:', 'olympus' ),
				'desc'			 => esc_html__( 'Images that will be displayed in slider on post list pages', 'olympus' ),
				'images_only'	 => true,
			)
		),
		'title'		 => esc_html__( 'Gallery post options', 'olympus' ),
		'context'	 => 'side',
		'priority'	 => 'high',
	),
	'customize-design'	 => array(
		'title'		 => 'Customize design',
		'type'		 => 'box',
		'priority'	 => 'high',
		'options'	 => array(
			'tab-post-style'		 => array(
				'title'		 => esc_html__( 'Post style and elements', 'olympus' ),
				'type'		 => 'tab',
				'context'	 => 'normal',
				'options'	 => array(
					'single_post_style'			 => apply_filters( 'crumina_option_single_post_style', fw()->theme->get_options( 'partial-single-post-style' ) ),
					'single_post_elements'		 => array(
						'type'		 => 'multi-picker',
						'label'		 => false,
						'desc'		 => false,
						'picker'	 => array(
							'customize' => array(
								'label'			 => esc_html__( 'Customize elements', 'olympus' ),
								'type'			 => 'switch',
								'value'			 => 'no',
								'left-choice'	 => array(
									'value'	 => 'no',
									'label'	 => esc_html__( 'No', 'olympus' ),
								),
								'right-choice'	 => array(
									'value'	 => 'yes',
									'label'	 => esc_html__( 'Yes', 'olympus' ),
								),
							)
						),
						'choices'	 => array(
							'yes' => array(
								'single_post_elements_popup' => array(
									'type'			 => 'popup',
									'label'			 => esc_html__( 'Custom Elements', 'olympus' ),
									'desc'			 => esc_html__( 'Change custom elements for this page', 'olympus' ),
									'button'		 => esc_html__( 'Change Elements', 'olympus' ),
									'size'			 => 'medium',
									'popup-options'	 => fw()->theme->get_options( 'partial-single-post-elements' )
								),
							),
						),
					),
					'general-customize-design'	 => array(
						'type'		 => 'multi-picker',
						'label'		 => false,
						'desc'		 => false,
						'picker'	 => array(
							'customize' => array(
								'label'			 => esc_html__( 'Customize design', 'olympus' ),
								'type'			 => 'switch',
								'value'			 => 'no',
								'left-choice'	 => array(
									'value'	 => 'no',
									'label'	 => esc_html__( 'No', 'olympus' ),
								),
								'right-choice'	 => array(
									'value'	 => 'yes',
									'label'	 => esc_html__( 'Yes', 'olympus' ),
								),
							)
						),
						'choices'	 => array(
							'yes' => array(
								'general-customize-design-popup' => array(
									'type'			 => 'popup',
									'label'			 => esc_html__( 'Custom Design', 'olympus' ),
									'desc'			 => esc_html__( 'Change custom design for this page', 'olympus' ),
									'button'		 => esc_html__( 'Change Design', 'olympus' ),
									'size'			 => 'medium',
									'popup-options'	 => fw()->theme->get_options( 'partial-general-customize-design' )
								),
							),
						),
					),
				),
			),
			'tab-top-user-panel'	 => fw()->theme->get_options( 'partial-top-user-panel' ),
			'tab-top-menu-panel'	 => fw()->theme->get_options( 'partial-top-menu-panel' ),
			'left-panel-fixed-tab'	 => fw()->theme->get_options( 'partial-left-panel-fixed-tab' )
		),
	),
);
