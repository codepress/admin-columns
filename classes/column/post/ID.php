<?php
/**
 * CPAC_Column_Post_ID
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_ID extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-postid';
		$this->properties['label']	 	= __( 'ID', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {
		return $post_id;
	}
}