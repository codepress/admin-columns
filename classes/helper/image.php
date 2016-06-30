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

		return $dest_file;
	}

	/**
	 * @param string $size
	 *
	 * @return array
	 */
	private function get_image_dimensions( $size ) {
		$dimensions = array(
			'width'  => 80,
			'height' => 80,
		);

		if ( is_array( $size ) ) {
			$dimensions['width'] = $size[0];
			$dimensions['height'] = $size[1];
		}
		else if ( $sizes = $this->get_image_sizes_by_name( $size ) ) {
			$dimensions['width'] = $sizes['width'];
			$dimensions['height'] = $sizes['height'];
		}

		return $dimensions;
	}

	/**
	 * @param int[]|int $ids
	 * @param array|string $size
	 *
	 * @return string HTML Images
	 */
	public function get_images_by_ids( $ids, $size ) {
		$images = array();

		$ids = is_array( $ids ) ? $ids : array( $ids );
		foreach ( $ids as $id ) {
			$images[] = $this->get_image_by_id( $id, $size );
		}

		return implode( $images );
	}

	/**
	 * @param int $id
	 * @param string|array $size
	 *
	 * @return string
	 */
	public function get_image_by_id( $id, $size ) {
		$image = false;

		if ( ! is_numeric( $id ) ) {
			return false;
		}

		// Is Image
		if ( $attributes = wp_get_attachment_image_src( $id, $size ) ) {
			$src = $attributes[0];

			if ( is_array( $size ) ) {
				$image = $this->markup_cover( $src, $size[0], $size[1] );
			}
			else {
				$image = $this->markup( $src, $attributes[1], $attributes[2] );
			}
		}
		// Is File, use icon
		else if ( $attributes = wp_get_attachment_image_src( $id, $size, true ) ) {
			$image = $this->markup( $attributes[0], $this->scale_size( $attributes[1], 0.7 ), $this->scale_size( $attributes[2], 0.7 ) );
		}

		return $image;
	}

	/**
	 * @param $size
	 * @param int $scale
	 *
	 * @return float
	 */
	private function scale_size( $size, $scale = 1 ) {
		return round( absint( $size ) * $scale );
	}

	/**
	 * @param string $url
	 * @param array|string $size
	 *
	 * @return string
	 */
	public function get_image_by_url( $url, $size ) {
		$dimensions = $this->get_image_dimensions( $size );
		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $url );

		if ( is_file( $image_path ) ) {
			// try to resize image
			if ( $resized = $this->resize( $image_path, $dimensions['width'], $dimensions['height'], true ) ) {
				$src = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized );

				$image = $this->markup( $src, $dimensions['width'], $dimensions['height'] );
			}
			else {

				$image = $this->markup( $url, $dimensions['width'], $dimensions['height'] );
			}
		}

		//External image
		else {
			$image = $this->markup_cover( $image_path, $dimensions['width'], $dimensions['height'] );
		}

		return $image;
	}

	/**
	 * @param array|int|string $images
	 * @param array $args
	 *
	 * @return array
	 */
	// TODO: move to custom field column
	public function thumbnail_block( $images, $args = array() ) {
		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', ac_helper()->string->strip_trim( str_replace( ' ', '', $images ) ) ) );
			}
			else {
				$images = array( $images );
			}
		}

		$defaults = array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		);
		$args = wp_parse_args( $args, $defaults );

		$size = $args['image_size'];
		if ( ! $args['image_size'] || 'cpac-custom' == $args['image_size'] ) {
			$size = array( $args['image_size_w'], $args['image_size_h'] );
		}

		$thumbnails = array();
		foreach ( $images as $value ) {
			if ( ac_helper()->string->is_image( $value ) ) {
				$thumbnails[] = $this->get_image_by_url( $value, $size );
			}
			// Media Attachment
			else if ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {
				$thumbnails[] = $this->get_image_by_id( $value, $size );
			}
		}

		return $thumbnails;
	}

	private function markup_cover( $src, $width, $height ) {
		return "<span class='cpac-column-value-image cpac-cover' style='width:{$width}px;height:{$height}px;background-size:cover;background-image:url({$src});background-position:center;'></span>";
	}

	private function markup( $src, $width, $height ) {
		return "<span class='cpac-column-value-image'><img style='max-width:{$width}px;max-height:{$height}px;' src='{$src}' alt=''/></span>";
	}

	/**
	 * @param string $name
	 *
	 * @return array Image sizes
	 */
	public function get_image_sizes_by_name( $name ) {
		global $_wp_additional_image_sizes;

		$sizes = false;

		if ( is_scalar( $name ) && isset( $_wp_additional_image_sizes[ $name ] ) ) {
			$sizes = $_wp_additional_image_sizes[ $name ];
		}

		return $sizes;
	}

}