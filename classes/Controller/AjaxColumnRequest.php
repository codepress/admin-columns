<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Controller\ColumnRequest\Refresh;
use AC\Controller\ColumnRequest\Select;
use AC\Controller\ListScreen\Save;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use LogicException;

class AjaxColumnRequest implements Registerable {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct( Storage $storage, Request $request ) {
		$this->storage = $storage;
		$this->request = $request;
	}

	public function register() {
		$this->get_ajax_handler()->register();
	}

	private function get_ajax_handler(): Ajax\Handler {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac-columns' )
			->set_callback( [ $this, 'handle_ajax_request' ] );

		return $handler;
	}

	public function handle_ajax_request(): void {
		$this->get_ajax_handler()->verify_request();

		switch ( $this->request->get( 'id' ) ) {
			case 'save':
				( new Save( $this->storage ) )->request( $this->request );
				break;
			case 'select':
				( new Select() )->request( $this->request );
				break;
			case 'refresh':
				( new Refresh() )->request( $this->request );
				break;
		}

		throw new LogicException( 'Could not handle request.' );
	}

}