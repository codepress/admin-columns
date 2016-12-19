<?php

class AC_Helper_Array {

	/**
	 * Implode for multi dimensional array
	 *
	 * @since NEWVERSION
	 *
	 * @param string $glue
	 * @param array $pieces
	 *
	 * @return string Imploded array
	 */
	public function implode_recursive( $glue, $pieces ) {
		if ( is_array( $pieces ) ) {
			foreach ( $pieces as $r_pieces ) {
				if ( is_array( $r_pieces ) ) {
					$retVal[] = $this->implode_recursive( $glue, $r_pieces );
				}
				else {
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

	/**
	 * Sorts an array. Return keys after they have been sorted by value.
	 *
	 * @since NEWVERSION
	 *
	 * @uses sort()
	 * @uses natcasesort()
	 *
	 * @param array $array ( [object_id] => [value] )
	 * @param int|string $sort_flags Sorting type flags. See sort().
	 *
	 * @return array Array keys
	 */
	public function get_array_keys_sorted_by_value( $array, $type = 'string' ) {
		if ( 'numeric' === strtolower( $type ) ) {
			asort( $array, SORT_NUMERIC );
		}
		else {
			natcasesort( $array );
		}

		return array_keys( $array );
	}

	/**
	 * Indents any object as long as it has a unique id and that of its parent.
	 *
	 * @since 1.0
	 *
	 * @param array $array
	 * @param int $parentId
	 * @param string $parentKey
	 * @param string $selfKey
	 * @param string $childrenKey
	 *
	 * @return array Indented Array
	 */
	public function indent( $array, $parentId = 0, $parentKey = 'post_parent', $selfKey = 'ID', $childrenKey = 'children' ) {
		$indent = array();

		$i = 0;
		foreach ( $array as $v ) {
			if ( $v->$parentKey == $parentId ) {
				$indent[ $i ] = $v;
				$indent[ $i ]->$childrenKey = $this->indent( $array, $v->$selfKey, $parentKey, $selfKey );

				$i++;
			}
		}

		return $indent;
	}

}