<?php

class AC_Settings_Setting_Type extends AC_Settings_Setting {

	/**
	 * @var string
	 */
	private $type;

	protected function define_options() {
		return array(
			'type' => $this->column->get_type(),
		);
	}

	public function create_view() {
		$type = $this->create_element( 'select' )
		             ->set_options( $this->get_grouped_columns() );

		$view = new AC_View( array(
			'setting' => $type,
			'label'   => __( 'Type', 'codepress-admin-columns' ),
			'tooltip' => __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>',
		) );

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
	 * @param AC_ListScreen $list_screen
	 *
	 * @return array
	 */
	private function get_grouped_columns() {
		$columns = array();

		// get columns and sort them
		foreach ( $this->column->get_list_screen()->get_column_types() as $column ) {
			$group = $column->get_group();

			// Labels with html will be replaced by the it's name.
			$columns[ $group ][ $column->get_type() ] = $this->get_clean_label( $column );

			if ( ! $column->is_original() ) {
				natcasesort( $columns[ $group ] );
			}
		}

		$grouped = array();

		// create select
		foreach ( AC()->groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			$grouped[ $slug ]['options'] = $columns[ $slug ];
		}

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
	 * @return bool
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return true;
	}

}