<?php
namespace AC\Admin;

use AC\ArrayIterator;

class Pages extends ArrayIterator {

	public function __construct() {
		parent::__construct( array(
			new Page\Columns(),
			new Page\Settings(),
			new Page\Addons(),
			new Page\Help(),
		) );
	}

	/**
	 * @return Page[]
	 */
	public function get_copy() {
		return parent::get_copy();
	}

}