<?php

namespace AC\Admin\Request\Column;

use AC\Admin\Request\Column;
use AC;

class Refresh extends Column {

	public function __construct( AC\ListScreenRepository\Aggregate $repo ) {
		parent::__construct( $repo, 'refresh' );
	}

	public function get_column( AC\Request $request, AC\ListScreen $list_screen ) {
		parse_str( $request->get('data'), $formdata );
		$options = $formdata['columns'];
		$name = filter_input( INPUT_POST, 'column_name' );

		if ( empty( $options[ $name ] ) ) {
			wp_die();
		}

		$settings = $options[ $name ];

		$settings['name'] = $name;

		return $list_screen->create_column( $settings );
	}

}