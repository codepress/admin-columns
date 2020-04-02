<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;

interface SourceAware {

	/**
	 * @param ListScreenId $id
	 *
	 * @return string
	 */
	public function get_source( ListScreenId $id );

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function has_source( ListScreenId $id );

}