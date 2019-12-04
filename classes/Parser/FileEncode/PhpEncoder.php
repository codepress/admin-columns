<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class PhpEncoder extends FileEncode {

	const FILE_FORMAT = 'php';

	public function format( ListScreenCollection $listScreens ) {
		return sprintf( '<?php return %s; ?>', var_export( $this->encode->encode( $listScreens ), true ) );
	}

	public function get_file_type() {
		return self::FILE_FORMAT;
	}

}