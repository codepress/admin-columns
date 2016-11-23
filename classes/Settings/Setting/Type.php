<?php

class AC_Settings_Setting_Type extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $type;

	public function __construct( AC_Column $column ) {
		parent::__construct( $column );

		$this->set_default( $column->get_type() );
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'type' );
	}

	public function view() {
		$type = $this->create_element( 'type', 'select' )
		             ->set_attribute( 'data-refresh', 'column' )
		             ->set_options( $this->get_grouped_columns() );

		$view = new AC_Settings_View();
		$view->set( 'setting', $type )
		     ->set( 'label', __( 'Type', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>' );

		return $view;
	}

	/**
	 * Returns the type label as human readable: no tags, underscores and capitalized.
	 *
	 * @param AC_Column|null $column
	 *
	 * @return string
	 */
	public function get_clean_label( AC_Column $column = null ) {
		if ( null === $column ) {
			$column = $this->column;
		}

		$label = $column->get_list_screen()->settings()->get_setting( 'label' );

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

		foreach ( $this->column->get_list_screen()->get_column_types() as $column ) {
			$group = $column->get_group();

			if ( ! isset( $grouped[ $group ] ) ) {
				$grouped[ $group ]['title'] = $group;
			}

			// Labels with html will be replaced by the it's name.
			$grouped[ $group ]['options'][ $column->get_type() ] = $this->get_clean_label( $column );

			if ( ! $column->is_original() ) {
				natcasesort( $grouped[ $group ]['options'] );
			}
		}

		krsort( $grouped );

		// todo: rename filter e.g. ac/settings/setting/type/columns
		return apply_filters( 'cac/grouped_columns', $grouped, $this );
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