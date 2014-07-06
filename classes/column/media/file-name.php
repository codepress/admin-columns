<?php
/**
 * CPAC_Column_Media_File_Name
 *
 * @since 2.0
 */
class CPAC_Column_Media_File_Name extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-file_name';
		$this->properties['label']	 	= __( 'File name', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$file 		= wp_get_attachment_url( $id );
		$filename 	= basename( $file );

		return "<a title='{$filename}' href='{$file}'>{$filename}</a>";
	}
}