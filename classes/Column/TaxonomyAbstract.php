<?php
defined( 'ABSPATH' ) or die();

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
abstract class AC_Column_TaxonomyAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-taxonomy';
		$this->properties['label'] = __( 'Taxonomy', 'codepress-admin-columns' );
	}

	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	public function apply_conditional() {
		return ac_helper()->taxonomy->is_taxonomy_registered( $this->get_post_type(), $this->get_taxonomy() );
	}

}