<?php

namespace AC\Integration\Filter;

class IsPluginNotActive extends IsPluginActive {

	public function __construct() {
		parent::__construct( false );
	}

}