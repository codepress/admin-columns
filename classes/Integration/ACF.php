<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class ACF extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-acf/ac-addon-acf.php',
			__( 'Advanced Custom Fields', 'codepress-admin-columns' ),
			'assets/images/addons/acf-v2.png',
			sprintf(
				'%s %s',
				__( 'Integrates ACF with Admin Columns.', 'codepress-admin-columns' ),
				__( 'Display, inline- and bulk-edit, export, smart filter and sort your ACF contents on any admin list table.', 'codepress-admin-columns' )
			),
			'https://www.advancedcustomfields.com',
			new Site( Site::PAGE_ADDON_ACF )
		);
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false ) || class_exists( 'ACF', false );
	}

	public function show_notice( Screen $screen ) {
		return in_array( $screen->get_id(), [
			'edit-acf-field-group',
			'acf-field-group',
		] );
	}

}