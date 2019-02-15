<?php

namespace AC\Preferences;

use AC\Preferences;

class User extends Preferences {

	/**
	 * @return bool
	 */
	public function save() {
		return (bool) update_user_meta( $this->get_user_id(), $this->get_key(), $this->data );
	}

	/**
	 * @return false|array
	 */
	protected function load() {
		return get_user_meta( $this->get_user_id(), $this->get_key(), true );
	}

}