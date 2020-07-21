<?php

namespace AC\Column;

interface AjaxValue {

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function get_ajax_value( $id );

}