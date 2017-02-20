<?php

class AC_Admin_Page_Help extends AC_Admin_Page {

	CONST TRANSIENT_COUNT_KEY = 'ac-deprecated-message-count';

	private $messages = array();

	public function __construct() {

		$this
			->set_slug( 'help' )
			->set_label_with_count();

		// Hide page when there are no messages
		if ( ! $this->get_message_count() ) {
			$this->set_show_in_menu( false );
		}

		// Init and request
		add_action( 'admin_init', array( $this, 'init' ), 9 );
		add_action( 'admin_init', array( $this, 'run_hooks_on_help_tab' ) );
	}

	private function set_label_with_count() {
		$label = __( 'Help', 'codepress-admin-columns' );
		if ( $count = $this->get_message_count() ) {
			$label .= '<span class="ac-badge">' . $count . '</span>';
		}

		$this->set_label( $label );

		return $this;
	}

	public function init() {
		if ( ! AC()->user_can_manage_admin_columns() ) {
			return;
		}

		// Run once
		if ( false === $this->get_message_count() ) {
			$this->run_hooks();
		}
	}

	/**
	 * Run all hooks when opening the help tab.
	 */
	public function run_hooks_on_help_tab() {
		if ( ! AC()->user_can_manage_admin_columns() || ! $this->is_current_screen() ) {
			return;
		}

		$this->run_hooks();
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-help-css', AC()->get_plugin_url() . 'assets/css/admin-page-help' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );
	}

	private function update_message_count() {
		$count_filters = count( $this->get_messages( 'filter' ) );
		$count_actions = count( $this->get_messages( 'action' ) );

		$count_notices = $count_actions + $count_filters;

		set_transient( self::TRANSIENT_COUNT_KEY, $count_notices );
	}

	private function get_message_count() {
		return get_transient( self::TRANSIENT_COUNT_KEY );
	}

	public function delete_message_count() {
		delete_transient( self::TRANSIENT_COUNT_KEY );
	}

	/**
	 * This will run all deprecated hooks and adds a message when a hook has been used on the site.
	 */
	public function run_hooks() {

		$types = array( 'post', 'user', 'comment', 'link', 'media' );
		$post_types = get_post_types();

		$columns = array();
		foreach ( AC()->get_list_screens() as $ls ) {
			foreach ( $ls->get_column_types() as $column ) {
				$columns[ $column->get_type() ] = $column->get_type();
			}
		}

		// Filters

		$this->deprecated_filter( 'cac/headings/label', 'NEWVERSION', 'cac-columns-custom' );
		$this->deprecated_filter( 'cac/column/meta/value', 'NEWVERSION', 'cac-column-meta-value' );
		$this->deprecated_filter( 'cac/column/meta/types', 'NEWVERSION', 'cac-column-meta-types' );
		$this->deprecated_filter( 'cac/settings/tabs', 'NEWVERSION', 'filter-reference/cac-settings-tabs' );
		$this->deprecated_filter( 'cac/editable/is_column_editable', 'NEWVERSION', 'cac-editable-is_column_editable' );
		$this->deprecated_filter( 'cac/editable/editables_data', 'NEWVERSION', 'cac-editable-editables_data' );
		$this->deprecated_filter( 'cac/editable/options', 'NEWVERSION', 'cac-editable-editables_data' );
		$this->deprecated_filter( 'cac/inline-edit/ajax-column-save/value', 'NEWVERSION', 'cac-inline-edit-ajax-column-save-value' );
		$this->deprecated_filter( 'cac/addon/filtering/options', 'NEWVERSION', 'cac-addon-filtering-options' );
		$this->deprecated_filter( 'cac/addon/filtering/dropdown_top_label', 'NEWVERSION', 'cac-addon-filtering-dropdown_top_label' );
		$this->deprecated_filter( 'cac/addon/filtering/taxonomy/terms_args', 'NEWVERSION', 'cac-addon-filtering-taxonomy-terms_args' );
		$this->deprecated_filter( 'cac/addon/filtering/dropdown_empty_option', 'NEWVERSION', 'cac-addon-filtering-taxonomy-terms_args' );
		$this->deprecated_filter( 'cac/column/actions/action_links', 'NEWVERSION', 'cac-column_actions-action_links' );
		$this->deprecated_filter( 'cac/acf/format_acf_value', 'NEWVERSION' );

		$this->deprecated_filter( 'cac/columns/custom', 'NEWVERSION', 'cac-columns-custom' );
		foreach ( $types as $type ) {
			$this->deprecated_filter( 'cac/columns/custom/type=' . $type, 'NEWVERSION', 'cac-columns-custom' );
		}

		foreach ( $post_types as $post_type ) {
			$this->deprecated_filter( 'cac/columns/custom/post_type=' . $post_type, 'NEWVERSION', 'cac-columns-custom' );
		}

		$this->deprecated_filter( 'cac/column/value', 'NEWVERSION', 'cac-column-value' );
		foreach ( $types as $type ) {
			$this->deprecated_filter( 'cac/column/value/' . $type, 'NEWVERSION', 'cac-column-value' );
		}

		$this->deprecated_filter( 'cac/editable/column_value', 'NEWVERSION', 'cac-editable-column_value' );
		foreach ( $columns as $column_type ) {
			$this->deprecated_filter( 'cac/editable/column_value/column=' . $column_type, 'NEWVERSION', 'cac-editable-column_value' );
		}

		$this->deprecated_filter( 'cac/editable/column_save', 'NEWVERSION', 'cac-editable-column_save' );
		foreach ( $columns as $column_type ) {
			$this->deprecated_filter( 'cac/editable/column_save/column=' . $column_type, 'NEWVERSION', 'cac-editable-column_save' );
		}

		// Actions

		$this->update_message_count();
		$this->set_label_with_count();
	}

