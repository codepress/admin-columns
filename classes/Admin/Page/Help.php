<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\Page;
use AC\Deprecated\Counter;
use AC\Deprecated\Hooks;

class Help extends Page
	implements AC\Registrable {

	const NAME = 'help';

	/** @var Counter */
	private $counter;

	/** @var Hooks */
	private $hooks;

	public function __construct() {
		$this->counter = new Counter();
		$this->hooks = new Hooks();

		$label = __( 'Help', 'codepress-admin-columns' );

		if ( $this->show_in_menu() ) {
			$label .= '<span class="ac-badge">' . $this->counter->get() . '</span>';
		}

		parent::__construct( self::NAME, $label );
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		$this->update_count();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function update_count() {
		$this->counter->update( $this->hooks->get_deprecated_count() );
	}

	/**
	 * @return bool
	 */
	public function show_in_menu() {
		return absint( $this->counter->get() ) > 0;
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-help-css', AC()->get_url() . 'assets/css/admin-page-help.css', array(), AC()->get_version() );
	}

	/**
	 * @param string $page Website page slug
	 *
	 * @return false|string
	 */
	private function get_documention_link( $page ) {
		return ac_helper()->html->link( ac_get_site_utm_url( 'documentation/' . $page, 'documentation' ), __( 'View documentation', 'codepress-admin-columns' ) . ' &raquo;', array( 'target' => '_blank' ) );
	}

	/**
	 * @param array $callbacks
	 *
	 * @return false|string
	 */
	private function get_callback_message( $callbacks ) {
		if ( ! $callbacks ) {
			return false;
		}

		return sprintf( _n( 'The callback used is %s.', 'The callbacks used are %s.', count( $callbacks ), 'codepress-admin-columns' ), '<code>' . implode( '</code>, <code>', $callbacks ) . '</code>' );
	}

	/**
	 * @return void
	 */
	private function render_actions() {
		$hooks = $this->hooks->get_deprecated_actions();

		if ( ! $hooks ) {
			return;
		}

		?>
		<h3><?php __( 'Deprecated Actions', 'codepress-admin-columns' ); ?></h3>
		<?php

		foreach ( $hooks as $hook ) {
			$message = sprintf( __( 'The action %s used on this website is deprecated since %s.', 'codepress-admin-columns' ), '<code>' . $hook->get_name() . '</code>', '<strong>' . $hook->get_version() . '</strong>' );

			$callbacks = $hook->get_callbacks();

			if ( $callbacks ) {
				$message .= ' ' . $this->get_callback_message( $callbacks );
			}

			$message .= ' ' . $this->get_documention_link( $hook->get_slug() ? 'action-reference/' . $hook->get_slug() : '#action-reference' );

			$this->render_message( $message );
		}
	}

	/**
	 * @return void
	 */
	private function render_filters() {
		$hooks = $this->hooks->get_deprecated_filters();

		if ( ! $hooks ) {
			return;
		}

		?>
		<h3><?php __( 'Deprecated Filters', 'codepress-admin-columns' ); ?></h3>
		<?php

		foreach ( $hooks as $hook ) {
			$message = sprintf( __( 'The filter %s used on this website is deprecated since %s.', 'codepress-admin-columns' ), '<code>' . $hook->get_name() . '</code>', '<strong>' . $hook->get_version() . '</strong>' );

			$callbacks = $hook->get_callbacks();

			if ( $callbacks ) {
				$message .= ' ' . $this->get_callback_message( $callbacks );
			}

			$message .= ' ' . $this->get_documention_link( $hook->get_slug() ? 'filter-reference/' . $hook->get_slug() : '#filter-reference' );

			$this->render_message( $message );
		}
	}

	private function render_message( $message ) {
		?>
		<div class="ac-deprecated-message">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	public function render() {
		?>
		<h2><?php _e( 'Help', 'codepress-admin-columns' ); ?></h2>
		<p>
			<?php _e( 'The Admin Columns plugin has undergone some major changes in version 4.', 'codepress-admin-columns' ); ?> <br/>

			<?php printf( __( 'This site is using some actions or filters that have changed. Please read %s to resolve them.', 'codepress-admin-columns' ), ac_helper()->html->link( ac_get_site_utm_url( 'documentation/faq/upgrading-from-v3-to-v4', 'help' ), __( 'our documentation', 'codepress-admin-columns' ) ) ); ?>
		</p>

		<?php

		$this->render_actions();
		$this->render_filters();
	}

}