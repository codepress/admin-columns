<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class Post extends Settings\Column
	implements Settings\FormatValue {

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
				$setting[] = new Settings\Column\Image( $this->column );
				break;
		}

		$setting[] = new Settings\Column\PostLink( $this->column );

		return $setting;
	}

	/**
	 * @param int   $id
	 * @param mixed $original_value
	 *
	 * @return string|int
	 */
	public function format( $id, $original_value ) {

		switch ( $this->get_post_property_display() ) {

			case 'author' :
				$value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			case 'thumbnail' :
				$value = get_post_thumbnail_id( $id );

				break;
			case 'title' :
				$value = ac_helper()->post->get_title( $id );

				break;
			default :
				$value = $id;
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		$options = array(
			'title'     => __( 'Title' ),
			'id'        => __( 'ID' ),
			'author'    => __( 'Author' ),
			'thumbnail' => _x( 'Featured Image', 'post' ),
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