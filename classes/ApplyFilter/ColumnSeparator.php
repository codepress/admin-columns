<?php

namespace AC\ApplyFilter;

use AC\ApplyFilter;
use AC\Column;

class ColumnSeparator implements ApplyFilter {

	/**
	 * @var Column
	 */
	private $column;

	public function __construct( Column $column ) {
		$this->column = $column;
	}

	public function apply_filters( $value ) {
		return (string) apply_filters( 'ac/column/separator', $value, $this->column );
	}

}