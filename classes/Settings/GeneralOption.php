<?php

namespace AC\Settings;

use AC\Storage;

class GeneralOption extends Storage\Option {

	const NAME = 'cpac_general_options';

	public function __construct() {
		parent::__construct( self::NAME );
	}

}