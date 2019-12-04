<?php
namespace AC\Parser;

use AC\ListScreenCollection;

abstract class FileEncode {

	/** @var Encode */
	protected $encode;

	public function __construct( Encode $encode ) {
		$this->encode = $encode;
	}

	/**
	 * @param ListScreenCollection $listScreenCollection
	 *
	 * @return string
	 */
	abstract public function format( ListScreenCollection $listScreenCollection );

	/**
	 * @return string File extension
	 */
	abstract public function get_file_type();

}