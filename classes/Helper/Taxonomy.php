<?php

namespace AC\Helper;

use WP_Term;

class Taxonomy {

	/**
	 * @param WP_Term[]   $terms Term objects
	 * @param null|string $post_type
	 *
	 * @return array
	 */
	public function get_term_links( $terms, $post_type = null ) {
		if ( ! $terms || is_wp_error( $terms ) ) {
			return [];
		}

		$values = [];

		foreach ( $terms as $t ) {
			if ( ! $t instanceof WP_Term ) {
				continue;
			}

			$values[] = ac_helper()->html->link( $this->get_term_url( $t, $post_type ), sanitize_term_field( 'name', $t->name, $t->term_id, $t->taxonomy, 'display' ) );
		}

		return $values;
	}

	public function get_term_url( $term, $post_type = null ) {
		$args = [
			'post_type' => $post_type,
			'taxonomy'  => $term->taxonomy,
			'term'      => $term->slug,
		];

		$page = 'attachment' === $post_type ? 'upload' : 'edit';

		return add_query_arg( $args, $page . '.php' );
	}

	/**
	 * @param WP_Term $term
	 *
	 * @return false|string
	 */
	public function get_term_display_name( $term ) {
		if ( ! $term || is_wp_error( $term ) ) {
			return false;
		}

		return sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'display' );
	}

	/**
	 * @param string $object_type post, page, user etc.
	 * @param string $taxonomy    Taxonomy Name
	 *
	 * @return bool
	 */
	public function is_taxonomy_registered( $object_type, $taxonomy = '' ) {
		if ( ! $object_type || ! $taxonomy ) {
			return false;
		}

		return is_object_in_taxonomy( $object_type, $taxonomy );
	}

	/**
	 * @param string $field
	 * @param int    $term_id
	 * @param string $taxonomy
	 *
	 * @return bool|mixed
	 * @since 3.0
	 */
	public function get_term_field( $field, $term_id, $taxonomy ) {
		$term = get_term_by( 'id', $term_id, $taxonomy );

		if ( ! $term || is_wp_error( $term ) ) {
			return false;
		}

		if ( ! isset( $term->{$field} ) ) {
			return false;
		}

		return $term->{$field};
	}

	/**
	 * @param $post_type
	 *
	 * @return array
	 * @since 3.0
	 */
	public function get_taxonomy_selection_options( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$options = [];
		foreach ( $taxonomies as $index => $taxonomy ) {
			if ( $taxonomy->name == 'post_format' ) {
				unset( $taxonomies[ $index ] );
			}
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		natcasesort( $options );

		return $options;
	}

	/**
	 * @param int    $term_ids
	 * @param string $taxonomy
	 *
	 * @return WP_Term[]
	 */
	public function get_terms_by_ids( $term_ids, $taxonomy ) {
		$terms = [];

		foreach ( (array) $term_ids as $term_id ) {
			$term = get_term( $term_id, $taxonomy );
			if ( $term && ! is_wp_error( $term ) ) {
				$terms[] = $term;
			}
		}

		return $terms;
	}

	public function get_taxonomy_label( $taxonomy, $key = 'name' ) {
		$label = $taxonomy;
		$taxonomy_object = get_taxonomy( $taxonomy );

		if ( ! $taxonomy_object ) {
			return $label;
		}

		$labels = get_taxonomy_labels( $taxonomy_object );

		if ( property_exists( $labels, $key ) ) {
			$label = $labels->$key;
		}

		return $label;
	}

}