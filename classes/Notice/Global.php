<?php

// TODO split up to diss.
class AC_Notice_Global extends AC_Notice {

	/**
	 * @var bool
	 */
	protected $dismissible = false;

	/**
	 * @var AC_Ajax_Handler
	 */
	protected $handler;

	public function __construct( AC_Ajax_Handler $handler = null ) {
		if ( null === $handler ) {
			$handler = new AC_Ajax_NullHandler();
		}

		$this->handler = $handler;
	}

	public function create_view() {
		$data = array(
			'message'     => $this->message,
			'type'        => $this->type,
			'dismissible' => $this->dismissible,
		);

		$view = new AC_View( $data );
		$view->set_template( 'notice/global' );

		return $view;
	}

	public function register() {
		if ( apply_filters( 'ac/suppress_site_wide_notices', false ) ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'display' ) );
		add_action( 'network_admin_notices', array( $this, 'display' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * @return bool
	 */
	public function is_dismissible() {
		return $this->dismissible;
	}

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function set_dismissible( $dismissible ) {
		$this->dismissible = (bool) $dismissible;

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $data
	 *
	 * @return $this
	 */
	public function set_data( $key, $data ) {
		$this->data[ $key ] = $data;

		return $this;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . 'assets/css/notice.css', array(), AC()->get_version() );

		if ( $this->is_dismissible() ) {
			wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice.js', array(), AC()->get_version(), true );
		}
	}

}