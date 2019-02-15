<?php

namespace AC\Helper;

class Media {

	/**
	 * @param string $image_url
	 * @param bool   $check_cropped_versions Checks for cropped version of the image. e.g. file-name-320x60.jpg
	 *
	 * @return bool
	 */
	public function get_attachment_id_by_url( $image_url, $check_cropped_versions = false ) {
		if ( ! $image_url ) {
			return false;
		}

		$upload_dir = wp_get_upload_dir();

		// Is image in upload folder?
		if ( false === strpos( $image_url, $upload_dir['baseurl'] ) ) {
			return false;
		}

		$file_with_relative_path = ltrim( str_replace( $upload_dir['baseurl'], '', $image_url ), '/' );

		$image_id = false;

		$images = get_posts( array(
			'post_type'      => 'attachment',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_wp_attached_file',
					'value' => $file_with_relative_path,
				),
			),
			'posts_per_page' => 1,
		) );

		if ( $images ) {
			$image_id = $images[0];
		}

		// Maybe it's a cropped version of an image. e.g. file-name-320x60.jpg
		if ( ! $image_id && $check_cropped_versions ) {

			$relative_upload_dir = dirname( $file_with_relative_path );

			$image_ids = get_posts( array(
				'post_type'      => 'attachment',
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_wp_attachment_metadata',
						'value'   => serialize( basename( $image_url ) ),
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_wp_attached_file',
						'value'   => $relative_upload_dir,
						'compare' => 'LIKE',
					),
				),
				'posts_per_page' => 1,
			) );

			if ( $image_ids ) {

				if ( 1 === count( $image_ids ) ) {

					// Direct match
					$image_id = $image_ids[0];
				} else {

					// Make sure the found image is in the same folder as the original
					foreach ( $image_ids as $_image_id ) {
						if ( 0 === strpos( get_post_meta( $_image_id, '_wp_attached_file', true ), $relative_upload_dir ) ) {
							$image_id = $_image_id;

						}
					}

				}
			}

		}

		return $image_id;
	}

}