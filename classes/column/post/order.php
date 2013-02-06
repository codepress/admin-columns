<?php

/**
 * CPAC_Column_Post_Order
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Order extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-order';
		$this->properties['label']	 	= __( 'Page Order', CPAC_TEXTDOMAIN );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
	
		return get_post_field( 'menu_order', $post_id );
	}
}