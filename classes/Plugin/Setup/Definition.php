<?php

namespace AC\Plugin\Setup;

use AC\Plugin\InstallCollection;
use AC\Plugin\UpdateCollection;
use AC\Storage\KeyValuePair;

abstract class Definition {

	/**
	 * @var InstallCollection
	 */
	protected $installers;

	/**
	 * @var UpdateCollection
	 */
	protected $updates;

	public function __construct( InstallCollection $installers = null, UpdateCollection $updates = null ) {
		if ( null === $installers ) {
			$installers = new InstallCollection();
		}

		if ( null === $updates ) {
			$updates = new UpdateCollection();
		}

		$this->installers = $installers;
		$this->updates = $updates;
	}

	/**
	 * @return InstallCollection
	 */
	public function get_installers() {
		return $this->installers;
	}

	/**
	 * @return UpdateCollection
	 */
	public function get_updates() {
		return $this->updates;
	}

	/**
	 * @return KeyValuePair
	 */
	abstract public function get_storage();

}