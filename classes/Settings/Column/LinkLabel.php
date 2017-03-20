<?php

class AC_Settings_Column_LinkLabel extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $link_label;

	protected function define_options() {
		return array( 'link_label' );
	}

	public function create_view() {
		$view = new AC_View( array(
			'setting' => $this->create_element( 'text' ),
			'label'   => __( 'Link Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
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

	/**
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		$url = $value_formatter->value;

		if ( filter_var( $url, FILTER_VALIDATE_URL ) && preg_match( '/[^\w.-]/', $url ) ) {
			$label = $this->get_value();

			if ( ! $label ) {
				$label = $url;
			}

			$value_formatter->value = ac_helper()->html->link( $url, $label );
		}

		return $value_formatter;
	}

}