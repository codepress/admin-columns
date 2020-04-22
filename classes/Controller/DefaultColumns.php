<?php

namespace AC\Controller;

use AC;
use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\Registrable;
use AC\Request;

class DefaultColumns implements Registrable {

	const ACTION_KEY = 'save-default-headings';
	const LISTSCREEN_KEY = 'list_screen';

	/** @var ListScreen */
	private $list_screen;

	/** @var Request */
	private $request;

	/** @var DefaultColumns */
	private $default_columns;

	public function __construct( Request $request, DefaultColumnsRepository $default_columns ) {
		$this->request = $request;
		$this->default_columns = $default_columns;
	}

	public function register() {
		add_action( 'admin_init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( '1' !== $this->request->get( self::ACTION_KEY ) ) {
			return;
		}

		if ( ! current_user_can( AC\Capabilities::MANAGE ) ) {
			return;
		}

		$this->list_screen = AC\ListScreenTypes::instance()->get_list_screen_by_key( $this->request->get( self::LISTSCREEN_KEY ) );

		if ( null === $this->list_screen ) {
			return;
		}

		// Save an empty array in case the hook does not run properly.
		$this->default_columns->update( $this->list_screen->get_key(), [] );

		// Our custom columns are set at priority 200. Before they are added we need to store the default column headings.
		add_filter( $this->list_screen->get_heading_hookname(), [ $this, 'save_headings' ], 199 );

		// no render needed
		ob_start();
	}

	public function save_headings( $columns ) {
		ob_end_clean();

		$this->default_columns->update( $this->list_screen->get_key(), $columns && is_array( $columns ) ? $columns : [] );

		exit( 'ac_success' );
	}

}