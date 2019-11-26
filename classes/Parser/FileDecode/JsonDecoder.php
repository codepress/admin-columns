<?php

namespace AC\Parser\FileDecode;

use AC\Parser\FileDecode;
use RuntimeException;
use SplFileInfo;

class JsonDecoder extends FileDecode {

	public function getDataFromFile( SplFileInfo $file ) {
		$filePath = $file->getRealPath();
		$contents = file_get_contents( $filePath );

		if ( ! $contents ) {
			throw new RuntimeException( 'Empty file.' );
		}

		return json_decode( $contents, true );
	}

}