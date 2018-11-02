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

	/**
	 * @param string       $message
	 * @param Handler|null $handler
	 */
	public function __construct( $message, Handler $handler = null ) {
		if ( null === $handler ) {
			$handler = new NullHandler();
		}

		$this->handler = $handler;

		parent::__construct( $message );
	}

	public function create_view() {
		return parent::create_view()
			->set_template( 'message/notice/dismissible' )
			->set( 'dismissible_callback', $this->handler->get_params() );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();

		wp_enqueue_script( 'ac-message', AC()->get_url() . 'assets/js/notice-dismissible.js', array(), AC()->get_version(), true );
	}

}