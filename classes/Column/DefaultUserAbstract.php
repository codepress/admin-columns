<?php
defined( 'ABSPATH' ) or die();

abstract class AC_Column_DefaultUserAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->set_property( 'group', __( 'Default', 'codepress-admin-columns' ) )
		     ->set_property( 'original', true );
	}

}