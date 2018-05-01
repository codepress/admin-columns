<?php

class AC_Settings_Column_Pro_Sorting extends AC_Settings_Column {

	protected function define_options() {
		return array( 'pro_feature_sorting' );
	}

	public function create_view() {
		$sort = $this->create_element( 'radio' )
		             ->set_options( array(
			             'on'  => __( 'Yes' ),
			             'off' => __( 'No' ),
		             ) );

		$view = new AC_View();
		$view->set( 'label', __( 'Sorting', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'This will make the column sortable.', 'codepress-admin-columns' ) )
		     ->set( 'setting', $sort )
		     ->set_template( 'settings/setting-pro' );

		return $view;
	}

}