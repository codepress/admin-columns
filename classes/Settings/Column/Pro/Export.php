<?php

class AC_Settings_Column_Pro_Export extends AC_Settings_Column_Pro {

	protected function set_name() {
		$this->name = 'export';
	}

	protected function get_label() {
		return __( 'Export', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( 'Export your column data to CSV.', 'codepress-admin-columns' );
	}

}