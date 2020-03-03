<?php

namespace AC\Admin\HelpTab;

use AC\Admin\HelpTab;
use AC\View;

class Basics extends HelpTab {

	public function __construct() {
		parent::__construct( __( "Basics", 'codepress-admin-columns' ) );
	}

	public function get_content() {
		return ( new View() )->set_template( 'admin/help-tab/basics' )->render();
	}

}