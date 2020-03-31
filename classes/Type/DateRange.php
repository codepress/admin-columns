<?php

namespace AC\Type;

use DateTime;

class DateRange {

	/**
	 * @var DateTime
	 */
	private $start;

	/**
	 * @var DateTime
	 */
	private $end;

	public function __construct( DateTime $start, DateTime $end ) {
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * @return DateTime
	 */
	public function get_start() {
		return $this->start;
	}

	/**
	 * @return DateTime
	 */
	public function get_end() {
		return $this->end;
	}

	/**
	 * @param DateTime|null $date
	 *
	 * @return bool
	 */
	public function in_range( DateTime $date = null ) {
		if ( null === $date ) {
			$date = new DateTime();
		}

		return $date >= $this->start && $date <= $this->end;
	}

}