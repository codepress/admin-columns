<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreen;
use AC\ListScreenRepository\Rule;

class EqualKey implements Rule {

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * @inheritDoc
	 */
	public function match( ListScreen $list_screen ) {
		return $list_screen->get_key() === $this->key;
	}

}