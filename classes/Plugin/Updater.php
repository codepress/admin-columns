<?php

class AC_Plugin_Updater {

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
	 * @var AC_Plugin_Update[]
	 */
	protected $updates;

	/**
	 * @var AC_Plugin
	 */
	protected $plugin;

	/**
	 * @param AC_Plugin $plugin
	 */
	public function __construct( AC_Plugin $plugin ) {
		$this->plugin = $plugin;
		$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
	}

	public function add_update( AC_Plugin_Update $update ) {
		$this->updates[ $update->get_version() ] = $update;
	}

	public function parse_updates() {
		// Network wide updating is not allowed
		if ( is_network_admin() ) {
			return;
		}

		$plugin = $this->plugin;

		if ( $plugin->is_fresh_install() ) {
			$plugin->update_stored_version( $plugin->get_version() );

			return;
		}

		krsort( $this->updates, SORT_NUMERIC );

		/* @var AC_Plugin_Update $update */
		foreach ( $this->updates as $update ) {
			if ( $update->needs_update() ) {
				if ( ! $this->apply_updates ) {
					$this->show_update_notice();

					return;
				}

				$update->apply_update();
				$plugin->update_stored_version( $update->get_version() );
			}
		}

		if ( $this->apply_updates ) {
			$plugin->update_stored_version( $plugin->get_version() );
			$this->show_completed_notice();
		}
	}

	protected function show_completed_notice() {
		$message = sprintf( '<strong>%s</strong> &ndash; %s',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'Your database is up to date. You are awesome.', 'codepress-admin-columns' )
		);

		AC()->notice( $message );
	}

	protected function show_update_notice() {
		$url = add_query_arg( array( 'ac_do_update' => 'true' ), AC()->admin()->get_settings_url() );

		$message = sprintf( '<strong>%s</strong> &ndash; %s <a href="%s" class="button ac-update-now">%s</a>',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'We need to update your database to the latest version.', 'codepress-admin-columns' ),
			esc_url( $url ),
			esc_html__( 'Run the updater', 'codepress-admin-columns' )
		);

		AC()->notice( $message, 'notice-info' );
	}

}