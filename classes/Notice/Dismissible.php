<?php

class AC_Notice_Dismissible extends AC_Notice {

	/**
	 * @var string
	 */
	private $name;

	public function __construct( $name, $message, $type = 'updated' ) {
		parent::__construct( $message, $type );

		$this->set_name( $name );
		$this->set_template( 'notice/dismissible' );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = sanitize_key( $name );
	}

	public function register() {
		parent::register();

		add_action( 'wp_ajax_ac_dismiss_notice', array( $this, 'dismiss_notice' ) );
	}

	public function dismiss_notice() {
		
	}

	public function scripts() {
		parent::scripts();

		wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . "assets/js/message.js", array(), AC()->get_version(), true );
	}

}