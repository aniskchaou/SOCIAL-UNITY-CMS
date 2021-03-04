<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'customize-cat-elements' => array(
		'title'		 => 'Customize category elements',
		'type'		 => 'box',
		'priority'	 => 'high',
		'options'	 => array(
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
		),
	),
);


