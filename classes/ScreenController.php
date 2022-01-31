<?php

namespace AC;

class ScreenController implements Registrable {

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * @var array
	 */
	private $headings = [];

	/**
	 * @var DefaultColumnsRepository
	 */
	private $default_columns;

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
		$this->default_columns = new DefaultColumnsRepository();
	}

	public function register() {
		// Headings
		add_filter( $this->list_screen->get_heading_hookname(), [ $this, 'add_headings' ], 200 );

		// Values
		$this->list_screen->set_manage_value_callback();

		do_action( 'ac/table/list_screen', $this->list_screen );
	}

	/**
	 * @param $columns
	 *
	 * @return array
	 * @since 2.0
	 */
	public function add_headings( $columns ) {
		if ( empty( $columns ) ) {
			return $columns;
		}

		if ( ! wp_doing_ajax() ) {
			$this->default_columns->update( $this->list_screen->get_key(), $columns );
		}

		// Run once
		if ( $this->headings ) {
			return $this->headings;
		}

		// Nothing stored. Show default columns on screen.
		if ( ! $this->list_screen->get_settings() ) {
			return $columns;
		}

		// Add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->headings['cb'] = $columns['cb'];
		}

		foreach ( $this->list_screen->get_columns() as $column ) {
			$this->headings[ $column->get_name() ] = $column->get_custom_label();
		}

		return apply_filters( 'ac/headings', $this->headings, $this->list_screen );
	}

}