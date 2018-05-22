<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class LinkLabel extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $link_label;

	protected function define_options() {
		return array( 'link_label' );
	}

	public function create_view() {
		$view = new View( array(
			'setting' => $this->create_element( 'text' ),
			'label'   => __( 'Link Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'Leave blank to display the URL', 'codepress-admin-columns' ),
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_link_label() {
		return $this->link_label;
	}

	/**
	 * @param string $link_label
	 *
	 * @return bool
	 */
	public function set_link_label( $link_label ) {
		$this->link_label = $link_label;

		return true;
	}

	public function format( $value, $original_value ) {
		$url = $value;

		if ( filter_var( $url, FILTER_VALIDATE_URL ) && preg_match( '/[^\w.-]/', $url ) ) {
			$label = $this->get_value();

			if ( ! $label ) {
				$label = $url;
			}

			$value = ac_helper()->html->link( $url, $label );
		}

		return $value;
	}

}