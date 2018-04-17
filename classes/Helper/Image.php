<?php

namespace AC\Helper;

class Image {

	/**
	 * Resize image
	 *
	 * @param string      $file
	 * @param int         $max_w
	 * @param int         $max_h
	 * @param bool        $crop
	 * @param null|string $suffix
	 * @param null|string $dest_path
	 * @param int         $jpeg_quality
	 *
	 * @return bool|string|\WP_Error
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
	 * @param int[]|int    $ids
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
	 * @param int          $id
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
				$image = $this->markup_cover( $src, $size[0], $size[1], $id );
			} else {
				$image = $this->markup( $src, $attributes[1], $attributes[2], $id );
			}
		} // Is File, use icon
		else if ( $attributes = wp_get_attachment_image_src( $id, $size, true ) ) {
			$image = $this->markup( $attributes[0], $this->scale_size( $attributes[1], 0.8 ), $this->scale_size( $attributes[2], 0.8 ), $id, true );
		}

		return $image;
	}

	/**
	 * @param     $size
	 * @param int $scale
	 *
	 * @return float
	 */
	private function scale_size( $size, $scale = 1 ) {
		return round( absint( $size ) * $scale );
	}

	/**
	 * @param string       $url
	 * @param array|string $size
	 *
	 * @return string
	 */
	public function get_image_by_url( $url, $size ) {
		$dimensions = array( 60, 60 );

		if ( is_string( $size ) && ( $sizes = $this->get_image_sizes_by_name( $size ) ) ) {
			$dimensions = array( $sizes['width'], $sizes['height'] );
		} else if ( is_array( $size ) ) {
			$dimensions = $size;
		}

		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $url );

		if ( is_file( $image_path ) ) {
			// try to resize image
			if ( $resized = $this->resize( $image_path, $dimensions[0], $dimensions[1], true ) ) {
				$src = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized );

				$image = $this->markup( $src, $dimensions[0], $dimensions[1] );
			} else {

				$image = $this->markup( $url, $dimensions[0], $dimensions[1] );
			}
		} // External image
		else {
			$image = $this->markup_cover( $image_path, $dimensions[0], $dimensions[1] );
		}

		return $image;
	}

	/**
	 * @param mixed        $images
	 * @param array|string $size
	 * @param bool         $skip_image_check Skips image check. Useful when the url does not have an image extension like jpg or gif (e.g. gravatar).
	 *
	 * @return array
	 */
	public function get_images( $images, $size = 'thumbnail', $skip_image_check = false ) {
		$thumbnails = array();

		foreach ( (array) $images as $value ) {
			if ( $skip_image_check && $value && is_string( $value ) ) {
				$thumbnails[] = $this->get_image_by_url( $value, $size );
			} else if ( ac_helper()->string->is_image( $value ) ) {
				$thumbnails[] = $this->get_image_by_url( $value, $size );
			} // Media Attachment
			else if ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {
				$thumbnails[] = $this->get_image_by_id( $value, $size );
			}
		}

		return $thumbnails;
	}

	/**
	 * @param int|string $image ID of Url
	 * @param string     $size
	 *
	 * @return string
	 */
	public function get_image( $image, $size = 'thumbnail', $skip_image_check = false ) {
		return implode( $this->get_images( $image, $size, $skip_image_check ) );
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

	/**
	 * @param int $attachment_id
	 *
	 * @return bool|string
	 */
	public function get_file_name( $attachment_id ) {
		$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

		if ( ! $file ) {
			return false;
		}

		return basename( $file );
	}

	/**
	 * @param int $attachment_id
	 *
	 * @return string File extension
	 */
	public function get_file_extension( $attachment_id ) {
		return pathinfo( $this->get_file_name( $attachment_id ), PATHINFO_EXTENSION );
	}

	// Helpers

	private function get_file_tooltip_attr( $media_id ) {
		return ac_helper()->html->get_tooltip_attr( $this->get_file_name( $media_id ) );
	}

	private function markup_cover( $src, $width, $height, $media_id = null ) {
		ob_start(); ?>
		<span class="ac-image cpac-cover" data-media-id="<?php echo esc_attr( $media_id ); ?>" style="width:<?php echo esc_attr( $width ); ?>px;height:<?php echo esc_attr( $height ); ?>px;background-size:cover;background-image:url(<?php echo esc_attr( $src ); ?>);background-position:center;"<?php echo $this->get_file_tooltip_attr( $media_id ); ?>></span>

		<?php
		return ob_get_clean();
	}

	private function markup( $src, $width, $height, $media_id = null, $add_extension = false ) {
		$class = false;

		if ( $media_id && ! wp_attachment_is_image( $media_id ) ) {
			$class = ' ac-icon';
		}

		ob_start(); ?>
		<span class="ac-image<?php echo $class; ?>" data-media-id="<?php echo esc_attr( $media_id ); ?>"<?php echo $this->get_file_tooltip_attr( $media_id ); ?>>
			<img style="max-width:<?php echo esc_attr( $width ); ?>px;max-height:<?php echo esc_attr( $height ); ?>px;" src="<?php echo esc_attr( $src ); ?>">

			<?php if ( $add_extension ) : ?>
				<span class="ac-extension"><?php echo esc_attr( $this->get_file_extension( $media_id ) ); ?></span>
			<?php endif; ?>

		</span>

		<?php
		return ob_get_clean();
	}

	/**
	 * Return dimensions and file type
	 *
	 * @see filesize
	 *
	 * @param string $url
	 *
	 * @return false|array
	 */
	public function get_local_image_info( $url ) {
		$path = $this->get_local_image_path( $url );

		if ( ! $path ) {
			return false;
		}

		return getimagesize( $path );
	}

	/**
	 * @param string $url
	 *
	 * @return false|string
	 */
	public function get_local_image_path( $url ) {
		$path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $url );

		if ( ! file_exists( $path ) ) {
			return false;
		}

		return $path;
	}

	/**
	 * @param string $url
	 *
	 * @return false|int
	 */
	public function get_local_image_size( $url ) {
		$path = $this->get_local_image_path( $url );

		if ( ! $path ) {
			return false;
		}

		return filesize( $path );
	}

	/**
	 * @param string $string
	 *
	 * @return array
	 */
	public function get_image_urls_from_string( $string ) {
		if ( ! $string ) {
			return array();
		}

		if ( ! class_exists( 'DOMDocument' ) ) {
			return array();
		}

		$dom = new \DOMDocument;
		@$dom->loadHTML( $string );
		$dom->preserveWhiteSpace = false;

		$urls = array();

		$images = $dom->getElementsByTagName( 'img' );

		foreach ( $images as $img ) {

			/** @var \DOMElement $img */
			$urls[] = $img->getAttribute( 'src' );
		}

		return $urls;
	}

}