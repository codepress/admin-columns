<?php

namespace AC\Meta;

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

	public function create_with_post_type( string $meta_key, string $meta_type, string $post_type ): Query {
		$query = $this->create( $meta_key, $meta_type );

		if ( $post_type ) {
			$query->where_post_type( $post_type );
		}

		return $query;
	}

}