<?php

abstract class AC_Relation {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @param string $id
	 */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	abstract public function get_type();

	/**
	 * @return false|stdClass
	 */
	abstract public function get_labels();

}