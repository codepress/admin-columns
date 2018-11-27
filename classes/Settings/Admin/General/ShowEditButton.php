<?php

namespace AC\Settings\Admin\General;

use AC\Form\Element\Checkbox;
use AC\Settings\Admin\General;

class ShowEditButton extends General {

	public function __construct() {
		parent::__construct( 'show_edit_button' );
	}

	// todo: check if stored value is 'yes' or '1'
	protected function get_value() {
		return $this->settings->is_empty() ? 'yes' : parent::get_value();
	}

	private function get_label() {
		return sprintf( '%s %s',
			sprintf( __( "Show %s button on table screen.", 'codepress-admin-columns' ), sprintf( '"%s"', __( 'Edit columns', 'codepress-admin-columns' ) ) ),
			sprintf( __( "Default is %s.", 'codepress-admin-columns' ), '<code>' . __( 'on', 'codepress-admin-columns' ) . '</code>' )
		);
	}

	public function show_button() {
		return 'yes' === $this->get_value();
	}

	/**
	 * @return string
	 */
	public function render() {
		$name = sprintf( '%s[%s]', $this->settings->get_name(), $this->name );

		$checkbox = new Checkbox( $name );

		$checkbox->set_options( array( 'yes' => $this->get_label() ) )
		         ->set_value( $this->get_value() );

		return $checkbox->render();
	}

}