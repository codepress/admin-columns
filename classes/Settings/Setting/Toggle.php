<?php

abstract class AC_Settings_Setting_Toggle extends AC_Settings_Setting {

	public function create_view() {
		$type = $this->create_element( 'radio' )
		             ->set_options( array(
			             'on'  => __( 'Yes' ),
			             'off' => __( 'No' ),
		             ) );

		$view = new AC_View( array(
			'setting' => $type,
		) );

		return $view;
	}

}