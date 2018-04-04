<?php

class AC_Message_Notice_Dismissible extends AC_Message_Notice {

	/**
	 * @var AC_Ajax_Handler
	 */
	protected $handler;

	public function __construct( AC_Ajax_Handler $handler = null ) {
		parent::__construct();

		if ( null === $handler ) {
			$handler = new AC_Ajax_Handler_Null();
		}

		$this->handler = $handler;
	}

	public function create_view() {
		$view = parent::create_view();
		$view->set( 'ajaxdata', $this->handler->get_params() );
		$view->set( 'dismissible', true );

		return $view;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		parent::scripts();

		wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice-dismissible.js', array(), AC()->get_version(), true );
	}

}