<?php

class AC_Notice extends AC_View {

	const SUCCESS = 'updated';

	const ERROR = 'error';

	const WARNING = 'notice-warning';

	const INFO = 'notice-info';

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var bool
	 */
	protected $dismissible;

	/**
	 * @param string      $message Message body
	 * @param string|null $type
	 */
	public function __construct( $message, $type = null ) {
		if ( null === $type ) {
			$type = self::SUCCESS;
		}

		parent::__construct( array(
			'message' => $message,
			'type'    => $type,
		) );

		$this->set_template( 'notice/message' );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = sanitize_key( $name );

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_dismissible() {
		return $this->dismissible;
	}

	/**
	 * @param bool        $dismissible
	 * @param string|null $name
	 *
	 * @return $this
	 */
	public function set_dismissible( $dismissible, $name = null ) {
		$this->dismissible = $dismissible;

		if ( null !== $name ) {
			$this->set_name( $name );
		}

		return $this;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . 'assets/css/notice.css', array(), AC()->get_version() );

		if ( $this->dismissible ) {
			wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice-dismiss.js', array(), AC()->get_version(), true );
		}
	}

}