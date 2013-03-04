<?php
/**
 * CPAC_Column_Media_Caption
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Caption extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-caption';
		$this->properties['label']	 = __( 'Caption', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		return get_post_field( 'post_excerpt', $id );
	}
}