<?php

namespace AC\Plugin;

use AC\Capabilities;
use AC\Message;
use AC\Plugin;

class Updater {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * @var bool
	 */
	protected $fresh_install;

	/**
	 * @var bool
	 */
	protected $apply_updates;

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
		// TODO: https://github.com/codepress/admin-columns-issues/issues/982
		//$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
		$this->apply_updates = true;
	}

	public function add_update( Update $update ) {
		$this->updates[ $update->get_version() ] = $update;
	}

	/**
	 * Checks conditions like user permissions
	 *
	 */
	public function check_update_conditions() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return false;
		}

		// Network wide updating is not supported yet
		if ( is_network_admin() ) {
			return false;
		}

		return true;
	}

	public function parse_updates() {
		if ( ! $this->check_update_conditions() ) {
			return;
		}

		if ( $this->plugin->is_new_install() ) {
			$this->plugin->update_stored_version();

			return;
		}

		// Sort by version number
		uksort( $this->updates, 'version_compare' );

		/* @var Update $update */
		foreach ( $this->updates as $update ) {
			if ( $update->needs_update() ) {
				if ( ! $this->apply_updates ) {
					$this->show_update_notice();

					return;
				}

				$update->apply_update();
				$this->plugin->update_stored_version( $update->get_version() );
			}
		}

		if ( ! $this->apply_updates ) {
			return;
		}

		$this->plugin->update_stored_version();
		// TODO: https://github.com/codepress/admin-columns-issues/issues/982
		//$this->show_completed_notice();
	}

	protected function show_completed_notice() {
		$message = sprintf( '<strong>%s</strong> &ndash; %s',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'Your database is up to date. You are awesome.', 'codepress-admin-columns' )
		);

		$notice = new Message\Notice();
		$notice->set_message( $message )
		       ->register();
	}

	protected function show_update_notice() {
		$url = add_query_arg( array( 'ac_do_update' => 'true' ), AC()->admin()->get_settings_url() );

		$message = sprintf( '<strong>%s</strong> &ndash; %s <a href="%s" class="button ac-update-now">%s</a>',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'We need to update your database to the latest version.', 'codepress-admin-columns' ),
			esc_url( $url ),
			esc_html__( 'Run the updater', 'codepress-admin-columns' )
		);

		$notice = new Message\Notice();
		$notice->set_message( $message )
		       ->set_type( $notice::INFO )
		       ->register();
	}

}