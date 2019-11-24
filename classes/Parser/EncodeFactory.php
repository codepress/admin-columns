<?php
namespace AC\Parser;

use RuntimeException;

class EncodeFactory {

	const FORMAT_PHP = 'php';
	const FORMAT_PHP_EXPORT = 'php-export';
	const FORMAT_JSON = 'json';

	public function create( $format ) {

		switch ( $format ) {
			case self::FORMAT_JSON :
				return new Encode\JsonEncoder();

			case self::FORMAT_PHP :
				return new Encode\PhpEncoder();

			case self::FORMAT_PHP_EXPORT :
				return new Encode\PhpHookEncoder();
		}

		throw new RuntimeException( 'Invalid Encoder.' );
	}

}