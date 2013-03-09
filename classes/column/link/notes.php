<?php
/**
 * CPAC_Column_Link_Notes
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Notes extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-notes';
		$this->properties['label']	 	= __( 'Notes', 'cpac' );

		// define additional options
		$this->options['excerpt_length'] = 30;

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $this->get_shortened_string( $bookmark->link_notes, $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}