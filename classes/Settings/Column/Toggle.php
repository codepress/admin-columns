<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

abstract class Toggle extends Settings\Column {

	public function create_view() {
		$setting = $this
			->create_element( 'radio' )
			->set_options( [
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			] );

		$view = new View( [
			'setting' => $setting,
		] );

		return $view;
	}

}