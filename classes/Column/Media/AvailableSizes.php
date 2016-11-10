<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_AvailableSizes extends AC_Column {

	private $intermediate_image_sizes = null;

	public function __construct() {
		$this->set_type( 'column-available_sizes' );
		$this->set_label( __( 'Available Sizes', 'codepress-admin-columns' ) );
	}

	public function get_intermediate_image_sizes() {
		if ( null === $this->intermediate_image_sizes ) {
			$this->intermediate_image_sizes = get_intermediate_image_sizes();
		}

		return $this->intermediate_image_sizes;
	}

	public function get_available_sizes( $id ) {
		$sizes = $this->get_raw_value( $id );

		return  $sizes ? array_intersect( array_keys( $sizes ), $this->get_intermediate_image_sizes() ) : false;
	}

	public function get_value( $id ) {
		$sizes = $this->get_raw_value( $id );

		if ( ! $sizes ) {
			return ac_helper()->string->get_empty_char();
		}

		$paths = array();

		// available sizes
		if ( $intersect = array_intersect( array_keys( $sizes ), get_intermediate_image_sizes() ) ) {

			$url = wp_get_attachment_url( $id );
			$filename = basename( $url );
			$paths[] = "<a title='{$filename}' href='{$url}'>" . __( 'full size', 'codepress-admin-columns' ) . "</a>";

			foreach ( $intersect as $size ) {
				$src = wp_get_attachment_image_src( $id, $size );

				if ( ! empty( $src[0] ) ) {
					$filename = basename( $src[0] );
					$paths[] = "<a title='{$filename}' href='{$src[0]}' class='available'>{$size}</a>";
				}
			}
		}

		global $_wp_additional_image_sizes;

		if ( ! empty( $_wp_additional_image_sizes ) ) {
			if ( isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
				unset( $_wp_additional_image_sizes['post-thumbnail'] );
			}

			// image does not have these additional sizes rendered yet
			if ( $missing = array_diff( array_keys( $_wp_additional_image_sizes ), array_keys( $sizes ) ) ) {
				foreach ( $missing as $size ) {
					$paths[] = "<span title='Missing size: Try regenerate thumbnails with the plugin: Force Regenerate Thumbnails' href='javascript:;' class='not-available'>{$size}</span>";
				}
			}
		}

		return "<div class='sizes'>" . implode( ac_helper()->html->divider(), $paths ) . "</div>";
	}

	public function get_raw_value( $id ) {
		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		return isset( $meta['sizes'] ) ? $meta['sizes'] : false;
	}

}