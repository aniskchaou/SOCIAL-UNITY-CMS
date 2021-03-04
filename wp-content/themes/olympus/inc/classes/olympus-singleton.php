<?php
/**
 * Singleton class
 *
 * @package olympus-wp
 */

/**
 * Class Olympus_Singleton
 *
 * Abstract class for all olympus singletons
 */
abstract class Olympus_Singleton {
	/**
	 * Class instance
	 *
	 * !!!this property should be override in child class
	 *
	 * @var static
	 */
	protected static $instance;
	/**
	 * Class data. each key is class property, accessible via magic method __get()
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Olympus_Singleton constructor.
	 */
	protected function __construct() {
	}

	/**
	 * Get class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Get class property
	 *
	 * @param string $name name of class property.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {

		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}

	/**
	 * Check is class property is set
	 *
	 * @param string $name name of class property.
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return isset( $this->data[ $name ] );
	}

	/**
	 * Empty method so object can not be serialized
	 */
	public function __wakeup() {
	}

	/**
	 * Empty method so object can not be cloned
	 */
	public function __clone() {
	}
}