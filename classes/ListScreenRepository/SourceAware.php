<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;

// TODO at all required?
// TODO check for use, add has_source and maybe only a layout id?
interface SourceAware {

	/**
	 * @return string
	 */
	public function get_source( ListScreen $listScreen );

}