<?php

/**
 * Storage Model
 *
 * @since 2.0
 */
abstract class CPAC_Storage_Model {

	CONST OPTIONS_KEY = 'cpac_options';

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
	 * @since 2.4.10
	 * @var string
	 */
	public $subpage;

	/**
	 * @since 2.5
	 * @var string
	 */
	public $screen;

	/**
	 * Active layout for presets
	 *
	 * @since 2.5
	 * @var string
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
	private $columns = array();

	/**
	 * @since 2.2
	 * @var array
	 */
	private $column_types = array();

	/**
	 * @since 2.5
	 * @var array
	 */
	private $stored_columns = array();

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
	 * Get the default column widths in percentages
	 *
	 * @since 2.5
	 */
	protected function get_default_column_widths() {
	}

	/**
	 * @since 2.5
	 */
	public function get_grouped_columns() {
		$grouped = array();
		foreach ( $this->column_types as $type => $column ) {

			// Labels with html will be replaced by the it's name.
			$grouped[ $column->properties->group ][ $type ] = ( 0 === strlen( strip_tags( $column->properties->label ) ) ) ? ucfirst( $column->properties->name ) : ucfirst( $column->properties->label );
			asort( $grouped[ $column->properties->group ] );
		}

		krsort( $grouped );

		return apply_filters( 'cac/grouped_columns', $grouped, $this );
	}

	public function get_screen_id() {
		return $this->screen ? $this->screen : $this->page;
	}

