<?php
class CPAC_Addons {

	/**
	 * CPAC class
	 *
	 * @since 2.2
	 */
	private $cpac;

	/**
	 * Registered add-ons
	 *
	 * @since 2.2
	 */
	protected $addons;

	/**
	 * Constructor
	 *
	 * @since 2.2
	 * @param CPAC
	 */
	function __construct( $cpac ) {

		$this->cpac = $cpac;

		// Hooks
		add_action( 'plugins_loaded', array( $this, 'register_addons' ) );
		add_filter( 'extra_plugin_headers', array( $this, 'plugindata_load_cpac_headers' ) );

		if ( is_admin() ) {
			add_filter( 'wp_redirect', array( $this, 'addon_plugin_statuschange_redirect' ) );
		}
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @since 2.2
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

		if ( ! empty( $urlparts['query'] ) && $urlparts['scheme'] . '://' . $urlparts['host'] . $urlparts['path'] == admin_url( 'plugins.php' ) ) {
			parse_str( $urlparts['query'], $request );

			if ( empty( $request['error'] ) ) {
				$location = add_query_arg( empty( $request['activate'] ) ? 'deactivate' : 'activate', true, $this->cpac->settings()->get_settings_url( 'addons' ) );
			}
		}


		return $location;
	}

	/**
	 * Add the Admin Columns headers to the list of headers to be loaded when fetching plugin file data
	 *
	 * @since 2.2
	 *
	 * @see filter:extra_{$context}_headers
	 */
	public function plugindata_load_cpac_headers( $headers ) {

		$headers['CPAC Addon ID'] = 'CPAC Addon ID';

		return $headers;
	}

	/**
	 * Get addon groups. Addons are grouped into addon groups by providing the group an addon belongs to (see CPAC_Addons::get_available_addons()).
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
	 * Get all available addons
	 *
	 * @since 2.2
	 *
	 * @param bool $grouped Whether to group the plugins by addon group ()
	 * @return array Available addons ([addon_name] => (array) [addon_details] if not grouped, a list of these key-value pairs per group otherwise ([group_name] => (array) [group_addons]))
	 */
	public function get_available_addons( $grouped = false ) {

		$addons = array(
			'acf' => array(
				'title' => __( 'Advanced Custom Fields', 'cpac' ),
				'group' => 'integration',
				'image' => CPAC_URL . '/assets/images/addons/acf.png'
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
				'title' => '',
				'group' => '',
				'image' => ''
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
	 * Register add-ons
	 *
	 * @since 2.2
	 */
	public function register_addons() {

		/**
		 * Fires after all plugins are loaded
		 * Use this to register add-ons to Admin Columns
		 *
		 * @since 2.2
		 *
		 * @param CPAC_Addons $cpac_addons Admin Columns plugin add-ons class instance
		 */
		do_action( 'cac/register_addons', $this );
	}

	/**
	 * Register an add-on by passing its main plugin class instance
	 *
	 * @since 2.2
	 *
	 * @param object $instance Main plugin class instance
	 */
	public function register_addon( $instance ) {

		$this->addons[ $instance->addon['id'] ] = $instance;
	}

	/**
	 * Get an add-on main plugin class instance by its id
	 *
	 * @since 2.2
	 *
	 * @param string $id Unique add-on ID
	 * @return bool|object Returns false if there is no add-on registered with the passed ID, the class instance otherwise
	 */
	public function get_registered_addon( $id ) {

		if ( ! isset( $this->addons[ $id ] ) ) {
			return false;
		}

		return $this->addons[ $id ];
	}

	/**
	 * Get whether an add-on is installed (i.e. the plugin is available in the plugin directory)
	 *
	 * @since 2.2
	 *
	 * @param string $id Unique add-on ID
	 * @return bool Returns true if there is no add-on installed with the passed ID, false otherwise
	 */
	public function is_addon_installed( $id ) {

		return $this->get_installed_addon_plugin_basename( $id ) ? true : false;
	}

	/**
	 * Get the plugin basename (see plugin_basename()) from a plugin, for example "my-plugin/my-plugin.php"
	 *
	 * @since 2.2
	 *
	 * @param string $id Unique add-on ID
	 * @return string|bool Returns the plugin basename if the plugin is installed, false otherwise
	 */
	public function get_installed_addon_plugin_basename( $id ) {

		$plugins = get_plugins();

		foreach ( $plugins as $plugin_basename => $plugin ) {
			if ( isset( $plugin['CPAC Addon ID'] ) && $plugin['CPAC Addon ID'] == $id ) {
				return $plugin_basename;
			}
		}

		return false;
	}

	/**
	 * Get a list of all registered add-on IDs
	 *
	 * @since 2.2
	 *
	 * @return array Registered add-on IDs
	 */
	public function get_registered_addons() {

		return array_keys( $this->addons );
	}

}
?>