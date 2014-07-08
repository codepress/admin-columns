<?php
/**
 * CPAC_Column_Link_Notes
 *
 * @since 2.0
 */
class CPAC_Column_Link_Notes extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-notes';
		$this->properties['label']	 	= __( 'Notes', 'cpac' );

		// Options
		$this->options['excerpt_length'] = 30;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $this->get_shortened_string( $bookmark->link_notes, $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}