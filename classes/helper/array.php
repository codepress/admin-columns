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
	 * @return array Sorted keys
	 */
	public function sort_keys_by_value( $array, $type = 'string' ) {
		if ( 'numeric' === strtolower( $type ) ) {
			asort( $array, SORT_NUMERIC );
		} else {
			natcasesort( $array );
		}

		return array_keys( $array );
	}
}