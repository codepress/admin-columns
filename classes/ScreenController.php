<?php

namespace AC;

class ScreenController {

	/** @var ListScreen */
	private $list_screen;

	/** @var array */
	private $headings = array();

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;

		// Headings
		add_filter( $this->list_screen->get_heading_hookname(), array( $this, 'add_headings' ), 200 );

		// Values
		$this->list_screen->set_manage_value_callback();

		do_action( 'ac/table/list_screen', $this->list_screen );
	}

	/**
	 * @since 2.0
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function add_headings( $columns ) {
		if ( empty( $columns ) ) {
			return $columns;
		}

		// Store default headings
		if ( ! AC()->is_doing_ajax() ) {
			$this->list_screen->save_default_headings( $columns );
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

		// On first visit 'columns' can be empty, because they were put in memory before 'default headings'
		// were stored. We force get_columns() to be re-populated.
		if ( ! $this->list_screen->get_columns() ) {
			$this->list_screen->reset();
			$this->list_screen->reset_original_columns();
		}

		foreach ( $this->list_screen->get_columns() as $column ) {
			$this->headings[ $column->get_name() ] = $column->get_custom_label();
		}

		return apply_filters( 'ac/headings', $this->headings, $this->list_screen );
	}

}