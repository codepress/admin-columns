<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_FileName extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-file_name' );
		$this->set_label( __( 'File name', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$file = wp_get_attachment_url( $id );
		$filename = $this->get_raw_value( $id );

		return "<a title='{$filename}' href='{$file}'>{$filename}</a>";
	}

	public function get_raw_value( $id ) {
		$file = wp_get_attachment_url( $id );

		return basename( $file );
	}

}