<?php

namespace AC\Plugin;

abstract class Updater {

	/**
	 * @var Update[]
	 */
	protected $updates;

	/**
	 * @param Update $update
	 *
	 * @return $this
	 */
	public function add_update( Update $update ) {
		$this->updates[ $update->get_version() ] = $update;

		return $this;
	}

	abstract public function parse_updates();

}