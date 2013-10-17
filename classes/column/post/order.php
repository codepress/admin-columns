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
		$this->properties['label']	 	= __( 'Page Order', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'page-attributes' ) )
			return true;

		return false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'menu_order', $post_id );
	}
}