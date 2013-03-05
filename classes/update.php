<?php

// For testing purpose only
// set_site_transient( 'update_plugins', null );

/**
 * Addon update class
 *
 * Example usage:
 * new CAC_Addon_Update( array(
 *		'store_url'			=> 'http://www.admincolumns.com',
 *		'item_name'			=> 'My Add-on', // need to exactly match EDD product name
 *		'license_key_id'	=> 'addon_id_license_key',
 *		'license_status_id'	=> 'addon_id_license_status'
 * ));
 *
 * @since 0.1
 */
class CAC_Addon_Update {

	/**
	 * The URL of the site with EDD installed
	 *
	 * @since 0.1
	 */
	private $store_url;

	/**
	 * The name of the product.
	 *
	 * This needs to match the download name in EDD exactly
	 *
	 * @since 0.1
	 */
	private $item_name;

	/**
	 * License key ID.
	 *
	 * Used to retrieve license from DB by get_option().
	 *
	 * @since 0.1
	 */
	private $license_key_id;

	/**
	 * License key status.
	 *
	 * Used to retrieve license status from DB by get_option().
	 *
	 * @since 0.1
	 */
	private $license_status_id;

	/**
	 * Product version
	 *
	 * @since 0.1
	 */
	private $version;

	/**
	 * Construct
	 *
	 * @since 0.1
	 *
	 * @param array $args Arguments;
	 */
	function __construct( $args ) {

		extract( $args );

		$this->store_url 			= $store_url;
		$this->item_name 			= $item_name;
		$this->license_key_id		= $license_key_id;
		$this->license_status_id 	= $license_status_id;
		$this->version				= $version;

		// setup updater
		$this->setup_updater();

		// setup setting fields
		add_filter( 'cpac_settings_groups', array( $this, 'settings_group' ) );
		add_action( 'cpac_settings_row_addons', array( $this, 'display' ) );

		// handle requests
		add_action( 'admin_init', array( $this, 'activate_license' ) );
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );
	}

	/**
	 * Setup EDD Updater
	 *
	 * @since 0.1
	 */
	private function setup_updater() {

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( $this->store_url, __FILE__, array(
				'version' 	=> '1.0',
				'license' 	=> $this->get_license_key(),
				'item_name' => $this->item_name,
				'author' 	=> 'Codepress'
			)
		);
	}

	/**
	 * Get license key
	 *
	 * You can add your license key to your theme's functions.php by adding the following code:
	 * add_filter( 'cac_sc_license_key', 'my_license_key'); function my_license_key() { return 'enter your license key here'; }
	 *
	 * @since 0.1
	 */
	public function get_license_key() {

		return trim( apply_filters( $this->license_key_id, get_option( $this->license_key_id ) ) );
	}

	/**
	 * Get license status
	 *
	 * @since 0.1
	 */
	function get_license_status() {

		return trim( get_option( $this->license_status_id ) );
	}

	/**
	 * Add settings group to CAC
	 *
	 * @since 0.1
	 */
	public function settings_group( $groups ) {

		if ( isset( $groups['addons'] ) )
			return $groups;

		$groups['addons'] =  array(
			'title'			=> __( 'Add-ons updates', 'cpac' ),
			'description'	=> __( 'Enter your license to receive automatic updates.', 'cpac' )
		);

		return $groups;
	}

	/**
	 * Connect API
	 *
	 * @since 0.1
	 *
	 * @param string $license License Key
	 * @param string $action activate_license | deactivate_license
	 * @return string License status
	 */
	function connect_api( $license, $action ) {

		// data to send in our API request
		$api_params = array(
			'edd_action'	=> $action,
			'license' 		=> $license,
			'item_name' 	=> urlencode( $this->item_name )
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, $this->store_url ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {

			cpac_admin_message( __( 'Could not connect to API.', 'cpac' ), 'error' );
			return false;
		}

		// get license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// error?
		if ( ! isset( $license_data->license ) ) {

			cpac_admin_message( __( 'Wrong response from API.', 'cpac' ), 'error' );
			return false;
		}

		// return license status
		return $license_data->license;
	}

	/**
	 * Deactivate license
	 *
	 * @since 0.1
	 */
	public function deactivate_license() {

		// check nonce
		if ( ! isset( $_POST['_wpnonce_addon_deactivate'] ) || ! wp_verify_nonce( $_POST['_wpnonce_addon_deactivate'], $this->license_key_id ) )
			return;

		// connect to API
		if ( ! $status = $this->connect_api( $this->get_license_key(), 'deactivate_license' ) )
			return false;

		// update license key and status
		delete_option( $this->license_key_id );
		update_option( $this->license_status_id, $status );

		// response not as expected?
		if ( 'deactivated' !== $status ) {

			cpac_admin_message( $this->item_name . ' ' . sprintf( __( 'license is %s.', 'cpac' ), "<strong>{$status}</strong>" ), 'error' );
			return false;
		}

		cpac_admin_message( $this->item_name . ' ' . sprintf( __( 'license is %s.', 'cpac' ), "<strong>{$status}</strong>" ), 'updated' );
	}

	/**
	 * Activate license
	 *
	 * @since 0.1
	 */
	public function activate_license() {

		// check nonce
		if ( ! isset( $_POST['_wpnonce_addon_activate'] ) || ! wp_verify_nonce( $_POST['_wpnonce_addon_activate'], $this->license_key_id ) )
			return;

		// license empty?
		if ( ! $_POST[ $this->license_key_id ] ) {
			cpac_admin_message( $this->item_name . ' ' . __( 'License is empty.', 'cpac' ), 'error' );
			return;
		}

		$license = sanitize_text_field( $_POST[ $this->license_key_id ] );

		// connect to API
		if ( ! $status = $this->connect_api( $license, 'activate_license' ) )
			return false;

		// response not as expected?
		if ( 'valid' !== $status ) {

			cpac_admin_message( $this->item_name . ' ' . sprintf( __( 'license is %s.', 'cpac' ), "<strong>{$status}</strong>" ), 'error' );
			return false;
		}

		cpac_admin_message( $this->item_name . ' ' . sprintf( __( 'license is %s.', 'cpac' ), "<strong>{$status}</strong>" ), 'updated' );

		// update license key and status
		update_option( $this->license_key_id, $license );
		update_option( $this->license_status_id, $status );
	}

	/**
	 * Display License field
	 *
	 * @since 0.1
	 */
	function display() {

		$license = $this->get_license_key();
		$status  = $this->get_license_status();
		?>

		<form action="" method="post">
			<label for="<?php echo $this->license_key_id; ?>">
				<strong><?php echo $this->item_name; ?></strong>
			</label>
			<br/>

		<?php if ( 'valid' !== $status ) : ?>

			<?php wp_nonce_field( $this->license_key_id, '_wpnonce_addon_activate' ); ?>

			<input type="text" value="<?php echo $license; ?>" id="<?php echo $this->license_key_id; ?>" name="<?php echo $this->license_key_id; ?>" class="regular-text" placeholder="<?php _e( 'Fill in your license code', 'cpac' ) ?>" >
			<input type="submit" class="button" value="<?php _e( 'Activate License', 'cpac' ); ?>" >
			<p class="description"><?php _e( 'Enter your license to receive automatic updates.', 'cpac' ); ?></p>

		<?php else : ?>

			<?php wp_nonce_field( $this->license_key_id, '_wpnonce_addon_deactivate' ); ?>

			<span class="status-valid"></span>
			<input type="password" value="<?php echo $license; ?>" id="<?php echo $this->license_key_id; ?>" class="regular-text" name="<?php echo $this->license_key_id; ?>">
			<input type="submit" class="button" value="<?php _e( 'Deactivate License', 'cpac' ); ?>" >

		<?php endif; ?>

		</form>
		<br/>

		<?php

	}
}

