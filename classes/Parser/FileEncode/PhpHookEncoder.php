<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class PhpHookEncoder extends FileEncode {

	const FILE_FORMAT = 'php';

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

	public function getFileType() {
		return self::FILE_FORMAT;
	}

}