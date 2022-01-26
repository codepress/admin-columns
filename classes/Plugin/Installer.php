<?php

namespace AC\Plugin;

// TODO this seems isolated from some 'more' intelligent class that handles this
class Installer {

	/**
	 * @var InstallCollection
	 */
	private $install_collection;

	public function __construct( InstallCollection $install_collection ) {
		$this->install_collection = $install_collection;
	}

	public function install() {
		array_map( [ $this, 'run_install' ], $this->install_collection->get_copy() );
	}

	private function run_install( Install $install ) {
		$install->install();
	}

}