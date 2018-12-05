<?php

namespace AC\Response;

use LogicException;

class Json {

	const MESSAGE = 'message';

	/**
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * @var int
	 */
	protected $status_code;

	public function send() {
		if ( empty( $this->parameters ) ) {
			throw new LogicException( 'Missing response body.' );
		}

		wp_send_json( $this->parameters, $this->status_code );
	}

	public function error() {
		wp_send_json_error( $this->parameters, $this->status_code );
	}

	public function success() {
		wp_send_json_success( $this->parameters, $this->status_code );
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function set_parameter( $key, $value ) {
		$this->parameters[ $key ] = $value;

		return $this;
	}

	/**
	 * @param array $values
	 *
	 * @return $this
	 */
	public function set_parameters( array $values ) {
		foreach ( $values as $key => $value ) {
			$this->set_parameter( $key, $value );
		}

		return $this;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function set_message( $message ) {
		$this->set_parameter( self::MESSAGE, $message );

		return $this;
	}

	/**
	 * @param int $code
	 *
	 * @return $this
	 */
	public function set_status_code( $code ) {
		$this->status_code = $code;

		return $this;
	}

}