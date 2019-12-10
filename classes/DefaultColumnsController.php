<?php

namespace AC;

class DefaultColumnsController implements Registrable {

	const ACTION_KEY = 'save_default_headings';
	const LISTSCREEN_KEY = 'list_screen';

	/** @var ListScreen */
	private $list_screen;

	/** @var DefaultColumns */
	private $default_columns;

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct() {
		$this->default_columns = new DefaultColumns();
	}

	public function register() {
		add_action( 'admin_init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) || '1' !== filter_input( INPUT_GET, self::ACTION_KEY ) ) {
			return;
		}

		$this->list_screen = ListScreenTypes::instance()->get_list_screen_by_key( filter_input( INPUT_GET, self::LISTSCREEN_KEY ) );

		if ( null === $this->list_screen ) {
			return;
		}

		ob_start();

		add_filter( $this->list_screen->get_heading_hookname(), [ $this, 'save_headings' ], 500 );
	}

	public function save_headings( $columns ) {
		if ( $columns ) {
			$this->default_columns->update( $this->list_screen->get_key(), $columns );
		} else {
			$this->default_columns->update( $this->list_screen->get_key(), [] );
		}

		ob_end_clean();
		exit( "1" );
	}

}