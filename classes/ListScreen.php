<?php

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class AC_ListScreen {

	CONST OPTIONS_KEY = 'cpac_options_';

	// TODO: getters and setters

	/**
	 * Unique Identifier for List Screen.
	 *
	 * @since 2.0
	 * @var string
	 */
	private $key;

	/**
	 * @since 2.0
	 * @var string
	 */
	protected $label;

	/**
	 * @since 2.3.5
	 * @var string
	 */
	protected $singular_label;

	/**
	 * Type of list screen. Example: post, media, user, comment
	 *
	 * @since 2.0
	 * @var string
	 */
	protected $type;

	/**
	 * Meta type of list screen; post, user, comment. Mostly used for fetching meta data.
	 *
	 * @since 3.0
	 * @var string
	 */
	protected $meta_type;

	/**
	 * Name of the base PHP file (without extension)
	 *
	 * @since 2.0
	 * @var string
	 */
	protected $base;

	/**
	 * Page menu slug. Applies only when a menu page is used.
	 *
	 * @since 2.4.10
	 * @var string
	 */
	protected $page;

	/**
	 * Class name of the WP_List_Table instance
	 *
	 * @since NEWVERSION
	 * @var string
	 */
	protected $list_table;

	/**
	 * Group slug. Used for menu.
	 * @var string
	 */
	private $group;

	/**
	 * The unique ID of the screen.
	 *
	 * @see WP_Screen::id
	 *
	 * @since 2.5
	 * @var string
	 */
	private $screen_id;

	/**
	 * @since 2.0.1
	 * @var AC_Column[]
	 */
	private $columns;

	/**
	 * @since 2.2
	 * @var AC_Column[]
	 */
	private $column_types;

	/**
	 * @since NEWVERSION
	 * @var array [ Name => Label ]
	 */
	private $default_columns;

	/**
	 * @var string Layout ID
	 */
	private $layout;

	/**
	 * @var string Storage key used for saving column data to the database
	 */
	private $storage_key;

	/**
	 * @var array Column settings data
	 */
	private $settings;

	/**
	 * @var bool True when column settings are set externally.
	 */
	private $external_settings = false;

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract function set_manage_value_callback();

	/**
	 * @return string
	 */
	public function get_storage_key() {
		// TODO
		return $this->storage_key ? $this->storage_key : $this->get_key();
	}

	/**
	 * @param string $key
	 */
	private function set_storage_key( $key ) {
		$this->storage_key = $key;

		$this->reset();
	}

	/**
	 * @param string $key
	 */
	protected function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function get_layout() {
		return $this->layout;
	}

	/**
	 * @param string $layout
	 *
	 * @return $this
	 */
	public function set_layout( $layout ) {
		$this->layout = $layout;

		$this->set_storage_key( $this->get_key() . $this->layout );

		return $this;
	}

	/**
	 * Get screen base
	 */
	public function get_base() {
		$this->base;
	}

	/**
	 * @return string
	 */
	public function get_singular_label() {
		return $this->singular_label;
	}

	/**
	 * Return a single object based on it's ID (post, user, comment etc.)
	 *
	 * @since NEWVERSION
	 * @return mixed
	 */
	protected function get_object_by_id( $id ) {
		return null;
	}

	/**
	 * Get a single row from list table
	 *
	 * @param int $object_id Object ID
	 *
	 * @since NEWVERSION
	 *
	 * @return false|string HTML
	 */
	public function get_single_row( $object_id ) {
		return false;
	}

	/**
	 * ID attribute of targeted list table
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_screen_id() {
		return $this->screen_id;
	}

	protected function set_screen_id( $screen_id ) {
		$this->screen_id = $screen_id;
	}

	/**
	 * @since 2.0.3
	 *
	 * @param WP_Screen $screen
	 *
	 * @return boolean
	 */
	public function is_current_screen( $wp_screen ) {
		return $wp_screen && $wp_screen->id === $this->get_screen_id();
	}

	/**
	 * @since 2.5
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @param string $group
	 */
	public function set_group( $group ) {
		$this->group = (string) $group;
	}

	/**
	 * Settings are loaded externally
	 *
	 * @since 2.3.4
	 */
	public function has_external_setttings() {
		return $this->external_settings;
	}

	/**
	 * @param bool $bool
	 */
	// TODO: maybe add private method to set_settings or set_settings with extra parameter for external settings
	public function set_external_setttings( $bool ) {
		$this->external_settings = $bool;
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
	 * @param $meta_type
	 */
	protected function set_meta_type( $meta_type ) {
		$this->meta_type = $meta_type;
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->page, 'layout' => $this->layout ), admin_url( $this->base . '.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return add_query_arg( array( 'cpac_key' => $this->key, 'layout_id' => $this->layout ), AC()->admin_columns_screen()->get_link() );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_default_columns() {
		return array();
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return WP_List_Table|false
	 */
	public function get_list_table( $args = array() ) {
		return class_exists( $this->list_table ) ? new $this->list_table( $args ) : false;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return AC_Column[]
	 */
	public function get_columns() {
		if ( null === $this->columns ) {
			$this->set_columns();
		}

		return $this->columns;
	}

	/**
	 * @return AC_Column[]
	 */
	public function get_column_types() {
		if ( null === $this->column_types ) {
			$this->set_column_types();
		}

		return $this->column_types;
	}

	/**
	 * Clears columns variable, which allow it to be repopulated by get_columns().
	 *
	 * @since 2.5
	 */
	private function reset() {
		$this->columns = null;
		$this->column_types = null;
		$this->default_columns = null;
		$this->settings = null;
	}

	/**
	 * @since 2.0
	 * @return false|AC_Column
	 */
	public function get_column_by_name( $name ) {
		$columns = $this->get_columns();

		return isset( $columns[ $name ] ) ? $columns[ $name ] : false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|AC_Column
	 */
	public function get_column_by_type( $type ) {
		$column_types = $this->get_column_types();

		return isset( $column_types[ $type ] ) ? $column_types[ $type ] : false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|string
	 */
	public function get_class_by_type( $type ) {
		$column = $this->get_column_by_type( $type );

		return $column ? get_class( $column ) : false;
	}

	/**
	 * Display column value
	 *
	 * @since NEWVERSION
	 */
	public function get_display_value_by_column_name( $column_name, $id, $value = null ) {
		if ( $column = $this->get_column_by_name( $column_name ) ) {
			$value = $column->get_value( $id );

			// TODO: comments, change name
			$value = apply_filters( "cac/column/value", $value, $id, $column );
			$value = apply_filters( "cac/column/value/" . $this->get_type(), $value, $id, $column );
		}

		return $value;
	}

	/**
	 * @param string $column_name
	 *
	 * @return string|false
	 */
	public function get_original_label( $column_name ) {
		$default_columns = $this->get_default_headings();

		return isset( $default_columns[ $column_name ] ) ? $default_columns[ $column_name ] : false;
	}

	/**
	 * @param AC_Column $column
	 */
	public function register_column_type( AC_Column $column ) {
		// Skip original columns that do not exist
		if ( $column->is_original() && ! $this->default_column_exists( $column->get_type() ) ) {
			return;
		}

		$column->set_list_screen( $this );

		if ( $column->is_valid() ) {
			$this->column_types[ $column->get_type() ] = $column;
		}
	}

	/**
	 * @param string $column_name
	 *
	 * @since NEWVERSION
	 *
	 * @return bool
	 */
	private function default_column_exists( $column_name ) {
		$default_columns = $this->get_default_headings();

		return isset( $default_columns[ $column_name ] );
	}

	/**
	 * Available column types
	 */
	public function set_column_types() {
		// Register default column types
		foreach ( array_keys( $this->get_default_headings() ) as $type ) {

			// Ignore the mandatory checkbox column
			if ( 'cb' == $type ) {
				continue;
			}

			$class = apply_filters( 'ac/plugin_column_class_name', 'AC_Column_Plugin' );

			if ( ! class_exists( $class ) ) {
				continue;
			}

			/* @var AC_Column $column */
			$column = new $class;
			$column->set_type( $type );

			$this->register_column_type( $column );
		}

		// Placeholder columns
		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( $addon->is_plugin_active() && ! $addon->is_addon_active() ) {
				$this->register_column_type( $addon->get_placeholder_column() );
			}
		}

		$this->register_column_type( new AC_Column_CustomField() );
		$this->register_column_type( new AC_Column_UsedByMenu() );

		$this->register_column_types_from_dir( AC()->get_plugin_dir() . 'classes/Column/' . ucfirst( $this->get_type() ), 'AC_' );

		// Backwards compatibility
		$this->deprecated_register_columns();

		do_action( 'ac/column_types', $this );
	}

	/**
	 * @param string $dir Absolute path to the column directory
	 * @param string $prefix Autoload prefix
	 */
	public function register_column_types_from_dir( $dir, $prefix ) {
		$prefix = rtrim( $prefix, '_' ) . '_';
		$classes = AC()->autoloader()->get_class_names_from_dir( $dir, $prefix );

		foreach ( $classes as $class ) {
			$this->register_column_type( new $class );
		}
	}

	/**
	 * @param string $column_type
	 */
	public function deregister_column_type( $column_type ) {
		if ( isset( $this->column_types[ $column_type ] ) ) {
			unset( $this->column_types[ $column_type ] );
		}
	}

	/**
	 * @param string $type Column type
	 * @param array $options Column options
	 * @param int $clone Column clone ID
	 *
	 * @return AC_Column|false
	 */
	public function create_column( array $settings ) {
		$defaults = array(
			'clone' => 0,
		);

		$settings = array_merge( $defaults, $settings );

		if ( ! isset( $settings['type'] ) || ! isset( $settings['label'] ) ) {
			return false;
		}

		$class = $this->get_class_by_type( $settings['type'] );

		if ( ! $class ) {
			return false;
		}

		/* @var AC_Column $column */
		$column = new $class();

		$column->set_list_screen( $this )
		       ->set_type( $settings['type'] )
		       ->set_clone( $settings['clone'] )
		       ->set_options( $settings ); // inject settings after clone is set

		return $column;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $column_name Column name
	 */
	public function deregister_column( $column_name ) {
		unset( $this->columns[ $column_name ] );
	}

	/**
	 * @param array $data Column options
	 */
	protected function register_column( AC_Column $column ) {
		$this->columns[ $column->get_name() ] = $column;

		do_action( 'ac/column', $column );
	}

	/**
	 * @since NEWVERSION
	 */
	private function set_columns() {
		foreach ( $this->get_settings() as $data ) {
			if ( $column = $this->create_column( $data ) ) {
				$this->register_column( $column );
			}
		}

		// Nothing stored. Use WP default columns.
		if ( null === $this->columns ) {
			foreach ( $this->get_default_headings() as $type => $label ) {
				if ( $column = $this->create_column( array( 'type' => $type, 'label' => $label ) ) ) {
					$this->register_column( $column );
				}
			}
		}

		if ( null === $this->columns ) {
			$this->columns = array();
		}
	}

	/**
	 * @var array [ Column Name =>  Column Label ]
	 */
	private function set_default_columns() {
		$default_columns = $this->get_stored_default_headings();

		if ( ! $default_columns ) {
			$default_columns = $this->get_default_columns();
		}

		$this->default_columns = $default_columns;
	}

	/**
	 * @return array  [ Column Name =>  Column Label ]
	 */
	public function get_default_headings() {
		if ( null === $this->default_columns ) {
			$this->set_default_columns();
		}

		return apply_filters( 'cac/default_column_names', $this->default_columns, $this );
	}

	/**
	 * Old way for registering columns. For backwards compatibility.
	 *
	 * @deprecated NEWVERSION
	 */
	// TODO
	private function deprecated_register_columns() {
		$class_names = apply_filters( 'cac/columns/custom', array(), $this );
		$class_names = apply_filters( 'cac/columns/custom/type=' . $this->get_type(), $class_names, $this );
		$class_names = apply_filters( 'cac/columns/custom/post_type=' . $this->get_key(), $class_names, $this );

		foreach ( $class_names as $class_name => $path ) {
			$autoload = true;

			// check for autoload condition
			if ( true !== $path ) {
				$autoload = false;

				if ( is_readable( $path ) ) {
					require_once $path;
				}
			}

			if ( class_exists( $class_name, $autoload ) ) {
				$this->register_column_type( new $class_name );
			}
		}
	}

	/**
	 * Store column settings
	 *
	 * @param $settings
	 *
	 * @return bool
	 */
	public function store( $settings ) {
		return update_option( self::OPTIONS_KEY . $this->get_storage_key(), $settings );
	}

	/**
	 * Populate settings from the database
	 */
	public function populate_stored_settings() {
		$this->set_settings( get_option( self::OPTIONS_KEY . $this->get_storage_key() ) );
	}

	/**
	 * @param array $settings Column settings
	 */
	public function set_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$this->settings = $settings;
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->populate_stored_settings();
		}

		return $this->settings;
	}

	/**
	 * @return string
	 */
	private function get_default_key() {
		return self::OPTIONS_KEY . $this->get_key() . "__default";
	}

	public function save_default_headings( $column_headings ) {
		return update_option( $this->get_default_key(), $column_headings );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_stored_default_headings() {
		return get_option( $this->get_default_key(), array() );
	}

	public function delete() {
		return delete_option( self::OPTIONS_KEY . $this->get_storage_key() );
	}

	public function delete_default_headings() {
		return delete_option( $this->get_default_key() );
	}

}