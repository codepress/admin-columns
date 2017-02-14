<?php

class AC_Admin_Page_Help extends AC_Admin_Page {

	protected $deprecated_filters = array();

	protected $deprecated_actions = array();

	public function __construct() {
		$this
			->set_slug( 'help' )
			->set_label( __( 'Help', 'codepress-admin-columns' ) );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-help-css', AC()->get_plugin_url() . 'assets/css/admin-page-help' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );

	}

	public function init() {
		$types = array( 'post', 'user', 'comment', 'link', 'media' );
		$post_types = get_post_types();

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

		$this->deprecated_filter( 'cac/column/meta/value', 'NEWVERSION', 'cac-column-meta-value' );
		$this->deprecated_filter( 'cac/column/meta/types', 'NEWVERSION', 'cac-column-meta-types' );
		$this->deprecated_filter( 'cac/columns/custom', 'NEWVERSION', 'cac-columns-custom' );
		foreach ( $types as $type ) {
			$this->deprecated_filter( 'cac/columns/custom/type=' . $type , 'NEWVERSION', 'cac-columns-custom' );
		}
		foreach ( $post_types as $post_type ) {
			$this->deprecated_filter( 'cac/columns/custom/post_type=' . $post_type , 'NEWVERSION', 'cac-columns-custom' );
		}
		$this->deprecated_action( 'cac/test', '1.2' );

	}

	public function display() { ?>
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