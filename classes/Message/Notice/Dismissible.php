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

		$view->set_template( 'message/notice/dismissible' )
		     ->set( 'dismissible_callback', $this->handler->get_params() );

		return $view;
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();

		wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . 'assets/js/notice-dismissible.js', array(), AC()->get_version(), true );
	}

}