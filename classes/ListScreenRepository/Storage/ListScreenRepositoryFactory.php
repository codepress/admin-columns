<?php

namespace AC\ListScreenRepository\Storage;

use AC\ListScreenRepository\Rules;

interface ListScreenRepositoryFactory {

	/**
	 * @param string     $path
	 * @param bool       $writable
	 * @param Rules|null $rules
	 *
	 * @return ListScreenRepository
	 */
	public function create( $path, $writable, Rules $rules = null );

}