<?php

class AC_Notice_Global extends AC_Notice {

	/**
	 * @var array
	 */
	protected $dismissible;

	public function create_view() {
		$data = array(
			'message'              => $this->message,
			'type'                 => $this->type,
			'dismissible'          => $this->dismissible,
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
	public function get_dismissible() {
		return ! empty( $this->dismissible );
	}

	/**
	 * @param string $callback
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function set_dismissible( array $params = array() ) {
		$this->set_dismissible( true );

		$this->dismissible_callback = array_merge( $params, array(
			'action'      => $callback,
			'_ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		) );

		return $this;
	}

	/**
	 * @param null|string $class
	 *
	 * @return $this
	 */
	protected function set_class( $class = null ) {
		if ( null === $class ) {
			if ( $this->type === self::SUCCESS ) {
				$class = 'updated';
			}

			if ( $this->type === self::ERROR ) {
				$class = 'error';
			}
		}

		parent::set_class( $class );

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