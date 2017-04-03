<?php

abstract class AC_Settings_Column_Toggle extends AC_Settings_Column {

	public function create_view() {
		$setting = $this
			->create_element( 'radio' )
			->set_options( array(
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			) );

		$view = new AC_View( array(
			'setting' => $setting,
		) );

		return $view;
	}

}