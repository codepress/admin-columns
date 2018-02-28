<?php

class AC_Notice extends AC_View {

	const SUCCESS = 'updated';

	const ERROR = 'error';

	const WARNING = 'notice-warning';

	const INFO = 'notice-info';

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

		$this->set_template( 'notice-text' );
	}

	/**
	 * @return bool
	 */
	public function is_dismissible() {
		return (bool) $this->get( 'dismissible' );
	}

	/**
	 * @param bool   $dismissible
	 * @param string $action
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function set_dismissible( $dismissible, $action = null, array $params = array() ) {
		if ( $dismissible ) {
			$dismissible = $params;

			if ( $action ) {
				$dismissible['action'] = 'ac_notice_dismiss_' . (string) $action;
				$dismissible['_ajax_nonce'] = wp_create_nonce( 'ac-ajax' );
			}
		}

		$this->set( 'dismissible', $dismissible );

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