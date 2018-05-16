<?php

namespace AC;

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

	public function __construct() {
		$this->type = self::SUCCESS;
	}

	/**
	 * Create a view that can be rendered
	 *
	 * @return View
	 */
	abstract protected function create_view();

	/**
	 * Render an View
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function render() {
		if ( empty( $this->message ) ) {
			throw new \Exception( 'Message cannot be empty' );
		}

		$view = $this->create_view();

		if ( ! ( $view instanceof View ) ) {
			throw new \Exception( 'AC\Notice::create_view should return an instance of View' );
		}

		return $view->render();
	}

	/**
	 * Display self::render to the screen
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
	 * @param string $message
	 *
	 * @return $this
	 */
	public function set_message( $message ) {
		$this->message = $message;

		return $this;
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