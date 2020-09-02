<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class YoastSeo extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-yoast-seo/ac-addon-yoast-seo.php',
			'Yoast SEO',
			'assets/images/addons/yoast-seo.png',
			__( 'Enrich the Yoast SEO columns with amazing pro features!', 'codepress-admin-columns' ),
			'https://www.yoast.com',
			new Site( Site::PAGE_ADDON_YOAST_SEO )
		);
	}

	public function is_plugin_active() {
		return defined( 'WPSEO_VERSION' );
	}

	public function show_notice( Screen $screen ) {
		return in_array( $screen->get_id(), [
			'toplevel_page_wpseo_dashboard',
			'seo_page_wpseo_titles',
		] );
	}

}