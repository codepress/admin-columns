<?php

namespace AC\ListScreenRepository;

use LogicException;

trait WritableTrait {

	/**
	 * @var string
	 */
	protected $writable = true;

	/**
	 * @param $writable
	 */
	public function set_writable( $writable ) {
		if ( ! is_bool( $writable ) ) {
			throw new LogicException( 'Expected boolean.' );
		}

		$this->writable = $writable;
	}

	/**
	 * @return bool
	 */
	public function is_writable() {
		return $this->writable;
	}

}