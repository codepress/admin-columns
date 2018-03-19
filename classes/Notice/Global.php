<?php

class AC_Notice_Global extends AC_Notice {

	/**
	 * @var bool
	 */
	protected $dismissible;

	/**
	 * @var array
	 */
	protected $dismissible_callback;

	/**
	 * @var string
	 */
	protected $template;

	public function render() {
		$data = array(
			'message'              => $this->message,
			'type'                 => $this->type,
			'dismissible'          => $this->dismissible,
			'dismissible_callback' => $this->dismissible_callback,
		);

		$template = $this->template;

		if ( ! $template ) {
			$template = 'notice/global';
		}

		$view = new AC_View( $data );
		$view->set_template( $template );

		return $view->render();
	}

	/**
	 * @param string $template
	 *
	 * @return $this
	 */
	public function set_template( $template ) {
		$this->template = $template;

		return $this;
	}

	public function display() {
		echo $this->render();
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