<?php

namespace AC\ListScreenRepository;

use AC\ListScreenCollection;

interface Filter {

	/**
	 * @param ListScreenCollection $list_screens
	 *
	 * @return ListScreenCollection
	 */
	public function filter( ListScreenCollection $list_screens );

}