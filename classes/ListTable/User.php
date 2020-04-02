<?php

namespace AC\ListTable;

use AC\ListTable;

class User implements ListTable {

	public function get_column_value( $column, $id ) {
		return apply_filters( 'manage_users_custom_column', '', $column, $id );
	}

}