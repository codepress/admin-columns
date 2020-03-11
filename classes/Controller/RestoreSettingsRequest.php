<?php

namespace AC\Controller;

use AC\ListScreenRepository\Storage\ListScreenRepository;
use AC\Message\Notice;
use AC\Registrable;

class RestoreSettingsRequest implements Registrable {

	/**
	 * @var ListScreenRepository
	 */
	private $repository;

	public function __construct( ListScreenRepository $repository ) {
		$this->repository = $repository;
	}

	public function register() {
		add_action( 'admin_init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( 'restore' !== filter_input( INPUT_POST, 'ac_action' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), 'restore' ) ) {
			return;
		}

		foreach ( $this->repository->find_all() as $list_screen ) {
			$this->repository->delete( $list_screen );
		}

		$notice = new Notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ) );
		$notice->register();
	}

}