<?php

class AC_Message_Notice
	implements AC_Message {

	const SUCCESS = 'updated';

	const ERROR = 'error';

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
	 * @var bool
	 */
	protected $dismissible;

	/**
	 * @var array
	 */
	protected $dismissible_callback;

	/**
	 * @param string      $message Message body
	 * @param string|null $type
	 * @param array       $data
	 */
	public function __construct( $message, $type = null ) {
		if ( null === $type ) {
			$type = self::SUCCESS;
		}

		$this->message = $message;
		$this->type = $type;
	}

	public function display() {
		$data = array(
			'message'              => $this->message,
			'type'                 => $this->type,
			'dismissible'          => $this->dismissible,
			'dismissible_callback' => $this->dismissible_callback,
		);

		$view = new AC_View( $data );
		$view->set_template( 'message/notice' );

		echo $view;
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
	 * @return bool
	 */
	public function is_dismissible() {
		return $this->dismissible;
	}

	/**
	 * @param $dismissible
	 */
	public function set_dismissible( $dismissible ) {
		$this->dismissible = (bool) $dismissible;

		return $this;
	}

	/**
	 * @param string $callback
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function set_dismissible_callback( $callback, array $params = array() ) {
		$this->set_dismissible( true );

		$this->dismissible_callback = array_merge( $params, array(
			'action'      => $callback,
			'_ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		) );

		return $this;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . 'assets/css/notice.css', array(), AC()->get_version() );

		if ( $this->is_dismissible() ) {
			wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice-dismiss.js', array(), AC()->get_version(), true );
		}
	}

}