<?php
defined( 'ABSPATH' ) or die();

/**
 * Storage Model
 *
 * @since 2.0
 */
abstract class AC_StorageModel {

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

	/**
	 * @var AC_Layouts
	 */
	private $layouts;

	abstract function init();

	/**
	 * @since 2.4.4
	 */
	function __construct() {
		$this->init();
		$this->set_columns_filepath();

		$this->layouts = new AC_Layouts( $this );
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
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

	/**
	 * @param string $key
	 */
	public function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * @return AC_Layouts
	 */
	public function layouts() {
		return $this->layouts;
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
			$default_columns = $this->settings()->get_default_headings();

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
	private function set_columns_filepath() {

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
		 * @param AC_StorageModel $storage_model Storage model class instance
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
	 * Display column value
	 *
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
	private function load_columns() {
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
		// Use 'ac/column'
		do_action( "cac/columns", $this->columns, $this );
		do_action( "cac/columns/storage_key={$this->key}", $this->columns, $this );
	}

	/**
	 * @since 2.5
	 * @return CPAC_Column[] List of column instances
	 */
	public function get_columns() {
		$this->load_columns();

		// Hook
		foreach ( $this->columns as $column ) {
			do_action( 'ac/column', $column, $this );
		}

		return $this->columns;
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
	 *
	 * @return AC_StorageModel
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
		 * @param AC_StorageModel $storage_model Storage model class instance
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
	 * @return bool
	 */
	public function has_stored_columns() {
		return $this->get_stored_columns() ? true : false;
	}

	/**
	 * @since 1.0
	 * @return array Column options
	 */
	public function get_stored_columns() {
		if ( empty( $this->stored_columns ) ) {

			$columns = apply_filters( 'cpac/storage_model/stored_columns', $this->settings()->get(), $this );
			$columns = apply_filters( 'cpac/storage_model/stored_columns/storage_key=' . $this->key, $columns, $this );

			$this->stored_columns = ! empty( $columns ) ? $columns : array();
		}

		return $this->stored_columns;
	}

	/**
	 * Are column set by third party plugin
	 *
	 * @since 2.3.4
	 */
	public function is_using_php_export() {
		$layout = $this->layouts->get_layout_object();

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
	 * @return AC_Settings
	 */
	public function settings() {
		return new AC_Settings( $this->get_key(), $this->layouts()->get_layout() );
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	protected function get_screen_link() {
		$url = is_network_admin() ? network_admin_url( $this->page . '.php' ) : admin_url( $this->page . '.php' );

		return add_query_arg( array( 'page' => $this->subpage ), $url );
	}

	/**
	 * New public function to get screen link instead of making get_screen_link public. To prevent errors in other plugins
	 * @since 2.5
	 */
	public function get_link() {
		return $this->get_screen_link();
	}

	/**
	 * @return string
	 */
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
		$layout = $this->layouts()->get_layout();

		return add_query_arg( array( 'layout_id' => $layout ? $layout : '' ), $this->settings_url() );
	}

	/**
	 * @since 2.5
	 */
	public function get_edit_link_by_layout( $layout_id ) {
		return add_query_arg( array( 'layout_id' => $layout_id ? $layout_id : '' ), $this->settings_url() );
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

}