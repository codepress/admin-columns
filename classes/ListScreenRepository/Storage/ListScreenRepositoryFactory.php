<?php

namespace AC\ListScreenRepository\Storage;

use AC\ListScreenRepository\Rules;

interface ListScreenRepositoryFactory {

	public function create( string $path, bool $writable, Rules $rules = null ): ListScreenRepository;

}