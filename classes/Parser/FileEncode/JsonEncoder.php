<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class JsonEncoder extends FileEncode {

	const FILE_FORMAT = 'json';

	public function format( ListScreenCollection $listScreens ) {
		return (string) json_encode( $this->encode->encode( $listScreens ), JSON_PRETTY_PRINT );
	}

	public function get_file_type() {
		return self::FILE_FORMAT;
	}

}