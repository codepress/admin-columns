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

	/**
	 * @param Select\Paginated $paginated
	 * @param ArrayIterator    $options
	 */
	public function __construct( Select\Paginated $paginated, ArrayIterator $options ) {
		$this->paginated = $paginated;

		parent::__construct( $options->get_copy() );
	}

	/**
	 * @inheritDoc
	 */
	public function get_total_pages() {
		return $this->paginated->get_total_pages();
	}

	/**
	 * @inheritDoc
	 */
	public function get_page() {
		return $this->paginated->get_page();
	}

	/**
	 * @inheritDoc
	 */
	public function is_last_page() {
		return $this->paginated->is_last_page();
	}

}