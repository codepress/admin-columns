<?php

namespace AC\Controller;

use AC\Capabilities;
use AC\ListScreen;
use AC\ListScreenRepository\Aggregate;
use AC\Message\Notice;
use AC\Registrable;

class ListScreenRestoreColumns implements Registrable {

	/** @var Aggregate */
	private $repository;

	public function __construct( Aggregate $repository ) {
		$this->repository = $repository;
	}

	public function register() {
		// todo: early enough?
		add_action( 'init', [ $this, 'handle_request' ] );
	}

	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'action' ) ) {

			case 'restore_by_type' :
				if ( $this->verify_nonce( 'restore-type' ) ) {

					$list_screen = $this->repository->find( filter_input( INPUT_POST, 'layout' ) );

					if ( ! $list_screen ) {
						return;
					}

					$list_screen->set_settings( [] );
					$this->repository->save( $list_screen );

					$notice = new Notice( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $this->get_list_screen_message_label( $list_screen ) ) . "</strong>" ) );
					$notice->register();
				}

				do_action( 'ac/restored_columns', $list_screen );
				break;
		}
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	private function verify_nonce( $action ) {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $action );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return string $label
	 */
	private function get_list_screen_message_label( ListScreen $list_screen ) {

		// todo: do we need this filter?
		return apply_filters( 'ac/settings/list_screen_message_label', $list_screen->get_label(), $list_screen );
	}

}