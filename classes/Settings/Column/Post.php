<?php

class AC_Settings_Column_Post extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $post_property;

	protected function set_name() {
		$this->name = 'post';
	}

	protected function define_options() {
		return array(
			'post_property_display' => 'title',
		);
	}

	public function get_dependent_settings() {
		$setting = array();

		switch ( $this->get_post_property_display() ) {
			case 'thumbnail' :
				$setting[] = new AC_Settings_Column_Image( $this->column );
				break;
		}

		$setting[] = new AC_Settings_Column_PostLink( $this->column );

		return $setting;
	}

	public function format( $value, $original_value ) {
		$id = $original_value;

		switch ( $this->get_post_property_display() ) {
			case 'author' :
				$value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			case 'thumbnail' :
				$value = get_post_thumbnail_id( $id );

				break;
			case 'title' :
				$post = get_post( $id );
				$value = $post->post_title;

				if ( 'attachment' == $post->post_type ) {
					$value = ac_helper()->image->get_file_name( $id );
				}

				break;
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		$options = array(
			'title'     => __( 'Title' ),
			'id'        => __( 'ID' ),
			'author'    => __( 'Author' ),
			'thumbnail' => __( 'Thumbnail' ),
		);

		asort( $options );

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_post_property_display() {
		return $this->post_property;
	}

	/**
	 * @param string $post_property
	 *
	 * @return bool
	 */
	public function set_post_property_display( $post_property ) {
		$this->post_property = $post_property;

		return true;
	}

}