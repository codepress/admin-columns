<?php

namespace AC;

use AC\Admin\Page\Columns;
use AC\Type\Url\Editor;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class PluginActionLinks implements Registrable {

	/**
	 * @var string
	 */
	private $basename;

	public function __construct( $basename ) {
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
		if ( $file !== $this->basename ) {
			return $links;
		}

		$url = new Editor( Columns::NAME );

		array_unshift(
			$links,
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( $url->get_url() ),
				__( 'Settings', 'codepress-admin-columns' )
			)
		);

		$upgrade_page_url = new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'upgrade' );

		if ( ! ac_is_pro_active() ) {
			$links[] = sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( $upgrade_page_url->get_url() ),
				sprintf(
					'<span style="font-weight: bold;">%s</span>',
					__( 'Go Pro', 'codepress-admin-columns' )
				)
			);
		}

		return $links;
	}

}