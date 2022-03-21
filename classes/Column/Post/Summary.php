<?php

namespace AC\Column\Post;

use AC\Column;
use AC\View;

class Summary extends Column implements Column\DetailedValue {

	public function __construct() {
		$this->set_type( 'column-summary' );
		$this->set_label( __( 'Summary', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return '<a data-modal-value href="#">' . __( 'View Summary', 'codepress-admin-columns' ) . '</a>';
	}

	public function get_detailed_value( $id ) {
		$taxonomies = [];

		foreach ( get_object_taxonomies( $this->get_post_type(), 'objects' ) as $taxonomy ) {
			$terms = $this->get_taxonomy_terms( $id, $taxonomy->name );

			if ( $terms ) {
				$taxonomies[ $taxonomy->label ] = $terms;
			}
		}

		$view = new View( [
			'title'          => get_the_title( $id ),
			'excerpt'        => get_the_excerpt( $id ),
			'status'         => get_post_status( $id ),
			'date_published' => get_the_time( 'Y-m-d H:i:s', $id ),
			'taxonomies'     => $taxonomies,
		] );

		return $view->set_template( 'column/value/summary' )
		            ->render();
	}

	private function get_taxonomy_terms( $id, $taxonomy ) {
		$terms = wp_get_post_terms( $id, $taxonomy, [] );

		return is_wp_error( $terms )
			? []
			: array_map( function ( $term ) {
				return $term->name;
			}, $terms );
	}

}