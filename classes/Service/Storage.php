<?php

namespace AC\Service;

use AC\ListScreenRepository;
use AC\ListScreenRepository\Database;
use AC\ListScreenTypes;
use AC\Registrable;

final class Storage implements Registrable {

	/**
	 * @var ListScreenRepository\Storage
	 */
	private $storage;

	/**
	 * @var ListScreenTypes
	 */
	private $list_screen_types;

	public function __construct( ListScreenRepository\Storage $storage, ListScreenTypes $list_screen_types ) {
		$this->storage = $storage;
		$this->list_screen_types = $list_screen_types;
	}

	public function register() {
		$this->storage->register_repository(
			new ListScreenRepository\Storage\ListScreenRepository(
				'acp_database',
				new Database( $this->list_screen_types ),
				true
			)
		);
	}

}