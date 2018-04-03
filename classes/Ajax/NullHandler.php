<?php

class AC_Ajax_NullHandler extends AC_Ajax_Handler {

	public function __construct() {
		parent::__construct( 'null' );
	}

	public function register( callable $callback, $priority = 10 ) {
		parent::register( $callback, $priority );
	}

}