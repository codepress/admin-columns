<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AC_Helper
 *
 * Implements __call to work around any keyword restrictions for PHP versions > 7
 *
 * @property AC_Helper_Array array
 * @property AC_Helper_Date date
 * @property AC_Helper_Image image
 * @property AC_Helper_Post post
 * @property AC_Helper_String string
 * @property AC_Helper_Taxonomy taxonomy
 * @property AC_Helper_User user
 * @property AC_Helper_Icon icon
 * @property AC_Helper_FormField formfield
 */
class AC_Layouts {

	CONST LAYOUT_KEY = 'cpac_layouts';

	/**
	 * @var string $storage_model
	 */
	private $storage_model_key;


	public function __construct( $storage_model_key ) {
		$this->storage_model_key = $storage_model_key;
	}

	private function get_layout_key( $layout_id = '' ) {
		return self::LAYOUT_KEY . $this->storage_model_key . $layout_id;
	}

	/**
	 * @return string Layout name
	 */
	public function get_layout_name( $layout_id ) {
		$object = $this->get_layout_by_id( $layout_id );

		return isset( $object->name ) ? $object->name : false;
	}


	// TODO: refactor
	/*public function init_settings_layout() {

		// try admin preference..
		$layout_id = $this->get_user_layout_preference();

		// ..when not found use the first one
		if ( false === $layout_id ) {
			$layout_id = $this->get_single_layout_id();
		}

		$this->set_layout( $layout_id );
	}*/

	/*public function init_listings_layout() {
		$layout_id = null;

		// User layouts
		if ( $layouts_current_user = $this->get_layouts_for_current_user() ) {
			$layout_preference = $this->get_user_layout_preference();

			$layout_found = false;

			// try user preference..
			foreach ( $layouts_current_user as $_layout ) {
				if ( $_layout->id == $layout_preference ) {
					$layout_id = $_layout->id;
					$layout_found = true;
					break;
				}
			}

			// when no longer available use the first user layout
			if ( ! $layout_found ) {
				$_layouts_current_user = array_values( $layouts_current_user );
				$layout_id = $_layouts_current_user[0]->id;
			}
		}

		// User doesn't have eligible layouts.. but the current (null) layout does exists, then the WP default columns are loaded
		else if ( $this->get_layout_by_id( $layout_id ) ) {
			$layout_id = '_wp_default_'; // _wp_default_ does not exists therefor will load WP default
		}

		$this->set_layout( $layout_id );
	}*/

	/*public function set_single_layout_id() {
		$this->set_layout( $this->get_single_layout_id() );
	}*/


	// TODO: refactor
	/*public function set_user_layout_preference() {
		update_user_meta( get_current_user_id(), $this->get_layout_key(), $this->active_layout );
	}*/

	public function layout_exists( $id ) {
		return $this->get_layout_by_id( $id ) ? true : false;
	}

	/**
	 * @return string Layout ID
	 */
	public function get_single_layout_id() {
		$layouts = array_values( (array) $this->get_layouts() );

		return isset( $layouts[0]->id ) ? $layouts[0]->id : null;
	}

	/**
	 * @return stdClass[] Layout objects
	 */
	/*public function get_layouts() {
		global $wpdb;
		$layouts = array();
		if ( $results = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id DESC", $this->get_layout_key() . '%' ) ) ) {
			foreach ( $results as $result ) {

				// Removes incorrect layouts.
				// For example when a storage model is called "Car" and one called "Carrot", then
				// both layouts from each model are in the DB results.
				if ( strlen( $result->option_name ) !== strlen( $this->get_layout_key() ) + 13 ) {
					continue;
				}

				$layout = (object) maybe_unserialize( $result->option_value );
				$layouts[ $layout->id ] = $layout;
			}
		}

		if ( empty( $layouts ) ) {
			$layouts = array();
		}

		return apply_filters( 'ac/layouts', $layouts, $this->storage_model_key );
	}*/

	/**
	 * @return false|stdClass Layout object
	 */
	/*public function get_layout_by_id( $id ) {
		$layouts = $this->get_layouts();

		return isset( $layouts[ $id ] ) ? $layouts[ $id ] : false;
	}*/

	/**
	 * @return string|false Layout ID
	 */
	/*public function get_user_layout_preference() {
		$id = get_user_meta( get_current_user_id(), $this->get_layout_key(), true );

		return $this->layout_exists( $id ) ? $id : false;
	}*/

	/**
	 * @return stdClass[] Layouts
	 */
	/*public function get_layouts_for_current_user() {
		$user_layouts = array();

		$current_user = get_current_user_id();
		$layouts = $this->get_layouts();
		foreach ( $layouts as $k => $layout ) {

			// Roles
			if ( ! empty( $layout->roles ) ) {
				foreach ( $layout->roles as $role ) {
					if ( current_user_can( $role ) ) {
						$user_layouts[ $k ] = $layout;
					}
				}
			}

			// Users
			if ( ! empty( $layout->users ) ) {
				foreach ( $layout->users as $user ) {
					if ( $current_user == $user ) {
						$user_layouts[ $k ] = $layout;
					}
				}
			}

			// Both
			if ( empty( $layout->roles ) && empty( $layout->users ) ) {
				$user_layouts[ $k ] = $layout;
			}
		}

		return $user_layouts;
	}*/

	/**
	 * @return array Layout default arguments
	 */
	public function get_default_layout_args( $args = array() ) {
		$default = array(
			'id'    => null,
			'name'  => __( 'Default' ),
			'roles' => '',
			'users' => '',
		);

		return array_merge( $default, $args );
	}

	/**
	 * @return stdClass|WP_Error Layout object
	 */
	public function save_layout( $id, $args ) {

		if ( empty( $args['name'] ) ) {
			return new WP_Error( 'empty-name' );
		}

		update_option( $this->get_layout_key( $id ), (object) array(
			'id'    => $id ? $id : null,
			'name'  => trim( $args['name'] ),
			'roles' => isset( $args['roles'] ) ? $args['roles'] : '',
			'users' => isset( $args['users'] ) ? $args['users'] : '',
		) );

		return $this->get_layout_by_id( $id );
	}

	/**
	 * @param $args array
	 * @param bool $is_default
	 *
	 * @return null|int
	 */
	public function create_layout( $args, $is_default = false ) {

		// The default layout has an empty ID, that way it stays compatible when layouts is disabled.
		$id = $is_default ? null : uniqid();
		$this->save_layout( $id, $args );

		return $id;
	}

	/**
	 * @return bool True, if layout is successfully deleted. False on failure.
	 */
	public function delete_layout( $id ) {
		return delete_option( $this->get_layout_key( $id ) );
	}

}