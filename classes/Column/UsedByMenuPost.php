<?php

abstract class AC_Column_UsedByMenuPost extends AC_Column_UsedByMenu {

	public function get_object_type() {
		return $this->get_post_type();
	}

}