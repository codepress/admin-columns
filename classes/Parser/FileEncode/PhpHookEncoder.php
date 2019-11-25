<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class PhpHookEncoder extends FileEncode {

	public function format( ListScreenCollection $listScreens ) {
		$php = sprintf( "
			add_action( 'ac/ready', function() {
				ac_load_columns(
					%s
				);
			});
		", var_export( $this->encode->encode( $listScreens ), true ) );

		return $php;
	}

}