<?php

namespace AC\Plugin;

interface SetupFactory {

	/**
	 * @return Setup
	 */
	public function create( UpdateCollection $updates = null, InstallCollection $installs = null );

}