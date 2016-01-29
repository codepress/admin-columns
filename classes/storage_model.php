<?php

/**
 * Storage Model
 *
 * @since 2.0
 */
abstract class CPAC_Storage_Model {

	CONST LAYOUT_KEY = 'cpac_layouts';

	/**
	 * @since 2.0
	 */
	public $label;

	/**
	 * @since 2.3.5
	 */
	public $singular_label;

	/**
	 * Identifier for Storage Model; Posttype etc.
	 *
	 * @since 2.0
	 */
	public $key;

	/**
	 * Type of storage model; Post, Media, User or Comments
	 *
	 * @since 2.0
	 */
	public $type;

	/**
	 * Meta type of storage model; post, user, comment. Mostly used for custom field data.
	 *
	 * @since 3.0
	 */
	public $meta_type;

	/**
	 * Groups the storage model in the menu.
	 *
	 * @since 2.0
	 */
	public $menu_type;

	/**
	 * @since 2.4.3
	 */
	private $column_headings;

	/**
	 * @since 2.0
	 * @var string
	 */
	public $page;

	/**
	 * Active profile for presets
	 *
	 * @since NEWVERSION
	 * @var int/string
	 */
	public $layout;

	/**
	 * Uses PHP export to display settings
	 *
	 * @since 2.0
	 * @var string
	 */
	private $php_export = false;

	/**
	 * @since 2.0.1
	 * @var array
	 */
	protected $columns_filepath;

	/**
	 * @since 2.0.1
	 * @var array
	 */
	public $columns = array();

	/**
	 * @since 2.1.0
	 * @var array
	 */
	public $custom_columns = array();

	/**
	 * @since 2.1.0
	 * @var array
	 */
	public $default_columns = array();

	/**
	 * @since 2.4.9
	 * @var array
	 */
	private $default_wp_columns = array();

	/**
	 * @since 2.2
	 * @var array
	 */
	public $stored_columns = null;

	/**
	 * @since NEWVERSION
	 * @var array
	 */
	public $stored_layouts = null;

	/**
	 * @since 2.2
	 * @var array
	 */
	public $column_types = array();

	/**
	 * @since 2.4.4
	 */
	abstract function get_default_column_names();

	/**
	 * @since 2.0
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();

	/**
	 * @since 2.2
	 */
	function __construct() {

		$this->set_columns_filepath();
	}

	/**
	 * Set menutype
	 *
	 * @since 2.4.1
	 */
	public function set_menu_type( $menu_type ) {
		$this->menu_type = $menu_type;

		return $this;
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since 1.0
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function is_menu_type_current( $first_posttype ) {

		// display the page that was being viewed before saving
		if ( ! empty( $_REQUEST['cpac_key'] ) ) {
			if ( $_REQUEST['cpac_key'] == $this->key ) {
				return true;
			}
		} // settings page has not yet been saved
		elseif ( $first_posttype == $this->key ) {
			return true;
		}

		return false;
	}

	/**
	 * @since 2.4.7
	 */
	public function format_meta_keys( $keys ) {
		$add_hidden_meta = true; // always true @todo

		$formatted_keys = array();
		foreach ( $keys as $key ) {

			// give hidden keys a prefix for identifaction
			if ( $add_hidden_meta && "_" == substr( $key[0], 0, 1 ) ) {
				$formatted_keys[] = 'cpachidden' . $key[0];
			} // non hidden keys are saved as is
			elseif ( "_" != substr( $key[0], 0, 1 ) ) {
				$formatted_keys[] = $key[0];
			}
		}

		return $formatted_keys;
	}

	/**
	 * @since 2.0
	 * @return array
	 */
	public function get_meta_keys() {

		if ( $cache = wp_cache_get( $this->key, 'cac_columns' ) ) {
			$keys = $cache;
		} else {
			$keys = $this->get_meta();
			wp_cache_add( $this->key, $keys, 'cac_columns', 10 ); // 10 sec.
		}

		if ( is_wp_error( $keys ) || empty( $keys ) ) {
			$keys = false;
		} else {
			$keys = $this->format_meta_keys( $keys );
		}

		/**
		 * Filter the available custom field meta keys
		 * If showing hidden fields is enabled, they are prefixed with "cpachidden" in the list
		 *
		 * @since 2.0
		 *
		 * @param array $keys Available custom field keys
		 * @param CPAC_Storage_Model $storage_model Storage model class instance
		 */
		$keys = apply_filters( 'cac/storage_model/meta_keys', $keys, $this );

		/**
		 * Filter the available custom field meta keys for this storage model type
		 *
		 * @since 2.0
		 * @see Filter cac/storage_model/meta_keys
		 */
		return apply_filters( "cac/storage_model/meta_keys/storage_key={$this->key}", $keys, $this );
	}

	/**
	 * @since 2.0
	 *
	 * @param array $fields Custom fields.
	 *
	 * @return array Custom fields.
	 */
	protected function add_hidden_meta( $fields ) {
		if ( ! $fields ) {
			return false;
		}

		$combined_fields = array();

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identifaction
			if ( "_" == substr( $field[0], 0, 1 ) ) {
				$combined_fields[] = 'cpachidden' . $field[0];
			} // non hidden fields are saved as is
			elseif ( "_" != substr( $field[0], 0, 1 ) ) {
				$combined_fields[] = $field[0];
			}
		}

		if ( empty( $combined_fields ) ) {
			return false;
		}

		return $combined_fields;
	}

