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
	 * Identifier for Storage Model; Posttype etc.
	 *
	 * @since 2.0.0
	 */
	public $key;

	/**
	 * Type
	 *
	 * Type of storage model; Post, Media, User or Comments
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
	 * Custom Column
	 *
	 * @since 2.0.1
	 */
	protected $custom_columns;

	/**
	 * Columns
	 *
	 * @since 2.0.1
	 */
	protected $columns;

	/**
	 * Get default columns
	 *
	 * @since 2.0.0
	 *
	 * @return array Column Name | Column Label
	 */
	abstract function get_default_columns();

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
	 * @since 2.0.0
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

		return apply_filters( "cac/meta_keys/storage_key={$this->key}", $keys, $this );
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

		cpac_admin_message( "<strong>{$this->label}</strong> " . __( 'settings succesfully restored.',  'cpac' ), 'updated' );
	}

	/**
	 * Store
	 *
	 * @since 2.0.0
	 */
	function store( $columns = '' ) {

		if ( ! empty( $_POST[ $this->key ] ) )
			$columns = array_filter( $_POST[ $this->key ] );

		if( ! $columns ) {
			cpac_admin_message( __( 'No columns settings available.',  'cpac' ), 'error' );
			return false;
		}

		// sanitize user inputs
		foreach ( $columns as $name => $options ) {
			if ( $_column = $this->get_column_by_name( $name ) ) {
				$columns[ $name ] = $_column->sanitize_storage( $options );
			}

			// Santize Label: Need to replace the url for images etc, so we do not have url problem on exports
			// this can not be done by CPAC_Column::sanitize_storage() because 3rd party plugins are not available there
			$columns[ $name ]['label'] = stripslashes( str_replace( site_url(), '[cpac_site_url]', trim( $options['label'] ) ) );
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
	 * Set custom columns
	 *
	 * Goes through all files in 'classes/column' and includes each file.
	 *
	 * since 2.0.1
	 *
	 * @return array Column Classnames | Filepaths
	 */
	protected function set_custom_columns() {
		$columns  = array(
			'CPAC_Column_Custom_Field' => CPAC_DIR . 'classes/column/custom-field.php'
		);

		$iterator = new DirectoryIterator( CPAC_DIR . 'classes/column/' . $this->type );

		foreach( $iterator as $leaf ) {

			// skip files that start with . ( e.g: .SVN .DS_STORE )
			if ( $leaf->isDot() || $leaf->isDir() || substr( $leaf->getFilename(), 0, 1 ) === '.' ) continue;

			// build classname from filename
			$class_name = implode( '_', array_map( 'ucfirst', explode( '-', basename( $leaf->getFilename(), '.php' ) ) ) );

			$columns[ 'CPAC_Column_' . ucfirst( $this->type ) . '_'  . $class_name  ] = $leaf->getPathname();
		}

		$this->custom_columns = apply_filters( 'cac/columns/custom/type=' . $this->type, $columns, $this );
	}

	/**
	 * Create column instance
	 *
	 * @since 2.0.0
	 *
	 * @param $column_name
	 * @param $label
	 * @return object CPAC_Column
	 */
	public function create_column_instance( $column_name, $label ) {

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

			$column = $this->create_column_instance( $column_name, $label );

			$columns[ $column->properties->name ] = $column;
		}

		do_action( "cac/columns/registered/default", $columns );
		do_action( "cac/columns/registered/default/storage_key={$this->key}", $columns );

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

		foreach ( $this->custom_columns as $classname => $path ) {

			include_once $path;

			if ( ! class_exists( $classname ) )
				continue;

			$column = new $classname( $this );

			// exlude columns that are not registered based on conditional logic within the child column
			if ( ! $column->properties->is_registered )
				continue;

			$columns[ $column->properties->type ] = $column;
		}

		do_action( "cac/columns/registered/custom", $columns );
		do_action( "cac/columns/registered/custom/storage_key={$this->key}", $columns );

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
	 * Set Columns
	 *
	 * @since 2.0.2
	 */
	function set_columns() {
		$this->columns = $this->get_columns();
	}

	/**
	 * Get Columns
	 *
	 * @since 2.0.0
	 */
	function get_columns() {

		// used for third party plugin support
		do_action( 'cac/get_columns', $this );

		$columns = array();

		// get columns
		$default_columns 	= $this->get_default_registered_columns();

		// @todo check if this solves the issue with not displaying value when using "manage_{$post_type}_posts_columns" at CPAC_Storage_Model_Post
		$registered_columns = array_merge( $default_columns, $this->get_custom_registered_columns() );

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

				// re-sanitize label
				$column->sanitize_label();

				$columns[ $name ] = $column;
			}

			// In case of an enabled plugin, we will add that column.
			// When $diff contains items, it means a default column has not been stored.
			if( $diff = array_diff( array_keys( $default_columns ), $this->get_default_stored_columns() ) ) {
				foreach( $diff as $name ) {

					// because of the filter "manage_{$post_type}_posts_columns" the columns
					// that are being added by CPAC will also appear in the $default_columns.
					// this will filter out those columns.
					if ( isset( $columns[ $name ] ) ) continue;

					// is the column registered?
					if ( ! isset( $registered_columns[ $name ] ) ) continue;

					$columns[ $name ] = clone $registered_columns[ $name ];
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

		// @todo check if no issues come up by using $this->columns
		//$columns = $this->get_columns();
		//if ( ! isset( $columns[ $name ] ) )
		//	return false;*/

		if ( ! isset( $this->columns[ $name ] ) )
			return false;

		return $this->columns[ $name ];
	}

	/**
	 * Add Headings
	 *
	 * @since 2.0.0
	 */
	function add_headings( $columns ) {

		global $pagenow;

		// only add headings on overview screens, to prevent deactivating columns in the Storage Model.
		if ( ! in_array( $pagenow, array( 'edit.php', 'users.php', 'edit-comments.php', 'upload.php', 'link-manager.php' ) ) )
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
			$label = apply_filters( 'cac/headings/label', stripslashes( $options['label'] ), $column_name, $options, $this );

			// maybe need site_url replacement
			$label = str_replace( '[cpac_site_url]', site_url(), $label );

			$column_headings[ $column_name ] = $label;
		}

		// Add 3rd party columns that have ( or could ) not been stored.
		// For example when a plugin has been activated after storing column settings.
		// When $diff contains items, it means an available column has not been stored.
		if ( $diff = array_diff( array_keys( $columns ), $this->get_default_stored_columns() ) ) {
			foreach ( $diff as $column_name ) {

				$column_headings[ $column_name ] = $columns[ $column_name ];
			}
		}

		// Remove 3rd party columns that have been deactivated.
		// While the column settings have not been stored yet.
		// When $diff contains items, it means the default stored columns are not available anymore.
		// @todo: check if working properly. cuurently issues with woocommerce columns
		/*
		if ( $diff = array_diff( $this->get_default_stored_columns(), array_keys( $columns ) ) ) {
			//echo '<pre>'; print_r( $diff ); echo '</pre>';
			foreach ( $diff as $column_name ) {
				if( isset( $column_headings[ $column_name ] ) )
					unset( $column_headings[ $column_name ] );
			}
		}*/



		return $column_headings;
	}

	/**
	 * Get screen link
	 *
	 * @since 2.0.0
	 *
	 * @return string Link
	 */
	protected function get_screen_link() {

		return admin_url( $this->page . '.php' );
	}

	/**
	 * Screen Link
	 *
	 * @since 2.0.0
	 */
	function screen_link() {

		echo '<a href="' . $this->get_screen_link() . '" class="add-new-h2">' . __('View', 'cpac') . '</a>';
	}

	/**
	 * Screen Link
	 *
	 * @since 2.0.0
	 */
	function get_edit_link() {

		return add_query_arg( array( 'page' => 'codepress-admin-columns', 'cpac_key' => $this->key ), admin_url( 'options-general.php' ) );
	}
}
