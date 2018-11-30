<?php

namespace AC\Admin\Request\Column;

use AC\Admin\Request\Column;

class Refresh extends Column {

	public function __construct() {
		parent::__construct( 'refresh' );
	}

	public function get_column() {
		$options = filter_input( INPUT_POST, 'columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$name = filter_input( INPUT_POST, 'column_name' );

		if ( empty( $options[ $name ] ) ) {
			wp_die();
		}

		$settings = $options[ $name ];

		$settings['name'] = $name;

		return $this->list_screen->create_column( $settings );
	}

}