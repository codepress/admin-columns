<?php

/**
 * Storage Model
 *
 * @since 2.0.0
 */
abstract class CPAC_Storage_Model {

	/**
	 * Label
	 *
	 * @since 2.0.0
	 */
	public $label;

	/**
	 * Key
	 *
	 * @since 2.0.0
	 */
	public $key;

	/**
	 * Type
	 *
	 * @since 2.0.0
	 */
	public $type;

	/**
	 * Page
	 *
	 * @since 2.0.0
	 */
	public $page;

	/**
	 * Get default columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();

	/**
	 * Constructor
	 * @todo : REMOVE
	 * @since 2.0.0
	 */
	/*function __construct() {
		add_action( 'wp_ajax_cpac_get_column_' . $this->key, array( $this, 'ajax_get_column_html' ) );
	}*/

	/**
	 * Add Column
	 *
	 * @todo : REMOVE
	 * Adds a columns to the DOM
	 *
	 * @since 2.0.0
	 */
	/*public function ajax_get_column_html() {

		$columns = $this->get_registered_columns();

		// get column by type
		if ( isset( $_POST['type'] ) ) {

			// column is registered
			if ( isset( $columns[ $_POST['type'] ] ) ) {
		 		$column = $columns[ $_POST['type'] ];
		 	}

		 	// sometimes columns that have been set by plugins have not yet been registered.
		 	// in these cases we create a new column instance for them.
		 	elseif ( isset( $_POST['label'] ) ) {
		 		$column = $this->get_column_instance( $_POST['type'], $_POST['label'] );
		 	}
		}

		// get first column
		else
			$column = array_shift( $columns );

		$column->display();
		exit;
	}*/

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @return bool
	 */
	public function is_menu_type_current( $first_posttpe ) {

		// display the page that was being viewed before saving
		if ( ! empty( $_REQUEST['cpac_key'] ) ) {
			if ( $_REQUEST['cpac_key'] == $this->key ) {
				return true;
			}

		// settings page has not yet been saved
		} elseif ( $first_posttpe == $this->key ) {
			return true;
		}

		return false;
	}

	/**
     * Get Meta Keys
     *
	 * @since 2.0.0.0
	 *
	 * @return array
     */
    public function get_meta_keys( $add_hidden_meta = false ) {
        global $wpdb;

        $keys = array();

		$fields = $this->get_meta();

		if ( is_wp_error( $fields ) || empty( $fields ) )
			$keys = false;

		if ( $fields ) {
			foreach ( $fields as $field ) {

				// give hidden fields a prefix for identifaction
				if ( $add_hidden_meta && "_" == substr( $field[0], 0, 1 ) ) {
					$keys[] = 'cpachidden' . $field[0];
				}

				// non hidden fields are saved as is
				elseif ( "_" != substr( $field[0], 0, 1 ) ) {
					$keys[] = $field[0];
				}
			}
		}

		return apply_filters( "cpac_get_meta_keys_{$this->key}", $keys, $this );
    }

	/**
	 * Add hidden meta - Utility Method
	 *
	 * @since 2.0.0
	 *
	 * @param array $fields Custom fields.
	 * @return array Custom fields.
	 */
	protected function add_hidden_meta( $fields ) {
		if ( ! $fields )
			return false;

		$combined_fields = array();

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identifaction
			if ( "_" == substr( $field[0], 0, 1 ) ) {
				$combined_fields[] = 'cpachidden'.$field[0];
			}

			// non hidden fields are saved as is
			elseif ( "_" != substr( $field[0], 0, 1 ) ) {
				$combined_fields[] = $field[0];
			}
		}

		if ( empty( $combined_fields ) )
			return false;

