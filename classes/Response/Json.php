<?php

namespace AC\Response;

class Json {

	const MESSAGE = 'message';

	/**
	 * @var array
	 */
	protected $parameters = array();

	public function send( $status ) {

	}

	public function error() {

	}

	public function success() {

	}

	public function set_parameter( $key, $value ) {

	}

	/**
	 * @param string $message
	 */
	public function set_message( $message ) {
		$this->set_parameter( self::MESSAGE, $message );
	}

}