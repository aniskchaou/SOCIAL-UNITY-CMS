<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'top-user-panel-options' => array(
		'type'	 => 'multi-picker',
		'label'	 => false,
		'desc'	 => false,
		'picker' => array(
			'show' => array(
				'label'		 => esc_html__( 'Top User Panel', 'olympus' ),
				'type'		 => 'radio',
				'value'		 => 'default',
				'inline'	 => true,
				'choices'	 => array(
					'default'		 => esc_html__( 'Default', 'olympus' ),
					'yes_for_logged' => esc_html__( 'Show for logged in users only', 'olympus' ),
					'yes'			 => esc_html__( 'Show', 'olympus' ),
					'no'			 => esc_html__( 'Hide', 'olympus' ),
				),
			),
		),
	),
);