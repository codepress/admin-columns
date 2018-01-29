<?php

abstract class AC_ListScreenPost extends AC_ListScreenWP {

	/**
	 * @var string Post type
	 */
	private $post_type;

	/**
	 * @param string $post_type
	 */
	public function __construct( $post_type ) {
		$this->set_meta_type( 'post' );
		$this->set_post_type( $post_type );
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * @param string $post_type
	 */
	protected function set_post_type( $post_type ) {
		$this->post_type = (string) $post_type;
	}

	/**
	 * @param int $id
	 *
	 * @return WP_Post
	 */
	protected function get_object( $id ) {
		return get_post( $id );
	}

	/**
	 * @param string $var
	 *
	 * @return string|false
	 */
	protected function get_post_type_label_var( $var ) {
		$post_type_object = get_post_type_object( $this->get_post_type() );

		return $post_type_object && isset( $post_type_object->labels->{$var} ) ? $post_type_object->labels->{$var} : false;
	}

	/**
	 * @return array
	 */
	public function get_default_orderby() {
		return array( 'date', true );
	}

	/**
	 * Register Taxonomy columns that are set by WordPress. These native columns are registered
	 * by setting 'show_admin_column' to 'true' as an argument in register_taxonomy();
	 * Only supports Post Types.
	 *
	 * @see register_taxonomy
	 */
	private function register_column_native_taxonomies() {
		$taxonomies = get_taxonomies(
			array(
				'show_ui'           => 1,
				'show_admin_column' => 1,
				'_builtin'          => 0,
			),
			'object'
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( in_array( $this->get_post_type(), $taxonomy->object_type ) ) {
				$column = new ACP_Column_NativeTaxonomy();
				$column->set_type( 'taxonomy-' . $taxonomy->name );

				$this->register_column_type( $column );
			}
		}
	}

	/**
	 * Register post specific columns
	 */
	protected function register_column_types() {
		$this->register_column_type( new AC_Column_CustomField );
		$this->register_column_type( new AC_Column_Menu );
		$this->register_column_type( new AC_Column_Actions );

		$this->register_column_native_taxonomies();
	}

}