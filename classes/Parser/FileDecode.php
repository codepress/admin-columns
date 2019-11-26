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
	abstract protected function getDataFromFile( SplFileInfo $file );

	/**
	 * @param SplFileInfo $file
	 *
	 * @return ListScreenCollection
	 */
	public function decodeFile( SplFileInfo $file ) {
		$data = $this->getDataFromFile( $file );

		if ( ! is_array( $data ) ) {
			throw new RuntimeException( 'Invalid file format.' );
		}

		return $this->decodeFactory->create( $data );
	}

}