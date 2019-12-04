<?php
namespace AC\Admin;

use AC\View;

abstract class Table {

	abstract public function get_columns();

	abstract public function get_items();

	abstract public function render_column( $name, $item );

	public function get_row_class( $item ) {
		return '';
	}

	protected function get_message() {
		return '';
	}

	public function getTableClasses() {
		return [ 'widefat', 'fixed', 'ac-table' ];
	}

	public function render() {
		$view = new View( [
			'table'   => $this,
			'message' => $this->get_message(),
		] );

		$view->set_template( 'admin/table' );

		echo $view->render();
	}

	public function getColumnNames() {
		return array_keys( $this->get_columns() );
	}

}