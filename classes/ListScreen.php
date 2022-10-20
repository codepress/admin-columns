<?php

namespace AC;

use AC\Sanitize\Kses;
use AC\Type\ListScreenId;
use AC\Type\Url\Editor;
use DateTime;
use LogicException;

/**
 * List Screen
 * @since 2.0
 */
abstract class ListScreen {

	/**
	 * @deprecated 4.0
	 */
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
	 * Meta type of list screen; post, user, comment. Mostly used for fetching metadata.
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
	private $settings = [];

	/**
	 * @var array ListScreen settings data
	 */
	private $preferences = [];

	/**
	 * @var bool True when column settings can not be overwritten
	 */
	private $read_only = false;

	/**
	 * @var bool
	 */
	private $network_only = false;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var DateTime
	 */
	private $updated;

	/**
	 * @return bool
	 */
	public function has_id() {
		return ListScreenId::is_valid_id( $this->layout_id );
	}

	/**
	 * @return ListScreenId
	 */
	public function get_id() {
		if ( ! $this->has_id() ) {
			throw new LogicException( 'ListScreen has no identity.' );
		}

		return new ListScreenId( $this->layout_id );
	}

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract public function set_manage_value_callback();

	/**
	 * Register column types
	 *
	 * @return void
	 */
	abstract protected function register_column_types();

