<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_ListingsScreen {

	/**
	 * @var array $column_headings
	 */
	private $column_headings;

	/**
	 * @var AC_StorageModel $storage_model
	 */
	private $storage_model;

	public function __construct() {
		add_action( 'current_screen', array( $this, 'load_storage_model' ) );
		add_action( 'admin_init', array( $this, 'load_storage_model_doing_ajax' ) );
		add_action( 'admin_head', array( $this, 'admin_head_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 11 );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20, 1 );
	}

	/**
	 * @return AC_StorageModel
	 */
	public function get_storage_model() {
		return $this->storage_model;
	}

	/**
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
	 *
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {
		if ( $this->storage_model ) {
			if ( ! $this->storage_model->get_column_by_name( $default ) ) {
				$default = key( $this->storage_model->columns()->get_columns() );
			}
		}

		return $default;
	}

	/**
	 * Adds a body class which is used to set individual column widths
	 *
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 *
	 * @return string
	 */
	public function admin_class( $classes ) {
		if ( $this->storage_model ) {
			$classes .= " cp-{$this->storage_model->key}";
		}

		return $classes;
	}

	/**
	 * @since 2.2.4
	 */
	public function admin_scripts() {
		if ( ! $this->storage_model ) {
			return;
		}

		$minified = AC()->minified();
		$url = AC()->get_plugin_url();
		$version = AC()->get_version();

		wp_register_script( 'cpac-admin-columns', $url . "assets/js/admin-columns{$minified}.js", array( 'jquery', 'jquery-qtip2' ), $version );
		wp_register_script( 'jquery-qtip2', $url . "external/qtip2/jquery.qtip{$minified}.js", array( 'jquery' ), $version );
		wp_register_style( 'jquery-qtip2', $url . "external/qtip2/jquery.qtip{$minified}.css", array(), $version, 'all' );
		wp_register_style( 'cpac-columns', $url . "assets/css/column{$minified}.css", array(), $version, 'all' );

		wp_enqueue_script( 'cpac-admin-columns' );
		wp_enqueue_style( 'jquery-qtip2' );
		wp_enqueue_style( 'cpac-columns' );

		/**
		 * @param AC_StorageModel $storage_model
		 */
		do_action( 'ac/enqueue_listings_scripts', $this->storage_model );
	}

	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	public function admin_head_scripts() {
		if ( ! $this->storage_model ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;
		foreach ( $this->storage_model->columns()->get_columns() as $column ) {
			if ( $width = $column->get_width() ) {
				$css_column_width .= ".cp-" . $this->storage_model->get_key() . " .wrap table th.column-" . $column->get_name() . " { width: " . $width . $column->get_width_unit() . " !important; }";
			}

			// Load external scripts
			$column->scripts();
		}
		?>
		<?php if ( $css_column_width ) : ?>
			<style type="text/css">
				<?php echo $css_column_width; ?>
			</style>
		<?php endif; ?>
		<?php

		// JS: Edit button
		if ( current_user_can( 'manage_admin_columns' ) && AC()->settings()->get_settings_tab()->show_edit_button() ) : ?>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					jQuery( '.tablenav.top .actions:last' ).append( '<a href="<?php echo esc_url( $this->storage_model->get_edit_link() ); ?>" class="cpac-edit add-new-h2"><?php _e( 'Edit columns', 'codepress-admin-columns' ); ?></a>' );
				} );
			</script>
		<?php endif; ?>

		<?php

		/**
		 * Add header scripts that only apply to column screens.
		 * @since 2.3.5
		 *
		 * @param object CPAC Main Class
		 */
		do_action( 'cac/admin_head', $this->storage_model, $this );
	}

	/**
	 * Load current storage model
	 */
	public function load_storage_model() {
		foreach ( AC()->get_storage_models() as $storage_model ) {
			if ( $storage_model->is_current_screen() ) {
				$this->init_storage_model( $storage_model );
			}
		}
	}

	/**
	 * @param WP_Screen $current_screen
	 */
	public function load_storage_model_doing_ajax() {
		if ( $storage_model = AC()->get_storage_model( $this->get_storage_model_when_doing_ajax() ) ) {
			$this->init_storage_model( $storage_model );
		}
	}

	/**
	 * @param AC_StorageModel $storage_model
	 */
	private function init_storage_model( AC_StorageModel $storage_model ) {

		do_action( 'cac/loaded_listings_screen', $storage_model );

		$this->storage_model = $storage_model;

		// Init Values
		$storage_model->init_manage_value();

		// Init Headings
		add_filter( "manage_" . $storage_model->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 ); // Filter is located in get_column_headers()
	}

	private function is_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Is WordPress doing ajax
	 *
	 * @since 2.5
	 */
	function get_storage_model_when_doing_ajax() {
		$storage_model = false;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			switch ( filter_input( INPUT_POST, 'action' ) ) {
				case 'inline-save' :  // Quick edit
					$storage_model = filter_input( INPUT_POST, 'post_type' );
					break;
				case 'add-tag' : // Adding term
				case 'inline-save-tax' : // Quick edit term
					$storage_model = 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' );
					break;
				case 'edit-comment' : // Quick edit comment
				case 'replyto-comment' :  // Inline reply on comment
					$storage_model = 'wp-comments';
					break;
				case 'cacie_column_save' :
					$storage_model = filter_input( INPUT_POST, 'storage_model' );
					break;
			}
		}

		return $storage_model;
	}

	/**
	 * Add Table headings
	 */
	public function add_table_headings() {
		add_filter( "manage_" . $this->storage_model->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 ); // Filter is located in get_column_headers()
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
		if ( ! get_current_screen() && ! $this->is_doing_ajax() ) {
			return $columns;
		}

		$settings = $this->storage_model->settings();

		// Stores the default columns on the listings screen
		if ( ! $this->is_doing_ajax() ) {
			$settings->store_default_headings( $columns );
		}

		// make sure we run this only once
		if ( $this->column_headings ) {
			return $this->column_headings;
		}

		$stored_columns = $this->storage_model->settings()->get_columns();

		$stored_columns = apply_filters( 'ac/listings_screen/columns', $stored_columns, $this->storage_model );

		if ( ! $stored_columns ) {
			return $columns;
		}

		$this->column_headings = array();

		// add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->column_headings['cb'] = $columns['cb'];
		}

		$this->storage_model->columns()->flush_columns(); // flush types, in case a column was deactivated
		$types = array_keys( $this->storage_model->columns()->get_column_types() );

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
			 * @param AC_StorageModel $storage_model Storage model class instance
			 */
			$label = apply_filters( 'cac/headings/label', $label, $column_name, $options, $this );
			$label = str_replace( '[cpac_site_url]', site_url(), $label );

			$this->column_headings[ $column_name ] = $label;
		}

		// Add 3rd party columns that have ( or could ) not been stored.
		// For example when a plugin has been activated after storing column settings.
		// When $diff contains items, it means an available column has not been stored.
		if ( ! $this->storage_model->is_using_php_export() && ( $diff = array_diff( array_keys( $columns ), array_keys( (array) $settings->get_default_headings() ) ) ) ) {
			foreach ( $diff as $column_name ) {
				$this->column_headings[ $column_name ] = $columns[ $column_name ];
			}
		}

		return $this->column_headings;
	}

}