<?php

class AC_Settings_Column_Type extends AC_Settings_Column {

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
		$type = $this
			->create_element( 'select' )
			->set_options( $this->get_grouped_columns() );

		// Tooltip
		$tooltip = __( 'Choose a column type.', 'codepress-admin-columns' );

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$tooltip .=  '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em>';

			if ( $this->column->get_name() ) {
				$tooltip .= '<em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>';
			}
		}

		$view = new AC_View( array(
			'setting' => $type,
			'label'   => __( 'Type', 'codepress-admin-columns' ),
			'tooltip' => $tooltip,
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
	private function get_clean_label( AC_Column $column ) {
		$label = $column->get_label();

		if ( 0 === strlen( strip_tags( $label ) ) ) {
			$label = ucfirst( str_replace( '_', ' ', $column->get_type() ) );
		}

		return strip_tags( $label );
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

			/**
			 * @param string $group Group slug
			 * @param AC_Column $column
			 */
			$group = apply_filters( 'ac/column_group', $column->get_group(), $column );

			// Labels with html will be replaced by it's name.
			$columns[ $group ][ $column->get_type() ] = $this->get_clean_label( $column );

			if ( ! $column->is_original() ) {
				natcasesort( $columns[ $group ] );
			}
		}

		$grouped = array();

		// create select options
		foreach ( AC()->column_groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			// hide empty groups
			if ( ! isset( $columns[ $slug ] ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			$grouped[ $slug ]['options'] = $columns[ $slug ];

			unset( $columns[ $slug ] );
		}

		// Add columns to a "default" group when it has an invalid group assigned
		foreach ( $columns as $group => $_columns ) {
			foreach ( $_columns as $name => $label ) {
				$grouped['default']['options'][ $name ] = $label;
			}
		}

		return $grouped;
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