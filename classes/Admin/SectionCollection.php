<?php

namespace AC\Admin;

use AC\Collection;

class SectionCollection extends Collection {

	/**
	 * @return Section[]
	 */
	public function all() {
		return parent::all();
	}

	public function add( Section $section ) {
		$this->put( $section->get_slug(), $section );

		return $this;
	}

}