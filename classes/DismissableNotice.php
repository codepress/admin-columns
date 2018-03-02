<?php

abstract class AC_DismissableNotice {

	/**
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * @return void
	 */
	abstract public function display();

	/**
	 * Register hooks
	 */
	public function register() {
		add_action( 'wp_ajax_ac_notice_dismiss_' . $this->get_name(), array( $this, 'ajax_dismiss_notice' ) );
	}

	/**
	 * @return AC_Preferences
	 */
	protected function preference() {
		return new AC_Preferences_Site( 'notices' );
	}

	/**
	 * @return bool
	 */
	public function is_dismissed() {
		return (bool) $this->preference()->get( 'dismiss_' . $this->get_name() );
	}

	public function ajax_dismiss_notice() {
		check_ajax_referer( 'ac-ajax' );

		$this->preference()->set( 'dismiss_' . $this->get_name(), true );
	}

}