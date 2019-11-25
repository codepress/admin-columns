<?php
namespace AC\Parser;

use AC\Parser\FileEncode\JsonEncoder;
use AC\Parser\FileEncode\PhpEncoder;
use RuntimeException;

class FileEncodeFactory {

	const FORMAT_PHP = 'php';
	const FORMAT_PHP_EXPORT = 'php-export';
	const FORMAT_JSON = 'json';

	public function create( $format ) {

		switch ( $format ) {
			case self::FORMAT_JSON :
				return new JsonEncoder( new Version480() );

			case self::FORMAT_PHP :
				return new PhpEncoder( new Version480() );

			case self::FORMAT_PHP_EXPORT :
				return new PhpEncoder( new Version480() );
		}

		throw new RuntimeException( 'Invalid Encoder.' );
	}

}