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
		$settings = [];

		switch ( $this->get_post_property_display() ) {
			case self::PROPERTY_FEATURED_IMAGE :
				$settings[] = new Settings\Column\Image( $this->column );
				break;
			case self::PROPERTY_DATE :
				$settings[] = new Settings\Column\Date( $this->column );
				break;
			case self::PROPERTY_TITLE :
				$setting = new Settings\Column\CharacterLimit( $this->column );
				$setting->set_default( 30 );

				$settings[] = $setting;
				break;
		}

		$settings[] = new Settings\Column\PostLink( $this->column );

		return $settings;
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
				return ac_helper()->user->get_display_name( ac_helper()->post->get_raw_field( 'post_author', $id ) ) ?: sprintf( '%s (%s)', __( 'No author', 'codepress-admin-columns' ), $id );;
			case self::PROPERTY_FEATURED_IMAGE :
				return get_post_thumbnail_id( $id );
			case self::PROPERTY_TITLE :
				return ac_helper()->post->get_title( $id ) ?: sprintf( '%s (%s)', __( 'No title', 'codepress-admin-columns' ), $id );
			case self::PROPERTY_DATE :
				return ac_helper()->post->get_raw_field( 'post_date', $id );
			default :
				return $id;
		}
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