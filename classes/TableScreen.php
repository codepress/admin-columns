<?php

final class AC_TableScreen {

	/**
	 * @var array $column_headings
	 */
	private $column_headings = array();

	/**
	 * @var AC_ListScreen $list_screen
	 */
	private $current_list_screen;

	public function __construct() {
		add_action( 'current_screen', array( $this, 'load_list_screen' ) );
		add_action( 'admin_init', array( $this, 'load_list_screen_doing_ajax' ) );
		add_action( 'admin_head', array( $this, 'admin_head_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 11 );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20 );
	}

	/**
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
	 *
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {
		if ( $this->current_list_screen ) {
			if ( ! $this->current_list_screen->get_column_by_name( $default ) ) {
				$default = key( $this->current_list_screen->get_columns() );
			}

			// If actions column is present, set it as primary
			foreach ( $this->current_list_screen->get_columns() as $column ) {
				if ( 'column-actions' == $column->get_type() ) {
					$default = $column->get_name();
				}
			};

			// Set inline edit data if the default column (title) is not present
			if ( $this->current_list_screen instanceof AC_ListScreen_Post && 'title' !== $default ) {
				add_filter( 'page_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
				add_filter( 'post_row_actions', array( $this, 'set_inline_edit_data' ), 20, 2 );
			}

			// Remove inline edit action if the default column (author) is not present
			if ( $this->current_list_screen instanceof AC_ListScreen_Comment && 'comment' !== $default ) {
				add_filter( 'comment_row_actions', array( $this, 'remove_quick_edit_from_actions' ), 20, 2 );
			}

		}

		return $default;
	}

	/**
	 * Sets the inline data when the title columns is not present on a AC_ListScreen_Post screen
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 */
	public function set_inline_edit_data( $actions, $post ) {
		get_inline_data( $post );

		return $actions;
	}

	/**
	 * Remove quick edit from actions
	 *
	 * @param array $actions
	 */
	public function remove_quick_edit_from_actions( $actions ) {
		unset( $actions['quickedit'] );

		return $actions;
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
		if ( $this->current_list_screen ) {
			$classes .= " ac-" . $this->current_list_screen->get_key();
		}

		return $classes;
	}

	/**
	 * @since 2.2.4
	 */
	public function admin_scripts() {
		if ( ! $this->current_list_screen ) {
			return;
		}

		// Tooltip
		wp_register_script( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".js", array( 'jquery' ), AC()->get_version() );
		wp_enqueue_style( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );

		// Main
		wp_enqueue_script( 'ac-table', AC()->get_plugin_url() . "assets/js/table" . AC()->minified() . ".js", array( 'jquery', 'jquery-qtip2' ), AC()->get_version() );
		wp_enqueue_style( 'ac-table', AC()->get_plugin_url() . "assets/css/table" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );

		wp_localize_script( 'ac-table', 'AC', array(
				'list_screen'  => $this->current_list_screen->get_key(),
				'layout'       => $this->current_list_screen->get_layout_id(),
				'column_types' => $this->get_column_types_mapping( $this->current_list_screen ),
				'ajax_nonce'   => wp_create_nonce( 'ac-ajax' ),
				'table_id'     => $this->current_list_screen->get_table_attr_id(),
			)
		);

		/**
		 * @param AC_ListScreen $list_screen
		 */
		do_action( 'ac/table_scripts', $this->current_list_screen );

		// Column specific scripts
		foreach ( $this->current_list_screen->get_columns() as $column ) {
			$column->scripts();
		}
	}

	/**
	 * @param AC_ListScreen $list_screen
	 *
	 * @return array
	 */
	private function get_column_types_mapping( AC_ListScreen $list_screen ) {
		$types = array();
		foreach ( $list_screen->get_columns() as $column ) {
			$types[ $column->get_name() ] = $column->get_type();
		}

		return $types;
	}

	public function get_current_list_screen() {
		return $this->current_list_screen;
	}

	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	public function admin_head_scripts() {
		if ( ! $this->current_list_screen ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;

		foreach ( $this->current_list_screen->get_columns() as $column ) {
			$width = $column->get_setting( 'width' );

			if ( $width->get_value() ) {
				$css_column_width .= ".ac-" . $this->current_list_screen->get_key() . " .wrap table th.column-" . $column->get_name() . " { width: " . implode( $width->get_values() ) . " !important; }";
			}
		}

		if ( $css_column_width ) : ?>
            <style>
                <?php echo $css_column_width; ?>
            </style>
			<?php
		endif;

		/* @var AC_Admin_Page_Settings $settings */
		$settings = AC()->admin()->get_page( 'settings' );

		// JS: Edit button
		if ( AC()->user_can_manage_admin_columns() && $settings->show_edit_button() ) : ?>
            <script>
				jQuery( document ).ready( function() {
					jQuery( '.tablenav.top .actions:last' ).append( '<a href="<?php echo esc_url( $this->current_list_screen->get_edit_link() ); ?>" class="cpac-edit add-new-h2"><?php _e( 'Edit columns', 'codepress-admin-columns' ); ?></a>' );
				} );
            </script>
			<?php
		endif;

		/**
		 * Add header scripts that only apply to column screens.
		 * @since 2.3.5
		 *
		 * @param object CPAC Main Class
		 */
		do_action( 'ac/admin_head', $this->current_list_screen, $this );
	}

	/**
	 * Load current list screen
     * @param WP_Screen $current_screen
	 */
	public function load_list_screen( $current_screen ) {
	    if ( $list_screen = AC()->get_list_screen_by_wpscreen( $current_screen ) ) {
		    $this->set_current_list_screen( $list_screen );
        }
	}

	/**
	 * Runs when doing native WordPress ajax calls, like quick edit.
	 *
	 * @param WP_Screen $current_screen
	 */
	public function load_list_screen_doing_ajax() {
		$this->set_current_list_screen( AC()->get_list_screen( $this->get_list_screen_when_doing_ajax() ) );
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	private function set_current_list_screen( $list_screen ) {
		if ( ! $list_screen ) {
			return;
		}

		$this->current_list_screen = $list_screen;

		// Init Values
		$list_screen->set_manage_value_callback();

		// Init Headings
		// Filter is located in get_column_headers()
		add_filter( "manage_" . $list_screen->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 );

		// Stores the row actions for each table. Only used by the AC_Column_Actions column.
		ac_action_column_helper();

		// @since NEWVERSION
		do_action( 'ac/table/list_screen', $list_screen );
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

			if ( 'cpac' === filter_input( INPUT_POST, 'plugin_id' ) ) {
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

		if ( ! $this->current_list_screen ) {
			return $columns;
		}

		// Store default headings
		if ( ! $this->is_doing_ajax() ) {
			$this->current_list_screen->save_default_headings( $columns );
		}

		// Run once
		if ( $this->column_headings ) {
			return $this->column_headings;
		}

		// Nothing stored. Show default columns on screen.
		if ( ! $this->current_list_screen->get_settings() ) {
			return $columns;
		}

		// Add mandatory checkbox
		if ( isset( $columns['cb'] ) ) {
			$this->column_headings['cb'] = $columns['cb'];
		}

		foreach ( $this->current_list_screen->get_columns() as $column ) {

			/**
			 * @since NEWVERSION
			 *
			 * @param string    $label
			 * @param AC_Column $column
			 */
			$label = apply_filters( 'ac/headings/label', $column->get_setting( 'label' )->get_value(), $column );

			$this->column_headings[ $column->get_name() ] = $label;
		}

		return apply_filters( 'ac/headings', $this->column_headings, $this->current_list_screen );
	}

}