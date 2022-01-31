<?php

namespace AC\Plugin\Setup\Definition;

use AC\Plugin\InstallCollection;
use AC\Plugin\Setup\Definition;
use AC\Plugin\UpdateCollection;
use AC\Storage\Option;

class Site extends Definition {

	/**
	 * @var string
	 */
	protected $version_key;

	public function __construct( $version_key, InstallCollection $installers = null, UpdateCollection $updates = null ) {
		parent::__construct( $installers, $updates );

		$this->version_key = $version_key;
	}

	/**
	 * @return Option
	 */
	public function get_storage() {
		return new Option( $this->version_key );
	}

}