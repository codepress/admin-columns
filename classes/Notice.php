<?php

abstract class AC_Notice
	implements AC_Message {

	const SUCCESS = 'notice-updated';

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
	protected $class;

	/**
	 * @param string      $message Message body
	 * @param string|null $type
	 * @param array       $data
	 */
	public function __construct( $message, $type = null ) {
		if ( null === $type ) {
			$type = self::SUCCESS;
		}

		$this->set_message( $message )
		     ->set_type( $type )
		     ->set_class();
	}

	abstract public function display();

	/**
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Only simple HTML is allowed (a, br and strong) the rest is stripped
	 *
	 * @param string $message
	 *
	 * @return $this
	 */
	public function set_message( $message ) {
		$this->message = wp_kses( $message, array(
			'strong' => array(),
			'br'     => array(),
			'a'      => array(
				'class' => true,
				'data'  => true,
				'href'  => true,
				'id'    => true,
				'title' => true,
			),
		) );

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
	 * @param null|string $class
	 *
	 * @return $this
	 */
	protected function set_class( $class = null ) {
		if ( null === $class ) {
			$class = $this->type;
		}

		$this->class = $class;

		return $this;
	}

}