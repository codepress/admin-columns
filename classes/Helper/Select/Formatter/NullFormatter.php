<?php

namespace AC\Helper\Select\Formatter;

use AC\Helper\Select;

class NullFormatter extends Select\Formatter {

	public function __construct( Select\Entities $entities ) {
		parent::__construct( $entities, new Select\ValueFormatter\StringFormatter() );
	}

}