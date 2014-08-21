<?php
/**
 * Replace a single key in an associative array
 *
 * @since 2.2.7
 *
 * @param array $input Input array.
 * @param int|string $oldkey Key to replace.
 * @param int|string $newkey Key to replace $oldkey with
 */
function cpac_array_key_replace( $input, $oldkey, $newkey ) {

	$keys = array_keys( $input );

	$oldkey_position = array_search( $oldkey, $keys );

	if ( $oldkey_position === false ) {
		return $input;
	}

	$keys[ $oldkey_position ] = $newkey;

	return array_combine( $keys, array_values( $input ) );
}
