<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

class Entities extends ArrayIterator {

	public function __construct( array $entities, UnqiueValueFormatter $value ) {
		$value_entity_map = [];
		$entities = array_filter( $entities );

		foreach ( $entities as $entity ) {
			$value_entity_map[ $value->format_value_unique( $entity ) ] = $entity;
		}

		parent::__construct( $value_entity_map );
	}

}