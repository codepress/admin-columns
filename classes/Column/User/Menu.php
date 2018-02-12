<?php

class AC_Column_User_Menu extends AC_Column_Menu {

	public function get_object_type() {
		return 'user';
	}

	/**
	 * @return string
	 */
	public function get_item_type() {
		return 'user';
	}

}