	/**
	 * Layouts
	 *
	 * @since NEWVERSION
	 */
	public function get_layout() {
		return $this->layout;
	}

	public function get_layout_object() {
		return $this->get_layout_by_id( $this->layout );
	}

	public function set_layout( $layout ) {
		$this->layout = $layout;
	}

	// Set the layout ID. First tries user preference, if not found, then first one served.
	public function init_layout() {
		$layout_id = $this->get_user_listings_layout();
		if ( ! $this->layout_exists( $layout_id ) ) {
			$layout_id = $this->get_single_layout_id();
		}
		$this->set_layout( $layout_id );
	}

	public function set_single_layout_id() {
		$this->set_layout( $this->get_single_layout_id() );
	}

	public function layout_exists( $id ) {
		return $this->get_layout_by_id( $id ) ? true : false;
	}

	public function get_single_layout_id() {
		$layouts = $this->get_layouts();

		return $layouts ? reset( $layouts )->id : null;
	}

	public function set_stored_layout( $layout ) {
		$this->stored_layouts[] = (object) $layout;
	}

	public function get_layouts() {
		if ( $this->is_using_php_export() ) {
			$layouts = $this->stored_layouts;
		} else {
			$layouts = get_option( $this->get_layout_key(), array() );
		}
		return (array) $layouts;
	}

	public function get_layout_by_id( $id ) {
		if ( $layouts = $this->get_layouts() ) {
			foreach ( $layouts as $layout ) {
				if ( $layout->id == $id ) {
					return $layout;
				}
			}
		}

		return false;
	}

	public function get_delete_layout_link( $layout_id ) {
		return add_query_arg( array( 'layout_id' => $layout_id, 'cpac_action' => 'delete_layout', '_cpac_nonce' => wp_create_nonce( 'delete-layout' ) ), $this->settings_url() );
	}

	private function get_layout_key() {
		return self::LAYOUT_KEY . $this->key;
	}

	public function set_user_listings_layout( $layout_id ) {
		update_user_meta( get_current_user_id(), $this->get_layout_key(), $layout_id );
	}

	public function get_user_listings_layout() {
		$layout_id = get_user_meta( get_current_user_id(), $this->get_layout_key(), true );

		return $this->get_layout_by_id( $layout_id ) ? $layout_id : null;
	}

