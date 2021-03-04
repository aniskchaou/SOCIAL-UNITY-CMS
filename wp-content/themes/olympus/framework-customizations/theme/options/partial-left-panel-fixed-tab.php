<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$options = array(
	'title'		 => esc_html__( 'Left Menu Panel', 'olympus' ),
	'type'		 => 'tab',
	'options'	 => array(
		'left-panel-fixed-options' => array(
			'type'	 => 'multi-picker',
			'label'	 => false,
			'desc'	 => false,
			'picker' => array(
				'show' => array(
					'label'		 => esc_html__( 'Left Menu Panel', 'olympus' ),
					'type'		 => 'radio',
					'value'		 => 'default',
					'inline'	 => true,
					'choices'	 => array(
						'default'		 => esc_html__( 'Default', 'olympus' ),
						'yes_for_logged' => esc_html__( 'Show for logged in users only', 'olympus' ),
						'yes'			 => esc_html__( 'Show', 'olympus' ),
						'no'			 => esc_html__( 'Hide', 'olympus' ),
					),
					'desc'		 => esc_html__( 'In order to display aside panel, you need to add the desired menu in Menu settings for "Left Menu Panel" menu location', 'olympus' ),
				),
			),
		),
	),
);
