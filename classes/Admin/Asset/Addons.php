<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Nonce;

class Addons extends Script {

	public function __construct( string $handle, Location $location = null ) {
		parent::__construct( $handle, $location, [ 'jquery' ] );
	}

	public function register(): void {
		parent::register();

		$translation = new Script\Localize\Translation( [
			'plugin_installed' => __( 'The Add-on %s is activated.', 'codepress-admin-columns' ),
		] );

		$this->localize( 'ACi18n', $translation )
		     ->add_inline_variable(
			     'AC', [
				     Nonce\Ajax::NAME   => ( new Nonce\Ajax() )->create(),
				     'is_network_admin' => is_network_admin(),
			     ]
		     );
	}

}