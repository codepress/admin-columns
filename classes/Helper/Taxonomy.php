<?php

namespace AC\Helper;

class Taxonomy {

	/**
	 * @param \WP_Term[]  $terms Term objects
	 * @param null|string $post_type
	 *
	 * @return array
	 */
	public function get_term_links( $terms, $post_type = null ) {
		if ( ! $terms || is_wp_error( $terms ) ) {
			return array();
		}

		$values = array();

		foreach ( $terms as $t ) {
			if ( ! is_a( $t, 'WP_Term' ) ) {
				continue;
			}

			$args = array(
				'post_type' => $post_type,
				'taxonomy'  => $t->taxonomy,
				'term'      => $t->slug,
			);

			$page = 'attachment' === $post_type ? 'upload' : 'edit';

			$values[] = ac_helper()->html->link( add_query_arg( $args, $page . '.php' ), sanitize_term_field( 'name', $t->name, $t->term_id, $t->taxonomy, 'display' ) );
		}

		return $values;
	}

	/**
	 * @param \WP_Term $term
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
		if ( ! $object_type ) {
			return false;
		}
		$taxonomies = get_object_taxonomies( $object_type );

		if ( ! $taxonomies ) {
			return false;
		}

		if ( $taxonomy ) {
			return in_array( $taxonomy, $taxonomies );
		}

		return true;
	}

	/**
	 * @since 3.0
	 *
	 * @param string $field
	 * @param int    $term_id
	 * @param string $taxonomy
	 *
	 * @return bool|mixed
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
	 * @since 3.0
	 *
	 * @param $post_type
	 *
	 * @return array
	 */
	public function get_taxonomy_selection_options( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		$options = array();
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
	 * @return \WP_Term[]
	 */
	public function get_terms_by_ids( $term_ids, $taxonomy ) {
		$terms = array();

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