<?php

class AC_Settings_Setting_Label extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $label;

	public function __construct( AC_Column $column ) {
		parent::__construct( $column );

		$this->set_default( $column->get_label() );
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'label' );
	}

	protected function get_view() {
		$view = new AC_Settings_View();

		// don't render this field
		if ( $this->column->is_original() && ac_helper()->string->contains_html_only( $this->get_label() ) ) {
			$view->set_template( false );

			return $view;
		}

		$label = $this->create_element( 'text' )
		              ->set_attribute( 'placeholder', $this->column->get_label() );

		$view->set_data( array(
			'name'    => $this->name,
			'label'   => __( 'Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'setting' => $label,
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

}