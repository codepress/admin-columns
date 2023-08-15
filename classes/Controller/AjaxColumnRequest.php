<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Controller\ColumnRequest\Refresh;
use AC\Controller\ColumnRequest\Select;
use AC\Controller\ListScreen\Save;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use LogicException;

class AjaxColumnRequest implements Registerable {

	private $storage;

	private $list_screen_factory;

	public function __construct( Storage $storage, ListScreenFactory $list_screen_factory ) {
		$this->storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
	}

	public function register(): void
    {
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

		$request = new Request();

		switch ( $request->get( 'id' ) ) {
			case 'save':
				( new Save( $this->storage, $this->list_screen_factory ) )->request( $request );
				break;
			case 'select':
				( new Select( $this->list_screen_factory ) )->request( $request );
				break;
			case 'refresh':
				( new Refresh( $this->list_screen_factory ) )->request( $request );
				break;
		}

		throw new LogicException( 'Could not handle request.' );
	}

}