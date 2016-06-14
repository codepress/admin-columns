<?php

class AC_Helper_Term {

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
}