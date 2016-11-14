<?php

abstract class AC_Settings_FieldAbstract {

	/**
	 * @var AC_Column
	 */
	protected $column;

	public function __construct( AC_Column $column ) {
		$this->column = $column;
	}

}