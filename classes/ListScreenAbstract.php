<?php
defined( 'ABSPATH' ) or die();

/**
 * List Screen
 *
 * @since 2.0
 */
abstract class AC_ListScreenAbstract {

	/**
	 * Identifier for List Screen; Post type etc.
	 *
	 * @since 2.0
	 */
	protected $key;

	/**
	 * @since 2.0
	 */
	protected $label;

	/**
	 * @since 2.3.5
	 */
	protected $singular_label;

	/**
	 * Type of list screen; Post, Media, User or Comments
	 *
	 * @since 2.0
	 */
	protected $type;

	/**
	 * Meta type of list screen; post, user, comment. Mostly used for custom field data.
	 *
	 * @since 3.0
	 */
	protected $meta_type;

	/**
	 * Groups the list screen in the menu.
	 *
	 * @since 2.0
	 */
	protected $menu_type;

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
	 * The unique ID of the screen.
	 *
	 * @see get_current_screen()
	 *
	 * @since 2.5
	 * @var string
	 */
	protected $screen;

	/**
	 * @var AC_Settings_Columns $settings
	 */
	private $settings;

	/**
	 * @since 2.0.1
	 * @var CPAC_Column[]
	 */
	private $columns;

	/**
	 * @since 2.2
	 * @var CPAC_Column[]
	 */
	private $column_types;

	/**
	 * @since NEWVERSION
	 * @var array [ Name => Label ]
	 */
	private $default_columns;

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract function set_manage_value_callback();

	// TODO: user getters and setters, make vars protected

	public function __get( $var ) {
		$vars = array(
			'type',
			'key',
		);

		if ( in_array( $var, $vars ) ) {
			return call_user_func( array( $this, 'get_' . $var ) );
		}
	}

