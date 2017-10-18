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
		add_action( 'admin_init', array( $this, 'load_list_screen_doing_quick_edit' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20 );
		add_action( 'wp_ajax_ac_get_column_value', array( $this, 'ajax_get_column_value' ) );
	}

	/**
	 * Get column value by ajax.
	 */
	public function ajax_get_column_value() {
		check_ajax_referer( 'ac-ajax' );

		// Get ID of entry to edit
		$id = intval( filter_input( INPUT_POST, 'pk' ) );

		if ( ! $id ) {
			$this->ajax_error( __( 'Invalid item ID.', 'codepress-admin-columns' ) );
		}

		$list_screen = AC()->get_list_screen( filter_input( INPUT_POST, 'list_screen' ) );

		if ( ! $list_screen ) {
			$this->ajax_error( __( 'Invalid list screen.', 'codepress-admin-columns' ) );
		}

		$list_screen->set_layout_id( filter_input( INPUT_POST, 'layout' ) );

		$column = $list_screen->get_column_by_name( filter_input( INPUT_POST, 'column' ) );

		if ( ! $column ) {
			$this->ajax_error( __( 'Invalid column.', 'codepress-admin-columns' ) );
		}

		if ( ! $column instanceof AC_Column_AjaxValue ) {
			$this->ajax_error( __( 'Invalid method.', 'codepress-admin-columns' ) );
		}

		// Trigger ajax callback
		echo $column->get_ajax_value( $id );
		exit;
	}

	private function ajax_error( $message ) {
		wp_die( $message, null, 400 );
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

					if ( $this->current_list_screen instanceof AC_ListScreen_Media ) {
						add_filter( 'media_row_actions', array( $this, 'set_media_row_actions' ), 10, 2 );
					}
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

			// Adds the default hidden bulk edit markup for the new primary column
			if ( $this->current_list_screen instanceof ACP_ListScreen_Taxonomy && 'name' !== $default ) {
				add_filter( 'tag_row_actions', array( $this, 'add_taxonomy_hidden_quick_edit_markup' ), 20, 2 );
			}
		}

		return $default;
	}

	/**
	 * Add a download link to the table screen
	 *
	 * @param array   $actions
	 * @param WP_Post $post
	 */
	public function set_media_row_actions( $actions, $post ) {
		$link_attributes = array(
			'download' => '',
			'title'    => __( 'Download', 'codepress-admin-columns' ),
		);
		$actions['download'] = ac_helper()->html->link( wp_get_attachment_url( $post->ID ), __( 'Download', 'codepress-admin-columns' ), $link_attributes );

		return $actions;
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
	 * Add the default markup for the default primary column for the Taxonomy list screen which is necessary for bulk edit
	 *
	 * @param $actions
	 * @param $term
	 */
	public function add_taxonomy_hidden_quick_edit_markup( $actions, $term ) {
		$list_table = $this->get_current_list_screen()->get_list_table();

		echo sprintf( '<div class="hidden">%s</div>', $list_table->column_name( $term ) );

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
		if ( ! $this->current_list_screen ) {
			return $classes;
		}

		$classes .= " ac-" . $this->current_list_screen->get_key();

		return apply_filters( 'ac/table/body_class', $classes, $this );
	}

	/**
	 * @since 2.2.4
	 *
	 * @param AC_ListScreen $list_screen
	 */
	public function admin_scripts() {
		if ( ! $this->current_list_screen ) {
			return;
		}

		$list_screen = $this->current_list_screen;

		// Tooltip
		wp_register_script( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".js", array( 'jquery' ), AC()->get_version() );
		wp_enqueue_style( 'jquery-qtip2', AC()->get_plugin_url() . "external/qtip2/jquery.qtip" . AC()->minified() . ".css", array(), AC()->get_version() );

		// Main
		wp_enqueue_script( 'ac-table', AC()->get_plugin_url() . "assets/js/table" . AC()->minified() . ".js", array( 'jquery', 'jquery-qtip2' ), AC()->get_version() );
		wp_enqueue_style( 'ac-table', AC()->get_plugin_url() . "assets/css/table" . AC()->minified() . ".css", array(), AC()->get_version() );

		wp_localize_script( 'ac-table', 'AC', array(
				'list_screen'  => $list_screen->get_key(),
				'layout'       => $list_screen->get_layout_id(),
				'column_types' => $this->get_column_types_mapping( $list_screen ),
				'ajax_nonce'   => wp_create_nonce( 'ac-ajax' ),
				'table_id'     => $list_screen->get_table_attr_id(),
				'edit_link'    => $this->get_edit_link( $list_screen ),
				'i18n'         => array(
					'edit_columns' => esc_html( __( 'Edit columns', 'codepress-admin-columns' ) ),
				),
			)
		);

		/**
		 * @param AC_ListScreen $list_screen
		 */
		do_action( 'ac/table_scripts', $list_screen );

		// Column specific scripts
		foreach ( $list_screen->get_columns() as $column ) {
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
	 * Applies the width setting to the table headers
	 */
	private function display_width_styles() {
		if ( $this->current_list_screen->get_settings() ) {

			// CSS: columns width
			$css_column_width = false;

			foreach ( $this->current_list_screen->get_columns() as $column ) {

				/* @var AC_Settings_Column_Width $setting */
				$setting = $column->get_setting( 'width' );

				if ( $width = $setting->get_display_width() ) {
					$css_column_width .= ".ac-" . $this->current_list_screen->get_key() . " .wrap table th.column-" . $column->get_name() . " { width: " . $width . " !important; }";
					$css_column_width .= "body.acp-overflow-table.ac-" . $this->current_list_screen->get_key() . " .wrap th.column-" . $column->get_name() . " { min-width: " . $width . " !important; }";
				}
			}

			if ( $css_column_width ) : ?>
				<style>
					@media screen and (min-width: 783px) {
					<?php echo $css_column_width; ?>
					}
				</style>
				<?php
			endif;
		}
	}

	/**
	 * @param AC_ListScreen $list_screen
	 *
	 * @return string|false
	 */
	private function get_edit_link( AC_ListScreen $list_screen ) {
		if ( ! AC()->user_can_manage_admin_columns() ) {
			return false;
		}

		/* @var AC_Admin_Page_Settings $settings */
		$settings = AC()->admin()->get_page( 'settings' );

		if ( ! $settings->show_edit_button() ) {
			return false;
		}

		return $list_screen->get_edit_link();
	}

	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	public function admin_footer_scripts() {
		if ( ! $this->current_list_screen ) {
			return;
		}

		$this->display_width_styles();

		/**
		 * Add header scripts that only apply to column screens.
		 * @since 2.3.5
		 *
		 * @param object CPAC Main Class
		 */
		do_action( 'ac/admin_footer', $this->current_list_screen, $this );
	}

	/**
	 * Load current list screen
	 *
	 * @param WP_Screen $current_screen
	 */
	public function load_list_screen( $current_screen ) {
		if ( $list_screen = AC()->get_list_screen_by_wpscreen( $current_screen ) ) {
			$this->set_current_list_screen( $list_screen );
		}
	}

	/**
	 * Runs when doing Quick Edit, a native WordPress ajax call
	 */
	public function load_list_screen_doing_quick_edit() {
		$this->set_current_list_screen( AC()->get_list_screen( $this->get_list_screen_when_doing_quick_edit() ) );
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public function set_current_list_screen( $list_screen ) {
		if ( ! $list_screen ) {
			return;
		}

		$this->current_list_screen = $list_screen;

		// Init Values
		$list_screen->set_manage_value_callback();

		/**
		 * Init Headings
		 * @see get_column_headers() for filter location
		 */
		add_filter( "manage_" . $list_screen->get_screen_id() . "_columns", array( $this, 'add_headings' ), 200 );

		/**
		 * @since 3.0
		 *
		 * @param AC_ListScreen
		 */
		do_action( 'ac/table/list_screen', $list_screen );
	}

	/**
	 * Is WordPress doing ajax
	 *
	 * @since 2.5
	 * @return string List screen key
	 */
	public function get_list_screen_when_doing_quick_edit() {
		$list_screen = false;

		if ( AC()->is_doing_ajax() ) {

			switch ( filter_input( INPUT_POST, 'action' ) ) {

				// Quick edit post
				case 'inline-save' :
					$list_screen = filter_input( INPUT_POST, 'post_type' );
					break;

				// Adding term & Quick edit term
				case 'add-tag' :
				case 'inline-save-tax' :
					$list_screen = 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' );
					break;

				// Quick edit comment & Inline reply on comment
				case 'edit-comment' :
				case 'replyto-comment' :
					$list_screen = 'wp-comments';
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
		if ( ! AC()->is_doing_ajax() ) {
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

		// On first visit 'columns' can be empty, because they were put in memory before 'default headings'
		// were stored. We force get_columns() to be re-populated.
		if ( ! $this->current_list_screen->get_columns() ) {
			$this->current_list_screen->reset();
			$this->current_list_screen->reset_original_columns();
		}

		foreach ( $this->current_list_screen->get_columns() as $column ) {

			/**
			 * @since 3.0
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