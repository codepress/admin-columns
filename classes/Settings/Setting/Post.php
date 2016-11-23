<?php

class AC_Settings_Setting_Post extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $post_property;

	protected function set_managed_options() {
		$this->managed_options = array( 'post_property_display' );
	}

	public function view() {

		$options = array(
			'title'  => __( 'Title' ),
			'id'     => __( 'ID' ),
			'author' => __( 'Author' ),
		);

		// sorts when translated
		natcasesort( $options );

		$select = $this->create_element( 'post_property_display', 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $options );

		$view = new AC_Settings_View();
		$view->set( 'setting', $select )
		     ->set( 'label', __( 'Display Format', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_post_property_display() {
		return $this->post_property;
	}

	/**
	 * @param string $post_property
	 *
	 * @return $this
	 */
	public function set_post_property_display( $post_property ) {
		$this->post_property = $post_property;

		return $this;
	}

}