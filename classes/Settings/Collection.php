<?php

/**
 * Class AC_Settings_Collection
 *
 * @property AC_Settings_Column_Width width
 * @property AC_Settings_Column_User user
 */
class AC_Settings_Collection extends AC_Collection {

	/**
	 * @param $key
	 * @param null $default
	 *
	 * @return AC_Settings_Column_User
	 */
	public function get( $key, $default = null ) {
		return parent::get( $key, $default );
	}

}