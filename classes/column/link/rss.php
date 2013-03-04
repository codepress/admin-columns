<?php
/**
 * CPAC_Column_Link_Rss
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Rss extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-rss';
		$this->properties['label']	 	= __( 'Rss', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $this->get_shorten_url( $bookmark->link_rss );
	}
}