if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {

	/**
	 * Allows plugins to use their own update API.
	 *
	 * @author Pippin Williamson
	 * @version 1.0
	 */
	class EDD_SL_Plugin_Updater {
		private $api_url  = '';
		private $api_data = array();
		private $name     = '';
		private $slug     = '';

		/**
		 * Class constructor.
		 *
		 * @uses plugin_basename()
		 * @uses hook()
		 *
		 * @param string $_api_url The URL pointing to the custom API endpoint.
		 * @param string $_plugin_file Path to the plugin file.
		 * @param array $_api_data Optional data to send with API calls.
		 * @return void
		 */
		function __construct( $_api_url, $_plugin_file, $_api_data = null ) {
			$this->api_url  = trailingslashit( $_api_url );
			$this->api_data = urlencode_deep( $_api_data );
			$this->name     = plugin_basename( $_plugin_file );
			$this->slug     = basename( $_plugin_file, '.php');
			$this->version  = $_api_data['version'];

			// Set up hooks.
			$this->hook();
		}

		/**
		 * Set up Wordpress filters to hook into WP's update process.
		 *
		 * @uses add_filter()
		 *
		 * @return void
		 */
		private function hook() {
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient_update_plugins_filter' ) );
			add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
		}

		/**
		 * Check for Updates at the defined API endpoint and modify the update array.
		 *
		 * This function dives into the update api just when Wordpress creates its update array,
		 * then adds a custom API call and injects the custom plugin data retrieved from the API.
		 * It is reassembled from parts of the native Wordpress plugin update code.
		 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
		 *
		 * @uses api_request()
		 *
		 * @param array $_transient_data Update array build by Wordpress.
		 * @return array Modified update array with custom plugin data.
		 */
		function pre_set_site_transient_update_plugins_filter( $_transient_data ) {


			if( empty( $_transient_data ) ) return $_transient_data;

			$to_send = array( 'slug' => $this->slug );

			$api_response = $this->api_request( 'plugin_latest_version', $to_send );

			if( false !== $api_response && is_object( $api_response ) ) {
				if( version_compare( $this->version, $api_response->new_version, '<' ) )
					$_transient_data->response[$this->name] = $api_response;
		}
			return $_transient_data;
		}


		/**
		 * Updates information on the "View version x.x details" page with custom data.
		 *
		 * @uses api_request()
		 *
		 * @param mixed $_data
		 * @param string $_action
		 * @param object $_args
		 * @return object $_data
		 */
		function plugins_api_filter( $_data, $_action = '', $_args = null ) {
			if ( ( $_action != 'plugin_information' ) || !isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) return $_data;

			$to_send = array( 'slug' => $this->slug );

			$api_response = $this->api_request( 'plugin_information', $to_send );
			if ( false !== $api_response ) $_data = $api_response;

			return $_data;
		}

		/**
		 * Calls the API and, if successfull, returns the object delivered by the API.
		 *
		 * @uses get_bloginfo()
		 * @uses wp_remote_post()
		 * @uses is_wp_error()
		 *
		 * @param string $_action The requested action.
		 * @param array $_data Parameters for the API action.
		 * @return false||object
		 */
		private function api_request( $_action, $_data ) {

			global $wp_version;

			$data = array_merge( $this->api_data, $_data );
			if( $data['slug'] != $this->slug )
				return;

			$api_params = array(
				'edd_action' 	=> 'get_version',
				'license' 		=> $data['license'],
				'name' 			=> $data['item_name'],
				'slug' 			=> $this->slug,
				'author'		=> $data['author']
			);
			$request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( !is_wp_error( $request ) ):
				$request = json_decode( wp_remote_retrieve_body( $request ) );
				if( $request )
					$request->sections = maybe_unserialize( $request->sections );
				return $request;
			else:
				return false;
			endif;
		}
	}
}