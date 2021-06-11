<?php

namespace AC\Sanitize;

use AC\Sanitize;

class Kses implements Sanitize {

	/**
	 * @param string $data
	 *
	 * @return string
	 */
	public function sanitize( $data ) {
		return wp_kses( $data, true, $this->get_allowed_protocols() );
	}

	protected function get_allowed_protocols() {
		return array_merge(
			wp_allowed_protocols(),
			[ 'data' ]
		);
	}

}