<?php

namespace AC;

use AC\Column\Placeholder;
use ReflectionClass;
use WP_Error;

/**
 * List Screen
 * @since 2.0
 */
abstract class ListScreen {

	const OPTIONS_KEY = 'cpac_options_';

	/**
	 * Unique Identifier for List Screen.
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
	 * @since 3.0
	 * @var string
	 */
	private $meta_type;

	/**
	 * Page menu slug. Applies only when a menu page is used.
	 * @since 2.4.10
	 * @var string
	 */
	private $page;

	/**
	 * Group slug. Used for menu.
	 * @var string
	 */
	private $group;

	/**
	 * Name of the base PHP file (without extension).
	 * @see   \WP_Screen::base
	 * @since 2.0
	 * @var string
	 */
	private $screen_base;

	/**
	 * The unique ID of the screen.
	 * @see   \WP_Screen::id
	 * @since 2.5
	 * @var string
	 */
	private $screen_id;

	/**
	 * @since 2.0.1
	 * @var Column[]
	 */
	private $columns;

	/**
	 * @since 2.2
	 * @var Column[]
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
	 * @return void
	 */
	abstract public function set_manage_value_callback();

	/**
	 * Register column types
	 * @return void
	 */
	abstract protected function register_column_types();

	/**
	 * @return string
	 */
	public function get_heading_hookname() {
		return 'manage_' . $this->get_screen_id() . '_columns';
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * @param string $key
	 *
	 * @return self
	 */
	protected function set_key( $key ) {
		$this->key = $key;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return self
	 */
	protected function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_singular_label() {
		if ( null === $this->singular_label ) {
			$this->set_singular_label( $this->label );
		}

		return $this->singular_label;
	}

	/**
	 * @param string $label
	 *
	 * @return self
	 */
	protected function set_singular_label( $label ) {
		$this->singular_label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @param string $meta_type
	 *
	 * @return self
	 */
	protected function set_meta_type( $meta_type ) {
		$this->meta_type = $meta_type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_screen_base() {
		return $this->screen_base;
	}

	/**
	 * @param string $screen_base
	 *
	 * @return self
	 */
	protected function set_screen_base( $screen_base ) {
		$this->screen_base = $screen_base;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_screen_id() {
		return $this->screen_id;
	}

	/**
	 * @param string $screen_id
	 *
	 * @return self
	 */
	protected function set_screen_id( $screen_id ) {
		$this->screen_id = $screen_id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_page() {
		return $this->page;
	}

	/**
	 * @param string $page
	 *
	 * @return self
	 */
	protected function set_page( $page ) {
		$this->page = $page;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @param string $group
	 *
	 * @return self
	 */
	public function set_group( $group ) {
		$this->group = $group;

		return $this;
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
	 * @return self
	 */
	public function set_layout_id( $layout_id ) {
		$this->layout_id = $layout_id;

		$this->set_storage_key( $this->get_key() . $layout_id );

		return $this;
	}

	/**
	 * ID attribute of targeted list table
	 * @since 3.0
	 * @return string
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @since 2.0.3
	 *
	 * @param $wp_screen
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
		return add_query_arg( array(
			'page'   => $this->get_page(),
			'layout' => $this->get_layout_id(),
		), $this->get_admin_url() );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return add_query_arg( array(
			'list_screen' => $this->key,
			'layout_id'   => $this->get_layout_id(),
		), ac_get_admin_url() );
	}

	/**
	 * @since 3.0
	 * @return Column[]
	 */
	public function get_columns() {
		if ( null === $this->columns ) {
			$this->set_columns();
		}

		return $this->columns;
	}

	/**
	 * @return Column[]
	 */
	public function get_column_types() {
		if ( null === $this->column_types ) {
			$this->set_column_types();
		}

		return $this->column_types;
	}

	/**
	 * Clears columns variable, which allow it to be repopulated by get_columns().
	 * @since 2.5
	 */
	public function reset() {
		$this->columns = null;
		$this->column_types = null;
		$this->settings = null;
	}

	/**
	 * @since 2.0
	 *
	 * @param $name
	 *
	 * @return false|Column
	 */
	public function get_column_by_name( $name ) {
		$columns = $this->get_columns();

		foreach ( $columns as $column ) {

			// Do not do a var type check. All column names
			// are stored as strings, even integers.
			if ( $column->get_name() == $name ) {
				return $column;
			}
		}

		return false;
	}

	/**
	 * @param string $type
	 *
	 * @return false|Column
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
	 * @param Column $column
	 */
	public function register_column_type( Column $column ) {
		if ( ! $column->get_type() ) {
			return;
		}

		$column->set_list_screen( $this );

		if ( ! $column->is_valid() ) {
			return;
		}

		// Skip the custom registered columns which are marked 'original' but are not available for this list screen
		if ( $column->is_original() && ! in_array( $column->get_type(), array_keys( $this->get_original_columns() ) ) ) {
			return;
		}

		$this->column_types[ $column->get_type() ] = $column;
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

		return (array) $this->original_columns;
	}

	/**
	 * @param array $columns
	 */
	public function set_original_columns( $columns ) {
		$this->original_columns = (array) $columns;
	}

	/**
	 * Reset original columns
	 */
	public function reset_original_columns() {
		$this->original_columns = null;
	}

	/**
	 * Available column types
	 */
	private function set_column_types() {
		$this->column_types = array();

		// Register default columns
		foreach ( $this->get_original_columns() as $type => $label ) {

			// Ignore the mandatory checkbox column
			if ( 'cb' === $type ) {
				continue;
			}

			$column = new Column();

			$column
				->set_type( $type )
				->set_original( true );

			$this->register_column_type( $column );
		}

		// Placeholder columns
		foreach ( new Integrations() as $integration ) {
			if ( ! $integration->show_placeholder( $this ) ) {
				continue;
			}

			$plugin_info = new PluginInformation( $integration->get_basename() );

			if ( $integration->is_plugin_active() && ! $plugin_info->is_active() ) {
				$column = new Placeholder();
				$column->set_integration( $integration );

				$this->register_column_type( $column );
			}
		}

		// Load Custom columns
		$this->register_column_types();

		/**
		 * Register column types
		 *
		 * @param ListScreen $this
		 */
		do_action( 'ac/column_types', $this );
	}

	/**
	 * @param string $namespace Namespace from the current path
	 *
	 * @throws \ReflectionException
	 */
	public function register_column_types_from_dir( $namespace ) {
		$classes = Autoloader::instance()->get_class_names_from_dir( $namespace );

		foreach ( $classes as $class ) {
			$reflection = new ReflectionClass( $class );

			if ( $reflection->isInstantiable() ) {
				$this->register_column_type( new $class );
			}
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
	 * @return Column|false
	 */
	public function create_column( array $settings ) {
		if ( ! isset( $settings['type'] ) ) {
			return false;
		}

		$class = $this->get_class_by_type( $settings['type'] );

		if ( ! $class ) {
			return false;
		}

		/* @var Column $column */
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
	 * @param Column $column
	 */
	protected function register_column( Column $column ) {
		$this->columns[ $column->get_name() ] = $column;

		/**
		 * Fires when a column is registered to a list screen, i.e. when it is created. Can be used
		 * to attach additional functionality to a column, such as exporting, sorting or filtering
		 * @since 3.0.5
		 *
		 * @param Column     $column      Column type object
		 * @param ListScreen $list_screen List screen object to which the column was registered
		 */
		do_action( 'ac/list_screen/column_registered', $column, $this );
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
			$setting = $column->get_setting( 'label' );

			if ( $setting ) {
				$sanitized[ $setting->get_name() ] = $setting->get_encoded_label();
			}

			$settings[ $column_name ] = array_merge( $options, $sanitized );
		}

		$result = update_option( self::OPTIONS_KEY . $this->get_storage_key(), $settings, false );

		if ( ! $result ) {
			return new WP_Error( 'same-settings' );
		}

		/**
		 * Fires after a new column setup is stored in the database
		 * Primarily used when columns are saved through the Admin Columns settings screen
		 * @since 3.0
		 *
		 * @param ListScreen $list_screen
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
	 *
	 * @return ListScreen
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
		return update_option( $this->get_default_key(), $column_headings, false );
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

		/**
		 * Fires before a column setup is removed from the database
		 * Primarily used when columns are deleted through the Admin Columns settings screen
		 * @since 3.0.8
		 *
		 * @param ListScreen $list_screen
		 */
		do_action( 'ac/columns_delete', $this );

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

		/**
		 * Column display value
		 * @since 3.0
		 *
		 * @param string $value  Column display value
		 * @param int    $id     Object ID
		 * @param Column $column Column object
		 */
		$value = apply_filters( 'ac/column/value', $value, $id, $column );

		return $value;
	}

	/**
	 * Get default column headers
	 * @return array
	 */
	public function get_default_column_headers() {
		return array();
	}

}