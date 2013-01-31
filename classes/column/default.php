<?php

/**
 * CPAC_Column_Post
 *
 * @since 2.0.0
 *
 * @param string $storage_key
 * @param string $column_name
 * @param string $label
 *
 */
class CPAC_Column_Default extends CPAC_Column {
	
	function __construct( $storage_key, $column_name = '', $label = '' ) {
		
		$this->properties['column_name'] = $column_name;
		
		$this->options['label'] = $label;
		$this->options['state'] = 'on';
		
		// checkbox column should not have an editable label
		if ( 'cb' == $column_name ) {
			$this->properties['editable_label'] = false;
		}
		
		parent::__construct( $storage_key, $column_name );
	}	
}