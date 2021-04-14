<?php

namespace AC;

use AC\ColumnRepository\Filter;
use AC\ColumnRepository\Sort;

class ColumnRepository {

	const ARG_FILTER = 'filter';
	const ARG_SORT = 'sort';

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * @param ListScreen $list_screen
	 */
	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	/**
	 * @param string $column_name
	 *
	 * @return Column|null
	 */
	public function find( $column_name ) {
		$column = $this->list_screen->get_column_by_name( $column_name );

		return $column ?: null;
	}

	/**
	 * @param array $args
	 *
	 * @return Column[]
	 */
	public function find_all( array $args = [] ) {
		$args = array_merge( [
			self::ARG_FILTER => null,
			self::ARG_SORT   => null,
		], $args );

		$columns = $this->list_screen->get_columns();

		if ( $args[ self::ARG_FILTER ] instanceof Filter ) {
			$columns = $args[ self::ARG_FILTER ]->filter( $columns );
		}

		if ( $args[ self::ARG_SORT ] instanceof Sort ) {
			$columns = $args[ self::ARG_SORT ]->sort( $columns );
		}

		return $columns;
	}

}