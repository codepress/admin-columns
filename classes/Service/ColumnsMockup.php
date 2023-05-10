<?php
declare( strict_types=1 );

namespace AC\Service;

use AC\Registerable;
use AC\View;

class ColumnsMockup implements Registerable {

	public function register() {
		add_action( 'ac/settings/after_columns', [ $this, 'render' ] );
	}

	public function render(): void {
		echo ( new View() )->set_template( 'admin/list-screen-settings-mockup' )->render();
	}

}