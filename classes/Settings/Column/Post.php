<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class Post extends Settings\Column
	implements Settings\FormatValue {

	const NAME = 'post';

	const PROPERTY_AUTHOR = 'author';
	const PROPERTY_FEATURED_IMAGE = 'thumbnail';
	const PROPERTY_ID = 'id';
	const PROPERTY_TITLE = 'title';
	const PROPERTY_DATE = 'date';

	/**
	 * @var string
	 */
	private $post_property;

	protected function set_name() {
		$this->name = self::NAME;
	}

	protected function define_options() {
		return [
			'post_property_display' => self::PROPERTY_TITLE,
		];
	}

	public function get_dependent_settings() {
		$setting = [];

		switch ( $this->get_post_property_display() ) {
			case self::PROPERTY_FEATURED_IMAGE :
				$setting[] = new Settings\Column\Image( $this->column );
				break;
			case self::PROPERTY_DATE :
				$setting[] = new Settings\Column\Date( $this->column );
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

			case self::PROPERTY_AUTHOR :
				$value = ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			case self::PROPERTY_FEATURED_IMAGE :
				$value = get_post_thumbnail_id( $id );

				break;
			case self::PROPERTY_TITLE :
				$value = ac_helper()->post->get_title( $id );

				break;
			case self::PROPERTY_DATE :
				$value = ac_helper()->post->get_raw_field( 'post_date', $id );

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

		$view = new View( [
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		] );

		return $view;
	}

	protected function get_display_options() {
		$options = [
			self::PROPERTY_TITLE          => __( 'Title' ),
			self::PROPERTY_ID             => __( 'ID' ),
			self::PROPERTY_AUTHOR         => __( 'Author' ),
			self::PROPERTY_FEATURED_IMAGE => _x( 'Featured Image', 'post' ),
			self::PROPERTY_DATE           => __( 'Date' ),
		];

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