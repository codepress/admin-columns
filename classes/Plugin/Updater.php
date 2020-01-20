<?php

namespace AC\Plugin;

abstract class Updater {

	/**
	 * @var Update[]
	 */
	protected $updates;

	public function add_update( Update $update ) {
		$this->updates[ $update->get_version() ] = $update;
	}

	abstract public function parse_updates();

}