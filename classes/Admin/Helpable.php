<?php

namespace AC\Admin;

interface Helpable {

	/**
	 * @return HelpTab[]
	 */
	public function get_help_tabs();

}