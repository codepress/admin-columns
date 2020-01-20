<?php

namespace AC\Plugin;

use AC\Message;
use AC\Plugin;

class Updater {

	/**
	 * @var Update[]
	 */
	protected $updates;

	/**
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function add_update( Update $update ) {
		$this->updates[ $update->get_version() ] = $update;
	}

	public function parse_updates() {
		if ( $this->plugin->is_new_install() ) {
			$this->plugin->update_stored_version();

			return;
		}

		// Sort by version number
		uksort( $this->updates, 'version_compare' );

		/* @var Update $update */
		foreach ( $this->updates as $update ) {
			if ( $update->needs_update() ) {

				$update->apply_update();
				$this->plugin->update_stored_version( $update->get_version() );
			}
		}

		$this->plugin->update_stored_version();
	}

	/**
	 * @deprecated
	 */
	protected function show_completed_notice() {
		$message = sprintf( '<strong>%s</strong> &ndash; %s',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'Your database is up to date. You are awesome.', 'codepress-admin-columns' )
		);

		$notice = new Message\Notice( $message );
		$notice->register();
	}

	/**
	 * @deprecated
	 */
	protected function show_update_notice() {
		$url = add_query_arg( array( 'ac_do_update' => 'true' ), ac_get_admin_url( 'settings' ) );

		$message = sprintf( '<strong>%s</strong> &ndash; %s <a href="%s" class="button ac-update-now">%s</a>',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'We need to update your database to the latest version.', 'codepress-admin-columns' ),
			esc_url( $url ),
			esc_html__( 'Run the updater', 'codepress-admin-columns' )
		);

		$notice = new Message\Notice( $message );
		$notice
			->set_type( $notice::INFO )
			->register();
	}

}