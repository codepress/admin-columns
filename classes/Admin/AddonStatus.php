<?php

namespace AC\Admin;

use AC\Integration;
use AC\PluginInformation;
use AC\View;
use ACP\Entity\License;

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

	/**
	 * @var License|null
	 */
	private $license;

	public function __construct( PluginInformation $plugin, Integration $integration, $is_multisite, $is_network_admin, License $license = null ) {
		$this->plugin = $plugin;
		$this->integration = $integration;
		$this->is_multisite = (bool) $is_multisite;
		$this->is_network_admin = (bool) $is_network_admin;
		$this->license = $license;
	}

	private function render_active_label() {
		echo ( new View() )->set_template( 'admin/page/component/addon/active-label' )->render();
	}

	private function render_network_active_label() {
		echo ( new View() )->set_template( 'admin/page/component/addon/network-active-label' )->render();
	}

	private function add_redirect( $url, $value ) {
		return add_query_arg( [
			self::REDIRECT_PARAM => $value,
		], $url );
	}

	/**
	 * @param string $action
	 *
	 * @return string
	 */
	private function get_plugin_action_url( $action ) {
		if ( 'activate' !== $action ) {
			$action = 'deactivate';
		}

		return add_query_arg( [
			'action' => $action,
			'plugin' => $this->plugin->get_basename(),
		], wp_nonce_url( admin_url( 'plugins.php' ), $action . '-plugin_' . $this->plugin->get_basename() ) );
	}

	/**
	 * @param string $action
	 *
	 * @return string
	 */
	private function get_plugin_network_action_url( $action ) {
		if ( 'activate' !== $action ) {
			$action = 'deactivate';
		}

		return add_query_arg( [
			'action' => $action,
			'plugin' => $this->plugin->get_basename(),
		], wp_nonce_url( network_admin_url( 'plugins.php' ), $action . '-plugin_' . $this->plugin->get_basename() ) );
	}

	private function render_network_deactivate() {
		$view = new View( [
			'url' => $this->add_redirect( $this->get_plugin_network_action_url( 'deactivate' ), self::REDIRECT_TO_NETWORK ),
		] );

		echo $view->set_template( 'admin/page/component/addon/deactivate' )->render();
	}

	private function render_network_activate() {
		$view = new View( [
			'url' => $this->add_redirect( $this->get_plugin_network_action_url( 'activate' ), self::REDIRECT_TO_NETWORK ),
		] );

		echo $view->set_template( 'admin/page/component/addon/network-activate' )->render();
	}

	private function render_deactivate() {
		$view = new View( [
			'url' => $this->add_redirect( $this->get_plugin_action_url( 'deactivate' ), self::REDIRECT_TO_SITE ),
		] );

		echo $view->set_template( 'admin/page/component/addon/deactivate' )->render();
	}

	private function render_activate() {
		$view = new View( [
			'url' => $this->add_redirect( $this->get_plugin_action_url( 'activate' ), self::REDIRECT_TO_SITE ),
		] );

		echo $view->set_template( 'admin/page/component/addon/activate' )->render();
	}

	private function render_missing_license() {
		echo ( new View() )->set_template( 'admin/page/component/addon/missing-license' )->render();
	}

	private function is_downloadable() {
		return $this->license && $this->license->is_active();
	}

	private function render_install() {
		echo ( new View() )->set_template( 'admin/page/component/addon/install' )->render();
	}

	private function render_more_info( $class = '' ) {
		$view = new View( [
			'url'   => $this->integration->get_link(),
			'class' => $class,
		] );

		echo $view->set_template( 'admin/page/component/addon/more-info' )->render();
	}

	private function is_network_active() {
		return $this->is_multisite && $this->plugin->is_installed() && $this->plugin->is_network_active();
	}

	private function is_active() {
		if ( $this->is_network_active() ) {
			return false;
		}

		if ( $this->is_network_admin && $this->plugin->is_active() ) {
			return false;
		}

		return $this->plugin->is_installed() && $this->plugin->is_active();
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

	private function is_license_active() {
		return $this->license && $this->license->is_active();
	}

	public function render() {
		if ( ! ac_is_pro_active() ) {
			ob_start();
			$this->render_more_info( '-pink' );

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
			$this->is_license_active()
				? $this->render_network_activate()
				: $this->render_missing_license();
		}

		if ( $this->is_activatable() ) {
			$this->is_license_active()
				? $this->render_activate()
				: $this->render_missing_license();
		}

		if ( $this->is_installable() ) {
			$this->is_license_active()
				? $this->render_install()
				: $this->render_missing_license();
		}

		if ( $this->show_more_info() ) {
			$this->render_more_info();
		}

		return ob_get_clean();
	}

}