	/**
	 * @since 2.5
	 */
	public function get_column_types() {

		if ( empty( $this->column_types ) ) {

			$column_types = array();

			// Get default column that have been set on the listings screen
			$default_columns = $this->get_default_stored_columns();

			// As a fallback we can use the table headings. this is not reliable, because most 3rd party column will not be loaded at this point.
			if ( empty( $default_columns ) ) {
				$default_columns = $this->get_default_column_headings();
			}

			// Default columns
			if ( $default_columns ) {

				// Remove checkbox
				if ( isset( $default_columns['cb'] ) ) {
					unset( $default_columns['cb'] );
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
				$default_column_names = apply_filters( 'cac/default_column_names', $this->get_default_column_names(), $this );
				$default_column_widths = apply_filters( 'cac/default_column_widths', $this->get_default_column_widths(), $this );

				foreach ( $default_columns as $name => $label ) {
					$column = $this->create_column_instance( $name, $label );

					// If it's not a default column it probably is set by a plugin
					if ( $default_column_names && ! in_array( $name, $default_column_names ) ) {
						$column->set_properties( 'group', __( 'Columns by Plugins', 'codepress-admin-columns' ) );
					}

					// Set the default percentage
					if ( $default_column_widths && isset( $default_column_widths[ $name ] ) ) {
						$column->set_options( 'width', $default_column_widths[ $name ]['width'] );

						if ( isset( $default_column_widths[ $name ]['unit'] ) ) {
							$column->set_options( 'width_unit', $default_column_widths[ $name ]['unit'] );
						}
					}

					$column_types[ $name ] = $column;
				}
			}

			// Custom columns
			foreach ( $this->columns_filepath as $classname => $path ) {
				include_once $path;
				if ( class_exists( $classname, false ) ) {
					$column = new $classname( $this->key );

					if ( $column->is_registered() ) {
						$column_types[ $column->get_type() ] = $column;
					}
				}
			}

			$this->column_types = $column_types;

			// @since 2.5
			do_action( "cac/column_types", $this->column_types, $this );
			do_action( "cac/column_types/storage_key={$this->key}", $this->column_types, $this );
		}

		return $this->column_types;
	}

	/**
	 * @since 2.5
	 */
	private function get_default_colummn_types() {
		$defaults = array();

		foreach ( $this->get_column_types() as $type => $column ) {
			if ( $column->is_default() || $column->is_original() ) {
				$defaults[ $type ] = $column;
			}
		}

		return $defaults;
	}

	/**
	 * @since 2.5
	 */
	public function get_column_type( $type ) {
		$column_types = $this->get_column_types();

		return isset( $column_types[ $type ] ) ? $column_types[ $type ] : false;
	}

	/**
	 * @since 2.5
	 */
	public function create_column( $options ) {
		$column_types = $this->get_column_types();

		if ( ! isset( $options['type'] ) || ! isset( $column_types[ $options['type'] ] ) ) {
			return false;
		}

		$column = clone $column_types[ $options['type'] ];

		if ( isset( $options['clone'] ) ) {
			$column->set_clone( $options['clone'] );
		}

		// merge default options with stored
		$column->options = (object) array_merge( (array) $column->options, $options );

		$column->sanitize_label();

		return $column;
	}

	/**
	 * Clears columns variable, which allow it to be repopulated by get_columns().
	 *
	 * @since 2.5
	 */
	public function flush_columns() {
		$this->stored_columns = array();
		$this->columns = array();
	}

	/**
	 * @since 2.5
	 */
	public function get_columns() {

		if ( empty( $this->columns ) ) {

			// Stored columns
			if ( $stored = $this->get_stored_columns() ) {
				foreach ( $stored as $name => $options ) {
					if ( $column = $this->create_column( $options ) ) {
						$this->columns[ $name ] = $column;
					}
				}
			}

			// Nothing stored
			else {
				$this->columns = $this->get_default_colummn_types();
			}

			do_action( "cac/columns", $this->columns, $this );
			do_action( "cac/columns/storage_key={$this->key}", $this->columns, $this );
		}

		return $this->columns;
	}

	/**
	 * initialize callback for managing the headers and values for columns
	 * @since 2.4.10
	 *
	 */
	public function init_manage_columns() {
	}

	/**
	 * @since 2.0.3
	 * @return boolean
	 */
	public function is_current_screen() {
		global $pagenow;

		return $this->page . '.php' === $pagenow && $this->subpage == filter_input( INPUT_GET, 'page' );
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
	 * @since 2.5
	 */
	public function get_menu_type() {
		return empty( $this->menu_type ) || 'other' === $this->menu_type ? __( 'Other', 'codepress-admin-columns' ) : $this->menu_type;
	}

	public function get_truncated_side_label( $main_label = '' ) {
		$sidelabel = $this->label;
		if ( 34 < ( strlen( $this->label ) + ( strlen( $main_label ) * 1.1 ) ) ) {
			$sidelabel = substr( $this->label, 0, 34 - ( strlen( $main_label ) * 1.1 ) ) . '...';
		}

		return $sidelabel;
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
		}
		else {
			$keys = $this->get_meta();
			wp_cache_add( $this->key, $keys, 'cac_columns', 10 ); // 10 sec.
		}

		if ( is_wp_error( $keys ) || empty( $keys ) ) {
			$keys = false;
		}
		else {
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
	 * @since 2.5
	 */
	public function get_layout() {
		return $this->layout;
	}

	public function get_layout_object() {
		return $this->get_layout_by_id( $this->layout );
	}

	public function get_layout_name() {
		$object = $this->get_layout_by_id( $this->layout );

		return isset( $object->name ) ? $object->name : false;
	}

	public function set_layout( $layout_id ) {
		$this->layout = is_scalar( $layout_id ) ? $layout_id : null;
		$this->flush_columns(); // forces $columns and $stored_columns to be repopulated
	}

	public function init_settings_layout() {

		// try admin preference..
		$layout_id = $this->get_user_layout_preference();

		// ..when not found use the first one
		if ( false === $layout_id ) {
			$layout_id = $this->get_single_layout_id();
		}

		$this->set_layout( $layout_id );
	}

	public function init_listings_layout() {
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
	}

	public function set_single_layout_id() {
		$this->set_layout( $this->get_single_layout_id() );
	}

	public function layout_exists( $id ) {
		return $this->get_layout_by_id( $id ) ? true : false;
	}

	public function get_single_layout_id() {
		$layouts = array_values( (array) $this->get_layouts() );

		return isset( $layouts[0]->id ) ? $layouts[0]->id : null;
	}

	public function get_layouts() {
		global $wpdb;
		$layouts = array();
		if ( $results = $wpdb->get_col( $wpdb->prepare( "SELECT option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id DESC", $this->get_layout_key() . '%' ) ) ) {
			foreach ( $results as $result ) {
				$layout = (object) maybe_unserialize( $result );
				$layouts[ $layout->id ] = $layout;
			}
		}

		if ( empty( $layouts ) ) {
			$layouts = array();
		}

		return apply_filters( 'ac/layouts', $layouts, $this );
	}

	public function get_layout_by_id( $id ) {
		$layouts = $this->get_layouts();

		return isset( $layouts[ $id ] ) ? $layouts[ $id ] : false;
	}

	public function get_delete_layout_link( $layout_id ) {
		return add_query_arg( array( 'layout_id' => $layout_id, 'cpac_action' => 'delete_layout', '_cpac_nonce' => wp_create_nonce( 'delete-layout' ) ), $this->settings_url() );
	}

	private function get_layout_key( $layout_id = '' ) {
		return self::LAYOUT_KEY . $this->key . $layout_id;
	}

	public function set_user_layout_preference() {
		update_user_meta( get_current_user_id(), $this->get_layout_key(), $this->layout );
	}

	public function get_user_layout_preference() {
		$id = get_user_meta( get_current_user_id(), $this->get_layout_key(), true );

		return $this->layout_exists( $id ) ? $id : false;
	}

	public function get_layouts_for_current_user() {
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
	}

	public function get_default_layout_args( $args = array() ) {
		$default = array(
			'id'    => null,
			'name'  => __( 'Default' ),
			'roles' => '',
			'users' => '',
		);

		return array_merge( $default, $args );
	}

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

	public function create_layout( $args, $is_default = false ) {

		// The default layout has an empty ID, that way it stays compatible when layouts is disabled.
		$id = $is_default ? null : uniqid();
		$this->save_layout( $id, $args );

		return $id;
	}

	public function delete_layout( $id ) {
		return delete_option( $this->get_layout_key( $id ) );
	}

	/**
	 * Get store ID
	 * @since 2.5
	 */
	private function get_storage_id() {
		$layout = $this->layout ? $this->layout : null;

		return $this->get_storage_key() . $layout;
	}

	/**
	 * @since 2.0
	 */
	public function restore() {
		return delete_option( $this->get_storage_id() );
	}

	/**
	 * @since 2.5
	 */
	public function get_label_or_layout_name() {
		$label = $this->label;

		if ( $name = $this->get_layout_name() ) {
			$label = $name;
		}

		return $label;
	}

	/**
	 * @since 2.0
	 *
	 * @param array $columns
	 * @param array $default_columns Default columns heading names.
	 */
	public function store( $columns ) {

		if ( ! $columns ) {
			return new WP_Error( 'no-settings', __( 'No columns settings available.', 'codepress-admin-columns' ) );
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

		// reset object
		$this->flush_columns();

		if ( ! $result ) {
			return new WP_Error( 'same-settings', sprintf( __( 'You are trying to store the same settings for %s.', 'codepress-admin-columns' ), "<strong>" . $this->get_label_or_layout_name() . "</strong>" ) );
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
	 * @since 2.5
	 */
	public function get_default_column_headings() {
		$default_columns = apply_filters( "cac/default_columns", $this->get_default_columns(), $this );
		$default_columns = apply_filters( "cac/default_columns/type=" . $this->type, $default_columns, $this );
		$default_columns = apply_filters( "cac/default_columns/storage_key=" . $this->key, $default_columns, $this );

		return $default_columns;
	}

	/**
	 * Goes through all files in 'classes/column' and includes each file.
	 *
	 * @since 2.0.1
	 * @return array Column Classnames | Filepaths
	 */
	public function set_columns_filepath() {

		require_once CPAC_DIR . 'classes/column.php';
		require_once CPAC_DIR . 'classes/column/default.php';
		require_once CPAC_DIR . 'classes/column/actions.php';

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
				if ( substr( $leaf->getFilename(), -4 ) !== '.php' ) {
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

		$column = new CPAC_Column( $this->key );

		$column
			->set_properties( 'type', $column_name )
			->set_properties( 'name', $column_name )
			->set_properties( 'label', $label )
			->set_properties( 'is_cloneable', false )
			->set_properties( 'default', true )
			->set_properties( 'group', __( 'Default', 'codepress-admin-columns' ) )
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

		return $column;
	}

	/**
	 * @since 1.0
	 *
	 * @param string $key
	 *
	 * @return array Column options
	 */
	public function get_default_stored_columns() {
		return get_option( $this->get_storage_key() . "__default", array() );
	}

	public function delete_default_stored_columns() {
		delete_option( $this->get_storage_key() . "__default" );
	}

	private function store_default_columns( $columns ) {
		return update_option( $this->get_storage_key() . "__default", $columns );
	}

	private function get_storage_key() {
		return self::OPTIONS_KEY . '_' . $this->key;
	}

	/**
	 * @since 1.0
	 * @return array Column options
	 */
	public function get_stored_columns() {
		if ( empty( $this->stored_columns ) ) {

			$columns = apply_filters( 'cpac/storage_model/stored_columns', $this->get_database_columns(), $this );
			$columns = apply_filters( 'cpac/storage_model/stored_columns/storage_key=' . $this->key, $columns, $this );

			$this->stored_columns = ! empty( $columns ) ? $columns : array();
		}

		return $this->stored_columns;
	}

	public function get_database_columns() {
		return get_option( $this->get_storage_id() );
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
	 * @since 2.4.10
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
	 * @since 2.0
	 */
	public function get_column_by_name( $name ) {
		$columns = $this->get_columns();

		return isset( $columns[ $name ] ) ? $columns[ $name ] : false;
	}

	/**
	 * @since 2.0
	 */
	public function add_headings( $columns ) {

		if ( empty( $columns ) ) {
			return $columns;
		}

		// for the rare case where a screen hasn't been set yet and a
		// plugin uses a custom version of apply_filters( "manage_{$screen->id}_columns", array() )
		if ( ! get_current_screen() && ! cac_wp_is_doing_ajax() ) {
			return $columns;
		}

		// Stores the default columns on the listings screen
		$this->store_default_columns( $columns );

		// make sure we run this only once
		if ( $this->column_headings ) {
			return $this->column_headings;
		}

		$stored_columns = $this->get_stored_columns();

		if ( ! $stored_columns ) {
			return $columns;
		}

		$this->column_headings = array();

		// add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->column_headings['cb'] = $columns['cb'];
		}

		$types = array_keys( $this->get_column_types() );

		// add active stored headings
		foreach ( $stored_columns as $column_name => $options ) {

			// Label needs stripslashes() for HTML tagged labels, like icons and checkboxes
			$label = stripslashes( $options['label'] );

			// Remove 3rd party columns that are no longer available (deactivated or removed from code)
			if ( ! in_array( $options['type'], $types ) ) {
				continue;
			}

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
		if ( ! $this->is_using_php_export() && ( $diff = array_diff( array_keys( $columns ), array_keys( (array) $this->get_default_stored_columns() ) ) ) ) {
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
	 * New public function to get screen link instead of making get_screen_link public. To prevent errors in other plugins
	 * @since 2.5
	 */
	public function get_link() {
		return $this->get_screen_link();
	}

	/**
	 * @since 2.0
	 */
	public function screen_link() {
		if ( $link = $this->get_screen_link() ) {
			echo '<a href="' . $link . '" class="page-title-action view-link">' . __( 'View', 'codepress-admin-columns' ) . '</a>';
		}
	}

	public function settings_url() {
		$args = array(
			'page'     => 'codepress-admin-columns',
			'cpac_key' => $this->key,
		);

		return add_query_arg( $args, admin_url( 'options-general.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return add_query_arg( array( 'layout_id' => $this->layout ? $this->layout : '' ), $this->settings_url() );
	}

	/**
	 * @since 2.5
	 */
	public function get_edit_link_by_layout( $layout_id ) {
		return add_query_arg( array( 'layout_id' => $layout_id ? $layout_id : '' ), $this->settings_url() );
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
	public function is_columns_screen() {
		_deprecated_function( 'is_columns_screen', '2.4.9', 'is_current_screen' );

		return $this->is_current_screen();
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