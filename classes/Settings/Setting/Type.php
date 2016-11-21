<?php

class AC_Settings_Setting_Type extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $type;

	public function __construct( AC_Column $column ) {
		$this->type = $column->get_type();

		parent::__construct( $column );
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'type' );
	}

	/**
	 * Returns the type label as human readable. Basically the same label but without tags or underscores and capitalized.
	 *
	 * @return string
	 */
	private function get_clean_type_label() {
		$column = $this->column;
		$label = $column->settings()->label->get_value();

		// todo: refactor, is now part of the column and needs to be tested used to come from the LS
		if ( $column->is_original() ) {
			$label = $column->get_original_label();
		}

		if ( empty( $label ) ) {
			$label = $column->get_label();
		}

		if ( 0 === strlen( strip_tags( $label ) ) ) {
			$label = $column->get_type();
		}

		return ucfirst( str_replace( '_', ' ', strip_tags( $label ) ) );
	}

	/**
	 * @param AC_ListScreenAbstract $list_screen
	 *
	 * @return mixed|void
	 */
	private function get_grouped_columns() {
		$grouped = array();

		foreach ( $this->column->get_list_screen()->get_column_types() as $type => $class ) {

			/* @var AC_Column $column */
			$column = new $class;
			$group = $column->get_group();

			if ( ! isset( $grouped[ $group ] ) ) {
				$grouped[ $group ]['title'] = $group;
			}

			// Labels with html will be replaced by the it's name.
			$grouped[ $group ]['options'][ $type ] = $this->get_clean_type_label();

			if ( ! $column->is_original() ) {
				natcasesort( $grouped[ $group ]['options'] );
			}
		}

		krsort( $grouped );

		return apply_filters( 'cac/grouped_columns', $grouped, $this );
	}

	public function view() {
		$type = $this->create_element( 'type', 'select' )
		             ->set_options( $this->get_grouped_columns() ); // todo: remove once column has list screen

		$view = new AC_Settings_View();
		$view->set( 'setting', $type )
		     ->set( 'label', __( 'Type', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>' );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

}