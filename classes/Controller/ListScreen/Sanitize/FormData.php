<?php

namespace AC\Controller\ListScreen\Sanitize;

use AC\Sanitize;

class FormData implements Sanitize {

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function sanitize( $data ) {
		if ( isset( $data[ Title::FIELD ] ) ) {
			$data[ Title::FIELD ] = ( new Title() )->sanitize( $data[ Title::FIELD ] );
		}

		return $data;
	}

}