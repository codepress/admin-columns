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
		$id = $value_formatter->get_original_value();

		switch ( $this->get_post_property_display() ) {
			case 'author' :
				$value_formatter->value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			case 'thumbnail' :
				$value_formatter->value = get_post_thumbnail_id( $id );

				break;
			case 'title' :
				if ( $post = get_post( $id ) ) {
					$value_formatter->value = $post->post_title;

					if ( 'attachment' === $post->post_type ) {
						$value_formatter->value = ac_helper()->image->get_file_name( $post->ID );
					}
				}

				break;
		}

		return $value_formatter;
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