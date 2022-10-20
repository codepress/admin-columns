<?php

namespace AC\ColumnRepository;

interface Filter {

	public function filter( array $columns ): array;

}