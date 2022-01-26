<?php

namespace AC\Plugin;

use AC;

final class InstallCollection extends AC\Iterator {

	public function __construct( array $installers ) {
		array_map( [ $this, 'add' ], $installers );
	}

	protected function add( Install $install ) {
		$this->data[] = $install;
	}

	/**
	 * @return Install
	 */
	public function current() {
		return parent::current();
	}

}