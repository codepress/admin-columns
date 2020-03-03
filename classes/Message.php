<?php

namespace AC;

use Exception;
use LogicException;

abstract class Message {

	const SUCCESS = 'updated';
	const ERROR = 'notice-error';
	const WARNING = 'notice-warning';
	const INFO = 'notice-info';

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @param string $message
	 */
	public function __construct( $message ) {
		$this->type = self::SUCCESS;
		$this->message = trim( $message );

		$this->validate();
	}

	protected function validate() {
		if ( empty( $this->message ) ) {
			throw new LogicException( 'Message cannot be empty' );
		}
	}

	/**
	 * Render an View
	 * @return string
	 */
	abstract public function render();

	/**
	 * Display self::render to the screen
	 * @throws Exception
	 */
	public function display() {
		echo $this->render();
	}

	/**
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}
}