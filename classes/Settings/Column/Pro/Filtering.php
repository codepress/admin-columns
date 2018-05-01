<?php

class AC_Settings_Column_Pro_Filtering extends AC_Settings_Column {

	protected function define_options() {
		return array( 'pro_feature_filtering' );
	}

	public function create_view() {
		$edit = $this->create_element( 'radio' )
		             ->set_options( array(
			             'on'  => __( 'Yes' ),
			             'off' => __( 'No' ),
		             ) );

		$view = new AC_View();
		$view->set( 'label', __( 'Filtering', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'This will make the column filterable.', 'codepress-admin-columns' ) )
		     ->set( 'setting', $edit )
		     ->set_template( 'settings/setting-pro' );

		return $view;
	}

}