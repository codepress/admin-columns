<?php

namespace AC\Settings\Form;

use AC\Form\Element;

class Instruction {

	/**
	 * @var Element
	 */
	private $element;

	/**
	 * @var string
	 */
	private $instruction;

	public function __construct( Element $element, $instruction ) {
		$this->element = $element;
		$this->instruction = (string) $instruction;
	}

	private function render() {
		$html = $this->element->render();

		if ( 'on' === $this->element->get_value() ) {
			$html .= $this->instruction;
		}

		return $html;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

}