<?php

class AC_Admin_Page_Help extends AC_Admin_Page {

	private $deprecated_filters = array();

	private $deprecated_actions = array();

	public function __construct() {
		$this
			->set_slug( 'help' )
			->set_label( __( 'Help', 'codepress-admin-columns' ) );

		// Init and request
		add_action( 'admin_init', array( $this, 'init_label' ) );
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! AC()->user_can_manage_admin_columns() || ! $this->is_current_screen() ) {
			return;
		}

		$this->init_deprecated_filters();

	}

	public function init_label() {
		$count_notices = get_transient( 'ac-deprecated-notices-count' );

		if ( ! $count_notices ) {
			$this->init_deprecated_filters();

			$count_filters = count( $this->deprecated_filters );
			$count_actions = count( $this->deprecated_actions );
			$count_notices = $count_actions + $count_filters;

			set_transient( 'ac-deprecated-notices-count', $count_notices );
		}

		if ( $count_notices > 0 ) {
			$label = $this->get_label();
			$label .= '<span class="ac-badge">' . $count_notices . '</span>';
			$this->set_label( $label );
		}
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-help-css', AC()->get_plugin_url() . 'assets/css/admin-page-help' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );

	}

	public function init_deprecated_filters() {
		if ( ! AC()->admin()->is_admin_screen() ) {
			return;
		}

		$types = array( 'post', 'user', 'comment', 'link', 'media' );
		$post_types = get_post_types();
		$columns = array();

		foreach ( AC()->get_list_screens() as $ls ) {
			foreach ( $ls->get_column_types() as $column ) {
				$columns[ $column->get_type() ] = $column->get_type();
			}
		}

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

		$this->deprecated_filter( 'cac/columns/custom', 'NEWVERSION', 'cac-columns-custom' );
		foreach ( $types as $type ) {
			$this->deprecated_filter( 'cac/columns/custom/type=' . $type, 'NEWVERSION', 'cac-columns-custom' );
		}

		foreach ( $post_types as $post_type ) {
			$this->deprecated_filter( 'cac/columns/custom/post_type=' . $post_type, 'NEWVERSION', 'cac-columns-custom' );
		}

		$this->deprecated_filter( 'cac/editable/column_value', 'NEWVERSION', 'cac-editable-column_value' );
		foreach ( $columns as $column_type ) {
			$this->deprecated_filter( 'cac/editable/column_value/column=' . $column_type, 'NEWVERSION', 'cac-editable-column_value' );
		}

		$this->deprecated_filter( 'cac/editable/column_save', 'NEWVERSION', 'cac-editable-column_save' );
		foreach ( $columns as $column_type ) {
			$this->deprecated_filter( 'cac/editable/column_save/column=' . $column_type, 'NEWVERSION', 'cac-editable-column_save' );
		}

	}

	public function display() {
		?>
        <h2><?php _e( 'Help', 'codepress-admin-columns' ); ?></h2>

        <h3>Deprecated Filters</h3>
		<?php echo $this->display_deprecated_filters(); ?>

        <h3>Deprecated Actions</h3>
		<?php echo $this->display_deprecated_actions();
	}

	private function deprecated_filter( $tag, $version, $doc_tag = null ) {
		if ( has_filter( $tag ) ) {
			$message = sprintf( 'The filter <strong>"%s"</strong> used on your website is deprecated since <strong>%s</strong>.', $tag, $version );
			if ( $doc_tag ) {
				$message .= ' <a href="' . $this->get_documentation_url( $doc_tag ) . '" target="_blank">View our documentation</a>';
			}

			$this->deprecated_filters[ $tag ] = $message;
		}
	}

	private function deprecated_action( $tag, $version, $doc_tag = null ) {
		if ( has_action( $tag ) ) {
			$message = sprintf( 'The action <strong>"%s"</strong> used on your website is deprecated since <strong>%s</strong>.', $tag, $version );
			if ( $doc_tag ) {
				$message .= ' <a href="' . $this->get_documentation_url( $doc_tag ) . '">View our documentation</a>';
			}

			$this->deprecated_actions[ $tag ] = $message;
		}
	}

	private function display_deprecated_filters() {
		foreach ( $this->deprecated_filters as $filter => $message ) {
			?>
            <div class="cac_deprecated_message">
                <p><?php echo $message; ?></p>
				<?php $this->display_callbacks( $filter ); ?>
            </div>
			<?php
		}
	}

	private function display_deprecated_actions() {
		foreach ( $this->deprecated_actions as $action => $message ) {
			?>
            <div class="cac_deprecated_message">
                <p><?php echo $message; ?></p>
				<?php $this->display_callbacks( $action, 'action' ); ?>
            </div>
			<?php
		}
	}

	private function display_callbacks( $tag, $type = 'filter' ) {
		global $wp_filter;
		$callbacks = array();

		if ( isset( $wp_filter[ $tag ] ) && ! empty( $wp_filter[ $tag ]->callbacks ) ) {
			foreach ( $wp_filter[ $tag ]->callbacks as $callback ) {
				foreach ( $callback as $cb ) {
					if ( is_scalar( $cb['function'] ) ) {
						$callbacks[] = $cb['function'];
					}
				}
			}
		}

		if ( ! empty( $callbacks ) ) {
			echo '<strong>' . sprintf( __( 'Callbacks used', 'codepress-admin-columns' ), $type ) . ': </strong>';
			echo implode( ', ', $callbacks );
		}
	}

	private function get_documentation_url( $doc_tag ) {
		return ac_get_site_url( 'documentation/' . $doc_tag );
	}
}