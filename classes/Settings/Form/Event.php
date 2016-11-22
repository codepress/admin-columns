<?php

class AC_Settings_Form_Events {

	private $events = array();

	public function add( $type, $id, $value ) {
		$this->events[] = array(
			'type' => $type,
			'id' => $id,
			'value' => $value,
		);
	}

	public function get() {
		return $this->events;
	}

	public function to_json() {
		return json_encode( $this->events );
	}

}