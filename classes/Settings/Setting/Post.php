<?php

class AC_Settings_Setting_Post extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

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
				$setting[] = new AC_Settings_Setting_Image( $this->column );
				break;
		}

		$setting[] = new AC_Settings_Setting_PostLink( $this->column );

		return $setting;
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function format( $value, $post_id = null ) {

		switch ( $this->get_post_property_display() ) {

			case 'author' :
				$value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $value ) );
				break;

			case 'thumbnail' :
				$value = get_post_thumbnail_id( $value );
				break;

			case 'title' :
				$value = ac_helper()->post->get_raw_field( 'post_title', $value );
				break;
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Post Field', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	private function get_display_options() {
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