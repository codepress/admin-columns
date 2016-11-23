<?php

class AC_Settings_Setting_Placeholder extends AC_Settings_SettingAbstract {

	protected function set_managed_options() {
		$this->managed_options = array( 'placeholder' );
	}

	public function view() {
		$view = new AC_Settings_View();

		return $view;
	}

}