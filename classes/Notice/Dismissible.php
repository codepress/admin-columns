<?php

class AC_Notice_Dismissible extends AC_Notice
	implements AC_Notice_Updatable {

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
		add_action( 'wp_ajax_ac_dismiss_notice', array( $this, 'ajax_dismiss_notice' ) );
	}

	/**
	 * @return AC_Preferences_User
	 */
	private function preference() {
		return new AC_Preferences_User( 'notices' );
	}

	/**
	 * @return bool
	 */
	private function is_dismissed() {
		return (bool) $this->preference()->get( 'dismiss-' . $this->get_name() );
	}

	public function scripts() {
		parent::scripts();

		wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . "assets/js/message.js", array(), AC()->get_version(), true );
	}

	public function render() {
		if ( ! $this->is_dismissed() ) {
			return parent::render();
		}
	}

	/**
	 * Hide notice
	 */
	public function update() {
		if ( $this->get_name() !== filter_input( INPUT_POST, 'name' ) ) {
			return;
		}

		$this->preference()->set( 'dismiss-' . $this->get_name(), true );
	}

}