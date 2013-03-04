<?php

/**
 * CPAC_Column_Post_Ping_Status
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Ping_Status extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-ping_status';
		$this->properties['label']	 	= __( 'Ping status', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$p = get_post( $post_id );

		$value = $this->get_asset_image( 'no.png', $p->ping_status );

		if ( 'open' == $p->ping_status )
			$value = $this->get_asset_image( 'checkmark.png', $p->ping_status );

		return $value;
	}
}