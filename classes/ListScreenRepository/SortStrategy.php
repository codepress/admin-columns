<?php
namespace AC\ListScreenRepository;

use AC\ListScreenCollection;

interface SortStrategy {

	/**
	 * @param ListScreenCollection $list_screens
	 *
	 * @return ListScreenCollection
	 */
	public function sort( ListScreenCollection $list_screens );

}