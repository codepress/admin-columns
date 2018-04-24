<?php

namespace AC\Message\Notice;

use AC\Ajax\Handler;
use AC\Ajax\NullHandler;
use AC\Message\Notice;

class Dismissible extends Notice {

	/**
	 * @var Handler
	 */
	protected $handler;

	public function __construct( Handler $handler = null ) {
		parent::__construct();

		if ( null === $handler ) {
			$handler = new NullHandler();
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

		wp_enqueue_script( 'ac-message', AC()->get_url() . 'assets/js/notice-dismissible.js', array(), AC()->get_version(), true );
	}

}