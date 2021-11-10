<?php

namespace AC\Admin\Asset;

use AC\Asset\Script;
use AC\Nonce;

class Settings extends Script {

	public function register() {
		parent::register();

		$nonce = new Nonce\Ajax();

		$this->add_inline_variable( 'AC', [
			Nonce\Ajax::NAME => $nonce->create(),
		] );

		wp_localize_script( $this->handle, 'AC_I18N', [
			'restore_settings' => __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ),
		] );
	}

}