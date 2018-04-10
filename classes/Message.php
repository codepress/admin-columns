<?php

abstract class AC_Message {

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

	public function __construct() {
		$this->type = self::SUCCESS;
	}

	/**
	 * Create a view that can be rendered
	 *
	 * @return AC_View
	 */
	abstract protected function create_view();

	/**
	 * Render an AC_View
	 *
	 * @return string
	 * @throws Exception
	 */
	public function render() {
		if ( empty( $this->message ) ) {
			throw new Exception( 'Message cannot be empty' );
		}

		$view = $this->create_view();

		if ( ! ( $view instanceof AC_View ) ) {
			throw new Exception( 'AC_Notice::create_view should return an instance of AC_View' );
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
		$sanitized = wp_kses( $message, array(
			'strong' => true,
			'br'     => true,
			'a'      => array(
				'class' => true,
				'data'  => true,
				'href'  => true,
				'id'    => true,
				'title' => true,
			),
			'div'    => array(
				'class' => true,
			),
			'p'      => array(
				'class' => true,
			),
		) );

		$this->message = $sanitized;

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

}