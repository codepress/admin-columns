<?php

class AC_Helper_Media {

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_default_image_sizes( $args ) {
		return wp_parse_args( $args, array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		) );
	}

	/**
	 * @param string $size
	 *
	 * @return array
	 */
	private function get_image_dimensions( $size ) {
		$dimensions = array(
			'width'  => 80,
			'height' => 80
		);

		if ( is_array( $size ) ) {
			$dimensions['width']  = $size[0];
			$dimensions['height'] = $size[1];

		} elseif ( $sizes = $this->get_image_size_by_name( $size ) ) {
			$dimensions['width']  = $sizes['width'];
			$dimensions['height'] = $sizes['height'];

		}

		return $dimensions;
	}

	/**
	 * @param int $id
	 * @param string|array $size
	 *
	 * @return string
	 */
	public function get_image_by_id( $id, $size ) {
		$dimensions = $this->get_image_dimensions( $size );

		// Is Image
		if ( $attributes = wp_get_attachment_image_src( $id, $size ) ) {
			$src = $attributes[0];

			if ( is_array( $size ) ) {
				return $this->image_cover_markup( $src, $dimensions['width'], $dimensions['height'] );
			} else {
				return $this->image_markup( $src, $dimensions['width'], $dimensions['height'] );
			}
		} // Is File, use icon
		elseif ( $attributes = wp_get_attachment_image_src( $id, $size, true ) ) {
			$src = $attributes[0];

			return $this->image_markup( $src, $dimensions['width'], $dimensions['height'] );
		}
	}

	public function get_image_by_url( $url, $size ) {
		$dimensions = $this->get_image_dimensions( $size );
		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $url );

		if ( is_file( $image_path ) ) {
			// try to resize image
			if ( $resized = $this->image_resize( $image_path, $dimensions['width'], $dimensions['height'], true ) ) {
				$src = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized );

				return $this->image_markup( $src, $dimensions['width'], $dimensions['height'] );
			}
			else {

				return $this->image_markup( $url, $dimensions['width'], $dimensions['height'] );
			}
		} else {
			//External image
			return $this->image_cover_markup( $image_path, $dimensions['width'], $dimensions['height'] );
		}
	}

	public function get_thumbnails( $images, $args = array() ) {
		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', $this->strip_trim( str_replace( ' ', '', $images ) ) ) );
			} else {
				$images = array( $images );
			}
		}

		$size = $args['image_size'];
		if ( ! $args['image_size'] || 'cpac-custom' == $args['image_size'] ) {
			$size = array( $args['image_size_w'], $args['image_size_h'] );
		}

		$thumbnails = array();
		foreach ( $images as $value ) {
			if ( $this->is_image_url( $value ) ) {
				$thumbnails[] = $this->get_image_by_url( $value, $size );
			} // Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {
				$thumbnails[] = $this->get_image_by_id( $value, $size );
			}
		}

		return $thumbnails;
	}

	public function image_cover_markup( $src, $width, $height ) {
		return "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;background-size:cover;background-image:url({$src});background-position:center;'></span>";
	}

	public function image_markup( $src, $width, $height ) {
		return "<span class='cpac-column-value-image'><img style='max-width:{$width}px;max-height:{$height}px;' src='{$src}' alt=''/></span>";
	}

	/**
	 * @since 1.2.0
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	public function is_image_url( $url ) {

		if ( ! is_string( $url ) ) {
			return false;
		}

		$validExt = array( '.jpg', '.jpeg', '.gif', '.png', '.bmp' );
		$ext      = strrchr( $url, '.' );

		return in_array( $ext, $validExt );
	}

	public function get_image_size_by_name( $name = '' ) {
		if ( ! $name || is_array( $name ) ) {
			return false;
		}

		global $_wp_additional_image_sizes;
		if ( ! isset( $_wp_additional_image_sizes[ $name ] ) ) {
			return false;
		}

		return $_wp_additional_image_sizes[ $name ];
	}

	/**
	 * @see image_resize()
	 * @since 2.0
	 * @return string Image URL
	 */
	public function image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
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

	public function strip_trim( $string ) {
		return trim( strip_tags( $string ) );
	}

}