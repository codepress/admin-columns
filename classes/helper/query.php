<?php

class AC_Helper_Query {

	public function get_meta_query( $key, $value, $type = '' ) {
		if ( 'cpac_empty' === $value ) {
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => $key,
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'   => $key,
					'value' => '',
				)
			);
		}
		else if ( 'cpac_not_empty' === $value ) {
			$meta_query = array(
				'key'     => $key,
				'value'   => '',
				'compare' => '!=',
			);
		}
		else {
			$meta_query = array(
				'key'   => $key,
				'value' => $value,
				'type'  => in_array( $type, array( 'numeric' ) ) ? 'NUMERIC' : 'CHAR'
			);
		}

		return $meta_query;
	}

	public function get_taxonomy_query( $value, $taxonomy ) {
		if ( 'cpac_empty' == $value ) {
			$tax_query = array(
				'taxonomy' => $taxonomy,
				'terms'    => false,
				'operator' => 'NOT EXISTS'
			);
		}
		else if ( 'cpac_not_empty' === $value ) {
			$tax_query = array(
				'taxonomy' => $taxonomy,
				'terms'    => false,
				'operator' => 'EXISTS'
			);
		}
		else {
			$tax_query = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $value
			);
		}

		return $tax_query;
	}
}