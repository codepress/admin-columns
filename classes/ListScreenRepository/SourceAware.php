<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;

interface SourceAware {

	/**
	 * @return string
	 */
	public function get_source( ListScreen $listScreen );

}