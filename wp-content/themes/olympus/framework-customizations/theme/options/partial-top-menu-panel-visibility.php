<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/*
 * Get default option value, previously stored in Theme Settings
 * TODO: Remove that option in future releases.
 *
 * */
$olympus       = Olympus_Options::get_instance();
$default_value = $olympus->get_option( 'top-menu-panel-options/show', 'yes', $olympus::SOURCE_SETTINGS );

$options = array(
	'top-menu-panel-options' => array(
		'type'   => 'multi-picker',
		'label'  => false,
		'desc'   => false,
		'value'  => $default_value,
		'picker' => array(
			'show' => array(
				'label'   => esc_html__( 'Section Visibility', 'olympus' ),
				'type'    => 'radio',
				'value'   => 'default',
				'inline'  => true,
				'choices' => array(
					'default'        => esc_html__( 'Default', 'olympus' ),
					'yes'            => esc_html__( 'Show', 'olympus' ),
					'no'             => esc_html__( 'Hide', 'olympus' ),
					'yes_for_logged' => esc_html__( 'Show for logged users', 'olympus' ),
				),
			),
		),
	),
);
