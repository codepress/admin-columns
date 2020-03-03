<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\View;

class PostType extends Settings\Column {

	/**
	 * @var string
	 */
	private $post_type;

	/**
	 * @var bool
	 */
	private $show_any;

	public function __construct( AC\Column $column, $show_any = false ) {
		parent::__construct( $column );

		$this->show_any = $show_any;
	}

	protected function define_options() {
		return [ 'post_type' ];
	}

	public function create_view() {
		$options = $this->get_post_type_labels();

		if ( $this->show_any ) {
			$options = [ 'any' => __( 'All Post Types', 'codepress-admin-columns' ) ] + $options;
		}

		$setting = $this->create_element( 'select' )
		                ->set_attribute( 'data-label', 'update' )
		                ->set_options( $options );

		$view = new View( [
			'label'   => __( 'Post Type', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );

		return $view;
	}

	private function get_post_type_labels() {
		$options = [];

		$post_types = get_post_types();

		if ( ! is_array( $post_types ) ) {
			return $options;
		}

		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$options[ $post_type ] = $post_type_object->labels->name;
		}

		natcasesort( $options );

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * @param string $post_type
	 *
	 * @return true
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;

		return true;
	}

}