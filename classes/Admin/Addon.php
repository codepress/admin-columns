<?php

namespace AC\Admin;

use AC;

class Addon {

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $logo;

	/**
	 * Icon is a small version of the logo. Mainly used on the promo banner.
	 *
	 * @var string
	 */
	private $icon;

	/**
	 * Plugin folder name
	 *
	 * @var AC\PluginInformation
	 */
	private $addon;

	/**
	 * Plugin basename. Example: plugin/plugin.php
	 *
	 * @var AC\PluginInformation[]
	 */
	private $plugins;

	/**
	 * External website link
	 *
	 * @var string
	 */
	private $link;

	/**
	 * Plugin URL. Place where the plugin can be downloaded from. Default is install plugin screen.
	 *
	 * @var string Url
	 */
	private $plugin_url;

	public function __construct( $addon_dirname ) {
		$this->addon = new AC\PluginInformation( $addon_dirname );
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	protected function set_title( $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Plugin folder name
	 *
	 * @return AC\PluginInformation[]
	 */
	public function get_plugins() {
		return $this->plugins;
	}

	/**
	 * @param string $slug Plugin folder name. Example: 'plugin/init.php' then directory name is 'plugin'.
	 */
	protected function add_plugin( $plugin ) {
		$this->plugins[] = new AC\PluginInformation( $plugin );

		return $this;
	}

	/**
	 * @return AC\PluginInformation
	 */
	public function get_plugin() {
		return $this->plugins[0];
	}

	/**
	 * @return string
	 */
	public function get_link() {
		if ( null === $this->link ) {
			$this->set_link( ac_get_site_utm_url( 'pricing-purchase', 'addon', $this->addon->get_dirname() ) );
		}

		return $this->link;
	}

	/**
	 * @param string $link
	 */
	protected function set_link( $url ) {
		if ( ac_helper()->string->is_valid_url( $url ) ) {
			$this->link = $url;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	protected function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_logo() {
		return $this->logo;
	}

	/**
	 * @param string $logo
	 */
	protected function set_logo( $logo ) {
		$this->logo = $logo;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	protected function set_icon( $icon ) {
		$this->icon = $icon;

		return $this;
	}

	/**
	 * Plugin folder name
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->addon->get_dirname();
	}

	/**
	 * @return bool
	 */
	public function is_installed() {
		return $this->addon->is_installed();
	}

	/**
	 * @return bool
	 */
	public function is_active() {
		return $this->addon->is_active();
	}

	/**
	 * @return string|false Returns the plugin version if the plugin is installed, false otherwise
	 */
	public function get_version() {
		return $this->addon->get_version();
	}

	/**
	 * @return string Basename
	 */
	public function get_basename() {
		return $this->addon->get_basename();
	}

	/**
	 * @return bool
	 */
	public function is_plugin_installed() {
		return $this->get_plugin()->is_installed();
	}

	/**
	 * @return bool
	 */
	public function is_plugin_active() {
		return $this->get_plugin()->is_active();
	}

	/**
	 * @return string Basename
	 */
	public function get_plugin_basename() {
		return $this->get_plugin()->get_basename();
	}

	/**
	 * @return string
	 */
	public function get_plugin_activation_url() {
		return $this->get_activation_url( $this->get_plugin_basename() );
	}

	/**
	 * Icon
	 */
	public function display_icon() {
		if ( $this->get_icon() ) : ?>
			<img class="icon <?php echo esc_attr( $this->get_slug() ); ?>" src="<?php echo esc_attr( $this->get_icon() ); ?>" alt="<?php echo esc_attr( $this->get_title() ); ?>">
		<?php endif;
	}

	public function display_promo() {
		if ( $this->get_icon() ) {
			$this->display_icon();

			return;
		}

		echo $this->get_title();
	}

	/**
	 * @return AC\Column\Placeholder
	 */
	public function get_placeholder_column() {
		$column = new AC\Column\Placeholder();
		$column->set_addon( $this );

		return $column;
	}

	/**
	 * @param string $title
	 *
	 * @return string
	 */
	protected function get_fields_description( $title ) {
		return sprintf( __( 'Display and edit %s fields in the posts overview in seconds!', 'codepress-admin-columns' ), $title );
	}

	/**
	 * Activate plugin
	 *
	 * @return string
	 */
	public function get_activation_url( $basename ) {
		return $this->get_plugin_action_url( 'activate', $basename );
	}

	/**
	 * Deactivate plugin
	 *
	 * @return string
	 */
	public function get_deactivation_url( $basename ) {
		return $this->get_plugin_action_url( 'deactivate', $basename );
	}

	/**
	 * Activate or Deactivate plugin
	 *
	 * @param string $action
	 *
	 * @return string
	 */
	private function get_plugin_action_url( $action = 'activate', $basename ) {
		$plugin_url = add_query_arg( array(
			'action'      => $action,
			'plugin'      => $basename,
			'ac-redirect' => true,
		), admin_url( 'plugins.php' ) );

		return wp_nonce_url( $plugin_url, $action . '-plugin_' . $basename );
	}

	/**
	 * @param string $plugin_url
	 *
	 * @return $this
	 */
	public function set_plugin_url( $plugin_url ) {
		$this->plugin_url = $plugin_url;

		return $this;
	}

	/**
	 * @param string $search_term
	 *
	 * @return string
	 */
	public function get_plugin_url() {
		if ( null === $this->plugin_url ) {
			$this->set_plugin_url( add_query_arg( array(
				'tab'  => 'search',
				'type' => 'term',
				's'    => $this->get_title(),
			), admin_url( 'plugin-install.php' ) ) );
		}

		return $this->plugin_url;
	}

	/**
	 * @return bool Should a notice be always shown
	 */
	public function is_notice_screen() {
		return true;
	}

}