<?php

namespace AC\Parser;

use AC\ListScreenCollection;
use RuntimeException;
use SplFileInfo;

abstract class FileDecode {

	/** @var DecodeFactory */
	protected $decodeFactory;

	public function __construct( DecodeFactory $decodeFactory ) {
		$this->decodeFactory = $decodeFactory;
	}

	/**
	 * @param SplFileInfo $file
	 *
	 * @return array
	 */
	abstract protected function get_data_from_file( SplFileInfo $file );

	/**
	 * @return string File extension
	 */
	abstract public function get_file_type();

	/**
	 * @param SplFileInfo $file
	 *
	 * @return ListScreenCollection
	 */
	public function decode_file( SplFileInfo $file ) {
		$data = $this->get_data_from_file( $file );

		if ( ! is_array( $data ) ) {
			throw new RuntimeException( 'Invalid file format.' );
		}

		return $this->decodeFactory->create( $data );
	}

}