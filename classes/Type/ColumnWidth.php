<?php

namespace AC\Type;

use InvalidArgumentException;

class ColumnWidth {

	/**
	 * @var string
	 */
	private $unit;

	/**
	 * @var int
	 */
	private $value;

	public function __construct( $unit, $value ) {
		$this->unit = $unit;
		$this->value = $value;

		$this->validate();
	}

	private function validate() {
		if ( ! in_array( $this->unit, [ 'px', '%' ] ) ) {
			throw new InvalidArgumentException( 'Invalid width unit.' );
		}
		if ( ! is_int( $this->value ) || $this->value < 0 ) {
			throw new InvalidArgumentException( 'Invalid width.' );
		}
	}

	/**
	 * @return string
	 */
	public function get_unit() {
		return $this->unit;
	}

	/**
	 * @return int
	 */
	public function get_value() {
		return $this->value;
	}

}