<?php
/**
 * CPAC_Column_Link_Rss
 *
 * @since 2.0
 */
class CPAC_Column_Link_Rss extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-rss';
		$this->properties['label']	 	= __( 'Rss', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $this->get_shorten_url( $bookmark->link_rss );
	}
}