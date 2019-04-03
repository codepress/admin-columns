<?php

namespace AC\Helper\Select;

interface Paginated {

	/**
	 * @return int
	 */
	public function get_total_pages();

	/**
	 * @return int
	 */
	public function get_page();

	/**
	 * @return bool
	 */
	public function is_last_page();

}