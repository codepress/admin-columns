<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->set_property( 'type', 'column-default' )
		     ->set_property( 'group', __( 'Default', 'codepress-admin-columns' ) )
		     ->set_property( 'original', true );
	}

}