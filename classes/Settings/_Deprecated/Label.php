<?php

final class AC_Settings_Form_Label extends AC_Settings_Form_Part {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $read_more;

	/**
	 * @var $string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $for;

	public function __construct() {
		$this->set_view( new AC_Settings_View_Label() );
	}

	public function render() {
		$view = $this->get_view();

		$view->label = $this->label;
		$view->description = $this->description;
		$view->read_more = $this->read_more;
		$view->for = $this->for;

		return $view->render();
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_read_more() {
		return $this->read_more;
	}

	/**
	 * @param string $read_more
	 *
	 * @return $this
	 */
	public function set_read_more( $read_more ) {
		$this->read_more = $read_more;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_for() {
		return $this->for;
	}

	/**
	 * @param string $for
	 *
	 * @return $this
	 */
	public function set_for( $for ) {
		$this->for = $for;

		return $this;
	}

}