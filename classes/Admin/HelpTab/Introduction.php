<?php

namespace AC\Admin\HelpTab;

use AC\Admin\HelpTab;
use AC\View;

class Introduction extends HelpTab {

	public function __construct() {
		parent::__construct( __( "Overview", 'codepress-admin-columns' ) );
	}

	public function get_content() {
		return ( new View() )->set_template( 'admin/help-tab/introduction' )->render();
	}

}