<?php

final class AC_ListScreenManager {

	/**
	 * @var array $column_headings
	 */
	private $column_headings = array();

	/**
	 * @var AC_ListScreenAbstract $list_screen
	 */
	private $list_screen;

	public function __construct() {
		add_action( 'current_screen', array( $this, 'load_list_screen' ) );
		add_action( 'admin_init', array( $this, 'load_list_screen_doing_ajax' ) );
		add_action( 'admin_head', array( $this, 'admin_head_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 11 );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20, 1 );
	}

	/**
	 * @return AC_ListScreenAbstract
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
	 *
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {
		if ( $this->list_screen ) {
			if ( ! $this->list_screen->get_column_by_name( $default ) ) {
				$default = key( $this->list_screen->get_columns() );
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
		if ( $this->list_screen ) {
			$classes .= " cp-" . $this->list_screen->get_key();
		}

		return $classes;
	}

	/**
	 * @since 2.2.4
	 */
	public function admin_scripts() {
		if ( ! $this->list_screen ) {
			return;
		}

		wp_register_script( 'cpac-admin-columns', AC()->get_plugin_url() . "assets/js/list-screen" . AC()->minified() . ".js", array( 'jquery', 'jquery-qtip2' ), AC()->get_version() );
		wp_register_script( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".js", array( 'jquery' ), AC()->get_version() );
		wp_register_style( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );
		wp_register_style( 'ac-columns', AC()->get_plugin_url() . "assets/css/list-screen" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );

		wp_enqueue_script( 'cpac-admin-columns' );
		wp_enqueue_style( 'jquery-qtip2' );
		wp_enqueue_style( 'ac-columns' );

		/**
		 * @param AC_ListScreenAbstract $list_screen
		 */
		do_action( 'ac/enqueue_listings_scripts', $this->list_screen );
	}

	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	public function admin_head_scripts() {
		if ( ! $this->list_screen ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;

		foreach ( $this->list_screen->get_columns() as $column ) {
			$width = $column->get_setting( 'width' );

			if ( $width->get_value() ) {
				$css_column_width .= ".cp-" . $this->list_screen->get_key() . " .wrap table th.column-" . $column->get_name() . " { width: " . implode( $width->get_values() ) . " !important; }";
			}

			// Load external scripts
			// TODO: remove? update doc
			//$column->scripts();
		}
		?>
		<?php if ( $css_column_width ) : ?>
			<style>
				<?php echo $css_column_width; ?>
			</style>
		<?php endif; ?>
		<?php

		// JS: Edit button
		if ( current_user_can( 'manage_admin_columns' ) && AC()->settings()->get_settings_tab()->show_edit_button() ) : ?>
			<script>
				jQuery( document ).ready( function() {
					jQuery( '.tablenav.top .actions:last' ).append( '<a href="<?php echo esc_url( $this->list_screen->get_edit_link() ); ?>" class="cpac-edit add-new-h2"><?php _e( 'Edit columns', 'codepress-admin-columns' ); ?></a>' );
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
		do_action( 'cac/admin_head', $this->list_screen, $this );
	}

	/**
	 * Load current list screen
	 */
	public function load_list_screen() {
		foreach ( AC()->get_list_screens() as $list_screen ) {
			if ( $list_screen->is_current_screen() ) {
				$this->init_list_screen( $list_screen );
			}
		}
	}

	/**
	 * @param WP_Screen $current_screen
	 */
	public function load_list_screen_doing_ajax() {
		if ( $list_screen = AC()->get_list_screen( $this->get_list_screen_when_doing_ajax() ) ) {
			$this->init_list_screen( $list_screen );
		}
	}

	/**
	 * @param AC_ListScreenAbstract $list_screen
	 */
	private function init_list_screen( AC_ListScreenAbstract $list_screen ) {
		$this->list_screen = $list_screen;

		// Init Values
		$list_screen->set_manage_value_callback();

		// Init Headings
		// Filter is located in get_column_headers()
		add_filter( "manage_" . $list_screen->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 );

		// Stores the row actions for each table. Only used by the AC_Column_ActionsAbstract column.
		ac_action_column_helper();

		// @since NEWVERSION
		do_action( 'ac/listings/list_screen', $list_screen );
	}

	/**
	 * @return bool True when doing ajax
	 */
	private function is_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Is WordPress doing ajax
	 *
	 * @since 2.5
	 */
	public function get_list_screen_when_doing_ajax() {
		$list_screen = false;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			if ( 'cpac' == filter_input( INPUT_GET, 'plugin_id' ) ) {
				$list_screen = filter_input( INPUT_GET, 'list_screen' );
			}

			if ( 'cpac' == filter_input( INPUT_POST, 'plugin_id' ) ) {
				$list_screen = filter_input( INPUT_POST, 'list_screen' );
			}

			// Default WordPress Ajax calls
			switch ( filter_input( INPUT_POST, 'action' ) ) {

				// Quick edit
				case 'inline-save' :
					$list_screen = filter_input( INPUT_POST, 'post_type' );
					break;

				// Adding term
				case 'add-tag' :

					// Quick edit term
				case 'inline-save-tax' :
					$list_screen = 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' );
					break;

				// Quick edit comment
				case 'edit-comment' :

					// Inline reply on comment
				case 'replyto-comment' :
					$list_screen = 'wp-comments';
					break;
				case 'cacie_column_save' :
					$list_screen = filter_input( INPUT_POST, 'list_screen' );
					break;
			}
		}

		return $list_screen;
	}

	/**
	 * @since 2.0
	 */
	public function add_headings( $columns ) {

		if ( empty( $columns ) ) {
			return $columns;
		}

		if ( ! $this->list_screen ) {
			return $columns;
		}

		$settings = $this->list_screen->settings();

		// Store default headings
		if ( ! $this->is_doing_ajax() ) {
			$settings->save_default_headings( $columns );
		}

		// Run once
		if ( $this->column_headings ) {
			return $this->column_headings;
		}

		// Nothing stored. Show default columns on screen.
		if ( ! $settings->get_settings() ) {
			return $columns;
		}

		// Add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->column_headings['cb'] = $columns['cb'];
		}

		// Flush cache. In case any columns are deactivated after saving them.
		$this->list_screen->flush_columns();

		foreach ( $this->list_screen->get_columns() as $column ) {

			// @deprecated NEWVERSION
			//$label = apply_filters( 'cac/headings/label', $column->settings()->get_value( 'label' ), $column->get_name(), $column->settings()->get_options(), $this );

			$this->column_headings[ $column->get_name() ] = $column->get_setting( 'label' )->get_value();
		}

		return $this->column_headings;
	}

}