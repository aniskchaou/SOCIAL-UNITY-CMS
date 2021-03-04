<?php

if ( ! function_exists( 'olympus_html_tag' ) ) {
	/**
	 * Generate html tag
	 *
	 * @param string $tag Tag name.
	 * @param array $attr Tag attributes.
	 * @param bool|string $end Append closing tag. Also accepts body content.
	 *
	 * @return string The tag's html
	 */
	function olympus_html_tag( $tag, $attr = array(), $end = false ) {
		$html = '<' . $tag . ' ' . olympus_attr_to_html( $attr );

		if ( true === $end ) {
			$html .= '></' . $tag . '>';
		} elseif ( false === $end ) {
			$html .= '/>';
		} else {
			$html .= '>' . $end . '</' . $tag . '>';
		}

		return $html;
	}
}

if ( ! function_exists( 'olympus_attr_to_html' ) ) {
	/**
	 * Generate attributes string for html tag
	 *
	 * @param array $attr_array array('href' => '/', 'title' => 'Test').
	 *
	 * @return string 'href="/" title="Test"'
	 */
	function olympus_attr_to_html( array $attr_array ) {
		$html_attr = '';

		foreach ( $attr_array as $attr_name => $attr_val ) {
			if ( false === $attr_val ) {
				continue;
			}

			$html_attr .= $attr_name . '="' . esc_attr( $attr_val ) . '" ';
		}

		return $html_attr;
	}
}

if ( ! function_exists( 'olympus_akg' ) ) {
	/**
	 * Recursively find a key's value in array
	 *
	 * @param string $keys 'a/b/c'.
	 * @param array|object $array_or_object array or object.
	 * @param null|mixed $default_value default value if key not found.
	 * @param string $keys_delimiter keys delimeter.
	 *
	 * @return null|mixed
	 */
	function olympus_akg( $keys, $array_or_object, $default_value = null, $keys_delimiter = '/' ) {
		if ( ! is_array( $keys ) ) {
			$keys = explode( $keys_delimiter, (string) $keys );
		}

		$key_or_property = array_shift( $keys );
		if ( null === $key_or_property ) {
			return $default_value;
		}

		$is_object = is_object( $array_or_object );

		if ( $is_object ) {
			if ( ! property_exists( $array_or_object, $key_or_property ) ) {
				return $default_value;
			}
		} else {
			if ( ! is_array( $array_or_object ) || ! array_key_exists( $key_or_property, $array_or_object ) ) {
				return $default_value;
			}
		}

		if ( isset( $keys[0] ) ) { // not used count() for performance reasons.
			if ( $is_object ) {
				return olympus_akg( $keys, $array_or_object->{$key_or_property}, $default_value );
			} else {
				return olympus_akg( $keys, $array_or_object[ $key_or_property ], $default_value );
			}
		} else {
			if ( $is_object ) {
				return $array_or_object->{$key_or_property};
			} else {
				return $array_or_object[ $key_or_property ];
			}
		}
	}
}



