<?php

namespace AC\Plugin\Update;

use AC\Plugin\Update;

class V3200 extends Update {

	public function get_dir() {
		return AC()->get_plugin_dir() . '/classes';
	}

	public function apply_update() {
		$this->uppercase_class_files();
	}

	protected function set_version() {
		$this->version = '3.2.0';
	}

	/**
	 * Set all files to the proper case
	 */
	private function uppercase_class_files() {
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $this->get_dir(), \FilesystemIterator::SKIP_DOTS )
		);

		foreach ( $iterator as $leaf ) {
			$file = $leaf->getFilename();

			if ( $file == strtolower( $file ) ) {
				@rename( $leaf->getPathname(), trailingslashit( $leaf->getPath() ) . ucfirst( $file ) );
			}
		}
	}

}