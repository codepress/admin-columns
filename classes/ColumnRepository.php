<?php

namespace AC;

use AC\ColumnRepository\Filter;
use AC\ColumnRepository\Sort;

class ColumnRepository {

	const PARAM_FILTER = 'filter';
	const PARAM_SORT = 'sort';

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
	 * @param array $args
	 *
	 * @return Column[]
	 */
	public function find_all( array $args = [] ) {
		$args = array_merge( [
			self::PARAM_FILTER => null,
			self::PARAM_SORT   => null,
		], $args );

		$columns = $this->list_screen->get_columns();

		if ( $args[ self::PARAM_FILTER ] instanceof Filter ) {
			$columns = $args[ self::PARAM_FILTER ]->filter( $columns );
		}

		if ( $args[ self::PARAM_SORT ] instanceof Sort ) {
			$columns = $args[ self::PARAM_SORT ]->sort( $columns );
		}

		return $columns;
	}

}