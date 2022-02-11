<?php

namespace AC\Controller;

use AC\Capabilities;
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
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		if ( 'restore' !== filter_input( INPUT_POST, 'ac_action' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), 'restore' ) ) {
			return;
		}

		foreach ( $this->repository->find_all() as $list_screen ) {
			$this->repository->delete( $list_screen );
		}

		$this->delete_options();
		$this->delete_user_preferences();

		$notice = new Notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ) );
		$notice->register();
	}

	private function delete_user_preferences() {
		global $wpdb;

		$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE '{$wpdb->get_blog_prefix()}ac_preferences_%'" );
	}

	private function delete_options() {
		global $wpdb;

		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_api_request%'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_cache_data%'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'ac_sorting_%'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'cpac_options%__default'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'cpac_general_options'" );
	}

}