<?php

namespace AC\Admin;

use AC\Integration;
use AC\PluginInformation;

class AddonStatus {

	const REDIRECT_PARAM = 'ac-redirect';
	const REDIRECT_TO_SITE = 1;
	const REDIRECT_TO_NETWORK = 2;

	/**
	 * @var PluginInformation
	 */
	private $plugin;

	/**
	 * @var Integration
	 */
	private $integration;

	/**
	 * @var bool
	 */
	private $is_multisite;

	/**
	 * @var bool
	 */
	private $is_network_admin;

	public function __construct( PluginInformation $plugin, Integration $integration, $is_multisite, $is_network_admin ) {
		$this->plugin = $plugin;
		$this->integration = $integration;
		$this->is_multisite = (bool) $is_multisite;
		$this->is_network_admin = (bool) $is_network_admin;
	}

	private function render_active_label() { ?>
		<div class="ac-addon__state">
			<span class="-green dashicons dashicons-yes"></span>
			<span class="ac-addon__state__label"><?php _e( 'Active', 'codepress-admin-columns' ); ?></span>
		</div>
		<?php
	}

	private function render_network_active_label() { ?>
		<div class="ac-addon__state">
			<span class="-green dashicons dashicons-yes"></span>
			<span class="ac-addon__state__label"><?php _e( 'Network Active', 'codepress-admin-columns' ); ?></span>
		</div>
		<?php
	}

	private function add_redirect( $url, $value ) {
		return add_query_arg( [
			self::REDIRECT_PARAM => $value,
		], $url );
	}

	private function render_network_deactivate() {
		?>
		<a href="<?php echo esc_url( $this->add_redirect( $this->plugin->get_plugin_network_action_url( 'deactivate' ), self::REDIRECT_TO_NETWORK ) ); ?>" class="ac-addon__button button">
			<?php _e( 'Deactivate', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function render_network_activate() {
		?>
		<a href="<?php echo esc_url( $this->add_redirect( $this->plugin->get_plugin_network_action_url( 'activate' ), self::REDIRECT_TO_NETWORK ) ); ?>" class="ac-addon__button button">
			<?php _e( 'Network Activate', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function render_deactivate() {
		?>
		<a href="<?php echo esc_url( $this->add_redirect( $this->plugin->get_plugin_action_url( 'deactivate' ), self::REDIRECT_TO_SITE ) ); ?>" class="ac-addon__button button">
			<?php _e( 'Deactivate', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function render_activate() {
		?>
		<a href="<?php echo esc_url( $this->add_redirect( $this->plugin->get_plugin_action_url( 'activate' ), self::REDIRECT_TO_SITE ) ); ?>" class="ac-addon__button button">
			<?php _e( 'Enable', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function render_install() {
		?>
		<a class="ac-addon__button button" data-install>
			<?php esc_html_e( 'Enable', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function render_more_info() {
		?>
		<a target="_blank" href="<?php echo esc_url( $this->integration->get_link() ); ?>" class="ac-addon__button button">
			<?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?>
		</a>
		<?php
	}

	private function is_network_active() {
		return $this->is_multisite && $this->plugin->is_network_active();
	}

	private function is_active() {
		if ( $this->is_network_active() ) {
			return false;
		}

		if ( $this->is_network_admin && $this->plugin->is_active() ) {
			return false;
		}

		return $this->plugin->is_active();
	}

	private function is_network_deactivatable() {
		return current_user_can( 'activate_plugins' )
		       && $this->is_multisite
		       && $this->is_network_admin
		       && $this->is_network_active();
	}

	private function is_network_activatable() {
		return current_user_can( 'activate_plugins' )
		       && $this->is_multisite
		       && $this->is_network_admin
		       && $this->plugin->is_installed()
		       && ! $this->is_network_active();
	}

	private function is_deactivatable() {
		if ( $this->is_network_deactivatable() ) {
			return false;
		}

		return current_user_can( 'activate_plugins' )
		       && ! $this->is_network_admin
		       && $this->is_active();
	}

	private function is_activatable() {
		if ( $this->is_network_activatable() ) {
			return false;
		}

		return current_user_can( 'activate_plugins' )
		       && ! $this->is_network_admin
		       && $this->plugin->is_installed()
		       && ! $this->plugin->is_network_active()
		       && ! $this->is_active();
	}

	private function is_installable() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		if ( $this->plugin->is_installed() ) {
			return false;
		}

		return $this->is_multisite
			? $this->is_network_admin
			: true;
	}

	private function show_more_info() {
		return ! $this->is_installable() && ! $this->plugin->is_installed();
	}

	public function render() {
		if ( ! ac_is_pro_active() ) {
			ob_start();
			$this->render_more_info();

			return ob_get_clean();
		}

		ob_start();

		if ( $this->is_network_active() ) {
			$this->render_network_active_label();
		}

		if ( $this->is_active() ) {
			$this->render_active_label();
		}

		if ( $this->is_network_activatable() ) {
			$this->render_network_activate();
		}

		if ( $this->is_activatable() ) {
			$this->render_activate();
		}

		if ( $this->is_installable() ) {
			$this->render_install();
		}

		if ( $this->show_more_info() ) {
			$this->render_more_info();
		}

		return ob_get_clean();
	}

}