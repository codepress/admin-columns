<?php

namespace AC\Plugin;

interface NewInstallCheck {

	/**
	 * @return bool
	 */
	public function is_new_install();

}