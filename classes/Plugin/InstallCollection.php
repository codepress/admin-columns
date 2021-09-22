<?php

namespace AC\Plugin;

use AC;

final class InstallCollection implements Install {

	/**
	 * @var Install[]
	 */
	private $installers;

	public function add_install( Install $installer ) {
		$this->installers[] = $installer;

		return $this;
	}

	public function install() {
		foreach ( $this->installers as $installer ) {
			$installer->install();
		}
	}

}