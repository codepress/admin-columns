<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Settings;
use AC\View;

class MediaMeta extends Settings\Column
	implements Settings\FormatValue {

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
		$this->media_meta_key = $media_meta_key;

		return true;
	}

	public function format( $value, $original_value ) {
		$keys = explode( '/', $this->media_meta_key );

		$_value = $value;

		if ( isset( $keys[0] ) && isset( $_value[ $keys[0] ] ) ) {
			$_value = $_value[ $keys[0] ];
		}

		if ( isset( $keys[1] ) && isset( $_value[ $keys[1] ] ) ) {
			$_value = $_value[ $keys[1] ];
		}

		return is_scalar( $_value )
			? $_value
			: '';
	}

}