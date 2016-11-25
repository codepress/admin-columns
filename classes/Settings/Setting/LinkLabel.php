<?php

class AC_Settings_Setting_LinkLabel extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $link_label;

	protected function set_managed_options() {
		$this->managed_options = array( 'link_label' );
	}

<<<<<<< Updated upstream
	protected function create_view() {
		$view = new AC_Settings_View( array(
			'set'     => $this->create_element(),
			'label'   => __( 'Link Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
		) );
=======
	protected function get_view() {
		$view = new AC_Settings_View();
		$view->set( 'setting', $this->create_element( 'link_label' ) )
		     ->set( 'label', __( 'Link Label', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Leave blank to display the url', 'codepress-admin-columns' ) );
>>>>>>> Stashed changes

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
	 * @return $this
	 */
	public function set_link_label( $link_label ) {
		$this->link_label = $link_label;

		return $this;
	}

	// TODO: no label available
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