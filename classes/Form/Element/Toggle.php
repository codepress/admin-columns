<?php

namespace AC\Form\Element;

use AC\Form\Element;
use AC\View;

class Toggle extends Element {

	/**
	 * @var boolean
	 */
	private $checked;

	/**
	 * @var string
	 */
	private $unchecked_value;

	public function __construct( $name, $label, $checked = false, $value = null, $unchecked_value = 'false' ) {
		parent::__construct( $name, [] );

		$this->set_label( $label );
		$this->checked = (bool) $checked;
		$this->unchecked_value = (string) $unchecked_value;

		if ( $value ) {
			$this->set_value( $value );
		}
	}

	protected function get_type() {
		return 'checkbox';
	}

	public function render() {
		$view = new View( [
			'id'              => $this->get_name(),
			'name'            => $this->get_name(),
			'label'           => $this->get_label(),
			'checked'         => $this->checked,
			'value'           => $this->get_value(),
			'unchecked_value' => $this->unchecked_value,
			'attributes'      => $this->get_attributes_as_string( $this->get_attributes() ),
		] );

		return $view->set_template( 'component/toggle-v2' )->render();
	}

}