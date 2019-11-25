<?php
namespace AC\Parser;

use AC\ListScreenCollection;

interface Decode {

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data );

}