<?php

namespace AC\Meta;

class QueryMeta extends Query {

	public function __construct( $meta_type, $meta_key, $post_type = null ) {
		parent::__construct( $meta_type );

		$this->join_where( 'meta_key', (string) $meta_key );

		if ( $post_type ) {
			$this->where_post_type( $post_type );
		}
	}

}