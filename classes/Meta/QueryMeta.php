<?php

namespace AC\Meta;

use AC\Column;

class QueryMeta extends Query {

	/**
	 * @param Column $column
	 */
	public function __construct( $meta_type, $meta_key, $post_type = null ) {
		parent::__construct( $meta_type );

		$this->join_where( 'meta_key', $meta_key );

		if ( $post_type ) {
			$this->where_post_type( $post_type );
		}
	}

}