<?php

/**
 * CPAC_Column_Post_Sticky
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Sticky extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-sticky';
		$this->properties['label']	 	= __( 'Sticky', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		if ( 'post' == $this->storage_model->key )
			return true;

		return false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$value = $this->get_asset_image( 'no.png' );

		if ( $this->get_raw_value( $post_id ) )
			$value = $this->get_asset_image( 'checkmark.png' );

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return is_sticky( $post_id );
	}
}