<?php
/**
 * @package
 */

/**
 * Autoload function for olympus theme classes
 *
 * @param string $class_name class name.
 *
 * @return bool true if class found and false otherwise
 */
function olympus_class_autoload( $class_name ) {

	$class_name = strtolower( $class_name );

	// check if this olympus theme class.
	if ( false === strpos( $class_name, 'olympus' ) ) {
		return false;
	}

	// define class folder.
	if ( false !== strpos( $class_name, 'template' ) ) {
		$class_folder = '/inc/classes/templates/';
	} elseif ( false !== strpos( $class_name, 'parts' ) ) {
		$class_folder = '/inc/classes/parts/';
	} else {
		$class_folder = '/inc/classes/';
	}

	// full path to class.
	$class_path = get_template_directory() . $class_folder . str_replace( '_', '-', $class_name ) . '.php';
	if ( file_exists( $class_path ) ) {
		require $class_path;

		return true;
	}

	return false;
}

spl_autoload_register( 'olympus_class_autoload', true, true );
