<?php

/**
 * CPAC_Column_Post_Before_Moretag
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Before_Moretag extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 = 'column-before-moretag';
		$this->properties['label']	 = __( 'Before More Tag', 'cpac' );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		
		$value = '';
		
		$p = get_post( $post_id );
		$extended = get_extended( $p->post_content );

		if ( ! empty( $extended['extended'] ) ) {
			$value = $this->get_shortened_string( $extended['main'], 20 );
		}
		
		return $value;
	}
}