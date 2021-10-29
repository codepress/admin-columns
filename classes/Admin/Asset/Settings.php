<?php

namespace AC\Admin\Asset;

use AC\Asset\Script;

class Settings extends Script {

	public function register() {
		parent::register();

		$this->add_inline_variable( 'AC', [
			'_ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		] );

		wp_localize_script( $this->handle, 'AC_I18N', [
			'restore_settings' => __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ),
		] );
	}

}