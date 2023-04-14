<?php
declare( strict_types=1 );

namespace AC\Table;

use AC\ArrayIterator;
use AC\Type\ListKey;

class ListKeyCollection extends ArrayIterator {

	public function __construct( array $list_keys = [] ) {
		parent::__construct();

		array_map( [ $this, 'add' ], $list_keys );
	}

	public function add( ListKey $list_key ): void {
		$this->array[] = $list_key;
	}

	/**
	 * @return ListKey[]
	 */
	public function all(): array {
		return $this->get_copy();
	}

}