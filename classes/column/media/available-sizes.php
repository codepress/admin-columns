<?php

/**
 * CPAC_Column_Media_Available_Sizes
 *
 * @since 2.0
 */
class CPAC_Column_Media_Available_Sizes extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-available_sizes';
		$this->properties['label']	 = __( 'Available Sizes', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {
		$paths = array();

		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		if ( ! isset( $meta['sizes'] ) )
			return false;

		// available sizes
		if ( $intersect = array_intersect( array_keys( $meta['sizes'] ), get_intermediate_image_sizes() ) ) {

			$url 		= wp_get_attachment_url( $id );
			$filename 	= basename( $url );
			$paths[] 	= "<a title='{$filename}' href='{$url}'>" . __( 'full size', 'cpac' ) . "</a>";

			foreach ( $intersect as $size ) {
				$src = wp_get_attachment_image_src( $id, $size );

				if ( ! empty( $src[0] ) ) {
					$filename 	= basename( $src[0] );
					$paths[] 	= "<a title='{$filename}' href='{$src[0]}' class='available'>{$size}</a>";
				}
			}
		}

		global $_wp_additional_image_sizes;

		if ( ! empty( $_wp_additional_image_sizes ) ) {
			if ( isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
				unset( $_wp_additional_image_sizes['post-thumbnail'] );
			}

			// image does not have these additional sizes rendered yet
			if ( $missing = array_diff( array_keys( $_wp_additional_image_sizes), array_keys( $meta['sizes'] ) ) ) {
				foreach  ( $missing as $size ) {
					$paths[] = "<span title='Missing size: Try regenerate thumbnails with the plugin: Force Regenerate Thumbnails' href='javascript:;' class='not-available'>{$size}</span>";
				}
			}
		}

		return "<div class='sizes'>" . implode( '<span class="cpac-divider"></span>', $paths ) . "</div>";
	}
}