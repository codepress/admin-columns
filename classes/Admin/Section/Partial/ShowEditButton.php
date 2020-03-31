<?php

namespace AC\Admin\Section\Partial;

use AC\Form\Element\Checkbox;
use AC\Renderable;
use AC\Settings\General;
use AC\Settings\Option\EditButton;

class ShowEditButton implements Renderable {

	const OPTION_NAME = 'show_edit_button';

	/**
	 * @var EditButton
	 */
	private $option;

	public function __construct() {
		$this->option = new EditButton();
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
		$name = sprintf( '%s[%s]', General::NAME, $this->option->get_name() );

		$checkbox = new Checkbox( $name );

		$checkbox->set_options( [ '1' => $this->get_label() ] )
		         ->set_value( $this->option->is_enabled() ? 1 : 0 );

		return $checkbox->render();
	}

}