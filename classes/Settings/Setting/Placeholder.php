<?php

class AC_Settings_Setting_Placeholder extends AC_Settings_SettingAbstract {

	protected function set_managed_options() {
		$this->managed_options = array( 'placeholder' );
	}

	public function view() {
		$word_limit = $this->create_element( 'excerpt_length', 'number' )
		                   ->set_attribute( 'min', 0 )
		                   ->set_attribute( 'step', 1 );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Word Limit', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>' )
		     ->set( 'setting', $word_limit );

		return $view;
	}

}