<?php
defined( 'ABSPATH' ) or die();

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
	 * Identifier for Storage Model; Post type etc.
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
	 * @since NEWVERSION
	 * @var string
	 */
	public $post_type;

	/**
	 * @since NEWVERSION
	 * @var string
	 */
	protected $table_classname;

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
	 * @since NEWVERSION
	 * @var array
	 */
	private $default_columns = null;

	/**
	 * @since NEWVERSION
	 * @var array
	 */
	private $column_classnames = array();

	abstract function init();

	/**
	 * @since 2.4.4
	 */
	function __construct() {
		$this->init();
		$this->set_columns_filepath();
	}

	/**
	 * @since 2.0
	 * @return array Column Name | Column Label
	 */
	public function get_default_columns() {
		if ( ! function_exists( '_get_list_table' ) || ! function_exists( 'get_column_headers' ) ) {
			return array();
		}

		// trigger WP_List_Table::get_columns()
		_get_list_table( $this->table_classname, array( 'screen' => $this->get_screen_id() ) );

		return (array) get_column_headers( $this->get_screen_id() );
	}

	public function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_headings() {
		add_filter( "manage_" . $this->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 ); // Filter is located in get_column_headers()
	}

	/**
	 * @since NEWVERSION
	 * @return mixed
	 */
	protected function get_object_by_id( $id ) {
		return null;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_list_selector() {
		return '#the-list';
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_single_row( $object_id ) {
		ob_start();
		$this->get_list_table()->single_row( $this->get_object_by_id( $object_id ) );

		return ob_get_clean();
	}

	/**
	 * @since 2.5
	 */
	public function get_grouped_columns() {
		$grouped = array();

		foreach ( $this->get_column_types() as $type => $column ) {
			if ( ! isset( $grouped[ $column->get_group() ] ) ) {
				$grouped[ $column->get_group() ]['title'] = $column->get_group();
			}

			// Labels with html will be replaced by the it's name.
			$grouped[ $column->get_group() ]['options'][ $type ] = strip_tags( ( 0 === strlen( strip_tags( $column->get_property( 'label' ) ) ) ) ? ucfirst( $column->get_name() ) : ucfirst( $column->get_property( 'label' ) ) );

			if ( ! $column->is_default() && ! $column->is_original() ) {
				natcasesort( $grouped[ $column->get_group() ]['options'] );
			}
		}

		krsort( $grouped );

		return apply_filters( 'cac/grouped_columns', $grouped, $this );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_screen_id() {
		return $this->screen ? $this->screen : $this->page;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_list_table() {
		return _get_list_table( $this->table_classname, array( 'screen' => $this->get_screen_id() ) );
	}

	/**
	 * @since NEWVERSION
	 */
	private function get_default_headings() {

		if ( null === $this->default_columns ) {

			// Get default column that have been set on the listings screen
			$default_columns = $this->get_default_stored_columns();

			// As a fallback we can use the table headings. this is not reliable, because most 3rd party column will not be loaded at this point.
			if ( empty( $default_columns ) ) {
				$default_columns = apply_filters( "cac/default_columns", $this->get_default_columns(), $this );
				$default_columns = apply_filters( "cac/default_columns/type=" . $this->type, $default_columns, $this );
				$default_columns = apply_filters( "cac/default_columns/storage_key=" . $this->key, $default_columns, $this );
			}

			if ( isset( $default_columns['cb'] ) ) {
				unset( $default_columns['cb'] );
			}

			$this->default_columns = $default_columns;
		}

		return $this->default_columns;
	}

	/**
	 * @since NEWVERSION
	 */
	private function get_column_classnames() {
		if ( empty( $this->column_classnames ) ) {

			foreach ( $this->columns_filepath as $class => $path ) {
				$autoload = true;

				// check for autoload condition
				if ( true !== $path ) {
					$autoload = false;

					if ( is_readable( $path ) ) {
						require_once $path;
					}
				}

				if ( ! class_exists( $class, $autoload ) ) {
					continue;
				}

				/* @var $column CPAC_Column */
				$column = new $class( $this->key );

				if ( $column->apply_conditional() ) {
					$this->column_classnames[ $column->get_type() ] = $class;
				}
			}
		}

		return $this->column_classnames;
	}

	/**
	 * Goes through all files in 'classes/column' and requires each file.
	 *
	 * @since 2.0.1
	 */
	public function set_columns_filepath() {

		$dir = cpac()->get_plugin_dir();

		require_once $dir . 'classes/Column.php';

		// Backwards compatibility
		require_once $dir . 'classes/Deprecated/column-default.php';

		$columns = array(
			'AC_Column_Plugin'  => true,
			'AC_Column_Default' => true,
		);

		// Add-on placeholders
		if ( ! cpac_is_pro_active() ) {

			// Display ACF placeholder
			if ( cpac_is_acf_active() ) {
				$columns['AC_Column_ACFPlaceholder'] = true;
			}

			// Display WooCommerce placeholder
			if ( cpac_is_woocommerce_active() ) {
				$columns['AC_Column_WooCommercePlaceholder'] = true;
			}
		}

		// Directory to iterate
		$columns = $this->add_autoload_columns( $dir . 'classes/Column/' . ucfirst( $this->get_type() ), 'AC_', $columns );

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
		$columns = apply_filters( 'cac/columns/custom/type=' . $this->get_type(), $columns, $this );

		/**
		 * Filter the available custom column types for a specific type
		 *
		 * @since 2.0
		 * @see Filter cac/columns/custom
		 */
		$columns = apply_filters( 'cac/columns/custom/post_type=' . $this->get_key(), $columns, $this );

		$this->columns_filepath = $columns;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $column_type
	 * @param false|int $clone clone ID
	 *
	 * @return CPAC_Column|false Column
	 */
	public function create_column_instance( $column_type, $clone = false ) {

		$classnames = $this->get_column_classnames();

		// Non defined columns will be from a plugin
		$class_type = 'column-plugin';

		// Hooks for other plugins to label their columns as group Default.
		$default_column_names = apply_filters( 'cac/default_column_names', array(), $this );

		// For backwards compatibility
		if ( method_exists( $this, 'get_default_column_names' ) ) {
			$default_column_names = $this->get_default_column_names();
		}
		if ( in_array( $column_type, $default_column_names ) ) {
			$class_type = 'column-default';
		}

		// Custom instance
		if ( isset( $classnames[ $column_type ] ) ) {
			$class_type = $column_type;
		}

		/* @var CPAC_Column $column */
		$column = new $classnames[ $class_type ]( $this->get_key() );

		// Set defaults
		if ( $column->is_original() ) {

			$default_columns = $this->get_default_headings();

			if ( ! isset( $default_columns[ $column_type ] ) ) {
				return false;
			}

			$column->set_defaults( $column_type, $default_columns[ $column_type ] );
		}

		$column->set_clone( $clone );

		// Hook
		do_action( 'ac/column', $column, $this );

		return $column;
	}

	/**
	 * @since 2.5
	 * @return CPAC_Column[] Column Types
	 */
	public function get_column_types() {
		if ( empty( $this->column_types ) ) {

			$class_names = $this->get_column_classnames();

			unset( $class_names['column-plugin'] );
			unset( $class_names['column-default'] );

			$default_types = array_keys( (array) $this->get_default_headings() );
			$custom_types = array_keys( $class_names );

			$column_types = array_merge( $default_types, $custom_types );

			foreach ( $column_types as $type ) {
				if ( $column = $this->create_column_instance( $type ) ) {
					$this->column_types[ $type ] = $column;
				}
			}
		}

		return $this->column_types;
	}

	/**
	 * Clears columns variable, which allow it to be repopulated by get_columns().
	 *
	 * @since 2.5
	 */
	public function flush_columns() {
		$this->stored_columns = array();
		$this->column_types = array();
		$this->columns = array();
	}

	/**
	 * @since NEWVERSION
	 */
	protected function get_display_value_by_column_name( $column_name, $id, $value = false ) {
		$column = $this->get_column_by_name( $column_name );

		return $column && ! $column->is_original() ? $column->get_display_value( $id ) : $value;
	}

	/**
	 * Loads the columns into memory
	 *
	 * @since NEWVERSION
	 * @return void
	 */
	public function load_columns() {
		if ( ! empty( $this->columns ) ) {
			return;
		}

		// Stored columns
		if ( $stored = $this->get_stored_columns() ) {
			foreach ( $stored as $name => $options ) {
				if ( isset( $options['type'] ) && isset( $options['clone'] ) ) {
					if ( $column = $this->create_column_instance( $options['type'], $options['clone'] ) ) {

						// @deprecated since NEWVERSION
						$this->populate_column_options( $column, $options );

						$this->columns[ $name ] = $column;
					}
				}
			}
		}

		// Nothing stored
		else {
			foreach ( $this->get_column_types() as $type => $column ) {
				if ( $column->is_default() || $column->is_original() ) {
					$this->columns[ $type ] = $column;
				}
			}
		}

		// Deprecated since NEWVERSION
		do_action( "cac/columns", $this->columns, $this );
		do_action( "cac/columns/storage_key={$this->key}", $this->columns, $this );

		// Hook
		do_action( "ac/columns", $this );
	}

	/**
	 * @since 2.5
	 * @return CPAC_Column[] List of column instances
	 */
	public function get_columns() {
		$this->load_columns();

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
	 * Set menu type
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
	 * @return array Meta keys
	 */
	protected function get_meta() {
		return array();
	}

	/**
	 * @since 2.0
	 * @return array|false
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
			foreach ( $keys as $k => $key ) {

				// give hidden keys a prefix
				$keys[ $k ] = "_" == substr( $key[0], 0, 1 ) ? 'cpachidden' . $key[0] : $key[0];
			}
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
	 * @return array|false Custom fields.
	 */
	protected function add_hidden_meta( $fields ) {
		if ( ! $fields ) {
			return false;
		}

		$combined_fields = array();

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identification
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
	 * @since 2.5
	 * @return string Layout ID
	 */
	public function get_layout() {
		return $this->layout;
	}

	/**
	 * @return stdClass Layout object
	 */
	public function get_layout_object() {
		return $this->get_layout_by_id( $this->layout );
	}

	/**
	 * @return string Layout name
	 */
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
	public function get_layouts() {
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

		return apply_filters( 'ac/layouts', $layouts, $this );
	}

	/**
	 * @return false|stdClass Layout object
	 */
	public function get_layout_by_id( $id ) {
		$layouts = $this->get_layouts();

		return isset( $layouts[ $id ] ) ? $layouts[ $id ] : false;
	}

	/**
	 * @return string Layout delete link
	 */
	public function get_delete_layout_link( $layout_id ) {
		return add_query_arg( array( 'layout_id' => $layout_id, 'cpac_action' => 'delete_layout', '_cpac_nonce' => wp_create_nonce( 'delete-layout' ) ), $this->settings_url() );
	}

	/**
	 * @return string Layout key
	 */
	private function get_layout_key( $layout_id = '' ) {
		return self::LAYOUT_KEY . $this->key . $layout_id;
	}

	public function set_user_layout_preference() {
		update_user_meta( get_current_user_id(), $this->get_layout_key(), $this->layout );
	}

	/**
	 * @return string|false Layout ID
	 */
	public function get_user_layout_preference() {
		$id = get_user_meta( get_current_user_id(), $this->get_layout_key(), true );

		return $this->layout_exists( $id ) ? $id : false;
	}

	/**
	 * @return stdClass[] Layouts
	 */
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
	 * @return string Layout name or Storage model label
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

				if ( ! empty( $options['label'] ) ) {

					// Label can not contains the character ":"" and "'", because
					// CPAC_Column::get_sanitized_label() will return an empty string
					// and make an exception for site_url()
					// Enable data:image url's
					if ( false === strpos( $options['label'], site_url() ) && false === strpos( $options['label'], 'data:' ) ) {
						$options['label'] = str_replace( ':', '', $options['label'] );
						$options['label'] = str_replace( "'", '', $options['label'] );
					}
				}

				if ( isset( $options['width'] ) ) {
					$options['width'] = is_numeric( $options['width'] ) ? trim( $options['width'] ) : '';
				}

				if ( isset( $options['date_format'] ) ) {
					$options['date_format'] = trim( $options['date_format'] );
				}

				$columns[ $name ] = $_column->sanitize_options( $options );
			}

			// Sanitize Label: Need to replace the url for images etc, so we do not have url problem on exports
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
		 * @param array $columns List of columns ([column id] => (array) [column properties])
		 * @param CPAC_Storage_Model $storage_model_instance Storage model instance
		 */
		do_action( 'cac/storage_model/columns_stored', $columns, $this );

		return true;
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

	public function has_stored_columns() {
		return $this->get_stored_columns() ? true : false;
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
		$layout = $this->get_layout_object();

		return ! empty( $layout->not_editable );
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
	 * @return false|CPAC_Column
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
		if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$this->store_default_columns( $columns );
		}

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

		$this->column_types = null; // flush types, in case a column was deactivated
		$types = array_keys( $this->get_column_types() );

		// add active stored headings
		foreach ( $stored_columns as $column_name => $options ) {

			// Strip slashes for HTML tagged labels, like icons and checkboxes
			$label = stripslashes( $options['label'] );

			// Remove 3rd party columns that are no longer available (deactivated or removed from code)
			if ( ! in_array( $options['type'], $types ) ) {
				continue;
			}

			/**
			 * Filter the stored column headers label for use in a WP_List_Table
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
			echo '<a href="' . esc_attr( $link ) . '" class="page-title-action view-link">' . esc_html( __( 'View', 'codepress-admin-columns' ) ) . '</a>';
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
			'cpac_action' => 'restore_by_type',
		);

		return add_query_arg( $args, $this->settings_url() );
	}

	/**
	 * Populate column with stored options
	 *
	 * Only exists for backwards compatibility (like Pods). Options variable is no longer in use by CPAC_Column.
	 *
	 *
	 * @since NEWVERSION
	 *
	 * @param CPAC_Column $column
	 * @param array $options
	 */
	public function populate_column_options( CPAC_Column $column, $options ) {
		// Set options
		if ( $options ) {
			if ( isset( $options['clone'] ) ) {
				$column->set_clone( $options['clone'] );
			}
			// replace urls, so export will not have to deal with them
			if ( isset( $options['label'] ) ) {
				$options['label'] = stripslashes( str_replace( '[cpac_site_url]', site_url(), $options['label'] ) );
			}
			$column->options = (object) array_merge( (array) $column->options, $options );
		}
	}

	/**
	 * Adds columns classnames from specified directory
	 *
	 * @param string $columns_dir Columns directory
	 * @param string $prefix Autoload prefix
	 * @param array $columns Columns [ class_name => autoload ]
	 *
	 * @return array
	 */
	public function add_autoload_columns( $columns_dir, $prefix, $columns = array() ) {
		$autoloader = AC_Autoloader::instance();
		$_columns = $autoloader->get_class_names_from_dir( $columns_dir, $prefix );

		// set to autoload (true)
		return array_merge( $columns, array_fill_keys( $_columns, true ) );
	}

	/**
	 * @deprecated deprecated since version 2.4.9
	 */
	public function is_columns_screen() {
		_deprecated_function( 'is_columns_screen', '2.4.9', 'is_current_screen' );

		return $this->is_current_screen();
	}

}