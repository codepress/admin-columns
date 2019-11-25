<?php
namespace AC\Parser\FileEncode;

use AC\ListScreenCollection;
use AC\Parser\FileEncode;

class JsonEncoder extends FileEncode {

	public function format( ListScreenCollection $listScreens ) {
		return (string) json_encode( $this->encode->encode( $listScreens ), JSON_PRETTY_PRINT );
	}

}