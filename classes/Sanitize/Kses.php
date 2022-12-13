<?php

namespace AC\Sanitize;

class Kses {

	public function sanitize( string $string ): string {
		return wp_kses( $string, true, $this->get_allowed_protocols() );
	}

	protected function get_allowed_protocols(): array {
		return array_merge(
			wp_allowed_protocols(),
			[ 'data' ]
		);
	}

}