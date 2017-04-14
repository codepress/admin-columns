<?php

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class AC_ListScreen {

	CONST OPTIONS_KEY = 'cpac_options_';

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
	private $label;

	/**
	 * @since 2.3.5
	 * @var string
	 */
	private $singular_label;

	/**
	 * Meta type of list screen; post, user, comment. Mostly used for fetching meta data.
	 *
	 * @since 3.0
	 * @var string
	 */
	private $meta_type;

	/**
	 * Page menu slug. Applies only when a menu page is used.
	 *
	 * @since 2.4.10
	 * @var string
	 */
	private $page;

	/**
	 * Class name of the WP_List_Table instance
	 * @see   WP_List_Table
	 * @since 3.0
	 * @var string
	 */
	private $list_table_class;

	/**
	 * Group slug. Used for menu.
	 * @var string
	 */
	private $group;

	/**
	 * Name of the base PHP file (without extension).
	 *
	 * @see   WP_Screen::base
	 *
	 * @since 2.0
	 * @var string
	 */
	private $screen_base;

	/**
	 * The unique ID of the screen.
	 *
	 * @see   WP_Screen::id
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
	 * @var array [ Column name => Label ]
	 */
	private $original_columns;

	/**
	 * @var string Layout ID
	 */
	private $layout_id;

	/**
	 * @var string Storage key used for saving column data to the database
	 */
	private $storage_key;

	/**
	 * @var array Column settings data
	 */
	private $settings;

	/**
	 * @var bool True when column settings can not be overwritten
	 */
	private $read_only = false;

	/**
	 * @var bool
	 */
	private $network_only = false;

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract public function set_manage_value_callback();

	/**
	 * Register column types
	 * @return void
	 */
	abstract protected function register_column_types();

	public function get_key() {
		return $this->key;
	}

	protected function set_key( $key ) {
		$this->key = $key;
	}

	public function get_label() {
		return $this->label;
	}

	protected function set_label( $label ) {
		$this->label = $label;
	}

	public function get_singular_label() {
		if ( null === $this->singular_label ) {
			$this->set_singular_label( $this->label );
		}

		return $this->singular_label;
	}

	protected function set_singular_label( $label ) {
		$this->singular_label = $label;
	}

	public function get_meta_type() {
		return $this->meta_type;
	}

	protected function set_meta_type( $meta_type ) {
		$this->meta_type = $meta_type;
	}

	public function get_screen_base() {
		return $this->screen_base;
	}

	protected function set_screen_base( $screen_base ) {
		$this->screen_base = $screen_base;
	}

	public function get_screen_id() {
		return $this->screen_id;
	}

	protected function set_screen_id( $screen_id ) {
		$this->screen_id = $screen_id;
	}

	public function get_page() {
		return $this->page;
	}

	protected function set_page( $page ) {
		$this->page = $page;
	}

	public function get_group() {
		return $this->group;
	}

	public function set_group( $group ) {
		$this->group = $group;
	}

	public function get_list_table_class() {
		return $this->list_table_class;
	}

	protected function set_list_table_class( $list_table_class ) {
		$this->list_table_class = $list_table_class;
	}

	/**
	 * @return string
	 */
	public function get_storage_key() {
		if ( null === $this->storage_key ) {
			$this->set_storage_key( $this->get_key() );
		}

		return $this->storage_key;
	}

	/**
	 * @param string $key
	 */
	private function set_storage_key( $key ) {
		$this->storage_key = $key;

		$this->reset();
	}

	/**
	 * @return string
	 */
	public function get_layout_id() {
		return $this->layout_id;
	}

	/**
	 * @param string $layout_id
	 *
	 * @return $this
	 */
	public function set_layout_id( $layout_id ) {
		$this->layout_id = $layout_id;

		$this->set_storage_key( $this->get_key() . $layout_id );

		return $this;
	}

	/**
	 * Return a single object based on it's ID (post, user, comment etc.)
	 *
	 * @since 3.0
	 * @return mixed
	 */
	protected function get_object_by_id( $id ) {
		return null;
	}

	/**
	 * ID attribute of targeted list table
	 *
	 * @since 3.0
	 * @return string
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @since 2.0.3
	 *
	 * @param WP_Screen $screen
	 *
	 * @return boolean
	 */
	public function is_current_screen( $wp_screen ) {
		return $wp_screen && $wp_screen->id === $this->get_screen_id() && $wp_screen->base === $this->get_screen_base();
	}

	/**
	 * Settings can not be overwritten
	 */
	public function is_read_only() {
		return $this->read_only;
	}

	/**
	 * @param bool $read_only
	 */
	public function set_read_only( $read_only ) {
		$this->read_only = (bool) $read_only;
	}

	/**
	 * Settings can not be overwritten
	 */
	public function is_network_only() {
		return $this->network_only;
	}

	/**
	 * @param bool $network_only
	 */
	public function set_network_only( $network_only ) {
		$this->network_only = (bool) $network_only;
	}

	/**
	 * @return string
	 */
	protected function get_admin_url() {
		return admin_url( $this->get_screen_base() . '.php' );
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->get_page(), 'layout' => $this->get_layout_id() ), $this->get_admin_url() );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return add_query_arg( array( 'list_screen' => $this->key, 'layout_id' => $this->get_layout_id() ), AC()->admin_columns_screen()->get_link() );
	}

	/**
	 * @since 3.0
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
	public function reset() {
		$this->columns = null;
		$this->column_types = null;
		$this->settings = null;
	}

	/**
	 * @since 2.0
	 * @return false|AC_Column
	 */
	public function get_column_by_name( $name ) {
		$columns = $this->get_columns();

		foreach ( $columns as $column ) {
			if ( $column->get_name() === $name ) {
				return $column;
			}
		}

		return false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|AC_Column
	 */
	public function get_column_by_type( $type ) {
		$column_types = $this->get_column_types();

		if ( ! isset( $column_types[ $type ] ) ) {
			return false;
		}

		return $column_types[ $type ];
	}

	/**
	 * @param string $type
	 *
	 * @return false|string
	 */
	public function get_class_by_type( $type ) {
		$column = $this->get_column_by_type( $type );

		if ( ! $column ) {
			return false;
		}

		return get_class( $column );
	}

	/**
	 * @param string $type Column type
	 */
	public function deregister_column_type( $type ) {
		if ( isset( $this->column_types[ $type ] ) ) {
			unset( $this->column_types[ $type ] );
		}
	}

	/**
	 * @param AC_Column $column
	 */
	public function register_column_type( AC_Column $column ) {
		if ( ! $column->get_type() ) {
			return false;
		}

		$column->set_list_screen( $this );

		if ( ! $column->is_valid() ) {
			return false;
		}

		// Skip the custom registered columns which are marked 'original' but are not available for this list screen
		if ( $column->is_original() && ! in_array( $column->get_type(), array_keys( $this->get_original_columns() ) ) ) {
			return false;
		}

		$this->column_types[ $column->get_type() ] = $column;

		return true;
	}

	/**
	 * @param string $type
	 *
	 * @return string Label
	 */
	public function get_original_label( $type ) {
		$columns = $this->get_original_columns();

		if ( ! isset( $columns[ $type ] ) ) {
			return false;
		}

		return $columns[ $type ];
	}

	/**
	 * @return array
	 */
	public function get_original_columns() {
		if ( null === $this->original_columns ) {
			$this->set_original_columns( $this->get_stored_default_headings() );
		}

		return $this->original_columns;
	}

	/**
	 * @param array $columns
	 */
	public function set_original_columns( $columns ) {
		$this->original_columns = $columns;
	}

	/**
	 * Available column types
	 */
	public function set_column_types() {

		// Register default columns
		foreach ( $this->get_original_columns() as $type => $label ) {

			// Ignore the mandatory checkbox column
			if ( 'cb' === $type ) {
				continue;
			}

			$column = new AC_Column();

			$column
				->set_type( $type )
				->set_original( true );

			$this->register_column_type( $column );
		}

		// Placeholder columns
		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( $addon->is_plugin_active() && ! $addon->is_active() ) {
				$this->register_column_type( $addon->get_placeholder_column() );
			}
		}

		$this->register_column_type( new AC_Column_CustomField() );
		$this->register_column_type( new AC_Column_UsedByMenu() );

		// Load Custom columns
		$this->register_column_types();

		/**
		 * Register column types
		 *
		 * @param AC_ListScreen $this
		 */
		do_action( 'ac/column_types', $this );
	}

	/**
	 * @param string $dir    Absolute path to the column directory
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
	 * @param string $type Column type
	 *
	 * @return bool
	 */
	private function is_original_column( $type ) {
		$column = $this->get_column_by_type( $type );

		if ( ! $column ) {
			return false;
		}

		return $column->is_original();
	}

	/**
	 * @param array $settings Column options
	 *
	 * @return AC_Column|false
	 */
	public function create_column( array $settings ) {
		if ( ! isset( $settings['type'] ) ) {
			return false;
		}

		$class = $this->get_class_by_type( $settings['type'] );

		if ( ! $class ) {
			return false;
		}

		/* @var AC_Column $column */
		$column = new $class();

		$column->set_list_screen( $this )
		       ->set_type( $settings['type'] );

		if ( isset( $settings['name'] ) ) {
			$column->set_name( $settings['name'] );
		}

		// Mark as original
		if ( $this->is_original_column( $settings['type'] ) ) {

			$column->set_original( true );
			$column->set_name( $settings['type'] );
		}

		$column->set_options( $settings );

		return $column;
	}

	/**
	 * @since 3.0
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
	}

	/**
	 * @since 3.0
	 */
	private function set_columns() {

		foreach ( $this->get_settings() as $name => $data ) {
			$data['name'] = $name;
			if ( $column = $this->create_column( $data ) ) {
				$this->register_column( $column );
			}
		}

		// Nothing stored. Use WP default columns.
		if ( null === $this->columns ) {
			foreach ( $this->get_original_columns() as $type => $label ) {
				if ( $column = $this->create_column( array( 'type' => $type, 'original' => true ) ) ) {
					$this->register_column( $column );
				}
			}
		}

		if ( null === $this->columns ) {
			$this->columns = array();
		}
	}

	/**
	 * Store column data
	 *
	 * @param array $column_data
	 *
	 * @return WP_Error|true
	 */
	public function store( $column_data ) {
		if ( ! $column_data ) {
			return new WP_Error( 'no-settings', __( 'No columns settings available.', 'codepress-admin-columns' ) );
		}

		$settings = array();

		foreach ( $column_data as $column_name => $options ) {
			if ( empty( $options['type'] ) ) {
				continue;
			}

			// New column, new key
			if ( 0 === strpos( $column_name, '_new_column_' ) ) {
				$column_name = uniqid();
			}

			$options['name'] = $column_name;

			$column = $this->create_column( $options );

			if ( ! $column ) {
				continue;
			}

			// Skip duplicate original columns
			if ( $column->is_original() ) {
				if ( in_array( $column->get_type(), wp_list_pluck( $settings, 'type' ), true ) ) {
					continue;
				}
			}

			$sanitized = array();

			// Sanitize data
			foreach ( $column->get_settings() as $setting ) {
				$sanitized += $setting->get_values();
			}

			// Encode site url
			if ( $setting = $column->get_setting( 'label' ) ) {
				$sanitized[ $setting->get_name() ] = $setting->get_encoded_label();
			}

			$settings[ $column_name ] = array_merge( $options, $sanitized );
		}

		$result = update_option( self::OPTIONS_KEY . $this->get_storage_key(), $settings );

		if ( ! $result ) {
			return new WP_Error( 'same-settings' );
		}

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 *
		 * @since 3.0
		 *
		 * @param AC_ListScreen $list_screen
		 */
		do_action( 'ac/columns_stored', $this );

		return true;
	}

	/**
	 * Populate settings from the database
	 */
	public function populate_settings() {

		// Load from DB
		$this->set_settings( get_option( self::OPTIONS_KEY . $this->get_storage_key() ) );

		// Load from API
		AC()->api()->set_column_settings( $this );
	}

	/**
	 * @param array $settings Column settings
	 */
	public function set_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$this->settings = $settings;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->populate_settings();
		}

		return $this->settings;
	}

	/**
	 * @return string
	 */
	private function get_default_key() {
		return self::OPTIONS_KEY . $this->get_key() . "__default";
	}

	/**
	 * @param array $column_headings Default column headings
	 *
	 * @return bool
	 */
	public function save_default_headings( $column_headings ) {
		return update_option( $this->get_default_key(), $column_headings );
	}

	/**
	 * @return array [ Column Name => Label ]
	 */
	public function get_stored_default_headings() {
		return get_option( $this->get_default_key(), array() );
	}

	/**
	 * @return bool
	 */
	public function delete_default_headings() {
		return delete_option( $this->get_default_key() );
	}

	/**
	 * @return bool
	 */
	public function delete() {
		return delete_option( self::OPTIONS_KEY . $this->get_storage_key() );
	}

	/**
	 * @param string $column_name
	 * @param int    $id
	 * @param null   $original_value
	 *
	 * @return string
	 */
	public function get_display_value_by_column_name( $column_name, $id, $original_value = null ) {
		$column = $this->get_column_by_name( $column_name );

		if ( ! $column ) {
			return $original_value;
		}

		$value = $column->get_value( $id );

		// You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
		if ( $column->is_original() && ac_helper()->string->is_empty( $value ) ) {
			return $original_value;
		}

		if ( $column instanceof AC_Column_AjaxValue && $value !== $column->get_empty_char() ) {
			$value = $this->toggle_box_ajax( $id, $value, $column->get_name() );
		}

		/**
		 * Column display value
		 *
		 * @since 3.0
		 *
		 * @param string    $value  Column display value
		 * @param int       $id     Object ID
		 * @param AC_Column $column Column object
		 */
		return apply_filters( 'ac/column/value', $value, $id, $column );
	}

	/**
	 * @param int    $id
	 * @param string $label
	 * @param string $column_name
	 */
	private function toggle_box_ajax( $id, $label, $column_name ) {
		return ac_helper()->html->link( '#', $label . '<div class="spinner"></div>', array(
			'class'              => 'ac-toggle-box-link',
			'data-column'        => $column_name,
			'data-item-id'       => $id,
			'data-ajax-populate' => 1,
		) );
	}

	/**
	 * Get a single row from list table
	 *
	 * @since 3.0
	 */
	public function get_single_row( $object_id ) {
		ob_start();

		$this->get_list_table()->single_row( $this->get_object_by_id( $object_id ) );

		return ob_get_clean();
	}

	/**
	 * @return WP_List_Table|object
	 */
	public function get_list_table() {
		return _get_list_table( $this->get_list_table_class(), array( 'screen' => $this->get_screen_id() ) );
	}

}