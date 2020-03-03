<?php

namespace AC\Form\Element;

class MultiSelect extends Select {

	public function __construct( $name, array $options = [] ) {
		parent::__construct( $name, $options );

		$this->set_attribute( 'multiple', 'multiple' );
	}

	protected function selected( $value ) {
		return in_array( $value, (array) $this->get_value(), true );
	}

}