<?php

class AC_Settings_Setting_PostType extends AC_Settings_Setting {

	/**
	 * @var string
	 */
	private $post_type;

	protected function set_managed_options() {
		$this->managed_options = array( 'post_type' );
	}

	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_options( $this->get_post_type_labels() );

		$view = new AC_View( array(
			'label'   => __( 'Post Type', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	private function get_post_type_labels() {
		$options = array();
		$post_types = AC()->get_post_types();

		if ( ! is_array( $post_types ) ) {
			return $options;
		}

		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$options[ $post_type ] = $post_type_object->labels->name;
		}

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
	 * @return $this
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;

		return $this;
	}

}