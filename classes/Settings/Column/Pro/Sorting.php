<?php

class AC_Settings_Column_Pro_Sorting extends AC_Settings_Column_Pro {

	protected function get_label() {
		return __( 'Sorting', 'codepress-admin-columns' );
	}

	protected function get_tooltip() {
		return __( "This will make the column sortable.", 'codepress-admin-columns' );
	}

	protected function define_options() {
		return array( 'sort' );
	}

}