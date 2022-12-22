<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

// TODO remove this base class
class Entities extends ArrayIterator {

	// TODO why the extra formatter?
	public function __construct( array $entities, EntityFormatter $formatter ) {
		$value_entity_map = [];
		$entities = array_filter( $entities );

		foreach ( $entities as $entity ) {
			$value_entity_map[ $formatter->format_entity_value( $entity ) ] = $entity;
		}

		parent::__construct( $value_entity_map );
	}

}