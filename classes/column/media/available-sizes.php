<?php

/**
 * CPAC_Column_Media_Available_Sizes
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Available_Sizes extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-available-sizes';
		$this->properties['label']	 	= __( 'Available Sizes', CPAC_TEXTDOMAIN );	
		
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

			$image_sizes 		= array_keys( $meta['sizes'] );
			$additional_sizes 	= array_keys( $_wp_additional_image_sizes );			
						
			// available size
			if ( $intersect = array_intersect( $image_sizes, get_intermediate_image_sizes() ) ) {
				$value .= "<div class='available_sizes'>" . implode( ', ', $intersect ) . "</div>";
			}
			
			// image does not these additional sizes rendered
			if ( $diff = array_diff( $additional_sizes, $image_sizes ) ) {
				$value .= "<br/><div class='missing_sizes'><span>" . implode( ', ', $diff ) . "</span> (" . __( 'missing', CPAC_TEXTDOMAIN ) . ")</div>";
			}
		}
				
		return $value;
	}	
}