<?php

namespace AC\Message\Notice;

use AC\Ajax\Handler;
use AC\Ajax\NullHandler;
use AC\Asset\Script;
use AC\Container;
use AC\Message\Notice;
use AC\View;

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

	public function render() {
		$data = [
			'message'              => $this->message,
			'type'                 => $this->type,
			'id'                   => $this->id,
			'dismissible_callback' => $this->handler->get_params(),
		];

		$view = new View( $data );
		$view->set_template( 'message/notice/dismissible' );

		return $view->render();
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();

		$script = new Script( 'ac-message', Container::get_location()->with_suffix( 'assets/js/notice-dismissible.js' ) );
		$script->enqueue();
	}

}