<?php

namespace AC\Plugin;

interface Install {

	/**
	 * Idempotent call to set up Admin Columns
	 *
	 * @return void
	 */
	public function install();

}