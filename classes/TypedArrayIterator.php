<?php

namespace AC;

use LogicException;

abstract class TypedArrayIterator extends ArrayIterator {

	/**
	 * @param array  $collection
	 * @param string $type Optional type to validate the collection against
	 */
	public function __construct( array $array, $type ) {
		parent::__construct( $array );

		$this->validate( $type );
	}

	/**
	 * Optional validation when a type was set
	 *
	 * @param string type
	 *
	 * @throws LogicException
	 */
	protected function validate( $type ) {
		foreach ( $this as $value ) {
			if ( ! $value instanceof $type ) {
				throw new LogicException( sprintf( 'Found object that is not a %s.', $type ) );
			}
		}
	}

}