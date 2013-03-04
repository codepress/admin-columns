<?php

/**
 * CPAC_Column_Post_Modified
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Modified extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-modified';
		$this->properties['label']	 	= __( 'Last modified', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$p = get_post( $post_id );

		return $this->get_date( $p->post_modified ) . ' ' . $this->get_time( $p->post_modified );
	}
}