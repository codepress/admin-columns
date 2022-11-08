<?php

namespace AC\Service;

use AC\Plugin;
use AC\Registerable;

final class Setup implements Registerable {

	/**
	 * @var Plugin\Setup
	 */
	private $setup;

	public function __construct( Plugin\Setup $setup ) {
		$this->setup = $setup;
	}

	public function register() {
		add_action( 'init', [ $this, 'run' ], 1000 );
	}

	public function run() {
		if ( wp_doing_ajax() ) {
			return;
		}

		$force_install = '1' === filter_input( INPUT_GET, 'ac-force-install' );

		$this->setup->run( $force_install );
	}

}