	private function get_groups() {
		$groups = array(
			'filter' => __( 'Deprecated Filters', 'codepress-admin-columns' ),
			'action' => __( 'Deprecated Actions', 'codepress-admin-columns' ),
		);

		return $groups;
	}

	private function deprecated_filter( $hook, $version, $page = null ) {
		if ( has_filter( $hook ) ) {
			$message = sprintf( __( 'The filter %s used on this website is deprecated since %s.', 'codepress-admin-columns' ), '<strong>"' . $hook . '"</strong>', '<strong>' . $version . '</strong>' );

			$this->add_deprecated_message( 'filter', $message, $hook, $page );
		}
	}

	private function deprecated_action( $hook, $version, $page = null ) {
		if ( has_action( $hook ) ) {
			$message = sprintf( __( 'The action %s used on this website is deprecated since %s.', 'codepress-admin-columns' ), '<strong>"' . $hook . '"</strong>', '<strong>' . $version . '</strong>' );

			$this->add_deprecated_message( 'action', $message, $hook, $page );
		}
	}

	/**
	 * @param string $type
	 * @param string $message
	 * @param string $hook
	 * @param null   $page
	 */
	private function add_deprecated_message( $type, $message, $hook, $page = null ) {
		if ( $callback_message = $this->get_callback_message( $hook ) ) {
			$message .= ' ' . $callback_message;
		}
		if ( $page ) {
			$message .= ' ' . $this->get_documention_link( $page );
		}

		$this->add_message( $message, $type );
	}

	/**
	 * @param string $message
	 * @param string $type
	 */
	private function add_message( $message, $type = 'filter' ) {
		$this->messages[ $type ][] = $message;
	}

	/**
	 * @param string $type
	 *
	 * @return array|false
	 */
	private function get_messages( $type = 'filter' ) {
		if ( ! isset( $this->messages[ $type ] ) ) {
			return array();
		}

		return $this->messages[ $type ];
	}

	private function get_group_label( $type ) {
		$groups = $this->get_groups();

		if ( ! isset( $groups[ $type ] ) ) {
			return false;
		}

		return $groups[ $type ];
	}

	/**
	 * @param string $page Website page slug
	 *
	 * @return false|string
	 */
	private function get_documention_link( $page ) {
		return ac_helper()->html->link( ac_get_site_url( 'documentation/' . $page ), __( 'View documentation', 'codepress-admin-columns' ) . ' &raquo;', array( 'target' => '_blank' ) );
	}

	/**
	 * @param string $hook Action or Filter
	 *
	 * @return string|false
	 */
	private function get_callback_message( $hook ) {
		global $wp_filter;
		$callbacks = array();

		if ( isset( $wp_filter[ $hook ] ) && ! empty( $wp_filter[ $hook ]->callbacks ) ) {
			foreach ( $wp_filter[ $hook ]->callbacks as $callback ) {
				foreach ( $callback as $cb ) {
					if ( is_scalar( $cb['function'] ) ) {
						$callbacks[] = $cb['function'];
					}
				}
			}
		}

		if ( empty( $callbacks ) ) {
			return false;
		}

		return sprintf( _n( 'The callback is %s.', 'The callbacks are %s', count( $callbacks ), 'codepress-admin-columns' ), '<strong>' . implode( '</strong>, </strong>', $callbacks ) . '</strong>' );
	}

	public function display() {
		?>

        <h2><?php _e( 'Help', 'codepress-admin-columns' ); ?></h2>

        <p><?php // TODO: add explanation ?>In this help section...</p>

		<?php foreach ( $this->get_groups() as $type => $label ) {
			if ( $messages = $this->get_messages( $type ) ) : ?>
                <h3><?php echo esc_html( $label ); ?></h3>
				<?php foreach ( $messages as $message ) : ?>
                    <div class="ac-deprecated-message">
                        <p><?php echo $message; ?></p>
                    </div>
				<?php endforeach; ?>
			<?php endif;
		}
	}

}