<?php
namespace AC\Storage;

use ArrayObject;

class DataObject extends ArrayObject {

	public function __construct( $data = array() ) {
		parent::__construct( $data, self::ARRAY_AS_PROPS );
	}

	/**
	 * @return bool
	 */
	public function is_empty() {
		return 0 === $this->count();
	}

}