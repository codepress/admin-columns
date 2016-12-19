<?php

final class AC_Addons {

	/**
	 * User meta key for hiding "Install addons" notice
	 *
	 * @since 2.4.9
	 */
	const OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY = 'cpac-hide-install-addons-notice';

	/**
	 * @var AC_Addon[]
	 */
	private $addons;

	/**
	 * @since 2.2
	 *
	 * @param CPAC
	 */
	function __construct() {
		add_filter( 'wp_redirect', array( $this, 'addon_plugin_statuschange_redirect' ) );
		add_action( 'admin_init', array( $this, 'handle_install_request' ) );
		add_action( 'admin_notices', array( $this, 'missing_addon_notices' ) );
		add_action( 'wp_ajax_cpac_hide_install_addons_notice', array( $this, 'ajax_hide_install_addons_notice' ) );
	}

	public function register_addon( AC_Addon $addon ) {
		$this->addons[] = $addon;
	}

	/**
	 * Register addon
	 */
	private function set_addons() {
		$classes = AC()->autoloader()->get_class_names_from_dir( AC()->get_plugin_dir() . 'classes/Addon', 'AC_' );

		foreach ( $classes as $class ) {
			$this->register_addon( new $class );
		}
    }

	/**
	 * @return AC_Addon[]
	 */
	public function get_addons() {
	    if ( null === $this->addons ) {
	        $this->set_addons();
        }

        return $this->addons;
	}

	/**
	 * Possibly adds an admin notice when a third party plugin supported by an addon is installed, but the addon isn't
	 *
	 * @since 2.4.9
	 */
	public function missing_addon_notices() {

		if ( AC()->suppress_site_wide_notices() ) {
			return;
		}

		if ( get_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY, true ) ) {
			return;
		}

		$plugins = array();

		foreach ( $this->get_addons() as $addon ) {
		    if ( $addon->is_plugin_active() && ! $addon->is_addon_active() ) {
			    $plugins[] = $addon->get_title();
            }
        }

