<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'customize-elements' => array(
		'title'		 => 'Customize elements',
		'type'		 => 'box',
		'priority'	 => 'high',
		'options'	 => apply_filters( 'crumina_option_page_tabs', array(
			'tab-customize-design'	 => array(
				'title'		 => esc_html__( 'Page styles', 'olympus' ),
				'type'		 => 'tab',
				'context'	 => 'high',
				'options'	 => array(
					'general-customize-design' => array(
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
					'full-content' => array(
						'label'   => esc_html__( 'Content width', 'olympus' ),
						'type'    => 'radio',
						'value'   => 'default',
						'choices' => array(
							'default' => esc_html__( 'Default', 'olympus' ),
							'full' => esc_html__( 'Full width', 'olympus' ),
							'container'  => esc_html__( 'Boxed', 'olympus' ),
						),
					),
					'custom-content-width' => array(
						'type'		 => 'multi-picker',
						'label'		 => false,
						'desc'		 => false,
						'picker'  => 'full-content',
						'choices' => array(
							'container' => array(
								'custom-container-width' => array(
									'label'   => esc_html__( 'Content Width (PX)', 'olympus' ),
									'type'	  => 'slider',
									'value' => 1170,
									'properties' => array(
										'min' => 640,
										'max' => 2560,
										'step' => 1,
									),
								)
							),
						),
					)
				),
			),
			'tab-blog-general'		 => array(
				'title'		 => 'Blog options',
				'type'		 => 'tab',
				'priority'	 => 'high',
				'options'	 => fw()->theme->get_options( 'partial-blog-general' ),
			),
			'tab-blog-panel'		 => array(
				'title'		 => 'Sorting panel',
				'type'		 => 'tab',
				'priority'	 => 'high',
				'options'	 => fw()->theme->get_options( 'partial-blog-panel' ),
			),
			'tab-top-user-panel'	 => fw()->theme->get_options( 'partial-top-user-panel' ),
			'tab-top-menu-panel'	 => fw()->theme->get_options( 'partial-top-menu-panel' ),
			'left-panel-fixed-tab'	 => fw()->theme->get_options( 'partial-left-panel-fixed-tab' )
		) ),
	),
);
