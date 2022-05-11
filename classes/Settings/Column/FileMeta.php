<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Settings;
use AC\View;

class FileMeta extends Settings\Column {

	const NAME = 'media_meta';

	/**
	 * @var string
	 */
	private $media_meta_key;

	/**
	 * @var array
	 */
	private $meta_options;

	/**
	 * @var string
	 */
	private $default_option;

	public function __construct( Column $column, array $meta_options, $default_option ) {
		parent::__construct( $column );

		$this->meta_options = $meta_options;
		$this->default_option = (string) $default_option;
	}

	protected function set_name() {
		$this->name = self::NAME;
	}

	protected function define_options() {
		return [ 'media_meta_key' => $this->default_option ];
	}

	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_attribute( 'data-label', 'update' )
		                ->set_attribute( 'data-refresh', 'column' )
		                ->set_options( $this->meta_options );

		return new View( [
			'label'   => $this->column->get_label(),
			'setting' => $setting,
		] );
	}

	/**
	 * @return string
	 */
	public function get_media_meta_key() {
		return $this->media_meta_key;
	}

	/**
	 * @param string $media_meta_key
	 *
	 * @return bool
	 */
	public function set_media_meta_key( $media_meta_key ) {
		$this->media_meta_key = (string) $media_meta_key;

		return true;
	}

	/**
	 * @return array
	 */
	public function get_media_meta_keys() {
		return array_filter( array_map( 'trim', explode( '.', $this->media_meta_key ) ) );
	}

}