<?php
namespace AC\Parser\Encode;

use AC\ListScreenCollection;
use AC\Parser\Encode;
use AC\Parser\Version480;

class JsonEncoder extends Encode {

	public function encode( ListScreenCollection $listScreens ) {
		$data = [
			'version' => Version480::VERSION,
		];

		foreach ( $listScreens as $listScreen ) {
			$data['list_screens'][] = $this->toArray( $listScreen );
		}

		return (string) json_encode( $data, JSON_PRETTY_PRINT );
	}

}