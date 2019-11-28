<?php
namespace AC\Admin;

use AC\View;

abstract class Table {

	abstract public function getColumns();

	abstract public function getItems();

	abstract public function renderColumn( $name, $item );

	public function getRowClass( $item ) {
		return '';
	}

	protected function getMessage() {
		return '';
	}

	public function getTableClasses() {
		return [ 'widefat', 'fixed', 'ac-table' ];
	}

	public function render() {
		$view = new View( [
			'table'   => $this,
			'message' => $this->getMessage(),
		] );

		$view->set_template( 'admin/table' );

		echo $view->render();
	}

	public function getColumnNames() {
		return array_keys( $this->getColumns() );
	}

}