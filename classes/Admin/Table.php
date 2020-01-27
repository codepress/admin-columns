<?php

namespace AC\Admin;

use AC\View;
use Traversable;

abstract class Table {

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @return array
	 */
	abstract public function get_headings();

	/**
	 * @return Traversable
	 */
	abstract public function get_rows();

	/**
	 * @param string $key
	 * @param mixed  $data
	 *
	 * @return string
	 */
	abstract public function get_column( $key, $data );

	/**
	 * @return bool
	 */
	public function has_message() {
		return null !== $this->message;
	}

	public function get_message() {
		return $this->message;
	}

	public function render() {
		$view = new View( [
			'table' => $this,
		] );

		$view->set_template( 'admin/table' );

		return $view->render();
	}

}