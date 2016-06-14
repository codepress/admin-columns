<?php

class AC_Helper_Array {

	public function implode_recursive( $glue, $pieces ) {
		if ( is_array( $pieces ) ) {
			foreach ( $pieces as $r_pieces ) {
				if ( is_array( $r_pieces ) ) {
					$retVal[] = self::implode_recursive( $glue, $r_pieces );
				} else {
					$retVal[] = $r_pieces;
				}
			}
			if ( isset( $retVal ) && is_array( $retVal ) ) {
				return implode( $glue, $retVal );
			}
		}

		if ( is_scalar( $pieces ) ) {
			return $pieces;
		}

		return false;
	}

	/**
	 * Replace a single key in an associative array
	 *
	 * @since 2.2.7
	 *
	 * @param array $input Input array.
	 * @param int|string $old_key Key to replace.
	 * @param int|string $new_key Key to replace $old_key with
	 */
	public function key_replace( $input, $old_key, $new_key ) {
		$keys = array_keys( $input );
		$old_key_pos = array_search( $old_key, $keys );

		if ( $old_key_pos === false ) {
			return $input;
		}
		
		$keys[ $old_key_pos ] = $new_key;

		return array_combine( $keys, array_values( $input ) );
	}

}