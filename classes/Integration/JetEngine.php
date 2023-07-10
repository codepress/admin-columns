<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class JetEngine extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-jetengine/ac-addon-jetengine.php',
			'JetEngine',
			'assets/images/addons/jetengine.svg?v3',
			sprintf(
				'%s %s',
				__( 'Integrates JetEngine with Admin Columns.', 'codepress-admin-columns' ),
				__( 'Display, inline- and bulk-edit, export, smart filter and sort your JetEngine contents on any admin list table.', 'codepress-admin-columns' )
			),
			'https://crocoblock.com/plugins/jetengine/',
			new Site( Site::PAGE_ADDON_JETENGINE )
		);
	}

	public function is_plugin_active(): bool
    {
		return class_exists( 'Jet_Engine', false );
	}

	public function show_notice( Screen $screen ): bool
    {
		return in_array( $screen->get_id(), [
			'toplevel_page_jet-engine',
			'jetengine_page_jet-engine-meta',
			'jetengine_page_jet-engine-cpt',
			'jetengine_page_jet-engine-cpt-tax',
			'jetengine_page_jet-engine-relations',
		] );
	}

}