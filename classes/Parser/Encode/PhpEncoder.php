<?php
namespace AC\Parser\Encode;

use AC\ListScreenCollection;
use AC\Parser\Encode;
use AC\Parser\Version480;

class PhpEncoder extends Encode {

	public function encode( ListScreenCollection $listScreens ) {
		$data = [
			'version' => Version480::VERSION,
		];

		foreach ( $listScreens as $listScreen ) {
			$data['list_screens'][] = $this->toArray( $listScreen );
		}
		$php = "
			add_action( 'ac/ready', function() {
				ac_load_columns(
					" . var_export( $data, true ) . "
				);
			});
		";

		return $php;
	}

}