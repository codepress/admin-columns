<?php

namespace AC\Settings\Column\BeforeAfter;

use AC\Settings\Column\BeforeAfter;

class ISO extends BeforeAfter {

	protected function define_options() {
		return array( 'before' => 'ISO ', 'after' => '' );
	}

}