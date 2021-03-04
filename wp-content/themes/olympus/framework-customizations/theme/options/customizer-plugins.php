<?php
if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}
$options = array(
	'panel_plugins'    => array(
		'title'   => esc_html__( 'Plugins options', 'olympus' ),
		'options' => array(
			fw()->theme->get_options( 'plugin-youzer-options' ),
		),
	),
);


