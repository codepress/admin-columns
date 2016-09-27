<?php
defined( 'ABSPATH' ) or die();


// TODO: remove this file
class AC_Columns {

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
	 * @var string $storage_model_key
	 */
	private $storage_model_key;

	/**
	 * @var AC_StorageModel $storage_model
	 */
	private $storage_model;

	public function __construct( $storage_model_key ) {
		$this->storage_model_key = $storage_model_key;
	}

	/**
	 * @return AC_StorageModel|false
	 */
	public function get_storage_model() {
		if ( null == $this->storage_model ) {
			$this->storage_model = AC()->get_storage_model( $this->storage_model_key );
		}

		return $this->storage_model;
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
	 * Display column value
	 *
	 * @since NEWVERSION
	 */
	public function get_display_value_by_column_name( $column_name, $id, $value = false ) {
		$column = $this->get_column_by_name( $column_name );

		return $column && ! $column->is_original() ? $column->get_display_value( $id ) : $value;
	}

	/**
	 * @since 2.0
	 * @return array Column Name | Column Label
	 */
	public function get_default_columns() {
		if ( ! function_exists( 'get_column_headers' ) ) {
			return array();
		}

		// trigger WP_List_Table::get_columns()
		$this->get_storage_model()->get_list_table();

		return (array) get_column_headers( $this->get_storage_model()->get_screen_id() );
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
		$default_column_names = apply_filters( 'cac/default_column_names', array(), $this->get_storage_model() );

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
		$column = new $classnames[ $class_type ]( $this->storage_model_key );

		// Set defaults
		if ( $column->is_original() ) {

			$default_columns = $this->get_default_headings();

			if ( ! isset( $default_columns[ $column_type ] ) ) {
				return false;
			}

			$column->set_defaults( array( 'type' => $column_type, 'label' => $default_columns[ $column_type ] ) );
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
		$this->column_types = array();
		$this->columns = array();
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
	private function populate_column_options( CPAC_Column $column, $options ) {
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
	 * @since NEWVERSION
	 */
	private function get_default_headings() {
		if ( null === $this->default_columns ) {

			$storage_model = $this->get_storage_model();

			// Get default column that have been set on the listings screen
			$default_columns = $storage_model->settings()->get_default_headings();

			// As a fallback we can use the table headings. this is not reliable, because most 3rd party column will not be loaded at this point.
			if ( empty( $default_columns ) ) {
				$default_columns = apply_filters( "cac/default_columns", $this->get_default_columns(), $storage_model );
				$default_columns = apply_filters( "cac/default_columns/type=" . $storage_model->get_type(), $default_columns, $storage_model );
				$default_columns = apply_filters( "cac/default_columns/storage_key=" . $storage_model->get_key(), $default_columns, $storage_model );
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

			foreach ( $this->get_columns_filepath() as $class_name => $path ) {
				$autoload = true;

				// check for autoload condition
				if ( true !== $path ) {
					$autoload = false;

					if ( is_readable( $path ) ) {
						require_once $path;
					}
				}

				if ( ! class_exists( $class_name, $autoload ) ) {
					continue;
				}

				/* @var $column CPAC_Column */
				$column = new $class_name( $this->storage_model_key );

				if ( $column->apply_conditional() ) {
					$this->column_classnames[ $column->get_type() ] = $class_name;
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
	private function get_columns_filepath() {

		$dir = AC()->get_plugin_dir();

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
		$columns = ac_add_autoload_columns( $dir . 'classes/Column/' . ucfirst( $this->get_storage_model()->get_type() ), 'AC_', $columns );

		/**
		 * Filter the available custom column types
		 * Use this to register a custom column type
		 *
		 * @since 2.0
		 *
		 * @param array $columns Available custom columns ([class_name] => [class file path])
		 * @param AC_StorageModel $storage_model Storage model class instance
		 */
		$columns = apply_filters( 'cac/columns/custom', $columns, $this->get_storage_model() );

		/**
		 * Filter the available custom column types for a specific type
		 *
		 * @since 2.0
		 * @see Filter cac/columns/custom
		 */
		$columns = apply_filters( 'cac/columns/custom/type=' . $this->get_storage_model()->get_type(), $columns, $this->get_storage_model() );

		/**
		 * Filter the available custom column types for a specific type
		 *
		 * @since 2.0
		 * @see Filter cac/columns/custom
		 */
		$columns = apply_filters( 'cac/columns/custom/post_type=' . $this->get_storage_model()->get_post_type(), $columns, $this->get_storage_model() );

		return $columns;
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
		if ( $stored = $this->get_storage_model()->settings()->get_columns() ) {
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
		do_action( "cac/columns/storage_key=" . $this->storage_model_key, $this->columns, $this );
	}

}