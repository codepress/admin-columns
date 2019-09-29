<?php

namespace AC;

class ScreenController implements Registrable {

	/** @var ListScreen */
	private $list_screen;

	/** @var array */
	private $headings = array();

	/** @var DefaultColumns */
	private $default_columns;

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
		$this->default_columns = new DefaultColumns();
	}

	public function register() {
		// Headings
		add_filter( $this->list_screen->get_heading_hookname(), array( $this, 'add_headings' ), 200 );

		// Values
		$this->list_screen->set_manage_value_callback();

		do_action( 'ac/table/list_screen', $this->list_screen );

		if ( $this->is_doing_saving_default_headings() ) {
			ob_start();
		}
	}

	private function is_doing_saving_default_headings() {
		return '1' === filter_input( INPUT_GET, 'save-default-headings' );
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

		if ( ! AC()->is_doing_ajax() ) {
			$this->default_columns->update( $this->list_screen->get_key(), $columns );
		}

		// Break script when storing headings is all we need to do
		if ( $this->is_doing_saving_default_headings() ) {
			ob_end_clean();
			exit( "1" );
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
		// todo: remove
//		if ( ! $this->list_screen->get_columns() ) {
//			$this->list_screen->reset();
//			$this->list_screen->reset_original_columns();
//		}

		foreach ( $this->list_screen->get_columns() as $column ) {
			$this->headings[ $column->get_name() ] = $column->get_custom_label();
		}

		return apply_filters( 'ac/headings', $this->headings, $this->list_screen );
	}

}