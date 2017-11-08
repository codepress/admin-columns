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
	 * Contains updates with plugin basename as key
	 *
	 * @var array
	 */
	protected $updates;

	/**
	 * Contains all plugins that have one or more updates available
	 *
	 * @var AC_Plugin[]
	 */
	protected $plugins;

	protected function __construct() {
		$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
		$this->set_fresh_install();
	}

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Check if the plugin was updated or that it's a first install
	 */
	private function set_fresh_install() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		$this->fresh_install = empty( $results );
	}

	/**
	 * @return bool
	 */
	public function is_fresh_install() {
		return $this->fresh_install;
	}

	public function add_update( AC_Plugin $plugin, AC_Plugin_Update $update ) {
		$this->plugins[ $plugin->get_basename() ] = $plugin;
		$this->updates[ $plugin->get_basename() ][ $update->get_version() ] = $update;
	}

	public function parse_updates() {
		foreach ( $this->plugins as $basename => $plugin ) {
			if ( $this->is_fresh_install() ) {
				$plugin->update_stored_version( $plugin->get_version() );

				continue;
			}

			krsort( $this->updates[ $basename ], SORT_NUMERIC );

			/* @var AC_Plugin_Update $update */
			foreach ( $this->updates[ $basename ] as $update ) {
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
			}
		}
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