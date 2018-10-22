<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class AvailableSizes extends Column\Media\MetaValue {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-available_sizes' );
		$this->set_label( __( 'Available Sizes', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'sizes';
	}

	public function get_value( $id ) {
		$sizes = $this->get_raw_value( $id );

		if ( ! $sizes ) {
			return $this->get_empty_char();
		}

		$paths = array();

		$available_sizes = $this->get_available_sizes( $sizes );

		if ( $available_sizes ) {

			$url = wp_get_attachment_url( $id );
			$paths[] = ac_helper()->html->tooltip( ac_helper()->html->link( $url, __( 'original', 'codepress-admin-columns' ) ), basename( $url ) );

			foreach ( $available_sizes as $size ) {
				$src = wp_get_attachment_image_src( $id, $size );

				if ( ! empty( $src[0] ) ) {
					$paths[] = ac_helper()->html->tooltip( ac_helper()->html->link( $src[0], $size ), basename( $src[0] ) );
				}
			}
		}

		// include missing image sizes?
		if ( '1' === $this->get_setting( 'include_missing_sizes' )->get_value() ) {

			$missing = $this->get_missing_sizes( $sizes );

			if ( $missing ) {
				foreach ( $missing as $size ) {
					$paths[] = ac_helper()->html->tooltip( $size, sprintf( __( 'Missing image file for size %s.', 'codepress-admin-columns' ), '<em>"' . $size . '"</em>' ), array( 'class' => 'ac-missing-size' ) );
				}
			}
		}

		return "<div class='ac-image-sizes'>" . implode( ac_helper()->html->divider(), $paths ) . "</div>";
	}

	/**
	 * @param array $image_sizes
	 *
	 * @return array
	 */
	public function get_available_sizes( $image_sizes ) {
		return array_intersect( array_keys( (array) $image_sizes ), (array) get_intermediate_image_sizes() );
	}

	/**
	 * @param array $image_sizes
	 *
	 * @return array
	 */
	public function get_missing_sizes( $image_sizes ) {
		global $_wp_additional_image_sizes;

		if ( empty( $_wp_additional_image_sizes ) ) {
			return array();
		}

		$additional_size = $_wp_additional_image_sizes;

		if ( isset( $additional_size['post-thumbnail'] ) ) {
			unset( $additional_size['post-thumbnail'] );
		}

		// image does not have these additional sizes rendered yet
		return array_diff( array_keys( (array) $additional_size ), array_keys( (array) $image_sizes ) );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\MissingImageSize( $this ) );
	}

}