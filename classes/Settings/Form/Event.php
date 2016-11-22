<?php

class AC_Settings_Form_Event {

	/**
	 * @var string
	 */
	private $target;

	/**
	 * @var string|bool|int
	 */
	private $value;

	/**
	 * @var string
	 */
	private $type;

	public function __construct( $type, $target = null, $value = null ) {
		$this->type = $type;
		$this->target = $target;
		$this->value = $value;
	}

	public function to_json() {
		return json_encode( get_object_vars( $this ) );
	}

}