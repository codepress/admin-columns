<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;
use LogicException;
use WP_Term;

class TermName implements ValueFormatter {

	public function format_value( $entity ): string {
		if ( ! $entity instanceof WP_Term ) {
			throw new LogicException( 'Invalid term' );
		}

		return $this->get_label( $entity );
	}

	private function get_taxonomies(): array {
		static $taxonomies;

		if ( null === $taxonomies ) {
			$taxonomies = get_taxonomies();
		}

		return $taxonomies;
	}

	private function is_term_post_format( WP_Term $term ): bool {
		$slug = str_replace( 'post-format-', '', $term->slug );

		return 0 === strpos( $term->slug, 'post-format-' ) && in_array( $slug, get_post_format_slugs(), true );
	}

	protected function get_label( WP_Term $term ): string {
		// Remove corrupt post formats. There can be post format added to the
		// DB that are not officially registered. Those are skipped.
		if ( 'post_format' === $term->taxonomy && ! $this->is_term_post_format( $term ) ) {
			return '';
		}

		// Extra check if the taxonomy (still) exists
		if ( ! in_array( $term->taxonomy, $this->get_taxonomies(), true ) ) {
			return '';
		}

		$label = htmlspecialchars_decode( $term->name );

		if ( ! $label ) {
			$label = $term->term_id;
		}

		if ( 0 !== $term->parent ) {
			$label = $this->get_label( get_term_by( 'id', $term->parent, $term->taxonomy ) ) . ' > ' . $label;
		}

		return (string) apply_filters( 'acp/select/formatter/term_name', $label, $term );
	}

}