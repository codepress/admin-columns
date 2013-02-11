<?php
/**
 * CPAC_Column_Media_File_Paths
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_File_Paths extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 = 'column-file-paths';
		$this->properties['label']	 = __( 'Upload paths', 'cpac' );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {
				
		$url 		= wp_get_attachment_url( $id );
		$filename 	= basename( $url );
		$paths[] 	= "<a title='{$filename}' href='{$url}'>" . __( 'original', 'cpac' ) . "</a>";
		
		if ( $sizes = get_intermediate_image_sizes() ) {
			foreach ( $sizes as $size ) {
				$src = wp_get_attachment_image_src( $id, $size );
				
				if ( ! empty( $src[0] ) ) {
					$filename 	= basename( $src[0] );
					$paths[] 	= "<a title='{$filename}' href='{$src[0]}'>{$size}</a>";
				}
			}
		}
		
		return implode( '<span class="cpac-divider"></span>', $paths );
	}
}