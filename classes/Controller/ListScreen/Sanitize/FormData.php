<?php

namespace AC\Controller\ListScreen\Sanitize;

use AC\Sanitize;

class FormData implements Sanitize {

	public function sanitize( array $data ): array {
		if ( isset( $data['title'] ) ) {
			$data['title'] = wp_kses( $data['title'], true );
		}

		return $data;
	}

}