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

	/**
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		switch ( $this->get_post_property_display() ) {
			case 'author' :
				$value_formatter->value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $value_formatter->get_id() ) );

				break;
			case 'thumbnail' :
				$value_formatter->value = get_post_thumbnail_id( $value_formatter->get_id() );

				break;
			case 'title' :
				$value_formatter->value = ac_helper()->post->get_raw_field( 'post_title', $value_formatter->get_id() );

				break;
		}

		return $value_formatter;
	}

	protected function get_post_type() {
		return $this->column->get_post_type();
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$post_type = get_post_type_object( $this->get_post_type() );

		$view = new AC_View( array(
			'label'   => sprintf( __( '%s Field', 'codepress-admin-columns' ), $post_type->labels->singular_name ),
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