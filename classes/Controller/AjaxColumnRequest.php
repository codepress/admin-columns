<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Controller\ColumnRequest\Save;
use AC\Controller\ColumnRequest\Select;
use AC\ListScreenRepository\Aggregate;
use AC\ListScreenRepository\ListScreenRepository;
use AC\Registrable;
use AC\Request;
use LogicException;

class AjaxColumnRequest implements Registrable {

	/** @var Aggregate */
	private $repository;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct( ListScreenRepository $repository ) {
		$this->repository = $repository;
		$this->request = new Request();
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

		switch ( $this->request->get( 'id' ) ) {
			case 'save':
				return new Save( $this->repository );

			case 'select':
				return new Select();

			case 'refresh':
				return new Refresh();
		}

		throw new LogicException( 'Could not handle request.' );
	}

}