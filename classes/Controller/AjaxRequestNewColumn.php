<?php

namespace AC\Controller;

use AC\Admin;
use AC\Ajax;
use AC\ListScreenRepository\Aggregate;
use AC\ListScreenRepository\ListScreenRepository;
use AC\Registrable;
use AC\Request;

class AjaxRequestNewColumn implements Registrable {

	/** @var Aggregate */
	private $repository;

	public function __construct( ListScreenRepository $repository ) {
		$this->repository = $repository;
	}

	public function register() {
		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac-columns' )
			->set_callback( [ $this, 'handle_ajax_request' ] );

		return $handler;
	}

	public function handle_ajax_request() {
		$this->get_ajax_handler()->verify_request();

		$request = new Request();

		$requests = [
			new Admin\Request\Column\Save( $this->repository ),
			new Admin\Request\Column\Refresh(),
			new Admin\Request\Column\Select(),
		];

		foreach ( $requests as $handler ) {
			if ( $handler->get_id() === $request->get( 'id' ) ) {
				$handler->request( $request );
			}
		}
	}

}