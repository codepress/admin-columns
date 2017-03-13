<?php

class AC_Settings_Column_Message extends AC_Settings_Column {

	private $label;

	private $message;

	protected function set_name() {
		$this->name = 'message';
	}

	protected function define_options() {
		return array();
	}

	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	public function set_message( $message ) {
		$this->message = $message;

		return $this;
	}

	public function create_view() {
		$view = new AC_View( array(
			'label'   => $this->label,
			'setting' => $this->message,
		) );

		return $view;
	}

}