<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultAbstract extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-default' );
		$this->set_group( __( 'Default', 'codepress-admin-columns' ) );
		$this->set_original( true );
	}

}