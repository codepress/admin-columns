<?php

namespace AC\Parser\FileDecode;

use AC\Parser\FileDecode;
use SplFileInfo;

class PhpDecoder extends FileDecode {

	public function get_data_from_file( SplFileInfo $file ) {
		return ( require $file->getRealPath() );
	}

}