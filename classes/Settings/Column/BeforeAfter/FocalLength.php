<?php

namespace AC\Settings\Column\BeforeAfter;

use AC\Settings\Column\BeforeAfter;

class FocalLength extends BeforeAfter {

	protected function define_options() {
		return array( 'before' => '', 'after' => 'mm' );
	}

}