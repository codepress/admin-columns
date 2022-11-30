<?php

namespace AC\ColumnRepository;

use AC\Column;

interface Sort {

	/**
	 * @param Column[] $columns
	 *
	 * @return Column[]
	 */
	public function sort( array $columns ): array;

}