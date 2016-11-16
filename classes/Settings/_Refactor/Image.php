<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Image extends AC_Settings_FieldAbstract {

	public function __construct() {
		$this->set_type( 'image' );
	}

	public function display() {
		$this->fields( $this->get_args() );
	}

	public function get_args() {
		return array(
			'label'  => __( 'Image Size', 'codepress-admin-columns' ),
			'fields' => array(
				array(
					'type'            => 'select',
					'name'            => 'image_size',
					'grouped_options' => $this->get_grouped_image_sizes(),
					'default_value'   => $this->get_default_value(),
				),
				$this->image_size_width_args(),
				$this->image_size_height_args(),
			),
		);
	}

	private function image_size_width_args() {
		return array(
			'type'          => 'text',
			'name'          => 'image_size_w',
			'label'         => __( "Width", 'codepress-admin-columns' ),
			'description'   => __( "Width in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_w',
			'hidden'        => true,
			'default_value' => 80,
		);
	}

	private function image_size_height_args() {
		return array(
			'type'          => 'text',
			'name'          => 'image_size_h',
			'label'         => __( "Height", 'codepress-admin-columns' ),
			'description'   => __( "Height in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_h',
			'hidden'        => true,
			'default_value' => 80,
		);
	}

	/**
	 * @since 1.0
	 * @return array Image Sizes.
	 */
	private function get_grouped_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array(
			'default' => array(
				'title'   => __( 'Default', 'codepress-admin-columns' ),
				'options' => array(
					'thumbnail' => __( "Thumbnail", 'codepress-admin-columns' ),
					'medium'    => __( "Medium", 'codepress-admin-columns' ),
					'large'     => __( "Large", 'codepress-admin-columns' ),
				),
			),
		);

		$all_sizes = get_intermediate_image_sizes();

		if ( ! empty( $all_sizes ) ) {
			foreach ( $all_sizes as $size ) {
				if ( 'medium_large' == $size || isset( $sizes['default']['options'][ $size ] ) ) {
					continue;
				}

				if ( ! isset( $sizes['defined'] ) ) {
					$sizes['defined']['title'] = __( "Others", 'codepress-admin-columns' );
				}

				$sizes['defined']['options'][ $size ] = ucwords( str_replace( '-', ' ', $size ) );
			}
		}

		// add sizes
		foreach ( $sizes as $key => $group ) {
			foreach ( array_keys( $group['options'] ) as $_size ) {

				$w = isset( $_wp_additional_image_sizes[ $_size ]['width'] ) ? $_wp_additional_image_sizes[ $_size ]['width'] : get_option( "{$_size}_size_w" );
				$h = isset( $_wp_additional_image_sizes[ $_size ]['height'] ) ? $_wp_additional_image_sizes[ $_size ]['height'] : get_option( "{$_size}_size_h" );
				if ( $w && $h ) {
					$sizes[ $key ]['options'][ $_size ] .= " ({$w} x {$h})";
				}
			}
		}

		// last
		$sizes['default']['options']['full'] = __( "Full Size", 'codepress-admin-columns' );

		$sizes['custom'] = array(
			'title'   => __( 'Custom', 'codepress-admin-columns' ),
			'options' => array( 'cpac-custom' => __( 'Custom Size', 'codepress-admin-columns' ) . '..' ),
		);

		return $sizes;
	}

	/*public function display() {
		$this->field( array(
			'type'        => 'text',
			'name'        => 'label',
			'placeholder' => $this->column->get_label(),
			'label'       => __( 'Label', 'codepress-admin-columns' ),
			'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'hidden'      => $this->column->is_hide_label(),
		) );
	}*/

}