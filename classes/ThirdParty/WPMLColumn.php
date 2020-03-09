<?php

namespace AC\ThirdParty;

/**
 * WPML: display correct flags on the overview screens
 */
class WPMLColumn {

	const COLUMN_NAME = 'icl_translations';

	private $column;

	function __construct( $post_type ) {
		add_filter( "manage_{$post_type}_posts_columns", [ $this, 'store_wpml_column' ], 11 ); // priority just after WPML set's it's column
		add_filter( "manage_edit-{$post_type}_columns", [ $this, 'replace_wpml_column' ], 201 ); // priority just after AC overwrite all columns
	}

	public function store_wpml_column( $columns ) {
		if ( empty( $this->column ) && isset( $columns[ self::COLUMN_NAME ] ) ) {
			$this->column = $columns[ self::COLUMN_NAME ];
		}

		return $columns;
	}

	public function replace_wpml_column( $columns ) {
		if ( $this->column && isset( $columns[ self::COLUMN_NAME ] ) ) {
			$columns[ self::COLUMN_NAME ] = $this->column;
		}

		return $columns;
	}
}