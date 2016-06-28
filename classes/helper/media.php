<?php

class AC_Helper_Media {

	private function get_default_image_sizes( $args ){
		return wp_parse_args( $args, array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		) );
	}

	public function get_image_by_id( $id, $args){

	}
	
	public function get_thumbnails( $images, $args = array() ){
		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', $this->strip_trim( str_replace( ' ', '', $images ) ) ) );
			}
			else {
				$images = array( $images );
			}
		}

		// Image size
		$args = $this->get_default_image_sizes( $args );

		$thumbnails = array();
		foreach ( $images as $value ) {

			if ( $this->is_image_url( $value ) ) {
				$image_size = $args['image_size'];
				$image_size_w = $args['image_size_w'];
				$image_size_h = $args['image_size_h'];


				// get dimensions from image_size
				if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
					$image_size_w = $sizes['width'];
					$image_size_h = $sizes['height'];
				}

				$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $value );

				if ( is_file( $image_path ) ) {
					// try to resize image
					if ( $resized = $this->image_resize( $image_path, $image_size_w, $image_size_h, true ) ) {
						$src = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized );
						$thumbnails[] = $this->image_markup( $src, $image_size_w, $image_size_h );
					} // return full image with maxed dimensions
					else {
						$thumbnails[] = $this->image_markup( $value, $image_size_w, $image_size_h );
					}
				} else {
					//External image
					$thumbnails[] = $this->image_markup( $image_path, $image_size_w, $image_size_h );
				}
			} // Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {
				$image_size = $args['image_size'];
				$width = '';
				$height = '';

				if ( ! $image_size || 'cpac-custom' == $image_size ) {
					$width = $args['image_size_w'];
					$height = $args['image_size_h'];

					// to make sure wp_get_attachment_image_src() get the image with matching dimensions.
					$image_size = array( $width, $height );
				}

				// Is Image
				if ( $attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

					$src = $attributes[0];
					$width = $attributes[1];
					$height = $attributes[2];

					// image size by name
					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}

					if ( is_array( $image_size ) ) {
						$thumbnails[] = $this->image_cover_markup( $src, $args['image_size_w'], $args['image_size_h'] );
					}
					else {
						$thumbnails[] = $this->image_markup( $src, $width, $height );
					}


				} // Is File, use icon
				elseif ( $attributes = wp_get_attachment_image_src( $value, $image_size, true ) ) {
					$src = $attributes[0];

					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}

					$thumbnails[] = $this->image_markup( $src, $width, $height );
				}

			}
		}

		return $thumbnails;
	}

	public function image_cover_markup( $src, $width, $height ){
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
	protected function is_image_url( $url ) {

		if ( ! is_string( $url ) ) {
			return false;
		}

		$validExt = array( '.jpg', '.jpeg', '.gif', '.png', '.bmp' );
		$ext = strrchr( $url, '.' );

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