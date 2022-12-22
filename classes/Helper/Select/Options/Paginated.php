<?php

namespace AC\Helper\Select\Options;

use AC\ArrayIterator;
use AC\Helper\Select;

class Paginated extends Select\Options
	implements Select\Paginated {

	/**
	 * @var Paginated
	 */
	protected $paginated;

	public function __construct( Select\Paginated $paginated, ArrayIterator $options ) {
		$this->paginated = $paginated;

		parent::__construct( $options->get_copy() );
	}

	public function get_total_pages(): int {
		return $this->paginated->get_total_pages();
	}

	public function get_page(): int {
		return $this->paginated->get_page();
	}

	public function is_last_page(): bool {
		return $this->paginated->is_last_page();
	}

}