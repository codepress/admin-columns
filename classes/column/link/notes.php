<?php
/**
 * CPAC_Column_Link_Notes
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Notes extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-notes';
		$this->properties['label']	 	= __( 'Notes', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $this->get_shortened_string( $bookmark->link_notes, 20 );
	}
}