<?php

class AC_Settings_Setting_LinkLabel extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

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

	public function format( $url ) {
		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) || ! preg_match( '/[^\w.-]/', $url ) ) {
			return false;
		}

		$label = $this->get_value();

		if ( ! $label ) {
			$label = $url;
		}

		return ac_helper()->html->link( $url, $label );
	}

}