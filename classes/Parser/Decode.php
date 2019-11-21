<?php
namespace AC\Parser;

use AC\ListScreen;

interface Decode {

	/**
	 * @param array $data
	 *
	 * @return ListScreen
	 */
	public function decode( array $data);

}