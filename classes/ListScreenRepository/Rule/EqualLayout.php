<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreen;
use AC\ListScreenRepository\Rule;

class EqualLayout implements Rule {

	/**
	 * @var string
	 */
	private $layout_id;

	/**
	 * @param string $layout_id
	 */
	public function __construct( $layout_id ) {
		$this->layout_id = $layout_id;
	}

	/**
	 * @inheritDoc
	 */
	public function match( ListScreen $list_screen ) {
		return $this->layout_id === $list_screen->get_layout_id();
	}

}