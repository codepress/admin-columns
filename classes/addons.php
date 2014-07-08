<?php
class CPAC_Addons {

	/**
	 * CPAC class
	 *
	 * @since 2.2
	 */
	private $cpac;

	/**
	 * @since 2.2
	 *
	 * @param CPAC
	 */
	function __construct( $cpac ) {

		$this->cpac = $cpac;

		// Redirect to addons settings tab on activation & deactivation
		if ( is_admin() ) {
			add_filter( 'wp_redirect', array( $this, 'addon_plugin_statuschange_redirect' ) );
		}

		// Handle install request
		add_action( 'admin_init', array( $this, 'handle_install_request' ) );
	}

	/**
	 * Handles the installation of the add-on
	 *
	 * @since 2.2
	 */
	public function handle_install_request() {

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'install-cac-addon' ) || ! isset( $_GET['plugin'] ) )
			return;

		if ( ! $this->get_addon( $_GET['plugin'] ) ) {
			cpac_admin_message( 'Addon does not exist.', 'error' );
			return;
		}

		if ( ! class_exists('CAC_Addon_Pro') ) {
			cpac_admin_message( 'You need Admin Columns Pro.', 'error' );
			return;
		}

		// Hook: trigger possible warning message before running WP installer ( api errors etc. )
		if ( $error = apply_filters( 'cac/addons/install_request/maybe_error', false, $_GET['plugin'] ) ) {
			cpac_admin_message( $error, 'error' );
			return;
		}

		$install_url = add_query_arg( array(
			'action' => 'install-plugin',
			'plugin' => $_GET['plugin'],
			'cpac-redirect' => true
		), wp_nonce_url( network_admin_url( 'update.php'), 'install-plugin_' . $_GET['plugin'] ) );

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

		if ( ! isset( $_GET['cpac-redirect'] ) ) {
			return $location;
		}

		$urlparts = parse_url( $location );

		if ( ! $urlparts ) {
			return $location;
		}

		if ( ! empty( $urlparts['query'] ) ) {
			$admin_url = $urlparts['scheme'] . '://' . $urlparts['host'] . $urlparts['path'];

			// activate or deactivae plugin
			if ( admin_url( 'plugins.php' ) == $admin_url ) {
				parse_str( $urlparts['query'], $request );

				if ( empty( $request['error'] ) ) {
					$location = add_query_arg( empty( $request['activate'] ) ? 'deactivate' : 'activate', true, $this->cpac->settings()->get_settings_url( 'addons' ) );
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
			'integration' => __( 'Third party plugin integration', 'cpac' )
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
	 * @return array Available addons ([addon_basename] => (array) [addon_details] if not grouped, a list of these key-value pairs per group otherwise ([group_name] => (array) [group_addons]))
	 */
	public function get_available_addons( $grouped = false ) {

		$addons = array(
			'cac-addon-acf' => array(
				'title' 		=> __( 'Advanced Custom Fields', 'cpac' ),
				'description' 	=> __( 'Display and edit Advanced Custom Fields fields in the posts overview in seconds!', 'cpac' ),
				'group' 		=> 'integration',
				'image' 		=> CPAC_URL . 'assets/images/addons/acf.png'
			),
			'cac-addon-woocommerce' => array(
				'title' 		=> __( 'WooCommerce', 'cpac' ),
				'description' 	=> __( 'Enhance the products, orders and coupons overviews with new columns and inline editing.', 'cpac' ),
				'group' 		=> 'integration',
				'image' 		=> CPAC_URL . 'assets/images/addons/woocommerce.png'
			)
		);

		/**
		 * Filter the available addons
		 *
		 * @since 2.2
		 *
		 * @param array $addons Available addons ([addon_name] => (array) [addon_details])
		 */
		$addons = apply_filters( 'cpac/addons/available_addons', $addons );

		foreach ( $addons as $addon_name => $addon ) {
			$addons[ $addon_name ] = wp_parse_args( $addon, array(
				'title' 	=> '',
				'group' 	=> '',
				'image' 	=> ''
			) );
		}

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
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 */
	public function group_addons( $addons ) {

		$groups = $this->get_addon_groups();
		$grouped_addons = array();

		foreach ( $addons as $addon_name => $addon ) {
			if ( ! isset( $groups[ $addon['group'] ] ) ) {
				continue;
			}

			if ( ! isset( $grouped_addons[ $addon['group'] ] ) ) {
				$grouped_addons[ $addon['group'] ] = array();
			}

			$grouped_addons[ $addon['group'] ][ $addon_name ] = $addon;
		}
		return $grouped_addons;
	}

	/**
	 * Get whether an add-on is installed (i.e. the plugin is available in the plugin directory)
	 *
	 * @since 2.2
	 *
	 * @param string $slug Plugin dirname/slug
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
	 * @param string $slug Plugin dirname/slug
	 * @return string|bool Returns the plugin basename if the plugin is installed, false otherwise
	 */
	public function get_installed_addon_plugin_basename( $slug ) {

		$plugins = get_plugins();

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
	 * @param string $slug Plugin dirname/slug
	 * @return string|bool Returns the plugin version if the plugin is installed, false otherwise
	 */
	public function get_installed_addon_plugin_version( $slug ) {

		$plugins = get_plugins();

		foreach ( $plugins as $plugin_basename => $plugin ) {
			if ( $slug == dirname( $plugin_basename ) ) {
				return $plugin['Version'];
			}
		}

		return false;
	}
}
