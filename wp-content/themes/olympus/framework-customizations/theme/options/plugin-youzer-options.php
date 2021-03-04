<?php

if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
if ( ! class_exists( 'Youzer' ) ) {
	return;
}

/**
 * Customizer options
 *
 * @var array $options Fill this array with options to generate theme style from frontend Customizer
 */

$options = array(
	'panel_youzer' => array(
		'title'   => esc_html__( 'Youzer', 'olympus' ),
		'options' => array(
			'youzer_options_desc' => array(
				'type'  => 'html',
				'label'        => esc_html__( 'Please be patient', 'olympus' ),
				'html'  => 'All options in that section not work on preview. <strong>Publish</strong> options and <strong>refresh</strong> the page.',
			),
			'enable_youzer_styles' => array(
				'label'        => esc_html__( 'Style customizations', 'olympus' ),
				'desc'         => esc_html__( 'Theme styles and additions for Youzer pages', 'olympus' ),
				'type'         => 'switch',
				'value'        => 'yes',
				'left-choice'  => array(
					'value' => 'no',
					'label' => esc_html__( 'Disable', 'olympus' )
				),
				'right-choice' => array(
					'value' => 'yes',
					'label' => esc_html__( 'Enable', 'olympus' )
				),
			),
			'enable_youzer_icons'  => array(
				'label'        => esc_html__( 'Icon replacement', 'olympus' ),
				'desc'         => esc_html__( 'Theme icons instead of Youzer plugin icons', 'olympus' ),
				'type'         => 'switch',
				'value'        => 'yes',
				'left-choice'  => array(
					'value' => 'no',
					'label' => esc_html__( 'Disable', 'olympus' )
				),
				'right-choice' => array(
					'value' => 'yes',
					'label' => esc_html__( 'Enable', 'olympus' )
				),
			),
		),
	),
);
