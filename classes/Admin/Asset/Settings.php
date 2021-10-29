<?php

namespace AC\Admin\Asset;

use AC\Asset\Script;
use AC\Nonce;

class Settings extends Script {

	public function register() {
		parent::register();

		$nonce = new Nonce\Ajax();

		$this->add_inline_variable( 'AC', [
			Nonce\Ajax::NAME => $nonce->create(),
		] );
	}

}