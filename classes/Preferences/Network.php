<?php

namespace AC\Preferences;

class Network extends Site {

	protected function get_key() {
		return 'network_' . parent::get_key();
	}

}