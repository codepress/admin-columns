<?php

namespace AC\Controller\ListScreen\Sanitize;

use AC\Sanitize;

class Title implements Sanitize {

	const FIELD = 'title';

	public function sanitize( $data ) {
		return wp_kses( $data, true );
	}

}