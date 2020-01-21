<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;

// TODO David at all required?
// TODO David check for use, add has_source and maybe only a layout id?
interface SourceAware {

	/**
	 * @return string
	 */
	public function get_source( ListScreen $listScreen );

}