<?php

class AC_Settings_Column_Pro_Sorting extends AC_Settings_Column_Pro {

	protected function set_name() {
		$this->name = 'sorting';
	}

	protected function get_label() {
		return __( 'Sorting', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column sortable.", 'codepress-admin-columns' );
	}

}