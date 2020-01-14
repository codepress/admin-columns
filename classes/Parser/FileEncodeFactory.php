<?php
namespace AC\Parser;

use AC\Parser\FileEncode\JsonEncoder;
use AC\Parser\FileEncode\PhpEncoder;
use AC\Parser\FileEncode\PhpHookEncoder;
use RuntimeException;

class FileEncodeFactory {

	const FORMAT_JSON = 'json';
	const FORMAT_PHP = 'php';
	const FORMAT_PHP_EXPORT = 'php-export';

	public function create( $format ) {
		switch ( $format ) {
			case self::FORMAT_JSON :
				return new JsonEncoder( new Version400() );

			case self::FORMAT_PHP :
				return new PhpEncoder( new Version400() );

			case self::FORMAT_PHP_EXPORT :
				return new PhpHookEncoder( new Version400() );
		}

		throw new RuntimeException( 'Invalid Encoder.' );
	}

}