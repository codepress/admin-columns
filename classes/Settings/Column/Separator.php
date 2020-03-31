<?php

namespace AC\Settings\Column;

use AC\Collection;
use AC\Settings;
use AC\View;

class Separator extends Settings\Column
	implements Settings\FormatCollection {

	/**
	 * @var string
	 */
	private $separator;

	protected function define_options() {
		return [ 'separator' => 'comma' ];
	}

	public function create_view() {
		$element = $this
			->create_element( 'select' )
			->set_options( [
				''            => __( 'Default', 'codepress-admin-columns' ),
				'comma'       => __( 'Comma Separated', 'codepress-admin-columns' ),
				'newline'     => __( 'New line', 'codepress-admin-columns' ),
				'none'        => __( 'None', 'codepress-admin-columns' ),
				'white_space' => __( 'Whitespace', 'codepress-admin-columns' ),
			] );

		$view = new View( [
			'label'   => __( 'Separator', 'codepress-admin-columns' ),
			'tooltip' => __( 'Select a repeater sub field.', 'codepress-admin-columns' ),
			'setting' => $element,
		] );

		return $view;
	}

	public function get_separator() {
		return $this->separator;
	}

	public function set_separator( $separator ) {
		$this->separator = $separator;

		return $this;
	}

	public function format( Collection $collection, $original_value ) {
		switch ( $this->separator ) {
			case 'comma' :
				$separator = ', ';

				break;
			case 'newline' :
				$separator = "<br/>";

				break;
			case 'none' :
				$separator = '';

				break;
			case 'white_space' :
				$separator = '&nbsp;';

				break;
			default :
				$separator = $this->column->get_separator();
		}

		return $collection->filter()->implode( $separator );
	}

}