	/**
	 * Register column types from a list with (fully qualified) class names
	 *
	 * @param string[] $list
	 */
	protected function register_column_types_from_list( array $list ): void {
		foreach ( $list as $column ) {
			$this->register_column_type( new $column );
		}
	}

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
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function set_title( $title ) {
		$this->title = $title;

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
	 * @return string
	 * @since 3.0
	 */
	public function get_table_attr_id() {
		return '#the-list';
	}

	/**
	 * @param $wp_screen
	 *
	 * @return boolean
	 * @since 2.0.3
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
	 *
	 * @return $this
	 */
	public function set_read_only( $read_only ) {
		$this->read_only = (bool) $read_only;

		return $this;
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
	 * @param DateTime $updated
	 *
	 * @return $this
	 */
	public function set_updated( DateTime $updated ) {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function get_updated() {
		return $this->updated ?: new DateTime();
	}

	/**
	 * @return string
	 */
	protected function get_admin_url() {
		return admin_url( $this->get_screen_base() . '.php' );
	}

	/**
	 * @return string Link
	 * @since 2.0
	 */
	public function get_screen_link() {
		return add_query_arg( [
			'page'   => $this->get_page(),
			'layout' => $this->get_layout_id(),
		], $this->get_admin_url() );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		$url = new Editor( 'columns' );
		$url->add( [
			'list_screen' => $this->key,
			'layout_id'   => $this->get_layout_id(),
		] );

		return $url->get_url();
	}

	/**
	 * @return Column[]
	 * @since 3.0
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
	 * @param $name
	 *
	 * @return false|Column
	 * @since 2.0
	 */
	public function get_column_by_name( $name ) {
		$columns = $this->get_columns();

		foreach ( $columns as $column ) {
			// Do not do a strict comparision. All column names are stored as strings, even integers.
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
		if ( $column->is_original() && ! array_key_exists( $column->get_type(), $this->get_original_columns() ) ) {
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
		return ( new DefaultColumnsRepository() )->get( $this->get_key() );
	}

	/**
	 * Available column types
	 */
	private function set_column_types() {
		$this->column_types = [];

		// Register default columns
		foreach ( $this->get_original_columns() as $type => $label ) {
			// Ignore the mandatory checkbox column
			if ( 'cb' === $type ) {
				continue;
			}

			$column = new Column;
			$column->set_type( $type )
			       ->set_original( true );

			$this->register_column_type( $column );
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
	 * @param string $column_name Column name
	 *
	 * @since 3.0
	 */
	public function deregister_column( $column_name ) {
		unset( $this->columns[ $column_name ] );
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

		do_action( 'ac/list_screen/column_created', $column, $this );

		return $column;
	}

	/**
	 * @param Column $column
	 */
	protected function register_column( Column $column ) {
		$this->columns[ $column->get_name() ] = $column;

		/**
		 * Fires when a column is registered to a list screen, i.e. when it is created. Can be used
		 * to attach additional functionality to a column, such as exporting, sorting or filtering
		 *
		 * @param Column     $column      Column type object
		 * @param ListScreen $list_screen List screen object to which the column was registered
		 *
		 * @since 3.0.5
		 */
		do_action( 'ac/list_screen/column_registered', $column, $this );
	}

	/**
	 * @param array $settings
	 *
	 * @return self
	 */
	public function set_settings( array $settings ) {
		$this->settings = $settings;

		return $this;
	}

	/**
	 * @since 3.0
	 */
	private function set_columns() {
		foreach ( $this->get_settings() as $name => $data ) {
			$data['name'] = $name;
			$column = $this->create_column( $data );

			if ( $column ) {
				$this->register_column( $column );
			}
		}

		// Nothing stored. Use WP default columns.
		if ( null === $this->columns ) {
			foreach ( $this->get_original_columns() as $type => $label ) {
				if ( $column = $this->create_column( [ 'type' => $type, 'original' => true ] ) ) {
					$this->register_column( $column );
				}
			}
		}

		if ( null === $this->columns ) {
			$this->columns = [];
		}
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	public function set_preferences( array $preferences ) {
		$this->preferences = apply_filters( 'ac/list_screen/preferences', $preferences, $this );

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_preferences() {
		return $this->preferences;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_preference( $key ) {
		if ( ! isset( $this->preferences[ $key ] ) ) {
			return null;
		}

		return $this->preferences[ $key ];
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

		if ( apply_filters( 'ac/column/value/sanitize', true, $column, $id ) ) {
			$value = ( new Kses() )->sanitize( $value );
		}

		// You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
		if ( $column->is_original() && ac_helper()->string->is_empty( $value ) ) {
			return $original_value;
		}

		/**
		 * Column display value
		 *
		 * @param string $value  Column display value
		 * @param int    $id     Object ID
		 * @param Column $column Column object
		 *
		 * @since 3.0
		 */
		$value = apply_filters( 'ac/column/value', $value, $id, $column );

		return $value;
	}

	/**
	 * @param string $namespace Namespace from the current path
	 *
	 * Can be removed after a short while from 6.0, e.g. 6.1 or after a few months, as this very custom to begin with
	 */
	public function register_column_types_from_dir( $namespace ) {
		_deprecated_function( __FUNCTION__, '6.0' );
	}

	/**
	 * @param array $columns
	 *
	 * @deprecated 4.0
	 */
	public function save_default_headings( $columns ) {
		_deprecated_function( __METHOD__, '4.0', 'AC\DefaultColumns::update( $key, $columns )' );

		( new DefaultColumnsRepository() )->update( $this->get_key(), $columns && is_array( $columns ) ? $columns : [] );
	}

	/**
	 * @return array
	 * @deprecated 4.0
	 */
	public function get_stored_default_headings() {
		_deprecated_function( __METHOD__, '4.0', 'AC\DefaultColumnsRepository()::get( $key )' );

		return ( new DefaultColumnsRepository() )->get( $this->get_key() );
	}

	/**
	 * @return void
	 */
	public function delete_default_headings() {
		_deprecated_function( __METHOD__, '4.0', 'AC\DefaultColumnsRepository()::delete( $key )' );

		( new DefaultColumnsRepository() )->delete( $this->get_key() );
	}

	/**
	 * @return bool
	 * @deprecated 4.0
	 */
	public function delete() {
		_deprecated_function( __METHOD__, '4.0' );

		return false;
	}

	/**
	 * Get default column headers
	 * @return array
	 * @deprecated 4.0
	 */
	public function get_default_column_headers() {
		_deprecated_function( __METHOD__, '4.0' );

		return [];
	}

	/**
	 * Clears columns variable, which allow it to be repopulated by get_columns().
	 * @deprecated 4.0
	 * @since      2.5
	 */
	public function reset() {
		_deprecated_function( __METHOD__, '4.0' );
	}

	/**
	 * @deprecated 4.0
	 */
	public function populate_settings() {
		_deprecated_function( __METHOD__, '4.0' );
	}

	/**
	 * Reset original columns
	 * @deprecated 4.0
	 */
	public function reset_original_columns() {
		_deprecated_function( __METHOD__, '4.0' );

		$this->original_columns = null;
	}

	/**
	 * Store column data
	 *
	 * @param array $column_data
	 *
	 * @deprecated 4.0
	 */
	public function store( $column_data ) {
		_deprecated_function( __METHOD__, '4.0' );
	}

	/**
	 * @param array $columns
	 *
	 * @deprecated 4.3
	 */
	public function set_original_columns( $columns ) {
		_deprecated_function( __METHOD__, '4.3' );

		$this->original_columns = (array) $columns;
	}

}