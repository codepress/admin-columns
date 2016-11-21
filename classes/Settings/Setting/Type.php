<?php

class AC_Settings_Setting_Type extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $type;

	// todo: remove once column has a list screen
	public $options;

	public function __construct( AC_Column $column ) {
		$this->type = $column->get_type();

		parent::__construct( $column );
	}

	protected function set_properties() {
		$this->properties = array( 'type' );
	}

	public function view() {
		$setting = $this->create_element( 'type', 'select' )
		                ->set_options( $this->options ); // todo: remove once column has list screen

		$view = new AC_Settings_View();
		$view->set( 'settings', $setting )
		     ->set( 'label', __( 'Type', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>' );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

}