	/**
	 * @since 2.4.4
	 */
	public function __construct() {
		$this->menu_type = __( 'Other', 'codepress-admin-columns' );
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
	public function get_singular_label() {
		return $this->singular_label;
	}

	/**
	 * @param string $key
	 */
	public function set_key( $key ) {
		$this->key = $key;
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
	 * @since NEWVERSION
	 */
	public function get_key() {
		return $this->key;
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
		return $this->screen;
	}

	/**
	 * @since 2.0.3
	 * @return boolean
	 */
	public function is_current_screen() {
		$screen = get_current_screen();

		return $screen && $screen->id === $this->screen;
	}

	/**
	 * Set menu type
	 *
	 * @since 2.4.1
	 *
	 * @return AC_ListScreenAbstract
	 */
	public function set_menu_type( $menu_type ) {
		$this->menu_type = $menu_type;

		return $this;
	}

	/**
	 * @since 2.5
	 */
	public function get_menu_type() {
		return $this->menu_type;
	}

	/**
	 * Are column set by third party plugin
	 *
	 * @since 2.3.4
	 */
	public function is_using_php_export() {

		/**
		 * @since NEWVERSION
		 */
		return apply_filters( 'ac/list_screen/is_using_php_export', false, $this );
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
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->page ), admin_url( $this->base . '.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {

		/**
		 * @since NEWVERSION
		 */
		return apply_filters( 'ac/list_screen/edit_link', add_query_arg( array( 'cpac_key' => $this->key ), AC()->settings()->get_link( 'columns' ) ) );
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
	 * @return AC_Settings_Columns
	 */
	public function settings() {
		if ( null === $this->settings ) {
			$this->settings = new AC_Settings_Columns( $this->key );
		}

		return $this->settings;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return CPAC_Column[]
	 */
	public function get_columns() {
		if ( null === $this->columns ) {
			$this->set_columns();
		}

		return $this->columns;
	}

	/**
	 * @return CPAC_Column[]
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
	public function flush_columns() {
		$this->columns = null;
		$this->column_types = null;
		$this->default_columns = null;
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

		return $column ? $column->get_display_value( $id ) : $value;
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
	 * @param CPAC_Column $column
	 */
	public function register_column_type( CPAC_Column $column ) {

		// TODO: change to is_valid()
		if ( ! $column->apply_conditional() ) {
			return;
		}

		// Skip original columns that do not exist
		if ( $column->is_original() ) {
			if ( ! $this->default_column_exists( $column->get_type() ) ) {
				return;
			}

			$column->set_property( 'group', __( 'Default', 'codepress-admin-columns' ) );
		}

		$this->column_types[ $column->get_type() ] = $column;
	}

	/**
	 * @param string $dir Absolute path to the column directory
	 * @param string $prefix Autoload prefix
	 */
	public function register_columns_from_dir( $dir, $prefix ) {
		$class_names = AC()->autoloader()->get_class_names_from_dir( $dir, $prefix );

		foreach ( $class_names as $class_name ) {
			$this->register_column_type( new $class_name );
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
	 * @return CPAC_Column|false
	 */
	public function create_column( $type, $options = array(), $clone = 0 ) {
		$_column_type = $this->get_column_type( $type );
		if ( ! $_column_type ) {
			return false;
		}

		/* @var CPAC_Column $column */
		$column = clone $_column_type;

		$column
			->set_clone( $clone )
			->set_options( $options );

		if ( $column->is_original() ) {

			$column->set_property( 'label', $this->get_original_label( $column->get_type() ) );

			// Hide label
			if ( ac_helper()->string->contains_html_only( $column->get_type_label() ) ) {
				$column->set_property( 'hide_label', true );
			}

			if ( ! $column->get_group() ) {
				$column->set_property( 'group', __( 'Default', 'codepress-admin-columns' ) );
			}
		}

		/**
		 * Add before and after fields to specific columns
		 *
		 * @since 2.0
		 * @deprecated NEWVERSION
		 */
		$column->set_property( 'use_before_after', apply_filters( 'cac/column/properties/use_before_after', $column->get_property( 'use_before_after' ), $this ) );

		return $column;
	}

	/**
	 * @param array $data Column options
	 */
	private function register_column( CPAC_Column $column ) {
		$this->columns[ $column->get_name() ] = $column;

		do_action( 'ac/column', $column );
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
	 * @since NEWVERSION
	 */
	private function set_columns() {

		foreach ( $this->settings()->get_columns() as $name => $data ) {
			if ( $column = $this->create_column( $data['type'], $data, $data['clone'] ) ) {
				$this->register_column( $column );
			}
		}

		// Nothing stored. Use WP default columns.
		if ( null === $this->columns ) {
			foreach ( $this->get_default_columns() as $name => $label ) {
				if ( $column = $this->create_column( $name, array( 'label' => $label ) ) ) {
					$this->register_column( $column );
				}
			}
		}

		if ( null === $this->columns ) {
			$this->columns = array();
		}
	}

	/**
	 * @param string $type
	 *
	 * @return false|CPAC_Column
	 */
	private function get_column_type( $type ) {
		$column_types = $this->get_column_types();

		return isset( $column_types[ $type ] ) ? $column_types[ $type ] : false;
	}

	/**
	 * @var array [ Column Name =>  Column Label ]
	 */
	private function set_default_columns() {

		$this->default_columns = $this->settings()->get_default_headings();

		if ( ! $this->default_columns ) {
			$this->default_columns = $this->get_default_columns();
		}

		$this->default_columns = apply_filters( 'cac/default_column_names', $this->default_columns, $this );
	}

	/**
	 * @return array  [ Column Name =>  Column Label ]
	 */
	private function get_default_headings() {
		if ( null === $this->default_columns ) {
			$this->set_default_columns();
		}

		return $this->default_columns;
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
	private function set_column_types() {

		// WP default columns
		foreach ( $this->get_default_headings() as $name => $label ) {
			if ( 'cb' === $name ) {
				continue;
			}

			$plugin_column_class_name = apply_filters( 'ac/plugin_column_class_name', 'AC_Column_Plugin' );

			if ( ! class_exists( $plugin_column_class_name ) ) {
				continue;
			}

			/* @var AC_Column_Plugin $column */
			$column = new $plugin_column_class_name( $this->get_key() );

			$column->set_property( 'type', $name );

			$this->register_column_type( $column );
		}

		$class_names = AC()->autoloader()->get_class_names_from_dir( AC()->get_plugin_dir() . 'classes/Column/' . ucfirst( $this->get_type() ), 'AC_' );

		// Add-on placeholders
		if ( cpac_is_acf_active() ) {
			$this->register_column_type( new AC_Column_ACFPlaceholder );
		}
		if ( cpac_is_woocommerce_active() ) {
			$this->register_column_type( new AC_Column_WooCommercePlaceholder );
		}

		foreach ( $class_names as $class_name ) {
			$this->register_column_type( new $class_name );
		}

		// For backwards compatibility
		$this->deprecated_register_columns();

		do_action( 'ac/column_types', $this );
	}

	/**
	 * Old way for registering columns. For backwards compatibility.
	 *
	 * @deprecated NEWVERSION
	 */
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

}