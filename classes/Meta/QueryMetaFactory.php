<?php

namespace AC\Meta;

use AC\Column\Meta;
use AC\MetaType;

final class QueryMetaFactory {

	public function create( string $meta_key, string $meta_type ): Query {
		$query = new Query( $meta_type );
		$query->select( 'meta_value' )
		      ->distinct()
		      ->join()
		      ->where( 'meta_value', '!=', '' )
		      ->where( 'meta_key', $meta_key )
		      ->order_by( 'meta_value' );

		return $query;
	}

	public function create_with_post_type( string $meta_key, string $post_type ): Query {
		return $this->create( $meta_key, MetaType::POST )
		            ->where_post_type( $post_type );
	}

	public function create_with_post_types( string $meta_key, array $post_types ): Query {
		return $this->create( $meta_key, MetaType::POST )
		            ->where_post_types( $post_types );
	}

	public function create_by_meta_column( Meta $column ): Query {
		switch ( $column->get_meta_key() ) {
			case MetaType::POST:
				return $this->create_with_post_type( $column->get_meta_key(), $column->get_post_type() );
			default:
				return $this->create( $column->get_meta_key(), $column->get_meta_type() );
		}
	}

}