<?php

if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Customizer options
 *
 * @var array $options Fill this array with options to generate theme style from frontend Customizer
 */

$options = array(
	fw()->theme->get_options( 'customizer-general' ),
	// fw()->theme->get_options( 'customizer-additional' ),
	fw()->theme->get_options( 'customizer-plugins' ),
	fw()->theme->get_options( 'customizer-extensions' ),
	fw()->theme->get_options( 'customizer-custom' ),
	fw()->theme->get_options( 'customizer-design' ),
	fw()->theme->get_options( 'customizer-stunning' ),
	fw()->theme->get_options( 'customizer-content-restriction' )
);
