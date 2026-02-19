<?php
if ( ! function_exists( 'rgget' ) ) {
	/**
	 * Helper function for getting values from query strings or arrays
	 *
	 * @param string $name The key
	 * @param array $arr The array to search through.  If null, checks query strings.  Defaults to null.
	 *
	 * @return string The value.  If none found, empty string.
	 */
	function rgget( $name, $arr = null ) {
		if ( ! isset( $arr ) ) {
			$arr = $_GET;
		}

		if ( ! is_array( $arr ) ) {
			return '';
		}

		if ( isset( $arr[ $name ] ) ) {
			return $arr[ $name ];
		}

		return '';
	}
}

if ( ! function_exists( 'rgpost' ) ) {
	/**
	 * Helper function to obtain POST values.
	 *
	 * @param string $name The key
	 * @param bool $do_stripslashes Optional. Performs stripslashes_deep.  Defaults to true.
	 *
	 * @return string The value.  If none found, empty string.
	 */
	function rgpost( $name, $do_stripslashes = true ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $do_stripslashes ? stripslashes_deep( $_POST[ $name ] ) : $_POST[ $name ];
		}

		return '';
	}
}

if ( ! function_exists( 'rgar' ) ) {
	/**
	 * Get a specific property of an array without needing to check if that property exists.
	 *
	 * Provide a default value if you want to return a specific value if the property is not set.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param array $arr Array from which the property's value should be retrieved.
	 * @param string $prop Name of the property to be retrieved.
	 * @param string $default_val Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 */
	function rgar( $arr, $prop, $default_val = null ) {

		if ( ! is_array( $arr ) && ! ( is_object( $arr ) && $arr instanceof ArrayAccess ) ) {
			return $default_val;
		}

		if ( isset( $arr[ $prop ] ) ) {
			$value = $arr[ $prop ];
		} else {
			$value = '';
		}

		return empty( $value ) && null !== $default_val ? $default_val : $value;
	}
}

if ( ! function_exists( 'rgars' ) ) {
	/**
	 * Gets a specific property within a multidimensional array.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param array $arr The array to search in.
	 * @param string $name The name of the property to find.
	 * @param string $default_val Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return null|string|mixed The value
	 */
	function rgars( $arr, $name, $default_val = null ) {

		if ( ! is_array( $arr ) && ! ( is_object( $arr ) && $arr instanceof ArrayAccess ) ) {
			return $default_val;
		}

		$names = explode( '/', $name );
		$val   = $arr;
		foreach ( $names as $current_name ) {
			$val = rgar( $val, $current_name, $default_val );
		}

		return $val;
	}
}

if ( ! function_exists( 'rgempty' ) ) {
	/**
	 * Determines if a value is empty.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param string $name The property name to check.
	 * @param array $arr Optional. An array to check through.  Otherwise, checks for POST variables.
	 *
	 * @return bool True if empty.  False otherwise.
	 */
	function rgempty( $name, $arr = null ) {

		if ( is_array( $name ) ) {
			return empty( $name );
		}

		if ( ! $arr ) {
			$arr = $_POST;
		}

		$val = rgar( $arr, $name );

		return empty( $val );
	}
}

if ( ! function_exists( 'rgblank' ) ) {
	/**
	 * Checks if the string is empty
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param string $text The string to check.
	 *
	 * @return bool True if empty.  False otherwise.
	 */
	function rgblank( $text ) {
		return empty( $text ) && ! is_array( $text ) && strval( $text ) != '0'; // phpcs:ignore
	}
}

if ( ! function_exists( 'rgobj' ) ) {
	/**
	 * Gets a property value from an object
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param object $obj The object to check
	 * @param string $name The property name to check for
	 *
	 * @return string The property value
	 */
	function rgobj( $obj, $name ) {
		if ( isset( $obj->$name ) ) {
			return $obj->$name;
		}

		return '';
	}
}
if ( ! function_exists( 'rgexplode' ) ) {
	/**
	 * Converts a delimiter separated string to an array.
	 *
	 * @since  Unknown
	 * @access public
	 *
	 * @param string $sep The delimiter between values
	 * @param string $str The string to convert
	 * @param int $count The expected number of items in the resulting array
	 *
	 * @return array $ary The exploded array
	 */
	function rgexplode( $sep, $str, $count ) {
		$ary = explode( $sep, $str );
		while ( count( $ary ) < $count ) {
			$ary[] = '';
		}

		return $ary;
	}
}
