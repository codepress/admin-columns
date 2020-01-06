<?php

namespace AC;

class DefaultColumnsController implements Registrable {

	const ACTION_KEY = 'save-default-headings';
	const LISTSCREEN_KEY = 'list_screen';

	/** @var ListScreen */
	private $list_screen;

	/** @var Request */
	private $request;

	/** @var DefaultColumns */
	private $default_columns;

	public function __construct( Request $request, DefaultColumns $default_columns ) {
		$this->request = $request;
		$this->default_columns = $default_columns;
	}

	public function register() {
		add_action( 'admin_init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) || '1' !== $this->request->get( self::ACTION_KEY ) ) {
			return;
		}

		$this->list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $this->request->get( self::LISTSCREEN_KEY ) );

		if ( null === $this->list_screen ) {
			return;
		}

		// Our custom columns are set at priority 200. Before they are added we need to store the default column headings.
		add_filter( $this->list_screen->get_heading_hookname(), [ $this, 'save_headings' ], 199 );

		// no render needed
		ob_start();
	}

	public function save_headings( $columns ) {
		ob_end_clean();

		$this->default_columns->update( $this->list_screen->get_key(), $columns && is_array( $columns ) ? $columns : [] );
		exit( "1" );
	}

}