<?php

namespace AC\Column\Post;

use AC\Column;

class Menu extends Column\Menu {

	/**
	 * @return string
	 */
	public function get_object_type() {
		return $this->get_post_type();
	}

	/**
	 * @return string
	 */
	public function get_item_type() {
		return 'post_type';
	}

}