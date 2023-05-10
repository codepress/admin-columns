<?php

namespace AC;

use AC\Admin\Page\Columns;
use AC\Type\Basename;
use AC\Type\Url\Editor;

class PluginActionLinks implements Registerable {

	private $basename;

	public function __construct( Basename $basename ) {
		$this->basename = $basename;
	}

	public function register() {
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
		if ( $file === (string) $this->basename ) {
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