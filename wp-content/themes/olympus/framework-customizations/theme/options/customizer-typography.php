<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
$typography_left_menu_color = get_option( 'side-panel-color', '' );
$options = array(
	'section_typography_colors' => array(
		'title'   => esc_html__( 'Default font colors', 'olympus' ),
		'options' => array(
			'primary_font_color' => array(
				'type'  => 'color-picker',
				'label' => esc_html__('Default font Color', 'olympus' ),
			),
			'accent_font_color' => array(
				'type'  => 'color-picker',
				'label' => esc_html__('Accent font Color', 'olympus' ),
			),
		),
	),
	'section_typography_body' => array(
		'title'   => esc_html__( 'Body font', 'olympus' ),
		'options' => array(
			'typography_body' => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '#888da8',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'Body font', 'olympus' ),
			),
		),
	),
	'section_typography_h1' => array(
		'title'   => esc_html__( 'H1 headings', 'olympus' ),
		'options' => array(
			'typography_h1'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'H1 headings', 'olympus' ),
			),
		),
	),
	'section_typography_h2' => array(
		'title'   => esc_html__( 'H2 headings', 'olympus' ),
		'options' => array(
			'typography_h2'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'H2 headings', 'olympus' ),
			),
		),
	),
	'section_typography_h3' => array(
		'title'   => esc_html__( 'H3 headings', 'olympus' ),
		'options' => array(
			'typography_h3'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'H3 headings', 'olympus' ),
			),
		),
	),
	'section_typography_h4' => array(
		'title'   => esc_html__( 'H4 headings', 'olympus' ),
		'options' => array(
			'typography_h4'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'H4 headings', 'olympus' ),
			),
		),
	),
	'section_typography_h5' => array(
		'title'   => esc_html__( 'H5 headings', 'olympus' ),
		'options' => array(
			'typography_h5'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'color'          => true,
				),
				'label'      => esc_html__( 'H5 headings', 'olympus' ),
			),
		),
	),
	'section_typography_h6' => array(
		'title'   => esc_html__( 'H6 headings', 'olympus' ),
		'options' => array(
			'typography_h6'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'line-height'    => false,
					'letter-spacing' => true,
					'color'          => true,
				),
				'label'      => esc_html__( 'H6 headings', 'olympus' ),
			),
		),
	),
	'section_typography_nav' => array(
		'title'   => esc_html__( 'Menu typography', 'olympus' ),
		'options' => array(
			'typography_nav'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => '',
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'line-height'    => false,
					'letter-spacing' => true,
					'color'          => true,
				),
				'label'      => esc_html__( 'Menu typography', 'olympus' ),
			),
		),
	),
	'section_typography_left_menu' => array(
		'title'   => esc_html__( 'Left menu typography', 'olympus' ),
		'options' => array(
			'typography_left_menu'   => array(
				'type'       => 'typography-v2',
				'value'      => array(
					'family'         => 'Default',
					'subset'         => '',
					'variation'      => '',
					'size'           => '',
					'letter-spacing' => '',
					'color'          => $typography_left_menu_color,
				),
				'components' => array(
					'family'         => true,
					'size'           => true,
					'line-height'    => false,
					'letter-spacing' => true,
					'color'          => true,
				),
				'label'      => esc_html__( 'Left menu typography', 'olympus' ),
			),
		),
	),
);