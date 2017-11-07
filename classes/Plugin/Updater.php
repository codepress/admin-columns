<?php

class AC_Plugin_Updater {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * @var bool
	 */
	protected $show_notice;

	/**
	 * @var bool
	 */
	protected $apply_updates;

	protected function __construct() {
		$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
		$this->show_notice = false;
	}

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @param AC_Plugin $plugin
	 * @param array     $updates Key should contain version, value should be an array with callbacks
	 */
	public function parse_updates( AC_Plugin $plugin, array $updates ) {
		if ( $this->show_notice || version_compare( $plugin->get_version(), $plugin->get_stored_version(), '<=' ) ) {
			return;
		}

		foreach ( $updates as $version => $callbacks ) {
			if ( version_compare( $plugin->get_stored_version(), $version, '>=' ) ) {
				continue;
			}

			// Stop further checking, queue update message
			if ( ! $this->apply_updates ) {
				add_action( 'admin_init', array( $this, 'show_update_notice' ) );

				return;
			}

			foreach ( $callbacks as $callback ) {
				if ( is_callable( $callback ) ) {
					call_user_func( $callback );
				}
			}
		}

		$plugin->update_stored_version( $plugin->get_version() );

		add_action( 'admin_init', array( $this, 'redirect_after_update' ) );
	}

	public function redirect_after_update() {
		wp_redirect( remove_query_arg( 'ac_do_update' ) );
	}

	public function show_update_notice() {
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