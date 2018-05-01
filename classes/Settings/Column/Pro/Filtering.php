<?php

class AC_Settings_Column_Pro_Filtering extends AC_Settings_Column_Pro {

	protected function set_name() {
		$this->name = 'filtering';
	}

	protected function get_label() {
		return __( 'Filtering', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column filterable.", 'codepress-admin-columns' );
	}

}