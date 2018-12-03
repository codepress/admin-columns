<?php

namespace AC\Settings\Admin;

use AC;
use AC\Settings\Admin;

abstract class General extends Admin {

	/** @var AC\Settings\General */
	protected $settings;

	public function __construct( $name ) {
		$this->settings = new AC\Settings\General();

		parent::__construct( $name );
	}

	protected function get_value() {
		return $this->settings->get_option( $this->name );
	}

}