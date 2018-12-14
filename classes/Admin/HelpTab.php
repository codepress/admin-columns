<?php
namespace AC\Admin;

abstract class HelpTab {

	/**
	 * @return string
	 */
	abstract public function get_content();

	/**
	 * @return string
	 */
	abstract public function get_title();

	/**
	 * @return string
	 */
	public function get_id() {
		return 'ac-tab-' . sanitize_key( get_called_class() );
	}

}