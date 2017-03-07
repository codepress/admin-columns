<?php

class AC_Meta_QueryColumn extends AC_Meta_Query {

	/**
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		parent::__construct( $column->get_list_screen()->get_meta_type() );

		if ( $column instanceof AC_Column_Meta ) {
			$this->join_where( 'meta_key', $column->get_meta_key() );
		}

		if ( $column->get_post_type() ) {
			$this->where_post_type( $column->get_post_type() );
		}

	}

}