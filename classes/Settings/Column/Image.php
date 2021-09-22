<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class Image extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $image_size;

	/**
	 * @var integer
	 */
	private $image_size_w;

	/**
	 * @var integer
	 */
	private $image_size_h;

	protected function set_name() {
		return $this->name = 'image';
	}

	protected function define_options() {
		return [
			'image_size'   => 'cpac-custom',
			'image_size_w' => 60,
			'image_size_h' => 60,
		];
	}

	public function create_view() {
		$width = new View( [
			'setting' => $this->create_element( 'number', 'image_size_w' ),
			'label'   => __( 'Width', 'codepress-admin-columns' ),
			'tooltip' => __( 'Width in pixels', 'codepress-admin-columns' ),
		] );

		$height = new View( [
			'setting' => $this->create_element( 'number', 'image_size_h' ),
			'label'   => __( 'Height', 'codepress-admin-columns' ),
			'tooltip' => __( 'Height in pixels', 'codepress-admin-columns' ),
		] );

		$size = $this->create_element( 'select', 'image_size' )
		             ->set_options( $this->get_grouped_image_sizes() );

		$view = new View( [
			'label'    => __( 'Image Size', 'codepress-admin-columns' ),
			'setting'  => $size,
			'sections' => [ $width, $height ],
		] );

		return $view;
	}

	/**
	 * @return array
	 * @since 1.0
	 */
	private function get_grouped_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = [
			'default' => [
				'title'   => __( 'Default', 'codepress-admin-columns' ),
				'options' => [
					'thumbnail' => __( 'Thumbnail', 'codepress-admin-columns' ),
					'medium'    => __( 'Medium', 'codepress-admin-columns' ),
					'large'     => __( 'Large', 'codepress-admin-columns' ),
				],
			],
		];

		$all_sizes = get_intermediate_image_sizes();

		if ( ! empty( $all_sizes ) ) {
			foreach ( $all_sizes as $size ) {
				if ( 'medium_large' === $size || isset( $sizes['default']['options'][ $size ] ) ) {
					continue;
				}

				if ( ! isset( $sizes['defined'] ) ) {
					$sizes['defined']['title'] = __( 'Others', 'codepress-admin-columns' );
				}

				$sizes['defined']['options'][ $size ] = ucwords( str_replace( '-', ' ', $size ) );
			}
		}

		foreach ( $sizes as $key => $group ) {
			foreach ( array_keys( $group['options'] ) as $_size ) {

				$w = isset( $_wp_additional_image_sizes[ $_size ]['width'] ) ? $_wp_additional_image_sizes[ $_size ]['width'] : get_option( "{$_size}_size_w" );
				$h = isset( $_wp_additional_image_sizes[ $_size ]['height'] ) ? $_wp_additional_image_sizes[ $_size ]['height'] : get_option( "{$_size}_size_h" );

				if ( $w && $h ) {
					$sizes[ $key ]['options'][ $_size ] .= " ($w x $h)";
				}
			}
		}

		$sizes['default']['options']['full'] = __( 'Full Size', 'codepress-admin-columns' );

		$sizes['custom'] = [
			'title'   => __( 'Custom', 'codepress-admin-columns' ),
			'options' => [ 'cpac-custom' => __( 'Custom Size', 'codepress-admin-columns' ) . '&hellip;' ],
		];

		return $sizes;
	}

	/**
	 * @return string
	 */
	public function get_image_size() {
		return $this->image_size;
	}

	/**
	 * @param string $image_size
	 *
	 * @return bool
	 */
	public function set_image_size( $image_size ) {
		$this->image_size = $image_size;

		return true;
	}

	/**
	 * @return int
	 */
	public function get_image_size_w() {
		return $this->image_size_w;
	}

	/**
	 * @param int $image_size_w
	 *
	 * @return bool
	 */
	public function set_image_size_w( $image_size_w ) {
		if ( ! is_numeric( $image_size_w ) ) {
			return false;
		}

		$this->image_size_w = $image_size_w;

		return true;
	}

	/**
	 * @return int
	 */
	public function get_image_size_h() {
		return $this->image_size_h;
	}

	/**
	 * @param int $image_size_h
	 *
	 * @return bool
	 */
	public function set_image_size_h( $image_size_h ) {
		if ( ! is_numeric( $image_size_h ) ) {
			return false;
		}

		$this->image_size_h = $image_size_h;

		return true;
	}

	protected function get_size_args() {
		$size = $this->get_image_size();

		if ( 'cpac-custom' === $size ) {
			$size = [ $this->get_image_size_w(), $this->get_image_size_h() ];
		}

		// fallback size
		if ( empty( $size ) ) {
			$size = [ 60, 60 ];
		}

		return $size;
	}

	public function format( $value, $original_value ) {
		return ac_helper()->image->get_image( $value, $this->get_size_args() );
	}

}