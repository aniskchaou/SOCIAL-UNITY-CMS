<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
$options = array(
	'panel_design' => array(
		'title'   => esc_html__( 'Design customize', 'olympus' ),
		'options' => array(
			'section_content_width'    => array(
				'title'   => esc_html__( 'Pages Content Width', 'olympus' ),
				'options' => array(
					'full-content'         => array(
						'label'   => esc_html__( 'Content width', 'olympus' ),
						'type'    => 'radio',
						'value'   => 'full',
						'choices' => array(
							'full'      => esc_html__( 'Full width', 'olympus' ),
							'container' => esc_html__( 'Boxed', 'olympus' ),
						),
					),
					'custom-content-width' => array(
						'type'    => 'multi-picker',
						'label'   => false,
						'desc'    => false,
						'picker'  => 'full-content',
						'choices' => array(
							'container' => array(
								'custom-container-width' => array(
									'label'      => esc_html__( 'Content Width (PX)', 'olympus' ),
									'type'       => 'slider',
									'value'      => 1170,
									'properties' => array(
										'min'  => 640,
										'max'  => 2560,
										'step' => 1,
									),
								)
							),
						),
					),
				),
			),
			'section_row_gaps'         => array(
				'title'   => esc_html__( 'Sections padding', 'olympus' ),
				'options' => array(
					'sections_padding' => array(
						'type'    => 'multi-picker',
						'label'   => false,
						'desc'    => false,
						'picker'  => array(
							'sections_padding_picker' => array(
								'label'   => esc_html__( 'Sections padding', 'olympus' ),
								'type'    => 'radio',
								'value'   => 'medium',
								'inline'  => true,
								'choices' => array(
									'small'  => esc_html__( 'Small', 'olympus' ),
									'medium' => esc_html__( 'Medium', 'olympus' ),
									'large'  => esc_html__( 'Large', 'olympus' ),
									'custom' => esc_html__( 'Custom', 'olympus' ),
								),
							),
						),
						'choices' => array(
							'custom' => array(
								'top'    => array(
									'type'  => 'text',
									'value' => 100,
									'label' => __( 'Padding top', 'olympus' ),
									'desc'  => __( 'Number only', 'olympus' ),
								),
								'bottom' => array(
									'type'  => 'text',
									'value' => 100,
									'label' => __( 'Padding bottom', 'olympus' ),
									'desc'  => __( 'Number only', 'olympus' ),
								),
							),
						),
					),
				)
			),
			'section_colors'           => array(
				'title'   => esc_html__( 'Colors', 'olympus' ),
				'options' => array()
			),
			'section_background_image' => array(
				'title'   => esc_html__( 'Background Image', 'olympus' ),
				'options' => array()
			),
			'section_preloader'        => array(
				'title'   => esc_html__( 'Preloader', 'olympus' ),
				'options' => array(
					'enable-preloader'   => array(
						'label'        => esc_html__( 'Enable Preloader', 'olympus' ),
						'type'         => 'switch',
						'value'        => 'no',
						'left-choice'  => array(
							'value' => 'no',
							'label' => esc_html__( 'No', 'olympus' ),
						),
						'right-choice' => array(
							'value' => 'yes',
							'label' => esc_html__( 'Yes', 'olympus' ),
						),
					),
					'preloader-settings' => array(
						'type'    => 'multi-picker',
						'label'   => false,
						'desc'    => false,
						'picker'  => 'enable-preloader',
						'choices' => array(
							'yes' => array(
								'preloader-timeout' => array(
									'label'      => esc_html__( 'Load time (seconds)', 'olympus' ),
									'type'       => 'slider',
									'value'      => 0.2,
									'properties' => array(
										'min'  => 0.2,
										'max'  => 2,
										'step' => 0.1,
									),
								),
								'preloader-image'   => array(
									'label'       => esc_html__( 'Image', 'olympus' ),
									'type'        => 'upload',
									'images_only' => true,
								),
								'background-color'  => array(
									'type'  => 'color-picker',
									'value' => '#ffffff',
									'label' => esc_html__( 'Background Color', 'olympus' ),
								)
							)
						)
					)
				)
			),
		),
	),
);