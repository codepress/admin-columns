<?php

namespace AC\Admin\Section\Partial;

use AC\Admin\Renderable;
use AC\Form\Element\Checkbox;
use AC\Settings\General;

class ShowEditButton implements Renderable {

	const OPTION_NAME = 'show_edit_button';

	/**
	 * @var General
	 */
	private $options;

	// todo: inject Option/ShowEditButton
	public function __construct( General $options ) {
		$this->options = $options;
	}

	private function get_label() {
		return sprintf( '%s %s',
			sprintf( __( "Show %s button on table screen.", 'codepress-admin-columns' ), sprintf( '"%s"', __( 'Edit columns', 'codepress-admin-columns' ) ) ),
			sprintf( __( "Default is %s.", 'codepress-admin-columns' ), '<code>' . __( 'on', 'codepress-admin-columns' ) . '</code>' )
		);
	}

	/**
	 * @return string
	 */
	public function render() {
		$name = sprintf( '%s[%s]', General::SETTINGS_NAME, self::OPTION_NAME );

		$checkbox = new Checkbox( $name );

		$checkbox->set_options( [ '1' => $this->get_label() ] )
		         ->set_value( $this->options->get_option( self::OPTION_NAME ) );

		return $checkbox->render();
	}

}