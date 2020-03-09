<?php

namespace AC\Controller;

use AC;

// todo
abstract class Handler {

	/** @var string */
	private $id;

	public function __construct( $id ) {
		$this->id = $id;
	}

	abstract public function request( AC\Request $request );

	public function get_id() {
		return $this->id;
	}

}