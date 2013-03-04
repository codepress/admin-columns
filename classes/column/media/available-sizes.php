<?php

/**
 * CPAC_Column_Media_Available_Sizes
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Available_Sizes extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 = 'column-available_sizes';
		$this->properties['label']	 = __( 'Available Sizes', 'cpac' );

		// call contruct
		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		global $_wp_additional_image_sizes;

		$value = '';

		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		if ( isset( $meta['sizes'] ) ) {

			unset( $_wp_additional_image_sizes['post-thumbnail'] );

			$image_sizes 		= array_keys( $meta['sizes'] );
			$additional_sizes 	= array_keys( $_wp_additional_image_sizes );

			// available size
			if ( $intersect = array_intersect( $image_sizes, get_intermediate_image_sizes() ) ) {

				$url 		= wp_get_attachment_url( $id );
				$filename 	= basename( $url );
				$paths[] 	= "<a title='{$filename}' href='{$url}'>" . __( 'original', 'cpac' ) . "</a>";

				foreach ( $intersect as $size ) {
					$src = wp_get_attachment_image_src( $id, $size );

					if ( ! empty( $src[0] ) ) {
						$filename 	= basename( $src[0] );
						$paths[] 	= "<a title='{$filename}' href='{$src[0]}'>{$size}</a>";
					}
				}

				$value .= "<div class='available_sizes'>" . implode( '<span class="cpac-divider"></span>', $paths ) . "</div>";
			}

			// image does not have these additional sizes rendered yet
			if ( $diff = array_diff( $additional_sizes, $image_sizes ) ) {
				$value .= "<br/><div class='missing_sizes'><span>" . implode( ', ', $diff ) . "</span> (" . __( 'missing', 'cpac' ) . ")</div>";
			}


			//http://wordpress.org/extend/plugins/force-regenerate-thumbnails/
		}

		return $value;
	}
}