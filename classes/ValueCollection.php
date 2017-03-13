<?php

class AC_ValueCollection extends AC_Collection {

	public function __construct( array $items = array() ) {
		parent::__construct( $items );

		foreach ( $this->items as $k => $item ) {
			$this->items[ $k ] = new AC_Value( $item );
		}
	}

	public function push( AC_Value $value ) {
		parent::push( $value );
	}

}