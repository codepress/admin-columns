<?php
namespace AC\Parser;

use RuntimeException;

class FileDecodeFactory {

	const FORMAT_PHP = 'php';
	const FORMAT_JSON = 'json';

	public function create( $format ) {

		switch ( $format ) {
			case self::FORMAT_JSON :
				return new FileDecode\JsonDecoder( new DecodeFactory() );

			case self::FORMAT_PHP :
				return new FileDecode\PhpDecoder( new DecodeFactory() );
		}

		throw new RuntimeException( 'Invalid Encoder.' );
	}

}