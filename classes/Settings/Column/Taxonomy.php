<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\View;

class Taxonomy extends Settings\Column {

	/**
	 * @var string
	 */
	private $taxonomy;

	/**
	 * @var string
	 */
	private $post_type;

	public function __construct( AC\Column $column, $post_type = null ) {
		$this->post_type = $post_type;

		parent::__construct( $column );
	}

	/**
	 * @return string
	 */
	protected function get_post_type() {
		return $this->post_type
			?: $this->column->get_post_type();
	}

	protected function define_options() {
		return [ 'taxonomy' ];
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$taxonomy = $this->create_element( 'select', 'taxonomy' );
		$taxonomy->set_no_result( __( 'No taxonomies available.', 'codepress-admin-columns' ) )
		         ->set_options( ac_helper()->taxonomy->get_taxonomy_selection_options( $this->get_post_type() ) )
		         ->set_attribute( 'data-label', 'update' )
		         ->set_attribute( 'data-refresh', 'column' );

		return new View( [
			'setting' => $taxonomy,
			'label'   => __( 'Taxonomy', 'codepress-admin-columns' ),
		] );
	}

	/**
	 * @return string
	 */
	public function get_taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->taxonomy = $taxonomy;

		return true;
	}

}