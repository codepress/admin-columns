<?php

namespace AC;

abstract class Config extends ArrayIterator {

	/**
	 * @param array $config
	 */
	public function __construct( array $config = array() ) {
		parent::__construct( $config );

		$this->validate_config();
	}

	/**
	 * Assert this config is valid.
	 */
	protected abstract function validate_config();

}
