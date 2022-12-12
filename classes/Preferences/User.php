<?php

namespace AC\Preferences;

use AC\Preferences;

class User extends Preferences {

	public function save(): bool {
		return (bool) update_user_meta( $this->get_user_id(), $this->get_key(), $this->data );
	}

	protected function load() {
		return get_user_meta( $this->get_user_id(), $this->get_key(), true );
	}

}