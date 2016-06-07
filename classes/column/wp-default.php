<?php

class CPAC_Column_WP_Default extends CPAC_Column {

	public function __construct( $storage_model, $type, $label ) {
		parent::__construct( $storage_model );

		if ( ! $label ) {
			$label = ucfirst( $type );
		}

		// Hide Label when it contains HTML elements
		if ( strlen( $label ) != strlen( strip_tags( $label ) ) ) {
			$this->set_properties( 'hide_label', true );
		}

		$this
			->set_properties( 'type', $type )
			->set_properties( 'name', $type )
			->set_properties( 'label', $label )
			->set_options( 'label', $label );
	}

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Default', 'codepress-admin-columns' );
		$this->properties['default'] = true;
		$this->properties['is_cloneable'] = false;
	}
}