		return $combined_fields;
	}

	/**
	 * Restore
	 *
	 * @since 2.0.0
	 */
	function restore() {

		delete_option( "cpac_options_{$this->key}" );

		cpac_admin_message( __( 'Settings succesfully restored.',  'cpac' ), 'updated' );
	}

	/**
	 * Store
	 *
	 * @since 2.0.0
	 */
	function store( $columns = '' ) {

		if ( ! empty( $_POST['columns'] ) )
			$columns = array_filter( $_POST['columns'] );

		if( ! $columns ) {
			cpac_admin_message( __( 'No columns settings available.',  'cpac' ), 'error' );
			return false;
		}

		// sanitize user inputs
		foreach ( $columns as $name => $options ) {
			if ( $_column = $this->get_column_by_name( $name ) ) {
				$columns[ $name ] = $_column->sanitize( $options );
			}
		}

		// store columns
		$result = update_option( "cpac_options_{$this->key}", $columns );

		// store default WP columns
		$result_default = update_option( "cpac_options_{$this->key}_default", array_keys( $this->get_default_columns() ) );

		// error
		if( ! $result && ! $result_default ) {
			cpac_admin_message( sprintf( __( 'You are trying to store the same settings for %s.', 'cpac' ), "<strong>{$this->label}</strong>" ), 'error' );
			return false;
		}

		cpac_admin_message( sprintf( __( 'Settings for %s updated succesfully.',  'cpac' ), "<strong>{$this->label}</strong>" ), 'updated' );

		return true;
	}

	/**
	 * Get custom columns
	 *
	 * Goes through all files in 'classes/column' and includes each file.
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Classnames
	 */
	function get_custom_columns() {

		$columns = get_transient( 'cpac_custom_columns' );

		// An empty transient means we need to rebuild rebuild it.
		// Get custom columns from the classes/column directory.
		if ( empty( $columns[ $this->type ] ) ) {

			$file = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( CPAC_DIR . 'classes/column' ) );

			while( $file->valid() ) {

				if ( ! $file->isDot() && $this->type == $file->getSubPath() ) {

					// build classname from filename
					$type = ucfirst( $file->getSubPath() );
					$name = implode( '_', array_map( 'ucfirst', explode( '-', basename( $file->key(), '.php' ) ) ) );

					$columns[ $this->type ]["CPAC_Column_{$type}_{$name}"] = $file->key();
				}

				$file->next();
			}

			set_transient( 'cpac_custom_columns', $columns );
		}

		if ( empty( $columns[ $this->type ] ) )
			return array();

		// hooks for adding custom columns by addons
		// $columns classname | include_path
		$columns = apply_filters( "cpac_custom_columns_{$this->type}", $columns[ $this->type ], $this );

		return $columns;
	}

	/**
	 * Get column instance by name and label
	 *
	 * @since 2.0.0
	 */
	function get_column_instance( $column_name, $label ) {

		// create column instance
		$column = new CPAC_Column( $this );

		$column
			->set_properties( 'type', $column_name )
			->set_properties( 'name', $column_name )
			->set_properties( 'label', $label )
			->set_properties( 'is_cloneable', false )
			->set_options( 'label', $label )
			->set_options( 'state', 'on' );

		// Hide Label when it contains HTML elements
		if( strlen( $label ) != strlen( strip_tags( $label ) ) ) {
			$column->set_properties( 'hide_label', true );
		}

		// Label empty? Use it's column_name
		if ( ! $label ) {
			$column->set_properties( 'label', ucfirst( $column_name ) );
		}

		return $column;
	}

	/**
	 * Get default registered columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_default_registered_columns() {

		$columns = array();

		// Default columns
		foreach ( $this->get_default_columns() as $column_name => $label ) {

			// checkboxes are mandatory
			if ( 'cb' == $column_name )
				continue;

			$column = $this->get_column_instance( $column_name, $label );

			$columns[ $column->properties->name ] = $column;
		}

		return $columns;
	}

	/**
	 * Get custom registered columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_custom_registered_columns() {

		$columns = array();

		foreach ( $this->get_custom_columns() as $classname => $path ) {

			include_once $path;

			if ( ! class_exists( $classname ) )
				continue;

			$column = new $classname( $this );

			// exlude columns that are not registered based on conditional logic within the child column
			if ( ! $column->properties->is_registered )
				continue;

			$columns[ $column->properties->type ] = $column;
		}

		do_action( "cpac_get_columns", $columns );
		do_action( "cpac_get_columns_{$this->key}", $columns );

		return $columns;
	}

	/**
	 * Get registered columns
	 *
	 * @todo: REMOVE
	 * @since 2.0.0
	 *
	 * @return array Column Type | Column Instance
	 */
	function get_registered_columns() {

		return array_merge( $this->get_custom_registered_columns(), $this->get_default_registered_columns() );
	}

	/**
	 * Get default column options from DB
	 *
	 * @since 1.0.0
	 *
	 * @paran string $key
	 * @return array Column options
	 */
	public function get_default_stored_columns() {

		if ( ! $columns = get_option( "cpac_options_{$this->key}_default" ) )
			return array();

		return $columns;
	}

	/**
	 * Get column options from DB
	 *
	 * @since 1.0.0
	 *
	 * @paran string $key
	 * @return array Column options
	 */
	public function get_stored_columns() {

		if ( ! $columns = get_option( "cpac_options_{$this->key}" ) )
			return array();

		return $columns;
	}

	/**
	 * Get Columns
	 *
	 * @since 2.0.0
	 */
	function get_columns() {

		$columns = array();

		// get columns
		$default_columns 	= $this->get_default_registered_columns();
		$registered_columns = array_merge( $this->get_custom_registered_columns(), $default_columns );

		// Stored columns
		if ( $stored_columns = $this->get_stored_columns() ) {

			$stored_names = array();

			foreach ( $stored_columns as $name => $options ) {

				if ( ! isset( $options['type'] ) )
					continue;

				// remember which types has been used, so we can filter them later
				$stored_names[] = $name;

				// In case of a disabled plugin, we will skip column.
				// This means the stored column type is not available anymore.
				if ( ! in_array( $options['type'], array_keys( $registered_columns ) ) )
					continue;

				// create clone
				$column = clone $registered_columns[ $options['type'] ];

				// add an clone number which defines the instance
				$column->set_clone( $options['clone'] );

				// repopulate the options, so they contains the right stored options
				$column->populate_options();

				$columns[ $name ] = $column;
			}

			// In case of an enabled plugin, we will add that column.
			// When $diff contains items, it means a default column has not been stored.
			if( $diff = array_diff( array_keys( $default_columns ), $this->get_default_stored_columns() ) ) {
				foreach( $diff as $name ) {
					if ( isset( $registered_columns[ $name ] ) ) {
						$columns[ $name ] = clone $registered_columns[ $name ];
					}
				}
			}
		}

		// When nothing has been saved yet, we return the default WP columns.
		else {

			foreach( array_keys( $default_columns ) as $name ) {
				if( isset( $registered_columns[ $name ] ) ) {
					$columns[ $name ] = clone $registered_columns[ $name ];
				}
			}
		}

		return $columns;
	}

	/**
	 * Get Column by name
	 *
	 * @since 2.0.0
	 */
	function get_column_by_name( $name ) {

		$columns = $this->get_columns();

		if ( ! isset( $columns[ $name ] ) )
			return false;

		return $columns[ $name ];
	}

	/**
	 * Add Headings
	 *
	 * @since 2.0.0
	 */
	function add_headings( $columns ) {

		global $pagenow;

		// only add headings on overview screens, to prevent deactivating columns in the Storage Model.
		if ( 'admin.php' == $pagenow )
			return $columns;

		// stored columns exists?
		if ( ! $stored_columns = get_option( "cpac_options_{$this->key}" ) )
			return $columns;

		// build the headings
		$column_headings = array();

		// add mandatory checkbox
		if ( isset( $columns['cb'] ) )
			$column_headings['cb'] = $columns['cb'];

		// add active stored headings
		foreach( $stored_columns as $column_name => $options ) {

			// label needs stripslashes() for HTML tagged labels, like icons and checkboxes
			$column_headings[ $column_name ] = stripslashes( $options['label'] );
		}

		// Add 3rd party columns that have ( or could ) not be stored.
		// For example when a plugin has been activated after storing column settings.
		// When $diff contains items, it means an available column has not been stored.
		if ( $diff = array_diff( array_keys( $columns ), $this->get_default_stored_columns() ) ) {
			foreach ( $diff as $column_name ) {
				$column_headings[ $column_name ] = $columns[ $column_name ];
			}
		}

		// Remove 3rd party columns that have been deactivated.
		// While the column settings have not been stored yet.
		// When $diff contains items, it means stored columns are not available anymore.
		if ( $diff = array_diff( array_keys( $stored_columns ), $this->get_default_stored_columns() ) ) {
			foreach ( $diff as $column_name ) {
				unset( $column_headings[ $column_name ] );
			}
		}
		/*if ( $diff = array_diff( array_keys( $stored_columns ), array_keys( $this->get_registered_columns() ) ) ) {
			foreach ( $diff as $column_name ) {
				unset( $column_headings[ $column_name ] );
			}
		}*/

		return $column_headings;
	}
}




