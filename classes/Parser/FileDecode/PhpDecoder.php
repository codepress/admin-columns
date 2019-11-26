<?php

namespace AC\Parser\FileDecode;

use AC\Parser\FileDecode;
use SplFileInfo;

class PhpDecoder extends FileDecode {

	public function getDataFromFile( SplFileInfo $file ) {
		return ( require $file->getRealPath() );
	}

}