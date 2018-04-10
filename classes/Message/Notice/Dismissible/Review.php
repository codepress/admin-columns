<?php

class AC_Message_Notice_Dismissible_Review extends AC_Message_Notice_Dismissible {

	public function create_view() {
		$view = parent::create_view();

		$view->set( 'class', $this->type . ' review' );

		return $view;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		parent::scripts();

		wp_enqueue_script( 'ac-notice-review', AC()->get_plugin_url() . 'assets/js/message-review.js', array( 'jquery' ), AC()->get_version() );
	}

}