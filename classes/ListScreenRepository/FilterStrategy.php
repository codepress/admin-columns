<?php
namespace AC\ListScreenRepository;

use AC\ListScreenCollection;

interface FilterStrategy {

	/**
	 * @param ListScreenCollection $list_screens
	 *
	 * @return ListScreenCollection
	 */
	public function filter( ListScreenCollection $list_screens );

}