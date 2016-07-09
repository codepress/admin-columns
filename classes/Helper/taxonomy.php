<?php

class AC_Helper_Taxonomy {

	/**
	 * @param WP_Term[] $terms Term objects
	 * @param null|string $post_type
	 *
	 * @return string
	 */
	public function display( $terms, $post_type = null ) {
		$value = '';
		if ( $terms ) {
			$out = array();
			$terms = (array) $terms;
			foreach ( $terms as $t ) {
				$args = array(
					'post_type' => $post_type,
					'taxonomy'  => $t->taxonomy,
					'term'      => $t->slug
				);

				$page = 'attachment' === $post_type ? 'upload' : 'edit';

				$out[] = sprintf( '<a href="%s">%s</a>',
					esc_url( add_query_arg( $args, $page . '.php' ) ),
					esc_html( sanitize_term_field( 'name', $t->name, $t->term_id, $t->taxonomy, 'display' ) )
				);
			}

			$value = join( __( ', ' ), $out );
		}

		return $value;
	}

	/**
	 * @param string $object_type Post, User etc.
	 * @param string $taxonomy Taxonomy Name
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
	 * @since NEWVERSION
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

		return $options;
	}

}