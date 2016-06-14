<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_Image {

	/**
	 * Resize image
	 *
	 * @param string $file
	 * @param int $max_w
	 * @param int $max_h
	 * @param bool $crop
	 * @param null|string $suffix
	 * @param null|string $dest_path
	 * @param int $jpeg_quality
	 *
	 * @return bool|string|WP_Error
	 */
	public function resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$editor = wp_get_image_editor( $file );

		if ( is_wp_error( $editor ) ) {
			return false;
		}

		$editor->set_quality( $jpeg_quality );

		$resized = $editor->resize( $max_w, $max_h, $crop );

		if ( is_wp_error( $resized ) ) {
			return false;
		}

		$dest_file = $editor->generate_filename( $suffix, $dest_path );
		$saved = $editor->save( $dest_file );

		if ( is_wp_error( $saved ) ) {
			return false;
		}

		$resized = $dest_file;

		return $resized;
	}

	// todo: booo on HTML
	public function thumbnail_blocks( $images, $args = array() ) {
		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', self::strip_trim( str_replace( ' ', '', $images ) ) ) );
			} else {
				$images = array( $images );
			}
		}

		// Image size
		$defaults = array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		);
		$args = wp_parse_args( $args, $defaults );

		$image_size = $args['image_size'];
		$image_size_w = $args['image_size_w'];
		$image_size_h = $args['image_size_h'];

		$thumbnails = array();

		foreach ( $images as $value ) {

			if ( self::is_image_url( $value ) ) {

				// get dimensions from image_size
				if ( $sizes = self::get_size_by_name( $image_size ) ) {
					$image_size_w = $sizes['width'];
					$image_size_h = $sizes['height'];
				}

				$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $value );

				if ( is_file( $image_path ) ) {

					// try to resize image
					if ( $resized = self::resize( $image_path, $image_size_w, $image_size_h, true ) ) {
						$thumbnails[] = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) . "' alt='' width='{$image_size_w}' height='{$image_size_h}' />";
					} // return full image with maxed dimensions
					else {
						$thumbnails[] = "<img src='{$value}' alt='' style='max-width:{$image_size_w}px; max-height:{$image_size_h}px' />";
					}
				}
			} // Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {
				$src = '';
				$width = '';
				$height = '';

				if ( ! $image_size || 'cpac-custom' == $image_size ) {
					$width = $image_size_w;
					$height = $image_size_h;

					// to make sure wp_get_attachment_image_src() get the image with matching dimensions.
					$image_size = array( $width, $height );
				}

				// Is Image
				if ( $attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

					$src = $attributes[0];
					$width = $attributes[1];
					$height = $attributes[2];

					// image size by name
					if ( $sizes = self::get_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}
				} // Is File, use icon
				elseif ( $attributes = wp_get_attachment_image_src( $value, $image_size, true ) ) {
					$src = $attributes[0];

					if ( $sizes = self::get_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}
				}

				if ( is_array( $image_size ) ) {
					$width = $image_size_w;
					$height = $image_size_h;

					$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px; background-size: cover; background-image: url({$src}); background-position: center;'></span>";

				} else {
					$max = max( array( $width, $height ) );
					$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$src}' alt=''/></span>";
				}

			}
		}

		return $thumbnails;
	}

	// todo: this is more string function?
	public function is_image_url( $url ) {
		$valid_extensions = array( 'jpg', 'jpeg', 'gif', 'png', 'bmp' );

		return is_string( $url ) && in_array( substr( strrchr( $url, '.' ), 1 ), $valid_extensions );
	}

	// todo: maybe hint more to image_size and connection with WP image sizes
	public function get_size_by_name( $name ) {
		global $_wp_additional_image_sizes;

		if ( ! is_scalar( $name ) || ! isset( $_wp_additional_image_sizes[ $name ] ) ) {
			return false;
		}

		return $_wp_additional_image_sizes[ $name ];
	}

}