	public function get_layouts_for_current_user() {
		$user_layouts = array();

		$current_user = get_current_user_id();
		$layouts = array_reverse( $this->get_layouts() );
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
					if ( $current_user == $user  ) {
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
	}

	public function update_layout( $args ) {
		$layouts = $this->get_layouts();

		if ( $layouts ) {
			foreach ( $layouts as $k => $layout ) {
				if ( $layout->id == $this->layout ) {

					if ( isset( $args['roles'] ) ) {
						$layout->roles = $args['roles'];
					}
					if ( isset( $args['users'] ) ) {
						$layout->users = $args['users'];
					}
					if ( ! empty( $args['name'] ) ) { // can not be empty
						$layout->name = $args['name'];
					}

					$layouts[ $k ] = $layout;
				}
			}
		}

		update_option( $this->get_layout_key(), array_filter( $layouts ) );
	}

	public function create_layout( $args, $is_default = false ) {

		if ( empty( $args['name'] ) ) {
			return new WP_Error( 'missing-name', __( 'Layout name can not be empty.', 'codepress-admin-columns' ) );
		}

		$layouts = $this->get_layouts();

		if ( ! isset( $args['roles'] ) ) {
			$args['roles'] = array();
		}
		if ( ! isset( $args['users'] ) ) {
			$args['users'] = array();
		}

		// The default layout has an empty ID, that way it stays compatible when layouts is disabled.
		$layout_id = null;

		// new ID is highest layout ID + 1
		if ( ! $is_default ) {
			$ids = wp_list_pluck( (array) $layouts, 'id' );
			$layout_id = $ids ? intval( max( $ids ) ) + 1 : 1;
		}

		$args['id'] = $layout_id;

		$layouts[] = (object) $args;

		update_option( $this->get_layout_key(), array_filter( $layouts ) );

		return $layout_id;
	}

	public function delete_layouts() {
		delete_option( $this->get_layout_key() );
	}

	public function delete_layout( $id ) {
		$deleted = false;
		if ( $layouts = $this->get_layouts() ) {
			foreach ( $layouts as $k => $layout ) {
				if ( $layout->id == $id ) {
					unset( $layouts[ $k ] );
					$deleted = $layout;
				}
			}
		}
		if ( $deleted ) {
			$this->set_layout( $id );
			$this->restore();

			update_option( $this->get_layout_key(), $layouts );
		}

		return $deleted;
	}

	/**
	 * Get store ID
	 * @since NEWVERSION
	 */
	private function get_storage_id() {
		$layout = $this->layout ? $this->layout : '';

		return "cpac_options_" . $this->key . $layout;
	}

	/**
	 * @since 2.0
	 */
	public function restore() {
		delete_option( $this->get_storage_id() );
	}

	/**
	 * @since 2.0
	 */
	public function store( $columns = '', $message = true ) {

		if ( ! empty( $_POST[ $this->key ] ) ) {
			$columns = array_filter( $_POST[ $this->key ] );
		}

		if ( ! $columns ) {
			if ( $message ) {
				cpac_admin_message( __( 'No columns settings available.', 'codepress-admin-columns' ), 'error' );
			}

			return false;
		}

		// sanitize user inputs
		foreach ( $columns as $name => $options ) {
			if ( $_column = $this->get_column_by_name( $name ) ) {
				$columns[ $name ] = $_column->sanitize_storage( $options );
			}

			// Santize Label: Need to replace the url for images etc, so we do not have url problem on exports
			// this can not be done by CPAC_Column::sanitize_storage() because 3rd party plugins are not available there
			$columns[ $name ]['label'] = stripslashes( str_replace( site_url(), '[cpac_site_url]', trim( $columns[ $name ]['label'] ) ) );
		}

		// store columns
		$result = update_option( $this->get_storage_id(), $columns );
		$result_default = update_option( $this->get_storage_id() . "_default", array_keys( $this->get_default_columns() ) );

		// error
		if ( ! $result && ! $result_default ) {
			if ( $message ) {
				cpac_admin_message( sprintf( __( 'You are trying to store the same settings for %s.', 'cpac' ), "<strong>{$this->label}</strong>" ), 'error' );
			}

			return false;
		}

		if ( $message ) {
			cpac_admin_message( sprintf( __( 'Settings for %s updated successfully.', 'cpac' ), "<strong>{$this->label}</strong>" ), 'updated' );
		}

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 *
		 * @since 2.2.9
		 *
		 * @param array $columns List of columns ([columnid] => (array) [column properties])
		 * @param CPAC_Storage_Model $storage_model_instance Storage model instance
		 */
		do_action( 'cac/storage_model/columns_stored', $columns, $this );

		return true;
	}

	/**
	 * Goes through all files in 'classes/column' and includes each file.
	 *
	 * @since 2.0.1
	 * @return array Column Classnames | Filepaths
	 */
	public function set_columns_filepath() {

		$columns = array(
			'CPAC_Column_Custom_Field' => CPAC_DIR . 'classes/column/custom-field.php',
			'CPAC_Column_Taxonomy'     => CPAC_DIR . 'classes/column/taxonomy.php',
			'CPAC_Column_Used_By_Menu' => CPAC_DIR . 'classes/column/used-by-menu.php'
		);

		// Add-on placeholders
		if ( ! cpac_is_pro_active() ) {

			// Display ACF placeholder
			if ( cpac_is_acf_active() ) {
				$columns['CPAC_Column_ACF_Placeholder'] = CPAC_DIR . 'classes/column/acf-placeholder.php';
			}

			// Display WooCommerce placeholder
			if ( cpac_is_woocommerce_active() ) {
				$columns['CPAC_Column_WC_Placeholder'] = CPAC_DIR . 'classes/column/wc-placeholder.php';
			}
		}

		// Directory to iterate
		$columns_dir = CPAC_DIR . 'classes/column/' . $this->type;
		if ( is_dir( $columns_dir ) ) {
			$iterator = new DirectoryIterator( $columns_dir );
			foreach ( $iterator as $leaf ) {

				if ( $leaf->isDot() || $leaf->isDir() ) {
					continue;
				}

				// only allow php files, exclude .SVN .DS_STORE and such
				if ( substr( $leaf->getFilename(), - 4 ) !== '.php' ) {
					continue;
				}

				// build classname from filename
				$class_name = 'CPAC_Column_' . ucfirst( $this->type ) . '_' . implode( '_', array_map( 'ucfirst', explode( '-', basename( $leaf->getFilename(), '.php' ) ) ) );

				// classname | filepath
				$columns[ $class_name ] = $leaf->getPathname();
			}
		}

		/**
		 * Filter the available custom column types
		 * Use this to register a custom column type
		 *
		 * @since 2.0
		 *
		 * @param array $columns Available custom columns ([class_name] => [class file path])
		 * @param CPAC_Storage_Model $storage_model Storage model class instance
		 */
		$columns = apply_filters( 'cac/columns/custom', $columns, $this );

		/**
		 * Filter the available custom column types for a specific type
		 *
		 * @since 2.0
		 * @see Filter cac/columns/custom
		 */
		$columns = apply_filters( 'cac/columns/custom/type=' . $this->type, $columns, $this );

		/**
		 * Filter the available custom column types for a specific type
		 *
		 * @since 2.0
		 * @see Filter cac/columns/custom
		 */
		$columns = apply_filters( 'cac/columns/custom/post_type=' . $this->key, $columns, $this );

		$this->columns_filepath = $columns;
	}

	/**
	 * @since 2.0
	 *
	 * @param $column_name
	 * @param $label
	 *
	 * @return object CPAC_Column
	 */
	public function create_column_instance( $column_name, $label ) {

		// create column instance
		$column = new CPAC_Column( $this );

		$column
			->set_properties( 'type', $column_name )
			->set_properties( 'name', $column_name )
			->set_properties( 'label', $label )
			->set_properties( 'is_cloneable', false )
			->set_properties( 'default', true )
			->set_properties( 'group', 'plugin' )
			->set_options( 'label', $label )
			->set_options( 'state', 'on' );

		// Hide Label when it contains HTML elements
		if ( strlen( $label ) != strlen( strip_tags( $label ) ) ) {
			$column->set_properties( 'hide_label', true );
		}

		// Label empty? Use it's column_name
		if ( ! $label ) {
			$column->set_properties( 'label', ucfirst( $column_name ) );
		}

		/**
		 * Filter the default column names
		 *
		 * @since 2.4.4
		 *
		 * @param array $default_column_names Default column names
		 * @param object $column Column object
		 * @param object $this Storage_Model object
		 */
		$default_column_names = apply_filters( 'cac/columns/defaults', $this->get_default_column_names(), $column, $this );
		$default_column_names = apply_filters( 'cac/columns/defaults/type=' . $this->get_type(), $default_column_names, $column, $this );
		$default_column_names = apply_filters( 'cac/columns/defaults/post_type=' . $this->get_post_type(), $default_column_names, $column, $this );

		// set group for WP Default
		if ( $default_column_names && in_array( $column_name, $default_column_names ) ) {
			$column->set_properties( 'group', 'default' );
		}

		return $column;
	}

	/**
	 * @since 2.0
	 * @return array Column Type | Column Instance
	 */
	private function get_default_registered_columns() {

		$columns = array();
		foreach ( $this->default_wp_columns as $column_name => $label ) {

			// checkboxes are mandatory
			if ( 'cb' == $column_name ) {
				continue;
			}
			$column = $this->create_column_instance( $column_name, $label );
			$columns[ $column->properties->name ] = $column;
		}

		do_action( "cac/columns/registered/default", $columns, $this );
		do_action( "cac/columns/registered/default/storage_key={$this->key}", $columns, $this );

		return $columns;
	}

	/**
	 * @since 2.0
	 * @return array Column Type | Column Instance
	 */
	public function get_custom_registered_columns() {

		$columns = array();

		foreach ( $this->columns_filepath as $classname => $path ) {
			include_once $path;

			if ( ! class_exists( $classname ) ) {
				continue;
			}

			$column = new $classname( $this );

			// exclude columns that are not registered based on conditional logic within the child column
			if ( ! $column->properties->is_registered ) {
				continue;
			}

			$columns[ $column->properties->type ] = $column;
		}

		do_action( "cac/columns/registered/custom", $columns, $this );
		do_action( "cac/columns/registered/custom/storage_key={$this->key}", $columns, $this );

		return $columns;
	}

	/**
	 * @since 1.0
	 *
	 * @param string $key
	 *
	 * @return array Column options
	 */
	public function get_default_stored_columns() {

		if ( ! $columns = get_option( $this->get_storage_id() . "_default" ) ) {
			return array();
		}

		return $columns;
	}

	/**
	 * @since 1.0
	 * @return array Column options
	 */
	public function get_stored_columns() {

		if ( $this->is_using_php_export() ) {
			$columns = $this->stored_columns;
		} else {
			$columns = $this->get_database_columns();
		}

		$columns = apply_filters( 'cpac/storage_model/stored_columns', $columns, $this );
		$columns = apply_filters( 'cpac/storage_model/stored_columns/storage_key=' . $this->key, $columns, $this );

		if ( ! $columns ) {
			return array();
		}

		return $columns;
	}

	public function get_database_columns() {
		return get_option( $this->get_storage_id() );
	}

	/**
	 * Set stopred column by 3rd party plugins
	 *
	 * @since 2.3
	 */
	public function set_stored_columns( $columns ) {
		$this->stored_columns = $columns;
	}

	/**
	 * Are column set by third party plugin
	 *
	 * @since 2.3.4
	 */
	public function is_using_php_export() {
		return $this->php_export;
	}

	/**
	 * @since NEWVERSION
	 */
	public function enable_php_export() {
		$this->php_export = true;
	}

	/**
	 * @since 2.1.1
	 */
	public function get_post_type() {
		return isset( $this->post_type ) ? $this->post_type : false;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @since 2.0.2
	 */
	public function set_columns() {

		do_action( 'cac/set_columns', $this );

		$this->custom_columns = $this->get_custom_registered_columns();
		$this->default_wp_columns = $this->get_default_columns();
		$this->default_columns = $this->get_default_registered_columns();
		$this->column_types = $this->get_grouped_column_types();
		$this->columns = $this->get_columns();

		do_action( 'cac/set_columns/after', $this );
	}

	public function get_grouped_column_types() {

		$types = array();
		$groups = array_keys( $this->get_column_type_groups() );

		$columns = array_merge( $this->default_columns, $this->custom_columns );

		foreach ( $groups as $group ) {
			$grouptypes = array();

			foreach ( $columns as $index => $column ) {
				if ( $column->properties->group == $group ) {
					$grouptypes[ $index ] = $column;
					unset( $columns[ $index ] );
				}
			}

			$types[ $group ] = $grouptypes;
		}

		return $types;
	}

	public function get_column_type_groups() {

		$groups = array(
			'default'      => __( 'Default', 'codepress-admin-columns' ),
			'custom-field' => __( 'Custom Field', 'codepress-admin-columns' ),
			'custom'       => __( 'Custom', 'codepress-admin-columns' ),
			'plugin'       => __( 'Columns by Plugins', 'codepress-admin-columns' ),
			'acf'          => __( 'Advanced Custom Fields', 'codepress-admin-columns' ),
			'woocommerce'  => __( 'WooCommerce', 'codepress-admin-columns' )
		);

		/**
		 * Filter the available column type groups
		 *
		 * @since 2.2
		 *
		 * @param array $groups Available groups ([groupid] => [label])
		 * @param CPAC_Storage_Model $storage_model_instance Storage model class instance
		 */
		$groups = apply_filters( "cac/storage_model/column_type_groups", $groups, $this );
		$groups = apply_filters( "cac/storage_model/column_type_groups/storage_key={$this->key}", $groups, $this );

		return $groups;
	}

	/**
	 * @since 2.0.2
	 */
	public function get_registered_columns() {
		$types = array();
		foreach ( $this->column_types as $grouptypes ) {
			$types = array_merge( $types, $grouptypes );
		}

		return $types;
	}

	/**
	 * @since 2.3.4
	 *
	 * @param string Column Type
	 */
	public function get_registered_column( $column_type ) {
		$columns = $this->get_registered_columns();

		return isset( $columns[ $column_type ] ) ? $columns[ $column_type ] : false;
	}

	/**
	 * @since 2.0
	 */
	public function get_columns() {

		do_action( 'cac/get_columns', $this );

		$columns = array();

		if ( ! $this->default_wp_columns ) {
			$this->default_wp_columns = $this->get_default_columns();
		}
		$registered_columns = $this->get_registered_columns();

		if ( $stored_columns = $this->get_stored_columns() ) {

			foreach ( $stored_columns as $name => $options ) {
				if ( ! isset( $options['type'] ) ) {
					continue;
				}

				// In case of a disabled plugin, we will skip column.
				// This means the stored column type is not available anymore.
				if ( ! in_array( $options['type'], array_keys( $registered_columns ) ) ) {
					continue;
				}

				// add an clone number which defines the instance
				$column = clone $registered_columns[ $options['type'] ];
				$column->set_clone( $options['clone'] );

				// merge default options with stored
				$column->options = (object) array_merge( (array) $column->options, $options );

				$column->sanitize_label();

				$columns[ $name ] = $column;
			}

			// In case of an enabled plugin, we will add that column.
			// When $diff contains items, it means a default column has not been stored.
			if ( $diff = array_diff( array_keys( $this->default_wp_columns ), $this->get_default_stored_columns() ) ) {
				foreach ( $diff as $name ) {
					// because of the filter "manage_{$post_type}_posts_columns" the columns
					// that are being added by CPAC will also appear in the $this->default_wp_columns.
					// this will filter out those columns.
					if ( isset( $columns[ $name ] ) ) {
						continue;
					}

					// is the column registered?
					if ( ! isset( $registered_columns[ $name ] ) ) {
						continue;
					}

					$columns[ $name ] = clone $registered_columns[ $name ];
				}
			}
		} // When nothing has been saved yet, we return the default WP columns.
		else {
			foreach ( array_keys( $this->default_wp_columns ) as $name ) {
				if ( isset( $registered_columns[ $name ] ) ) {
					$columns[ $name ] = clone $registered_columns[ $name ];
				}
			}

			/**
			 * Filter the columns that should be loaded if there were no stored columns
			 *
			 * @since 2.2.4
			 *
			 * @param array $columns List of columns ([column name] => [column instance])
			 * @param CPAC_Storage_Model $storage_model_instance Storage model class instance
			 */
			$columns = apply_filters( 'cpac/storage_model/columns_default', $columns, $this );
		}

		do_action( "cac/columns", $columns );
		do_action( "cac/columns/storage_key={$this->key}", $columns );

		return $columns;
	}

	/**
	 * @since 2.0
	 */
	public function get_column_by_name( $name ) {
		return isset( $this->columns[ $name ] ) ? $this->columns[ $name ] : false;
	}

	/**
	 * @since 2.0
	 */
	public function add_headings( $columns ) {

		// make sure we run this only once
		if ( $this->column_headings ) {
			return $this->column_headings;
		}

		if ( ! $this->default_columns ) {
			return $columns;
		}

		if ( ! ( $stored_columns = $this->get_stored_columns() ) ) {
			return $columns;
		}

		$this->column_headings = array();

		// add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->column_headings['cb'] = $columns['cb'];
		}

		// add active stored headings
		foreach ( $stored_columns as $column_name => $options ) {

			// Label needs stripslashes() for HTML tagged labels, like icons and checkboxes
			$label = stripslashes( $options['label'] );

			/**
			 * Filter the stored column headers label for use in a WP_List_Table
			 * Label needs stripslashes() for HTML tagged labels, like icons and checkboxes
			 *
			 * @since 2.0
			 *
			 * @param string $label Label
			 * @param string $column_name Column name
			 * @param array $options Column options
			 * @param CPAC_Storage_Model $storage_model Storage model class instance
			 */
			$label = apply_filters( 'cac/headings/label', $label, $column_name, $options, $this );
			$label = str_replace( '[cpac_site_url]', site_url(), $label );

			$this->column_headings[ $column_name ] = $label;
		}

		// Add 3rd party columns that have ( or could ) not been stored.
		// For example when a plugin has been activated after storing column settings.
		// When $diff contains items, it means an available column has not been stored.
		if ( ! $this->is_using_php_export() && ( $diff = array_diff( array_keys( $columns ), $this->get_default_stored_columns() ) ) ) {
			foreach ( $diff as $column_name ) {
				$this->column_headings[ $column_name ] = $columns[ $column_name ];
			}
		}

		return $this->column_headings;
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	protected function get_screen_link() {

		return is_network_admin() ? network_admin_url( $this->page . '.php' ) : admin_url( $this->page . '.php' );
	}

	/**
	 * @since 2.0
	 */
	public function screen_link() {

		if ( $link = $this->get_screen_link() ) {
			echo '<a href="' . $link . '" class="add-new-h2">' . __( 'View', 'codepress-admin-columns' ) . '</a>';
		}
	}

	public function settings_url() {
		$args = array(
			'page'     => 'codepress-admin-columns',
			'cpac_key' => $this->key,
		);
		if ( $this->layout ) {
			$args['layout_id'] = $this->layout;
		}

		return add_query_arg( $args, admin_url( 'options-general.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link( $layout_id = '' ) {
		$args = array();
		if ( '' !== $layout_id ) {
			$args = array( 'layout_id' => $layout_id );
		}
		return add_query_arg( $args, $this->settings_url() );
	}

	/**
	 * @since 2.0
	 */
	public function get_restore_link() {
		$args = array(
			'_cpac_nonce' => wp_create_nonce( 'restore-type' ),
			'cpac_action' => 'restore_by_type'
		);

		return add_query_arg( $args, $this->settings_url() );
	}


	/**
	 * @deprecated deprecated since version 2.4.9
	 */
	public function is_columns_screen(){
		_deprecated_function( 'is_columns_screen', '2.4.9', 'is_current_screen' );
		return $this->is_current_screen();
	}

	/**
	 * @since 2.0.3
	 * @global string $pagenow
	 * @global object $current_screen
	 * @return boolean
	 */
	public function is_current_screen() {

		global $pagenow;

		if ( $this->page . '.php' != $pagenow ) {
			return false;
		}

		// posttypes
		if ( 'post' == $this->type ) {
			$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : $this->type;

			if ( $this->key != $post_type ) {
				return false;
			}
		}

		// taxonomy
		if ( 'taxonomy' == $this->type ) {
			$taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';

			if ( $this->taxonomy != $taxonomy ) {
				return false;
			}
		}

		// users
		if ( 'wp-users' == $this->key && is_network_admin() ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 2.3.2
	 */
	public function delete_general_option() {
		delete_option( 'cpac_general_options' );
	}

	/**
	 * @since 2.1.1
	 */
	public function get_general_option( $option ) {
		$options = get_option( 'cpac_general_options' );

		if ( ! isset( $options[ $option ] ) ) {
			return false;
		}

		return $options[ $option ];
	}
}