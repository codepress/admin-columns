<?php

class AC_Column_Comment_Menu extends AC_Column_Menu {

	public function get_object_type() {
		return 'comment';
	}

	/**
	 * @return string
	 */
	public function get_item_type() {
		return 'comment';
	}

}