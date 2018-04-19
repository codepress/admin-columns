<?php

namespace AC\Meta;

use AC\Column;

class QueryColumn extends Query {

	/**
	 * @param Column $column
	 */
	public function __construct( Column $column ) {
		parent::__construct( $column->get_list_screen()->get_meta_type() );

		if ( $column instanceof Column\Meta ) {
			$this->join_where( 'meta_key', $column->get_meta_key() );
		}

		if ( $column->get_post_type() ) {
			$this->where_post_type( $column->get_post_type() );
		}
	}

}