<?php

class AC_Helper_Array {

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
	public function get_array_keys_sorted_by_value( $array, $sort_flags = 'natural' ) {
		if ( 'natural' === $sort_flags ) {
			natcasesort( $array );
		}
		else if ( is_int( $sort_flags ) ) {
			asort( $array, $sort_flags );
		}

		return array_keys( $array );
	}
}