		if ( $plugins ) {
			$num_plugins = count( $plugins );

			foreach ( $plugins as $index => $plugin ) {
				$plugins[ $index ] = '<strong>' . $plugin . '</strong>';
			}

			$plugins_list = $plugins[0];

			if ( $num_plugins > 1 ) {
				if ( $num_plugins > 2 ) {
					$plugins_list = implode( ', ', array_slice( $plugins, 0, $num_plugins - 1 ) );
					$plugins = array( $plugins_list, $plugins[ $num_plugins - 1 ] );
				}

				$plugins_list = sprintf( __( '%s and %s', 'codepress-admin-columns' ), $plugins[0], $plugins[1] );
			}
			?>
            <div class="cpac_message updated">
                <a href="#" class="hide-notice hide-install-addons-notice"></a>

                <p><?php printf(
						__( "Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!", 'codepress-admin-columns' ),
						$plugins_list,
						'<a href="' . esc_attr( AC()->settings()->get_link( 'addons' ) ) . '">' . esc_html( __( 'the addons page', 'codepress-admin-columns' ) ) . '</a>'
					); ?>
            </div>
            <style type="text/css">
                body .wrap .cpac_message {
                    position: relative;
                    padding-right: 40px;
                }

                .cpac_message .spinner.right {
                    visibility: visible;
                    display: block;
                    right: 8px;
                    text-decoration: none;
                    text-align: right;
                    position: absolute;
                    top: 50%;
                    margin-top: -10px;
                }

                .cpac_message .hide-notice {
                    right: 8px;
                    text-decoration: none;
                    width: 32px;
                    text-align: right;
                    position: absolute;
                    top: 50%;
                    height: 32px;
                    margin-top: -16px;
                }

                .cpac_message .hide-notice:before {
                    display: block;
                    content: '\f335';
                    font-family: 'Dashicons', serif;
                    margin: .5em 0;
                    padding: 2px;
                }
            </style>
            <script type="text/javascript">
				jQuery( function( $ ) {
					$( document ).ready( function() {
						$( '.updated a.hide-install-addons-notice' ).click( function( e ) {
							e.preventDefault();

							var el = $( this ).parents( '.cpac_message' );
							var el_close = el.find( '.hide-notice' );

							el_close.hide();
							el_close.after( '<div class="spinner right"></div>' );
							el.find( '.spinner' ).show();

							$.post( ajaxurl, {
								'action' : 'cpac_hide_install_addons_notice'
							}, function() {
								el.find( '.spinner' ).remove();
								el.slideUp();
							} );

							return false;
						} );
					} );
				} );
            </script>
			<?php
		}
	}

	/**
	 * Ajax callback for hiding the "Missing addons" notice used for notifying users of available integration addons for plugins they have installed
	 *
	 * @since 2.4.9
	 */
	public function ajax_hide_install_addons_notice() {
		update_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY, '1', true );
	}

	/**
	 * Handles the installation of the add-on
	 *
	 * @since 2.2
	 */
	public function handle_install_request() {

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'install-cac-addon' ) || ! isset( $_GET['plugin'] ) ) {
			return;
		}

		if ( ! $this->get_addon( $_GET['plugin'] ) ) {
			cpac_admin_message( __( 'Addon does not exist.', 'codepress-admin-columns' ), 'error' );

			return;
		}

		if ( ! cpac_is_pro_active() ) {
			cpac_admin_message( __( 'You need Admin Columns Pro.', 'codepress-admin-columns' ), 'error' );

			return;
		}

		// Hook: trigger possible warning message before running WP installer ( api errors etc. )
		if ( $error = apply_filters( 'cac/addons/install_request/maybe_error', false, $_GET['plugin'] ) ) {
			cpac_admin_message( $error, 'error' );

			return;
		}

		$install_url = add_query_arg( array(
			'action'        => 'install-plugin',
			'plugin'        => $_GET['plugin'],
			'cpac-redirect' => true,
		), wp_nonce_url( network_admin_url( 'update.php' ), 'install-plugin_' . $_GET['plugin'] ) );

		wp_redirect( $install_url );
		exit;
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @since 2.2
	 *
	 * @see filter:wp_redirect
	 */
	public function addon_plugin_statuschange_redirect( $location ) {

		if ( ! is_admin() || ! isset( $_GET['cpac-redirect'] ) ) {
			return $location;
		}

		$urlparts = parse_url( $location );

		if ( ! $urlparts ) {
			return $location;
		}

		if ( ! empty( $urlparts['query'] ) ) {
			$admin_url = $urlparts['scheme'] . '://' . $urlparts['host'] . $urlparts['path'];

			// activate or deactivate plugin
			if ( admin_url( 'plugins.php' ) == $admin_url ) {
				parse_str( $urlparts['query'], $request );

				if ( empty( $request['error'] ) ) {
					$location = add_query_arg( empty( $request['activate'] ) ? 'deactivate' : 'activate', true, AC()->settings()->get_link( 'addons' ) );
				}
			}
		}

		return $location;
	}

	/**
	 * Addons are grouped into addon groups by providing the group an addon belongs to (see CPAC_Addons::get_available_addons()).
	 *
	 * @since 2.2
	 *
	 * @return array Available addon groups ([group_name] => [label])
	 */
	public function get_addon_groups() {

		$addon_groups = array(
			'integration' => __( 'Plugins', 'codepress-admin-columns' ),
		);

		/**
		 * Filter the addon groups
		 *
		 * @since 2.2
		 *
		 * @param array $addon_groups Available addon groups ([group_name] => [label])
		 */
		$addon_groups = apply_filters( 'cpac/addons/addon_groups', $addon_groups );

		return $addon_groups;
	}

	/**
	 * @since 2.2
	 *
	 * @param bool $grouped Whether to group the plugins by addon group ()
	 *
	 * @return array Available addons ([addon_basename] => (array) [addon_details] if not grouped, a list of these key-value pairs per group otherwise ([group_name] => (array) [group_addons]))
	 */
	public function get_available_addons( $grouped = false ) {
		$addons = $this->get_addons();

		// Maybe group add-ons
		if ( $grouped ) {
			$addons = $this->group_addons( $addons );
		}

		return $addons;
	}

	/**
	 * Get add-on details from the available add-ons list
	 *
	 * @since 2.2
	 *
	 * @param string $id Unique addon ID
	 *
	 * @return bool|array Returns addon details if the add-on exists, false otherwise
	 */
	public function get_addon( $id ) {

		$addons = $this->get_available_addons();

		if ( isset( $addons[ $id ] ) ) {
			return $addons[ $id ];
		}

		return false;
	}

	/**
	 * Group a list of add-ons
	 *
	 * @since 2.2
	 * @uses CPAC_Addons::group_addons()
	 *
	 * @param array $addons List of addons ([addon_name] => (array) [addon_details])
	 *
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 */
	public function group_addons( $addons ) {

		$groups = $this->get_addon_groups();
		$grouped_addons = array();

		foreach ( $addons as $addon_name => $addon ) {
			if ( ! isset( $groups[ $addon->get_group() ] ) ) {
				continue;
			}

			if ( ! isset( $grouped_addons[ $addon->get_group() ] ) ) {
				$grouped_addons[ $addon->get_group() ] = array();
			}

			$grouped_addons[ $addon->get_group() ][ $addon_name ] = $addon;
		}

		return $grouped_addons;
	}

	/**
	 * Get whether an add-on is installed (i.e. the plugin is available in the plugin directory)
	 *
	 * @since 2.2
	 *
	 * @param string $slug Plugin directory name/slug
	 *
	 * @return bool Returns true if there is no add-on installed with the passed ID, false otherwise
	 */
	public function is_addon_installed( $slug ) {
		return $this->get_installed_addon_plugin_basename( $slug ) ? true : false;
	}

	/**
	 * Get the plugin basename (see plugin_basename()) from a plugin, for example "my-plugin/my-plugin.php"
	 *
	 * @since 2.2
	 *
	 * @param string $slug Plugin directory name/slug
	 *
	 * @return string|bool Returns the plugin basename if the plugin is installed, false otherwise
	 */
	public function get_installed_addon_plugin_basename( $slug ) {
		$plugins = (array) get_plugins();

		foreach ( $plugins as $plugin_basename => $plugin ) {
			if ( $slug == dirname( $plugin_basename ) ) {
				return $plugin_basename;
			}
		}

		return false;
	}

	/**
	 * @since 2.2
	 *
	 * @param string $slug Plugin directory name/slug
	 *
	 * @return string|bool Returns the plugin version if the plugin is installed, false otherwise
	 */
	public function get_installed_addon_plugin_version( $slug ) {
		$plugins = (array) get_plugins();

		foreach ( $plugins as $plugin_basename => $plugin ) {
			if ( $slug == dirname( $plugin_basename ) ) {
				return $plugin['Version'];
			}
		}

		return false;
	}

}