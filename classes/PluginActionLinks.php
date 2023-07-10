<?php

namespace AC;

use AC\Admin\Page\Columns;
use AC\Type\Url\Editor;
use AC\Entity;

class PluginActionLinks implements Registerable {

	private $plugin;

	public function __construct( Entity\Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function register(): void
    {
		add_filter( 'plugin_action_links', [ $this, 'add_settings_link' ], 1, 2 );
		add_filter( 'network_admin_plugin_action_links', [ $this, 'add_settings_link' ], 1, 2 );
	}

	/**
	 * Add a settings link to the Admin Columns entry in the plugin overview screen
	 *
	 * @param array  $links
	 * @param string $file
	 *
	 * @return array
	 * @see   filter:plugin_action_links
	 * @since 1.0
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file === $this->plugin->get_basename() ) {
			array_unshift(
				$links,
				sprintf(
					'<a href="%s">%s</a>',
					esc_url( ( new Editor( Columns::NAME ) )->get_url() ),
					__( 'Settings', 'codepress-admin-columns' )
				)
			);
		}

		return $links;
	}

}