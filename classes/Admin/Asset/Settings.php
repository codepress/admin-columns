<?php

namespace AC\Admin\Asset;

use AC\Asset\Script;

class Settings extends Script {

	public function register() {
		parent::register();

		$this->add_inline_variable( 'AC', [
			'_ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		] );
	}

}